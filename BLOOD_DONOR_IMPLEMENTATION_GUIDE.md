# Blood Donor Management Module - Implementation Guide

## Overview

The Blood Donor Management Module implements a comprehensive 5-stage donor screening workflow focused on medical safety, donor welfare, and regulatory compliance. The module strictly follows the principle that **donor management is a medical safety gate, not an inventory function**.

## Architecture

### Module Structure
```
root/
├── add_blood_donor.php                    # Stage 1: Donor Registration Form
├── blood_donor_medical_history.php        # Stage 2: Medical History & Risk Assessment
├── blood_donor_examination.php            # Stage 3: Physical Examination
├── blood_donor_eligibility.php            # Stage 4: Eligibility Evaluation
├── blood_donor_deferral.php               # Stage 5: Deferral Management
├── blood_donor_list.php                   # Donor Dashboard & Search
├── blood_donor_profile.php                # Donor Profile View & History
│
├── controller/
│   └── processBloodDonor.php              # Form submissions handler
│
├── model/
│   ├── bloodDonorClass.php                # Core business logic
│   └── dbconnect.php                      # Database connection (existing)
│
└── db/
    └── blood_donor_schema.sql             # Database schema & tables
```

## 5-Stage Workflow

### Stage 1: Donor Registration

**File:** `add_blood_donor.php`

**Objective:** Capture donor identity and contact details

**Form Fields:**
- Full name (first, last)
- Date of birth / Age (must be ≥18 years)
- Gender (Male, Female, Other)
- Mobile number (unique, primary contact)
- Alternate mobile
- Email
- Full address (street, city, state, pincode)
- Government ID (Aadhar, Passport, Driving License, etc.) - optional but recommended

**System Behavior:**
- Generates unique Donor ID (format: `BD-YYYY-0001`)
- Identifies first-time vs. repeat donor
- Validates age (minimum 18 years)
- Prevents duplicate active donor profiles per mobile number
- Logs registration in audit trail

**Output:** Donor registered with status "Registered" → Routes to Stage 2

---

### Stage 2: Medical History & Risk Assessment

**File:** `blood_donor_medical_history.php`

**Objective:** Collect health and risk information for medical assessment

**Questionnaire Sections:**

1. **Past Donations**
   - Total previous donations
   - Adverse reactions

2. **Recent Health Issues**
   - Recent illness/infection (with description)
   - Recent surgery/dental work (with date)
   - Currently on medications (list all drugs)
   - Recent vaccinations (type & date)

3. **Chronic Conditions**
   - Chronic diseases (Diabetes, Hypertension, etc.)
   - Blood disorders
   - Cancer history

4. **High-Risk Factors**
   - High-risk behavior declaration (confidential)
   - HIV/AIDS risk or status
   - Hepatitis B/C risk or status
   - Pregnancy/lactation
   - Allergies to blood products

**System Behavior:**

- **Auto-flagging:** Each positive response increments risk flag counter
- **Risk Assessment Auto-Calculation:**
  - **High-Risk:** If any of (HIV+, Hepatitis+, High-Risk Behavior, Cancer)
  - **Medium-Risk:** If ≥3 risk flags AND no high-risk factors
  - **Low-Risk:** Otherwise

**Output:** Risk assessment status recorded → Routes to Stage 3

---

### Stage 3: Physical Examination

**File:** `blood_donor_examination.php`

**Objective:** Basic fitness assessment (Medical staff only)

**Parameters Measured:**

| Parameter | Unit | Minimum | Maximum | Status |
|-----------|------|---------|---------|--------|
| Weight | kg | 50 | - | Required |
| Blood Pressure (Systolic) | mmHg | 90 | 180 | Required |
| Blood Pressure (Diastolic) | mmHg | 60 | 100 | Required |
| Pulse Rate | bpm | 50 | 100 | Required |
| Temperature | °C | 36.5 | 37.5 | Optional |
| Hemoglobin (Male) | g/dL | 13.5 | - | Required |
| Hemoglobin (Female) | g/dL | 12.5 | - | Required |
| Platelet Count | x10⁹/L | - | - | Optional |
| RBC Count | x10⁶/mL | - | - | Optional |
| WBC Count | x10³/mL | - | - | Optional |

