# Blood Donor Deferral Clearance System - Complete Implementation

## 📋 Overview

This system automatically handles the clearance of expired temporary deferrals and manages the re-evaluation workflow for donors before they can donate again.

---

## 🗂️ Files Created

### 1. **scripts/check_expired_deferrals.php**
**Purpose:** Automated background script that runs daily to clear expired deferrals

**Functionality:**
- Checks for temporary deferrals where `deferral_end_date < CURDATE()`
- Deactivates expired deferrals (`is_active = 0`)
- Updates donor status: `Deferred_Temporary` → `Pending_Re_Evaluation`
- Creates audit trail entries
- Adds donors to re-evaluation queue
- Logs all operations to `scripts/logs/deferral_clearance_YYYY-MM.log`

**Setup:**
```bash
# Linux/Mac - Add to crontab (runs daily at 2 AM)
0 2 * * * /usr/bin/php /path/to/check_expired_deferrals.php

# Windows - Task Scheduler
Program: C:\xampp\php\php.exe
Arguments: C:\path\to\check_expired_deferrals.php
Trigger: Daily at 2:00 AM
```

---

### 2. **scripts/deferral_clearance_schema_updates.sql**
**Purpose:** Database schema updates to support deferral clearance workflow

**Changes Made:**
- Added new status: `Pending_Re_Evaluation` to `blood_donor_master.current_status`
- Added clearance tracking fields to `blood_donor_deferral`:
  - `clearance_date` - When deferral was cleared
  - `clearance_method` - How it was cleared (Automatic/Manual/Override)
  - `clearance_notes` - Additional notes
  - `re_evaluation_required` - Flag for re-evaluation requirement
- Created new table: `donor_re_evaluation_queue` - Tracks donors needing re-evaluation
- Created view: `v_donors_pending_re_evaluation` - Easy access to pending donors

**Installation:**
```sql
mysql -u username -p database_name < deferral_clearance_schema_updates.sql
```

---

### 3. **blood_donor_re_evaluation.php**
**Purpose:** Interactive workflow page guiding staff through donor re-evaluation

**Features:**
- Displays donor information and previous deferral details
- Shows 4-stage workflow visualization (Current → Medical History → Examination → Eligibility)
- Animated current step indicator
- Clear action buttons to start re-evaluation process
- Warning alerts about requirements
- Links to blood_donor_medical_history.php with `re_evaluation=1` parameter

**Access:** 
```
blood_donor_re_evaluation.php?donor_id={donor_id}
```

---

### 4. **blood_donor_pending_re_evaluation.php**
**Purpose:** List view of all donors pending re-evaluation

**Features:**
- Table view using `v_donors_pending_re_evaluation` view
- Shows days since deferral clearance
- Priority badges (High/Normal/Low)
- Quick action buttons (Start Re-Evaluation / View Profile)
- Empty state message when no pending donors

**Access:**
```
Blood Bank Menu → Pending Re-Evaluation
```

---

### 5. **Updated: add_blood_collection.php**
**Purpose:** Enhanced eligibility checks to handle cleared deferrals

**New Validations:**
- Check for `Pending_Re_Evaluation` status with helpful error message
- Check for recently cleared deferrals (within 90 days)
- Display clickable link to re-evaluation workflow
- Show days remaining in cooling period
- Prevent collection until full re-evaluation complete

---

## 🔄 Complete Workflow

### **Scenario: Donor Deferred on Jan 1, 2026 for 30 days**

#### **Day 0 (Jan 1, 2026) - Deferral Created:**
```sql
INSERT INTO blood_donor_deferral (
    donor_id, deferral_type, deferral_reason, 
    deferral_start_date, deferral_end_date, is_active
) VALUES (
    123, 'Temporary', 'Recent Illness - Fever', 
    '2026-01-01', '2026-01-31', 1
);

UPDATE blood_donor_master 
SET current_status = 'Deferred_Temporary', can_donate = 0 
WHERE donor_id = 123;
```

**Donor Status:** `Deferred_Temporary` ❌ Cannot donate

---

#### **Day 31 (Feb 1, 2026) - Automated Clearance:**
```bash
# Cron job executes: check_expired_deferrals.php
```

