# AI Coding Instructions for Alva's Hospital Management System

## System Overview

**Alva's Hospital Management System** is a PHP-based Hospital Information Management System (HIMS) managing clinical, operational, and financial workflows across multiple departments. Key modules include Patient Management (IPD/OPD), Pharmacy, Laboratory, Radiology, Store/Inventory, CathLab, Billing, and Operations Theatre.

## Architecture Pattern: MVC with Session-Based Authentication

### Core Structure
```
root/
├── {page}.php              # Forms & views (e.g., add_medicines.php, ipregistration.php)
├── controller/             # Business logic handlers (process*.php files)
│   ├── processLogin.php    # Authentication & session setup
│   ├── processStore.php    # Inventory operations
│   ├── processCathLab.php  # CathLab procedures & billing
│   └── process*.php        # Domain-specific handlers
├── model/                  # Data access & business classes
│   ├── dbconnect.php       # MySQL connection class (userlogin)
│   ├── *Class.php          # Domain classes (storeClass, ipClass, medicineClass, etc.)
│   └── ajaxClass.php       # AJAX request handlers
└── ajax.php                # Central AJAX dispatcher (routes to ajaxClass methods)
```

### Critical Pattern: Modularized Class Structure

Each domain has its own class file with setter/getter properties and business methods:
- **storeClass.php**: ~2,446 lines, handles PO→GRN→Issue→Payment, batch tracking, GST calculations
- **ipClass.php**: IP registration, admission, discharge workflows
- **medicineClass.php**: Pharmacy operations
- **labClass.php**: Pathology & sample collection
- **radiologyClass.php**: Radiology imaging procedures

**All classes follow this pattern:**
```php
class domainClass {
    private $property1, $property2;  // Use setters/getters for all properties
    
    function setProperty($val) { $this->property = $val; }
    function getProperty() { return $this->property; }
    
    function businessMethod() {
        $con = new userlogin();
        $db = $con->dbconnect();  // Session-checked connection
        // Run queries, return results
    }
}
```

## Authentication & Session Management

**Critical:** Every file must check session and include `dbconnect.php`:
```php
<?php
if(!isset($_SESSION)) { session_start(); }
include('model/dbconnect.php');
```

Database credentials are hard-coded in [model/dbconnect.php](model/dbconnect.php):
- **Host**: localhost | **DB**: shesayur_alvas_hospital
- Two connection methods: `dbconnect()` (session-required) and `dbconnect1()` (no session check)

**Session Variables Set at Login** ([controller/processLogin.php](controller/processLogin.php)):
- `$_SESSION['loginid']`, `$_SESSION['name']`, `$_SESSION['designation']`, `$_SESSION['department']`, `$_SESSION['role']`, `$_SESSION['storeid']`

## Data Flow: Forms → Controller → Model → Database

### Example: Store Invoice Payment Workflow
1. **Form** ([add_store_payment.php](add_store_payment.php)): User enters invoice & payment details
2. **AJAX/Submit**: POST to [controller/processStore.php](controller/processStore.php) with `_REQUEST['action']`
3. **Controller**: Validates, instantiates [model/storeClass.php](model/storeClass.php), calls business method
4. **Model**: Sets properties via setters, executes stored procedure or multi-table INSERT/UPDATE, returns success/error
5. **Response**: JSON or redirect with `?success=1` parameter for notifications

### AJAX Pattern: Central Dispatcher
All AJAX requests POST to [ajax.php](ajax.php) with a `page` parameter:
```php
// Client: $.ajax({ url: 'ajax.php', data: { page: 'ipreg', op_no: '12345' } })
// Server: ajax.php dispatches to ajaxClass method based on $_REQUEST['page']
if($_REQUEST['page'] == 'ipreg') { 
    $newajax->setopno($_REQUEST['op_no']);
    echo $newajax->getOPDetails();
}
```

## Complex Business Workflows (Multi-File Understanding Required)

### 1. **CathLab Module** - Independent Sub-Store with Procedure Linking
**Files**: [CATHLAB_PROJECT_SUMMARY.md](CATHLAB_PROJECT_SUMMARY.md), [cathlab_grn.php](cathlab_grn.php), [cathlab_procedure.php](cathlab_procedure.php), [controller/processCathLab.php](controller/processCathLab.php)

**Architecture**:
- **Independent GRN**: CathLab receives items directly without main store dependency
- **Batch-Wise Inventory**: `cathlab_batch_master` tracks by batch+expiry
- **Procedure Reservation**: Items reserved during procedure entry, deducted on billing
- **Direct Patient Billing**: Consumed items bill directly to patient via CathLab billing interface
- **Database Tables** (7): cathlab_store_items, cathlab_batch_master, cathlab_stock_ledger, cathlab_procedures, etc.

**Integration Point**: CathLab billing integrates with main [billing/controller/processBilling.php](billing/controller/processBilling.php) for final patient invoice.

### 2. **GST (Goods and Services Tax) System** - Item-Level Implementation
**Files**: [GST_IMPLEMENTATION_GUIDE.md](GST_IMPLEMENTATION_GUIDE.md), [model/storeClass.php](model/storeClass.php)

**Pattern**:
- GST rates stored per category in `store_item_gst_rates` table
- Three calculation columns per invoice: `gst_percent`, `gst_amount`, `net_amount`
- Applies to Purchase Orders, Invoices, and Issue records
- **Key Methods in storeClass**:
  - `calculateItemWithGst($qty, $rate, $gst_percent)`: Returns array with item_total, gst_amount, net_amount
  - `setGstRateForCategory($category_id, $gst_percent)`: Updates category-level default
  - `getGstRateForCategory($category_id)`: Fetch active rate