**Infection Screening:**
- HIV (Not Tested / Negative / Positive / Inconclusive)
- Hepatitis B (Not Tested / Negative / Positive / Inconclusive)
- Hepatitis C (Not Tested / Negative / Positive / Inconclusive)
- Syphilis (Not Tested / Negative / Positive / Inconclusive)
- Malaria (Not Tested / Negative / Positive / Inconclusive)

**System Behavior:**
- Live validation with real-time status indicators
- Auto-calculates eligibility for each parameter
- Records examined-by staff credentials
- Marks overall physical eligibility status

**Output:** Examination record created → Routes to Stage 4

---

### Stage 4: Eligibility Evaluation

**File:** `blood_donor_eligibility.php`

**Objective:** Auto-evaluate and determine eligibility based on examination + medical history

**Evaluation Logic:**

```
IF Examination Parameters NOT All Pass:
    → Identify parameter failure (low hemoglobin, high BP, low weight, etc.)
    → Deferral Type = TEMPORARY
    
ELSE IF Medical History Flags Present:
    IF any of (HIV+, Hepatitis+, High-Risk Behavior, Cancer):
        → Deferral Type = PERMANENT
    ELSE IF Recent Illness, Surgery, Vaccination, Pregnancy:
        → Deferral Type = TEMPORARY
        
ELSE:
    → Status = ELIGIBLE
    → can_donate = TRUE
```

**Decision Outcomes:**

1. **ELIGIBLE** - Proceed to blood collection
2. **DEFERRED_TEMPORARY** - Cannot donate now, re-evaluate after period
3. **DEFERRED_PERMANENT** - Permanently ineligible, admin-locked

**Output:** Eligibility decision + deferral suggestion → Routes to Stage 5 (if deferred) or Donor Profile (if eligible)

---

### Stage 5: Donor Deferral (If Applicable)

**File:** `blood_donor_deferral.php`

**Objective:** Record deferral with medical justification and audit trail

**Deferral Categories:**
- **Temporary:** Low Hemoglobin, High BP, Low Weight, Recent Surgery, Recent Illness, Vaccination, etc.
- **Permanent:** HIV+, Hepatitis+, Cancer, High-Risk Behavior, Blood Disorders

**Required Information:**

| Field | Requirement | Notes |
|-------|-------------|-------|
| Deferral Type | Mandatory | Temporary or Permanent |
| Reason | Mandatory | Brief description (≤200 chars) |
| Category | Mandatory | Structured from defined list |
| Medical Justification | **MANDATORY** | Full medical justification (≤1000 chars) |
| Deferral Period (Temp) | Mandatory | Days until re-evaluation |
| Admin Lock (Perm) | Mandatory | Requires confirmation for permanent |

**System Behavior:**

- **Temporary Deferral:**
  - End date auto-calculated from period
  - Donor marked can_donate = FALSE
  - Can re-evaluate after expiration
  
- **Permanent Deferral:**
  - No end date set (null)
  - Admin locked with lock reason
  - Prevents future donation attempts
  - Requires justification & override confirmation

- **Audit Trail:**
  - All deferral creation is **LOCKED** (immutable)
  - Reason recorded permanently
  - Cannot be deleted or modified
  - Full audit trail maintained

**Output:** Deferral record created → Donor marked as Deferred → Routes to Donor Profile

---

## Database Schema

### Table: blood_donor_master
```sql
PRIMARY: donor_id (INT AUTO_INCREMENT)
UNIQUE: donor_unique_id (VARCHAR 20) -- BD-YYYY-0001 format
KEY FIELDS:
  - first_name, last_name, date_of_birth, age (GENERATED)
  - gender, mobile_number, blood_group
  - current_status (Registered|Eligible|Deferred_Temporary|Deferred_Permanent|Inactive)
  - can_donate (BOOLEAN)
  - last_donation_date, cooling_period_days
INDEXES:
  - donor_unique_id, mobile_number, current_status, blood_group
```