**Script Actions:**
1. Finds deferral record where `deferral_end_date = '2026-01-31'` and `CURDATE() = '2026-02-01'`
2. Updates deferral:
```sql
UPDATE blood_donor_deferral 
SET is_active = 0, 
    clearance_date = '2026-02-01',
    clearance_method = 'Automatic_Expiry'
WHERE deferral_id = xxx;
```
3. Updates donor:
```sql
UPDATE blood_donor_master 
SET current_status = 'Pending_Re_Evaluation', 
    can_donate = 0 
WHERE donor_id = 123;
```
4. Creates re-evaluation queue entry:
```sql
INSERT INTO donor_re_evaluation_queue (
    donor_id, reason, priority, due_date, status
) VALUES (
    123, 'Deferral clearance - Medical re-evaluation required', 
    'Normal', '2026-03-01', 'Pending'
);
```

**Donor Status:** `Pending_Re_Evaluation` ⚠️ Requires re-evaluation

---

#### **Day 32 (Feb 2, 2026) - Staff Checks System:**

**Option A: View Pending List**
- Navigate to: `Blood Bank → Pending Re-Evaluation`
- See donor in list with "1 day since clearance"
- Click: "Start Re-Evaluation" button

**Option B: Try Collection (Blocked)**
- Navigate to: `add_blood_collection.php?donor_id=123`
- System shows error:
  ```
  ❌ Collection Blocked - Eligibility Issues:
  • Donor requires medical re-evaluation after deferral clearance
  • Required: Complete Medical History → Physical Examination → Approval
  • [Click here to start re-evaluation process]
  ```

---

#### **Day 32 (Feb 2, 2026) - Re-Evaluation Process:**

**Step 1: Review Workflow**
- Page: `blood_donor_re_evaluation.php?donor_id=123`
- Shows previous deferral: "Recent Illness - Fever (Cleared on 01-Feb-2026)"
- Workflow visualization with animated current step
- Click: "Start Medical History (Stage 2)"

**Step 2: Medical History**
- Page: `blood_donor_medical_history.php?donor_id=123&re_evaluation=1`
- Complete fresh medical questionnaire
- Verify fever is resolved, no new issues
- Submit → Status becomes `Medical_History_Completed`

**Step 3: Physical Examination**
- Page: `blood_donor_examination.php?donor_id=123`
- Check vital signs, hemoglobin, overall health
- All parameters normal
- Submit → Status becomes `Examination_Completed`

**Step 4: Medical Officer Approval**
- Medical officer reviews:
  - Previous deferral reason (fever)
  - Current medical history (no issues)
  - Examination results (all normal)
- **Decision: APPROVED**

```sql
UPDATE blood_donor_master 
SET current_status = 'Eligible', 
    can_donate = 1 
WHERE donor_id = 123;

UPDATE donor_re_evaluation_queue 
SET status = 'Completed', 
    completed_date = NOW() 
WHERE donor_id = 123 AND status = 'Pending';
```

**Donor Status:** `Eligible` ✅ Can donate

---

#### **Day 32 (Feb 2, 2026) - Collection Allowed:**
- Navigate to: `add_blood_collection.php?donor_id=123`
- Eligibility checks pass ✓
- Collection can proceed
- Blood unit created

---

## 🎯 Status Lifecycle

```
┌──────────────────┐
│    Registered    │ (Initial registration)
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│     Eligible     │ (After Stage 2 + 3 approval)
└────────┬─────────┘
         │
         ▼ (Issue detected during screening)
┌──────────────────┐
│Deferred_Temporary│ (Fever, medication, etc.)
└────────┬─────────┘
         │
         ▼ (Clearance date reached - AUTOMATED)
┌──────────────────┐
│Pending_Re_Eval   │ (Requires fresh screening)
└────────┬─────────┘
         │
         ▼ (Medical History + Examination)
┌──────────────────┐
│ Medical Review   │ (Staff evaluation)
└────────┬─────────┘
         │
    ┌────┴────┐
    │         │
    ▼         ▼
┌────────┐ ┌──────────────────┐
│Eligible│ │Deferred (New)    │
└────────┘ └──────────────────┘
```

