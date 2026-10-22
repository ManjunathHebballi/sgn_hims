# Blood Donor Module - Quick Reference Guide

## Module Access Points

### Main Entry
- **Donor Registration:** `add_blood_donor.php`
- **Donor Dashboard:** `blood_donor_list.php`
- **Donor Profile:** `blood_donor_profile.php?donor_id=X`

## 5-Stage Workflow Flow Chart

```
┌─────────────────────────────────────────────────────────────────┐
│ Stage 1: DONOR REGISTRATION (add_blood_donor.php)              │
│ ├─ Capture: Name, DOB (≥18), Gender, Mobile, Address, ID Proof │
│ ├─ Validation: Age check, Mobile uniqueness, Duplicate check    │
│ └─ Output: Donor ID generated → Stage 2                         │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ Stage 2: MEDICAL HISTORY (blood_donor_medical_history.php)     │
│ ├─ Collect: Past donations, Illness, Surgery, Medications      │
│ ├─ Questionnaire: Yes/No + Detail fields for positive answers   │
│ ├─ Risk Assessment: Auto-flag & categorize (Low/Medium/High)    │
│ └─ Output: Risk assessment → Stage 3                            │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ Stage 3: PHYSICAL EXAM (blood_donor_examination.php)            │
│ ├─ Measure: Weight, BP, Pulse, Temperature, Hemoglobin          │
│ ├─ Test: HIV, Hepatitis B/C, Syphilis, Malaria                 │
│ ├─ Validation: Real-time status for each parameter              │
│ └─ Output: Exam results → Stage 4                               │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ Stage 4: ELIGIBILITY (blood_donor_eligibility.php)              │
│ ├─ Auto-Evaluate: Exam + Medical History vs. Rules              │
│ ├─ Decision: Eligible OR Deferred (Temp/Perm)                  │
│ └─ Output: If ELIGIBLE → Profile | If DEFERRED → Stage 5       │
└─────────────────────────────────────────────────────────────────┘
                            ↓ (If Deferred)
┌─────────────────────────────────────────────────────────────────┐
│ Stage 5: DEFERRAL (blood_donor_deferral.php)                    │
│ ├─ Record: Reason, Category, Medical Justification (MANDATORY)  │
│ ├─ Lock: Temporary (with period) OR Permanent (admin-locked)    │
│ └─ Output: Deferral immutable → Profile                         │
└─────────────────────────────────────────────────────────────────┘
```

## Key Thresholds & Rules

### Age & Demographics
- Minimum Age: **18 years**
- Legal Eligibility: Age ≥ 18
- Repeat Donation Cooling: **90 days**

### Weight
- Minimum: **50 kg**
- Below 50 kg → Temporary Deferral

### Hemoglobin (g/dL)
- Male: ≥ **13.5**
- Female: ≥ **12.5**
- Below minimum → Temporary Deferral

### Blood Pressure (mmHg)
- Systolic: 90-180 (Target)
- Diastolic: 60-100 (Target)
- Out of range → Temporary Deferral

### Pulse Rate (bpm)
- Target: 50-100
- Below/above → Temporary Deferral

### Temperature (°C)
- Normal: 36.5-37.5
- Abnormal → Temporary Deferral (optional check)

## Risk Assessment Auto-Calculation

**Input:** Medical history flags (YES/NO answers)

**Logic:**
```
IF any of (HIV+, Hepatitis+, High-Risk Behavior, Cancer):
    Risk = HIGH_RISK
    Deferral Type = PERMANENT

ELSE IF (≥3 flags and no high-risk factors):
    Risk = MEDIUM_RISK
    Deferral Type = TEMPORARY (if medical history issue)

ELSE:
    Risk = LOW_RISK
    (Check Examination results for deferral)
```

**Risk Flags Counted:**
1. Recent Illness
2. Recent Surgery
3. Chronic Disease
4. Current Medication
5. Recent Vaccination
6. High-Risk Behavior
7. Cancer History
8. Blood Disorder
9. HIV Risk
10. Hepatitis Risk
11. Pregnancy
12. Allergies

## Deferral Categories

### Temporary Deferrals (can re-evaluate after period)
- Low_Hemoglobin (suggest 30 days)
- High_Blood_Pressure (suggest 30 days)
- Low_Blood_Pressure (suggest 30 days)
- Insufficient_Weight (suggest 30 days)
- Recent_Surgery (suggest 90 days)
- Recent_Illness (suggest 14 days)
- Recent_Vaccination (suggest 7 days)
- Pregnancy (suggest 365 days)
- Menstruation (suggest 3 days)
- Medication_Contraindication (suggest 14 days)

### Permanent Deferrals (admin-locked, no re-evaluation)
- HIV_Positive
- Hepatitis_Positive
- Malaria_Risk
- Cancer_History
- Blood_Disorder
- High_Risk_Behavior
- Disease_Risk

## Database Tables

| Table | Purpose | Key Fields |
|-------|---------|-----------|
| `blood_donor_master` | Donor registration | donor_id, donor_unique_id, current_status, can_donate |
| `blood_donor_medical_history` | Health questionnaire | donor_id, risk_assessment_status, risk_flags_count |
| `blood_donor_examination` | Physical measurements | donor_id, hemoglobin_gm_dl, weight_kg, overall_physical_eligible |
| `blood_donor_deferral` | Deferral records | donor_id, deferral_type, deferral_start_date, is_locked |
| `blood_donor_audit_log` | Immutable audit trail | donor_id, action_type, is_locked |
| `blood_donor_eligibility_rules` | Configuration | min_hemoglobin_male/female, min_weight_kg, cooling_period_days |

## Class Methods Reference

### bloodDonorClass (model/bloodDonorClass.php)