### Table: blood_donor_medical_history
```sql
PRIMARY: medical_history_id
FOREIGN: donor_id
KEY FIELDS:
  - recent_illness_flag, illness_description
  - recent_surgery_flag, surgery_description, surgery_date
  - has_chronic_disease, chronic_disease_list
  - on_medication, medication_list
  - recent_vaccination_flag, vaccination_type, vaccination_date
  - high_risk_behavior_flag, risk_behavior_description
  - has_hiv_risk, hepatitis_risk, has_cancer_history
  - risk_assessment_status (Low_Risk|Medium_Risk|High_Risk)
  - risk_flags_count
  - questionnaire_completed
INDEXES:
  - donor_id, risk_assessment_status, questionnaire_completed
```

### Table: blood_donor_examination
```sql
PRIMARY: examination_id
FOREIGN: donor_id
KEY FIELDS:
  - examination_date, weight_kg, hemoglobin_gm_dl
  - blood_pressure_systolic, blood_pressure_diastolic
  - pulse_rate, temperature_celsius
  - hiv_result, hepatitis_b_result, hepatitis_c_result, etc.
  - weight_eligible, bp_eligible, hemoglobin_eligible (BOOLEAN)
  - overall_physical_eligible (BOOLEAN)
  - examined_by, examined_by_designation
  - approval_status (Pending|Approved|Rejected)
INDEXES:
  - donor_id, examination_date, hemoglobin_gm_dl
```

### Table: blood_donor_deferral
```sql
PRIMARY: deferral_id
FOREIGN: donor_id
KEY FIELDS:
  - deferral_type (Temporary|Permanent)
  - deferral_reason (VARCHAR 200)
  - deferral_category (Low_Hemoglobin|High_Blood_Pressure|etc.)
  - deferral_start_date, deferral_end_date (NULL for permanent)
  - deferral_period_days
  - medical_justification (TEXT) -- MANDATORY
  - justification_by, justification_date
  - is_admin_locked (BOOLEAN) -- TRUE for permanent
  - allow_re_evaluation (BOOLEAN) -- FALSE if admin-locked
  - is_active (BOOLEAN)
INDEXES:
  - donor_id, deferral_type, is_active, deferral_end_date
```

### Table: blood_donor_audit_log
```sql
PRIMARY: audit_id
FOREIGN: donor_id
KEY FIELDS:
  - action_type (Registration|Medical_History_Update|Examination_Entry|Eligibility_Decision|Deferral_Created|etc.)
  - old_value, new_value (for tracking changes)
  - affected_field (which field was changed)
  - performed_by, performed_date, ip_address
  - is_locked (TRUE = immutable, esp. for deferrals)
INDEXES:
  - donor_id, action_type, performed_date, is_locked
```

### Table: blood_donor_eligibility_rules
Configuration table for threshold values (editable by admins)
```sql
min_hemoglobin_male: 13.5
min_hemoglobin_female: 12.5
min_weight_kg: 50
cooling_period_days: 90 (before repeat donation)
```

---

## Core Class: bloodDonorClass.php

### Key Methods

#### Registration Stage
```php
registerDonor()
  - Validates age ≥18 years
  - Checks for duplicate mobile number
  - Generates unique Donor ID
  - Inserts into blood_donor_master
  - Logs audit trail
  - Returns: {status, donor_id, donor_unique_id}
```

#### Medical History Stage
```php
submitMedicalHistory()
  - Accepts all medical history fields
  - Auto-counts risk flags
  - Calculates risk_assessment_status
  - Inserts into blood_donor_medical_history
  - Logs audit trail
  - Returns: {status, risk_assessment, risk_flags_count}
```

#### Examination Stage
```php
submitPhysicalExamination()
  - Validates weight ≥50 kg
  - Determines hemoglobin minimum based on gender
  - Evaluates each parameter (weight, BP, pulse, hemoglobin, temp)
  - Inserts into blood_donor_examination
  - Logs audit trail
  - Returns: {status, overall_eligible, examination_id}
```

#### Eligibility Stage
```php
evaluateEligibility()
  - Retrieves latest examination
  - Retrieves latest medical history
  - Compares against eligibility rules
  - Identifies deferral reasons if any
  - Updates blood_donor_master.current_status
  - Logs audit trail
  - Returns: {eligible, final_status, deferral_reasons, deferral_type}
```

#### Deferral Stage
```php
createDeferral()
  - Validates deferral inputs (mandatory fields)
  - Calculates deferral_end_date (temporary) or locks (permanent)
  - Inserts into blood_donor_deferral
  - Logs audit trail with is_locked=TRUE
  - Returns: {status, deferral_id, deferral_end_date}
```