---

## 🔒 Business Rules

| Rule | Description |
|------|-------------|
| **Auto-Clear** | Only temporary deferrals auto-clear; permanent deferrals need medical director override |
| **No Auto-Eligible** | Cleared deferral → `Pending_Re_Evaluation`, NOT `Eligible` |
| **Full Re-Screening** | Must complete Medical History + Examination + Approval |
| **Cooling Period** | Still applies even after deferral clearance |
| **Audit Trail** | All status changes logged in `blood_donor_audit_trail` |
| **Queue Priority** | High-risk deferrals (HIV, Hepatitis) get priority re-evaluation |
| **Notification** | Optional SMS/Email when deferral clears (commented in script) |

---

## 📊 Database Views & Queries

### **Find Expired Deferrals (Manual Check):**
```sql
SELECT d.donor_id, dm.donor_unique_id, 
       CONCAT(dm.first_name, ' ', dm.last_name) AS name,
       d.deferral_reason, d.deferral_end_date,
       DATEDIFF(CURDATE(), d.deferral_end_date) AS days_overdue
FROM blood_donor_deferral d
INNER JOIN blood_donor_master dm ON d.donor_id = dm.donor_id
WHERE d.is_active = 1 
AND d.deferral_type = 'Temporary'
AND d.deferral_end_date < CURDATE()
ORDER BY d.deferral_end_date ASC;
```

### **Pending Re-Evaluation Summary:**
```sql
SELECT * FROM v_donors_pending_re_evaluation 
WHERE days_since_clearance > 7
ORDER BY days_since_clearance DESC;
```

### **Re-Evaluation Completion Rate:**
```sql
SELECT 
    COUNT(CASE WHEN status = 'Completed' THEN 1 END) AS completed,
    COUNT(CASE WHEN status = 'Pending' THEN 1 END) AS pending,
    COUNT(*) AS total,
    ROUND(COUNT(CASE WHEN status = 'Completed' THEN 1 END) * 100.0 / COUNT(*), 2) AS completion_rate
FROM donor_re_evaluation_queue
WHERE created_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY);
```

---

## 🛠️ Troubleshooting

### **Script Not Running:**
```bash
# Check cron logs
grep CRON /var/log/syslog

# Test script manually
php scripts/check_expired_deferrals.php

# Check log file
cat scripts/logs/deferral_clearance_2026-02.log
```

### **Donors Not Clearing:**
```sql
-- Check if deferrals exist
SELECT * FROM blood_donor_deferral 
WHERE is_active = 1 
AND deferral_end_date < CURDATE();

-- Manual clear (if needed)
UPDATE blood_donor_deferral 
SET is_active = 0, clearance_date = CURDATE() 
WHERE deferral_id = xxx;
```

### **View Not Working:**
```sql
-- Recreate view
DROP VIEW IF EXISTS v_donors_pending_re_evaluation;
-- Then run view creation from schema file
```

---

## 📱 User Interface Access Points

1. **Main Menu:** `Blood Bank → Pending Re-Evaluation`
2. **Donor Profile:** Shows alert if status = `Pending_Re_Evaluation`
3. **Collection Page:** Blocks with error + link to re-evaluation
4. **Donor List:** Filter by status `Pending_Re_Evaluation`

---

## ✅ Testing Checklist

- [ ] Run schema updates SQL
- [ ] Test automated script manually
- [ ] Create test deferral with past end date
- [ ] Verify auto-clearance works
- [ ] Check donor status changes correctly
- [ ] Test re-evaluation workflow page loads
- [ ] Complete full re-evaluation process
- [ ] Verify collection is blocked until eligible
- [ ] Check audit trail entries created
- [ ] Test pending re-evaluation list page

---

## 📞 Support

For issues or questions about the deferral clearance system, check:
- Script logs: `scripts/logs/deferral_clearance_*.log`
- Database audit: `blood_donor_audit_trail` table
- System notifications in donor profile page

---

**Implementation Date:** February 3, 2026  
**Version:** 1.0  
**Status:** Production Ready ✅
