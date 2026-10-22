# OPD / IPD Registration API

Two endpoints for registering OPD patients and admitting them as IPD patients from another system.
They follow the same rules as the OP Registration and IP Registration screens: same OPD / UHID / IPD
numbering, same per-doctor token, same bed handling. Logic lives in `model/registrationService.php`.

## Basics

| | |
|---|---|
| Base URL | `http://<server>/sgn_jalna_hospital/api` |
| Method | `POST` only |
| Body | JSON (`Content-Type: application/json`). Form fields are also accepted. |
| Auth | Header `X-API-Key: <REGISTRATION_API_KEY>` (set in `.env`). If the key is not configured, every request is rejected. |
| CORS | Any origin; `OPTIONS` preflight returns `204`. |

Every response has the same shape:

```json
{ "success": true, "message": "…", "data": { … } }
```

On failure `success` is `false`, `message` says what went wrong, and `data` is `null`:

```json
{ "success": false, "message": "Missing required fields: mobile, doctor", "data": null }
```

| HTTP | Meaning |
|------|---------|
| 201 | Created |
| 401 | Missing or wrong API key |
| 404 | (IPD) `opd_no` not found |
| 405 | Not a POST |
| 409 | (IPD) bed already occupied · (OPD) could not allocate a number, retry |
| 422 | Validation error: missing or invalid field, unknown department / doctor / bed |
| 500 | Database error |

Each call creates a new record. Calling twice with the same data registers the patient twice.

---

## 1. OPD Registration

`POST /api/opd_registration.php`

Creates a new OPD registration with a new UHID (`BMSH…`), OPD number (`OP…`) and the doctor's next token for that day.

| Field | Required | Notes |
|-------|----------|-------|
| `name` | yes | Stored in capitals |
| `gender` | yes | `Male`, `Female` or `Other` (`M` / `F` and any letter case also accepted) |
| `age` | yes | e.g. `34` |
| `mobile` | yes | 10 digits; spaces and dashes are removed |
| `department` | yes | Department id, or exact department name |
| `doctor` | yes | Doctor id, or exact "First Last" name |
| `date` | no | `YYYY-MM-DD`, default today |
| `dob` | no | `YYYY-MM-DD` |
| `patient_type` | no | A value from Master → Patient Type, e.g. `GENERAL` |
| `address`, `city`, `district`, `state`, `pincode` | no | |
| `parent_name`, `marital_status`, `religion`, `caste`, `occupation`, `education` | no | |
| `diagnosis`, `sub_department` | no | |
| `economic`, `bpl_no` | no | `bpl_no` is kept only when `economic` is `BPL` |
| `annual_income`, `category`, `pan`, `aadhar`, `policy` | no | |
| `mlc` | no | `0` or `1`, default `0` |
| `abha_number`, `abha_address` | no | |
| `created_by` | no | Shown as "Created By" in reports, default `API` |

### Request

```bash
curl -X POST http://<server>/sgn_jalna_hospital/api/opd_registration.php \
  -H "Content-Type: application/json" \
  -H "X-API-Key: <your key>" \
  -d '{
    "name": "Ravi Kumar",
    "gender": "Male",
    "age": "34",
    "mobile": "9876543210",
    "department": "GENERAL MEDICINE",
    "doctor": "12",
    "address": "MG Road",
    "city": "Hubli",
    "patient_type": "GENERAL"
  }'
```

### Response `201`

```json
{
  "success": true,
  "message": "OPD registration successful",
  "data": {
    "id": 10452,
    "uhid": "BMSH202610090012",
    "opd_no": "OP202610090031",
    "token": 7,
    "date": "2026-10-09",
    "name": "RAVI KUMAR",
    "doctor": "12",
    "rx_id": ""
  }
}
```

`doctor` is the doctor id. `rx_id` is the pharmacy customer id, empty when pharmacy sync is not configured.
Keep `opd_no` to admit the patient later.

---

## 2. IPD Registration (Admission)

`POST /api/ipd_registration.php`

Admits a patient who already has an OPD registration. Name, age, gender, mobile, address and similar details are
copied from the latest OPD registration for `opd_no`. Any of those fields you send replaces the copied value.

The bed is marked occupied and the OPD visit is flagged as admitted, in one transaction: if anything fails, nothing is saved.

| Field | Required | Notes |
|-------|----------|-------|
| `opd_no` | yes | e.g. `OP202610090031` |
| `department` | yes | Department id, or exact department name |
| `doctor` | yes | Doctor id, or exact "First Last" name |
| `bed_number` or `bed_id` | one of them | Bed number (matched within `ward` if given), or bed id. The bed must be free. |
| `ward` | no | Defaults to the bed's ward |
| `admission_date` | no | `YYYY-MM-DD` or `YYYY-MM-DD HH:MM:SS`, default now |
| `diagnosis` | no | Text, or an array of texts (joined with `,`) |
| `patient_type` | no | Default: the OPD registration's patient type |
| `policy`, `insurance_name`, `coverage_type` | no | For `CASHLESS` patient types these are added to the remark, as on the IP Registration screen |
| `refer_by`, `cmo`, `remark` | no | |
| `mlc`, `is_generated` | no | `0` or `1`, default `0` |
| `ipcard_no` | no | Default: the OPD registration's `rx_id` |
| `name`, `age`, `gender`, `mobile`, `address`, `category`, `religion`, `caste`, `parent_name`, `occupation`, `annual_income` | no | Replace the values copied from OPD |
| `created_by` | no | Shown as "Created By" in reports, default `API` |

### Request

```bash
curl -X POST http://<server>/sgn_jalna_hospital/api/ipd_registration.php \
  -H "Content-Type: application/json" \
  -H "X-API-Key: <your key>" \
  -d '{
    "opd_no": "OP202610090031",
    "department": "GENERAL MEDICINE",
    "doctor": "12",
    "ward": "Male",
    "bed_number": "M01",
    "diagnosis": ["Fever", "Dehydration"]
  }'
```

### Response `201`

```json
{
  "success": true,
  "message": "IPD admission successful",
  "data": {
    "id": 3120,
    "ipd_no": "IP20261009004",
    "opd_no": "OP202610090031",
    "uhid": "BMSH202610090012",
    "name": "RAVI KUMAR",
    "admission_date": "2026-10-09 11:20:00",
    "ward": "Male",
    "bed_id": 45,
    "bed_number": "M01",
    "doctor": "Ramesh Patil"
  }
}
```

`doctor` is the doctor's full name, which is how admissions store it.

### Common errors

| HTTP | message |
|------|---------|
| 404 | `No OPD registration found for opd_no OP202610090099` |
| 409 | `Bed M01 (Male) is already occupied` |
| 422 | `Unknown department: CARDIO` |
| 422 | `Unknown bed: M99` |