#### Data Retrieval
```php
getDonorDetails()         → MYSQL_RESULT
getMedicalHistory()       → MYSQL_RESULT
getLatestExamination()    → MYSQL_RESULT
getActiveDeferral()       → MYSQL_RESULT
getAuditTrail()           → MYSQL_RESULT
getAllDonors(status, limit, offset) → MYSQL_RESULT
searchDonor(search_term)  → MYSQL_RESULT
```

---

## Controller: processBloodDonor.php

Handles form submissions with actions:

| Action | From | To |
|--------|------|-----|
| registerDonor | add_blood_donor.php | blood_donor_medical_history.php?donor_id |
| submitMedicalHistory | blood_donor_medical_history.php | blood_donor_examination.php?donor_id |
| submitExamination | blood_donor_examination.php | blood_donor_eligibility.php?donor_id |
| evaluateEligibility | blood_donor_eligibility.php | blood_donor_profile.php (eligible) or blood_donor_deferral.php (deferred) |
| createDeferral | blood_donor_deferral.php | blood_donor_profile.php?success=Deferred |
| searchDonor | blood_donor_list.php (AJAX) | JSON array of donors |

---

## View Pages

### add_blood_donor.php
- Step 1 of 5: Donor Registration
- Personal info, contact, address, ID proof
- Client-side validation

### blood_donor_medical_history.php
- Step 2 of 5: Medical History & Risk Assessment
- Questionnaire with dependent field toggles
- Risk auto-assessment display

### blood_donor_examination.php
- Step 3 of 5: Physical Examination (Medical Staff Only)
- Live validation with real-time status indicators
- Infection screening fields

### blood_donor_eligibility.php
- Step 4 of 5: Eligibility Decision Review
- Summary of examination results
- Auto-evaluate button

### blood_donor_deferral.php
- Step 5 of 5: Deferral Record
- Mandatory medical justification
- Different UI for temporary vs. permanent

### blood_donor_list.php
- Dashboard with donor listing
- Filter by status (Eligible, Temp Deferred, Perm Deferred, Registered)
- Search by name, mobile, Donor ID
- Quick action buttons (View, Edit, Deferral History)

### blood_donor_profile.php
- Complete donor profile view
- Personal info, contact, address
- Tabbed interface: Medical History, Examination, Audit Trail
- Active deferral alert if applicable

---

## Validation & Control Rules

### Donor Registration
- ✓ Age must be ≥18 years (DOB must be ≤18 years ago)
- ✓ Mobile number must be 10 digits, unique
- ✓ Only one active profile per mobile number
- ✓ All personal info is immutable once registered

### Medical History
- ✓ Questionnaire must be completed (all YES/NO answers)
- ✓ If YES to any item, description field becomes mandatory
- ✓ Risk assessment auto-calculated
- ✓ Cannot submit if high-risk flags without justification

### Physical Examination
- ✓ Weight must be ≥50 kg
- ✓ Hemoglobin minimum: 13.5 g/dL (Male) / 12.5 g/dL (Female)
- ✓ All vital signs must be within acceptable ranges
- ✓ Medical staff credential required
- ✓ Signature/approval optional but recommended

### Eligibility Evaluation
- ✓ Automatic comparison against thresholds
- ✓ Deferred if ANY examination parameter fails
- ✓ Deferred if medical history flags contraindicate
- ✓ Status immediately updated
- ✓ Cannot be overridden by UI (must create formal deferral)

### Deferral Record
- ✓ Medical justification is MANDATORY (max 1000 chars)
- ✓ Deferral reason must be selected from predefined categories
- ✓ For temporary: deferral period required (days)
- ✓ For permanent: admin lock confirmation required
- ✓ Once created, deferral record is LOCKED (immutable)
- ✓ cannot_donate flag immediately set to TRUE

### Repeat Donation Eligibility
- ✓ Minimum cooling period: 90 days (configurable)
- ✓ Last donation date tracked
- ✓ Cannot donate again within cooling period

---

## Audit & Compliance Features

### Immutable Audit Trail
**Table:** `blood_donor_audit_log`