**Setter Methods:**
```php
setDonorId($id)
setFirstName($name)
setLastName($name)
setDateOfBirth($date)
setGender($gender)
setMobileNumber($mobile)
setAddress($address)
// ... and all other property setters
```

**Getter Methods:**
```php
getDonorId()
getFirstName()
getBloodGroup()
getCurrentStatus()
getCanDonate()
getHemoglobin()
getRiskAssessmentStatus()
// ... and all other property getters
```

**Business Methods:**
```php
registerDonor()                 → {status, donor_id, donor_unique_id}
submitMedicalHistory()          → {status, risk_assessment, risk_flags_count}
submitPhysicalExamination()     → {status, overall_eligible, examination_id}
evaluateEligibility()           → {eligible, final_status, deferral_reasons}
createDeferral()                → {status, deferral_id, deferral_end_date}
getDonorDetails()               → MySQL Result
getMedicalHistory()             → MySQL Result
getLatestExamination()          → MySQL Result
getActiveDeferral()             → MySQL Result
getAuditTrail()                 → MySQL Result
getAllDonors($status, $limit)   → MySQL Result
searchDonor($search_term)       → MySQL Result
```

## Controller Actions (processBloodDonor.php)

```
POST action=registerDonor
    From: add_blood_donor.php
    Redirect: blood_donor_medical_history.php?donor_id=X

POST action=submitMedicalHistory
    From: blood_donor_medical_history.php
    Redirect: blood_donor_examination.php?donor_id=X

POST action=submitExamination
    From: blood_donor_examination.php
    Redirect: blood_donor_eligibility.php?donor_id=X

POST action=evaluateEligibility
    From: blood_donor_eligibility.php
    Redirect: blood_donor_profile.php (if eligible)
              blood_donor_deferral.php (if deferred)

POST action=createDeferral
    From: blood_donor_deferral.php
    Redirect: blood_donor_profile.php?success=Deferred

GET action=searchDonor&term=XXX
    From: blood_donor_list.php (AJAX)
    Response: JSON array of matching donors
```

## Form Field Validation

### Registration Form
- First Name: Required, max 100 chars
- Last Name: Optional, max 100 chars
- Date of Birth: Required, max 18 years ago
- Gender: Required, enum (Male|Female|Other)
- Mobile Number: Required, 10 digits, unique
- Address: Required, max 500 chars
- City, State, Pincode: Optional
- ID Proof Type: Optional enum
- ID Proof Number: Optional, max 50 chars

### Medical History Form
- Total Donations: Optional, min 0
- All medical flags: Checkbox (YES/NO)
- Detail fields: Text/TextArea, required if flag=YES
- Risk Assessment: Auto-calculated on submit

### Examination Form
- Examination Date: Required, today's date
- Weight: Required, min 30 kg, max 200 kg
- Blood Pressure: Required, systolic/diastolic
- Pulse Rate: Required, 20-200 bpm
- Hemoglobin: Required, decimal
- Temperature: Optional
- Infection Tests: Optional, enum (Not Tested|Negative|Positive|Inconclusive)

### Deferral Form
- Deferral Reason: Required, max 200 chars
- Medical Justification: **REQUIRED**, max 1000 chars
- Deferral Period (Temp): Required if temporary
- Admin Lock Confirm (Perm): Required checkbox if permanent
- Staff Comments: Optional

## Common Issues & Solutions

### Problem: Donor registered but can't find them
**Solution:** Search using Donor ID (BD-YYYY-XXXX format) or exact mobile number

### Problem: "Duplicate active donor profile" error
**Solution:** Check if same mobile number already registered. Use search to find existing donor.

### Problem: Hemoglobin fails eligibility but no deferral created
**Solution:** Create deferral from blood_donor_deferral.php with medical justification. This is a mandatory step.

### Problem: Deferral record shows locked error
**Solution:** Deferral records are immutable by design. Cannot modify once created. Contact admin if correction needed.

### Problem: Temporary deferral expired but still shows as deferred
**Solution:** System doesn't auto-expire. Must manually re-evaluate donor after period ends and create new eligible record.

## Sample Donor ID Format
- **BD-2026-0001** (Blood Donor - Year - Sequence)
- **BD-2026-0250** (250th donor registered in 2026)

## Key Design Principles

1. **Medical Safety First:** All decisions are conservative (defer when in doubt)
2. **Immutability:** Deferral records cannot be deleted or modified (audit compliance)
3. **Transparency:** Full audit trail of all changes with user/timestamp
4. **No Inventory Link:** Donor management is separate from blood unit tracking
5. **Donor Welfare:** Deferred donors are protected from health risks
6. **Regulatory Compliance:** Full documentation for medical & safety audits

## Quick Setup

```bash
1. Run SQL script: db/blood_donor_schema.sql
2. Include in navigation: blood_donor_list.php
3. Set session: $_SESSION['loginid'], $_SESSION['name'], $_SESSION['designation']
4. Navigate: http://yourhost/blood_donor_list.php
5. Start: "Register New Donor" button
```

## File Sizes & Performance

| File | Lines | Purpose |
|------|-------|---------|
| bloodDonorClass.php | ~1200 | Core business logic |
| processBloodDonor.php | ~280 | Form controller |
| blood_donor_schema.sql | ~400 | Database setup |
| add_blood_donor.php | ~280 | Registration form |
| blood_donor_medical_history.php | ~350 | Medical questionnaire |
| blood_donor_examination.php | ~500 | Exam form with validation |
| blood_donor_eligibility.php | ~250 | Decision review |
| blood_donor_deferral.php | ~350 | Deferral creation |
| blood_donor_list.php | ~380 | Dashboard |
| blood_donor_profile.php | ~480 | Profile view |
| **Total** | **~3900** | Complete module |

---

**Last Updated:** February 2026  
**Version:** 1.0 - Production Ready