**Important**: Old GST rates deactivated (not deleted) when new rate set—allows audit trail.

### 3. **Store Inventory Workflow** - Purchase → Receipt → Issue → Payment
**Files**: [add_store_po.php](add_store_po.php), [add_store_grn.php](add_store_grn.php), [add_store_issue.php](add_store_issue.php), [add_store_payment.php](add_store_payment.php), [model/storeClass.php](model/storeClass.php)

**Transactions**:
1. **PO (Purchase Order)**: Create with items → status PENDING
2. **GRN (Goods Receipt)**: Link to PO (optional) → updates `store_stock` qty_available, logs in `store_stock_ledger`
3. **ISSUE**: Deduct from stock → updates qty_available, logs qty_out event
4. **PAYMENT**: Link invoice to GRN → tracks payment status

**Stock Ledger** (`store_stock_ledger`): Audit trail with transaction types = GRN_IN | ISSUE_OUT | RETURN_IN | ADJUSTMENT | PROCEDURE_DEDUCT

### 4. **Billing System** - Multi-Type Patient Billing
**Modules**: IP Billing ([ippatient_billing.php](ippatient_billing.php)), OP Billing, Lab Billing, Radiology Billing, Pharmacy Billing

**Cross-Module**: All generate invoice records in `billing_master` and line items in `billing_details`. Uses same [billing/controller/processBilling.php](billing/controller/processBilling.php) controller regardless of module.

## Code Conventions & Patterns

### Form Submission Pattern
```php
<!-- HTML Form -->
<form method="POST" action="../controller/processStore.php">
    <input name="action" value="addItem" />
    <!-- fields -->
</form>

<!-- Controller (processStore.php) -->
if($_REQUEST['action'] == 'addItem') {
    $item = new store();
    $item->setName($_REQUEST['item_name']);
    $item->addItem($con);  // or $item->updateItem($con)
}
```

### Data Retrieval Pattern
```php
$newMedicine = new medicine();
$newMedicine->setMedicineId($_REQUEST['editid']);
$result = $newMedicine->getMedicineDetail();  // Returns MySQL result object
$row = mysqli_fetch_assoc($result);
echo $row['field_name'];
```

### Error Handling
- **Exception**: Rarely used; instead check `num_rows` or return false on failure
- **User Feedback**: Redirect with `?success=1` or `?error=1` and display alert in HTML
- **Logging**: Check error_log file in root for PHP errors (warnings suppressed with `error_reporting(0)`)

### Timezone Configuration
All files set timezone: `date_default_timezone_set("Asia/Calcutta")` or `"Asia/Kolkata"`

### Form Validation
Typically client-side via Bootstrap validation or jQuery; server-side validation minimal—assume data comes formatted correctly.

## Key Files to Understand Architecture

| File | Purpose | Critical for |
|------|---------|----------|
| [model/dbconnect.php](model/dbconnect.php) | Database connection class | All data operations |
| [model/storeClass.php](model/storeClass.php) | Inventory core logic (~2,446 lines) | Store/PO/GRN/Issue/Payment workflows |
| [ajax.php](ajax.php) | Central AJAX dispatcher | Dynamic form behavior, autocomplete |
| [controller/processStore.php](controller/processStore.php) | Store module controller | All store form submissions |
| [controller/processCathLab.php](controller/processCathLab.php) | CathLab operations | Cathlab GRN, procedures, billing integration |
| [CATHLAB_PROJECT_SUMMARY.md](CATHLAB_PROJECT_SUMMARY.md) | CathLab architecture doc | Understanding sub-store model |
| [GST_IMPLEMENTATION_GUIDE.md](GST_IMPLEMENTATION_GUIDE.md) | Tax calculation system | Item-level GST in PO/Invoice |

## When Adding New Features

1. **Check if module class exists** (e.g., does `otClass.php` exist for OT features?)
   - If not, create it in [model/](model/) following existing class patterns
2. **Decide: Form → Controller → Model or Direct Query?**
   - Prefer form→controller→model for reusability
   - Only use ajax.php dispatcher for autocomplete/lookup operations
3. **Database**: Run SQL in MySQL directly; no migrations system—document schema changes in markdown
4. **Session Awareness**: Include auth check at top of all new files
5. **Naming Convention**:
   - Forms: `add_feature.php` (create), `edit_feature.php` (edit), `feature_list.php` (read)
   - Controllers: `process{Module}.php`
   - Classes: `{module}Class.php` (lowercase module name)
6. **Test data**: Available in [db/ayurvedha.sql](db/ayurvedha.sql)

## Common Pitfalls to Avoid

- **Don't hardcode session check**: Always include `if(!isset($_SESSION))` block
- **Don't create new DB connection per method**: Reuse `$con = $this->dbconnect()` within class
- **Don't mix calculation logic in views**: Move totals, GST calculations to model methods
- **Don't forget error_reporting(0)**: Many files suppress warnings—check if intentional before removing
- **Don't ignore timezone**: Set timezone in every file for consistent timestamps
- **Variable naming**: `$_REQUEST` used instead of `$_POST/$_GET` (implicit form handling); be aware in security context

## Documentation References

- **CathLab**: [CATHLAB_IMPLEMENTATION_GUIDE.md](CATHLAB_IMPLEMENTATION_GUIDE.md) (architecture, workflows)
- **GST**: [GST_QUICK_REFERENCE.md](GST_QUICK_REFERENCE.md) (quick lookup)
- **Store**: [STORE_MODULE_IMPLEMENTATION_GUIDE.md](STORE_MODULE_IMPLEMENTATION_GUIDE.md) (PO→GRN→Issue)
- **All Guides**: Search `*GUIDE*.md` or `*SUMMARY*.md` files for detailed explanations