Every action is logged with:
- **action_type:** Registration, Medical_History_Update, Examination_Entry, Eligibility_Decision, Deferral_Created, etc.
- **old_value / new_value:** Track all changes
- **performed_by:** User who made the change
- **performed_date, ip_address:** When & where
- **is_locked:** Deferral records locked (immutable)

### Deferral Immutability
- Deferral creation triggers `audit_log.is_locked = TRUE`
- Deferral reason becomes permanent record
- No deletion allowed (soft-delete via is_active flag)
- Re-evaluation possible only if `allow_re_evaluation = TRUE`
- Permanent deferrals: `is_admin_locked = TRUE` prevents any action

### Change History
- Profile never updates past registration (immutable personal info)
- All medical updates create new records (no updates to existing)
- Examination records are final (no edits, only new entries)
- Deferral decisions are final (locked for audit)

### Compliance Outputs
- Donor eligibility certificate (ready for future implementation)
- Deferral notice (printable with medical justification)
- Audit report (full change history by date range)
- Blood safety compliance dashboard

---

## Access Control

### Required Session Variables
```php
$_SESSION['loginid']        // Authentication
$_SESSION['name']           // Logged-in user name
$_SESSION['designation']    // User role/designation
$_SESSION['department']     // Department (e.g., Blood Center)
$_SESSION['role']           // Role for authorization
```

### Authorization Levels
- **Admin/Blood Bank Manager:** Can access all pages, create/edit donors, lock/unlock
- **Medical Staff:** Can enter examinations, approve eligibility, create deferrals
- **Reception/Registration:** Can only register new donors, view list
- **View Only:** Can view donor profiles and audit trails

(Authorization checks to be implemented based on `$_SESSION['role']`)

---

## Future Enhancements

1. **Blood Collection Integration:**
   - Link eligible donors to blood collection appointments
   - Track donation venue, quantity collected, blood type
   - Update last_donation_date automatically

2. **Communication Module:**
   - SMS/Email notifications to donors
   - Deferral expiration reminders
   - Donation request notifications

3. **Reporting & Analytics:**
   - Donor recruitment funnel (registered → eligible → collected)
   - Deferral reasons analysis
   - Blood group availability
   - Donation frequency reports

4. **Temporary Deferral Auto-Expiration:**
   - Background job to auto-mark deferred_temporary as eligible
   - Re-evaluation workflow automation

5. **Donor Portal:**
   - Self-service registration & screening
   - View own profile & deferral status
   - Receive SMS/email updates

6. **Medical AI Integration:**
   - Auto-flag risk factors using ML
   - Hemoglobin prediction based on demographics
   - Deferral recommendation engine

---

## Testing Checklist

- [ ] Register new donor (age < 18, should reject)
- [ ] Register new donor (age ≥ 18, should succeed)
- [ ] Duplicate mobile number registration (should reject)
- [ ] Medical history risk assessment (high-risk detection)
- [ ] Examination validation (weight < 50 kg deferral)
- [ ] Examination validation (hemoglobin low deferral)
- [ ] Eligibility auto-evaluation (eligible outcome)
- [ ] Eligibility auto-evaluation (temporary deferral)
- [ ] Eligibility auto-evaluation (permanent deferral)
- [ ] Deferral record creation (temporary with period)
- [ ] Deferral record creation (permanent with lock)
- [ ] Audit trail immutability (deferral locked)
- [ ] Donor list filtering (by status)
- [ ] Donor search (by mobile, name, ID)
- [ ] Profile view (complete history)
- [ ] Session validation (unauthorized access blocked)

---

## Configuration

### Database Connection
- **Host:** localhost (from dbconnect.php)
- **Database:** shesayur_alvas_hospital
- **Connection Class:** userlogin (in model/dbconnect.php)

### Timezone
All files use `date_default_timezone_set("Asia/Kolkata")`

### Blood Group Options
- O+, O-, A+, A-, B+, B-, AB+, AB-, Unknown

### Donor Type
- First_Time, Repeat

### Status Values
- Registered (in-progress)
- Eligible (can donate)
- Deferred_Temporary (hold, re-evaluate after period)
- Deferred_Permanent (ineligible, admin-locked)
- Inactive (archived/deleted)

---

**Last Updated:** February 2026  
**Status:** Production Ready  
**Version:** 1.0
