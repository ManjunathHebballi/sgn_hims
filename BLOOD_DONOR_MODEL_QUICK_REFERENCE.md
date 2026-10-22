# Blood Donor Model Class - Quick Reference
## bloodDonorClass.php Method Cheat Sheet

### 🔹 Deferral & Re-Evaluation Methods

#### Get Pending Re-Evaluations (All Donors)
```php
$donor = new bloodDonor();
$result = $donor->getDonorsPendingReEvaluation();
// Returns: MySQLi result from v_donors_pending_re_evaluation view
// Usage: Pending re-evaluation list page
```

#### Get Cleared Deferral Details (Single Donor)
```php
$donor = new bloodDonor();
$donor->setDonorId($donor_id);
$result = $donor->getClearedDeferralDetails();
// Returns: Most recent cleared deferral for donor
// Usage: Re-evaluation workflow page
```

#### Check Recent Cleared Deferrals
```php
$donor = new bloodDonor();
$donor->setDonorId($donor_id);
$result = $donor->getRecentlyClearedDeferral(90); // 90 days
// Returns: Recently cleared deferrals within timeframe
// Usage: Eligibility validation
```

#### Get Re-Evaluation Queue Entry
```php
$donor = new bloodDonor();
$donor->setDonorId($donor_id);
$result = $donor->getReEvaluationQueueEntry();
// Returns: Pending queue entry for donor
// Usage: Workflow tracking
```

#### Update Re-Evaluation Status
```php
$donor = new bloodDonor();
$donor->setDonorId($donor_id);
$success = $donor->updateReEvaluationStatus('Completed', 'Medical screening passed');
// Parameters: status ('Pending'|'In_Progress'|'Completed'), notes (optional)
// Returns: Boolean
// Usage: Mark re-evaluation complete
```

#### Check if Re-Evaluation Required
```php
$donor = new bloodDonor();
$donor->setDonorId($donor_id);
$check = $donor->checkReEvaluationRequired();
// Returns: ['required' => bool, 'reason' => string, 'action' => string]
// Usage: Display re-evaluation alerts
```

#### Check Complete Eligibility
```php
$donor = new bloodDonor();
$donor->setDonorId($donor_id);
$eligibility = $donor->checkDonorEligibility();
// Returns: ['eligible' => bool, 'errors' => array]
// Checks: Status, Active deferral, Recent clearances, Cooling period
// Usage: Pre-collection validation
```

#### Get Expired Deferrals (Static - for Scripts)
```php
$result = bloodDonor::getExpiredDeferrals($db);
// Parameters: $db connection
// Returns: MySQLi result with expired temporary deferrals
// Usage: Automated clearance scripts
```

#### Clear Expired Deferral (Transaction Safe)
```php
$donor = new bloodDonor();
$donor->setDonorId($donor_id);
$result = $donor->clearExpiredDeferral('Automatic_Expiry');
// Parameters: clearance_method (optional)
// Returns: ['status' => 'success'|'error', 'message' => string]
// Usage: Automated/manual deferral clearance
```

---

### 🔹 Blood Collection Methods

#### Get Collection with Unit Details
```php
$donor = new bloodDonor();
$result = $donor->getCollectionWithUnitDetails($collection_id, $unit_id);
// Parameters: collection_id, unit_id
// Returns: Complete collection + unit + donor data (complex join)
// Usage: Collection success page, unit details display
```

#### Get All Collections for Donor
```php
$donor = new bloodDonor();
$donor->setDonorId($donor_id);
$result = $donor->getDonorCollections();
// Returns: Collection history with unit info (ordered recent first)
// Usage: Donor profile, collection history tab
```

#### Get Cooling Period Details
```php
$donor = new bloodDonor();
$donor->setDonorId($donor_id);
$result = $donor->getCoolingPeriodDetails();
// Returns: Active cooling period (end_date >= today)
// Usage: Eligibility checks, next donation date display
```

---

## 📋 Common Usage Patterns

### Pattern 1: List All Pending Donors (No ID Required)
```php
$donor = new bloodDonor();
$result = $donor->getDonorsPendingReEvaluation();

while ($row = mysqli_fetch_assoc($result)) {
    echo $row['donor_unique_id'] . " - " . $row['first_name'];
}
```

### Pattern 2: Check Single Donor Eligibility
```php
$donor = new bloodDonor();
$donor->setDonorId($_GET['donor_id']);
$eligibility = $donor->checkDonorEligibility();

if (!$eligibility['eligible']) {
    foreach ($eligibility['errors'] as $error) {
        echo "<div class='alert alert-danger'>$error</div>";
    }
} else {
    // Proceed to collection form
}
```

### Pattern 3: Display Collection Success Page
```php
$donor = new bloodDonor();
$result = $donor->getCollectionWithUnitDetails($_GET['collection_id'], $_GET['unit_id']);

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo "Unit Barcode: " . $data['unit_barcode'];
    echo "Donor: " . $data['first_name'] . " " . $data['last_name'];
    // ... display other fields
}
```

### Pattern 4: Automated Script (Static Method)
```php
// In scripts/check_expired_deferrals.php
require_once(__DIR__ . '/../model/bloodDonorClass.php');

$con = new userlogin();
$db = $con->dbconnect1(); // Non-session connection

$result = bloodDonor::getExpiredDeferrals($db);

while ($deferral = mysqli_fetch_assoc($result)) {
    $donor = new bloodDonor();
    $donor->setDonorId($deferral['donor_id']);
    $clearance_result = $donor->clearExpiredDeferral();
    
    if ($clearance_result['status'] == 'success') {
        echo "✓ Cleared donor " . $deferral['donor_unique_id'];
    }
}
```

---

## ⚠️ Important Notes

### Database Connection
- All non-static methods use `private function dbconnect()`
- Automatically checks session via `userlogin` class
- Scripts should use `dbconnect1()` for non-session connections

### Property Setting Required
Most methods require setting donor_id first:
```php
$donor = new bloodDonor();
$donor->setDonorId($id); // REQUIRED before calling most methods
$result = $donor->getClearedDeferralDetails(); // Now works
```

### Return Types
- **MySQLi Result Object**: Most query methods return `mysqli_result`
  - Check with `if ($result && $result->num_rows > 0)`
  - Fetch with `mysqli_fetch_assoc($result)` or `$result->fetch_assoc()`
  
- **Array**: Business logic methods return associative arrays
  - `checkDonorEligibility()` → `['eligible' => bool, 'errors' => array]`
  - `clearExpiredDeferral()` → `['status' => string, 'message' => string]`

- **Boolean**: Update methods return success/failure
  - `updateReEvaluationStatus()` → `true/false`

### Transaction Safety
Methods with transactions:
- `clearExpiredDeferral()` - Uses `mysqli_begin_transaction()` with rollback

For other operations requiring transactions, wrap in controller:
```php
mysqli_begin_transaction($db);
try {
    // Call model methods
    mysqli_commit($db);
} catch (Exception $e) {
    mysqli_rollback($db);
}
```

---

## 🚫 What NOT to Do

### ❌ Don't Write SQL in Views/Pages
```php
// WRONG - Direct SQL in page
$sql = "SELECT * FROM blood_donor_deferral WHERE donor_id = $id";
$result = mysqli_query($db, $sql);
```

### ✅ Do Use Model Methods
```php
// CORRECT - Use model method
$donor = new bloodDonor();
$donor->setDonorId($id);
$result = $donor->getClearedDeferralDetails();
```

### ❌ Don't Forget to Set Properties
```php
// WRONG - Forgot to set donor_id
$donor = new bloodDonor();
$result = $donor->getClearedDeferralDetails(); // Won't work!
```

### ✅ Do Set Required Properties First
```php
// CORRECT
$donor = new bloodDonor();
$donor->setDonorId($donor_id); // Set property first
$result = $donor->getClearedDeferralDetails(); // Now works
```

---

## 📖 Related Documentation

- [MVC_REFACTORING_SUMMARY.md](MVC_REFACTORING_SUMMARY.md) - Complete refactoring details
- [model/bloodDonorClass.php](model/bloodDonorClass.php) - Full source code
- [DEFERRAL_CLEARANCE_SYSTEM_README.md](DEFERRAL_CLEARANCE_SYSTEM_README.md) - Workflow documentation
- [.github/copilot-instructions.md](.github/copilot-instructions.md) - Project patterns

---

**Last Updated**: 2024-02-03  
**Model Class Location**: `model/bloodDonorClass.php`  
**Method Count**: 11 public methods (8 instance + 1 static + 2 helper)
