# Patient Details API - Quick Reference

## 🚀 Quick Start

### Test the API (Web Interface)
```
http://localhost/hospital/api/test_patient_api.html      (URL parameters)
http://localhost/hospital/api/test_json_api.html         (JSON body)
```

### Run PHP Tests
```
http://localhost/hospital/api/test_api.php
```

## 📡 API Calls

### JSON Request (Primary Method)
```bash
curl -X POST http://localhost/hospital/api/patient_details.php \
  -H "Content-Type: application/json" \
  -d '{"uhid":"ALV2024001"}'
```

```bash
curl -X POST http://localhost/hospital/api/patient_details.php \
  -H "Content-Type: application/json" \
  -d '{"mobile":"9876543210"}'
```

### URL Parameters (Backward Compatible)
```
GET /hospital/api/patient_details.php?uhid=ALV2024001
GET /hospital/api/patient_details.php?mobile=9876543210
```

## 📥 Response Format

```json
{
  "success": true|false,
  "message": "Status message",
  "data": {
    "uhid": "ALV2024001",
    "name": "PATIENT NAME",
    "mobile": "9876543210",
    "opd_no": "OP2024001",
    "age": "35",
    "gender": "Male",
    "address": "Patient Address",
    "last_visit_date": "2024-01-15 10:30:00"
  }
}
```

## 💻 Code Examples

### JavaScript (JSON Body)
```javascript
fetch('api/patient_details.php', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ uhid: 'ALV2024001' })
})
.then(res => res.json())
.then(data => console.log(data));
```

### JavaScript (URL Parameters)
```javascript
fetch('api/patient_details.php?uhid=ALV2024001')
  .then(res => res.json())
  .then(data => console.log(data));
```

### jQuery (JSON Body)
```javascript
$.ajax({
  url: 'api/patient_details.php',
  type: 'POST',
  contentType: 'application/json',
  data: JSON.stringify({ uhid: 'ALV2024001' }),
  success: function(data) { console.log(data); }
});
```

### jQuery (URL Parameters)
```javascript
$.get('api/patient_details.php', {uhid: 'ALV2024001'}, function(data) {
  console.log(data);
}, 'json');
```

### PHP (JSON Request)
```php
$options = [
  'http' => [
    'method' => 'POST',
    'header' => 'Content-Type: application/json',
    'content' => json_encode(['uhid' => 'ALV2024001'])
  ]
];
$context = stream_context_create($options);
$data = json_decode(
  file_get_contents('api/patient_details.php', false, $context),
  true
);
```

### PHP (URL Parameters)
```php
$data = json_decode(
  file_get_contents('api/patient_details.php?uhid=ALV2024001'),
  true
);
```

### cURL (JSON)
```bash
curl -X POST http://localhost/hospital/api/patient_details.php \
  -H "Content-Type: application/json" \
  -d '{"uhid":"ALV2024001"}'
```

### cURL (URL Parameters)
```bash
curl "http://localhost/hospital/api/patient_details.php?uhid=ALV2024001"
```

## ✅ Success Response
```json
{
  "success": true,
  "message": "Patient details retrieved successfully",
  "data": { /* patient object */ }
}
```

## ❌ Error Response
```json
{
  "success": false,
  "message": "No patient found with the provided uhid",
  "data": null
}
```

## 🔑 Parameters

| Param  | Required | Type   | Example       |
|--------|----------|--------|---------------|
| uhid   | Yes*     | string | ALV2024001    |
| mobile | Yes*     | string | 9876543210    |

*Provide either `uhid` OR `mobile`

## 📋 Response Fields

| Field               | Type    | Description           |
|--------------------|---------|-----------------------|
| success            | boolean | Operation status      |
| message            | string  | Status message        |
| data               | object  | Patient info          |
| data.uhid          | string  | Patient UHID          |
| data.name          | string  | Patient name          |
| data.mobile        | string  | Mobile number         |
| data.opd_no        | string  | OPD number            |
| data.age           | string  | Patient age           |
| data.gender        | string  | Patient gender        |
| data.address       | string  | Patient address       |
| data.last_visit_date | string | Last visit timestamp |

## 🔒 Security

- Input sanitized with `mysqli_real_escape_string()`
- CORS enabled (Access-Control-Allow-Origin: *)
- No session required (uses dbconnect1)
- Returns most recent record only

## 📝 Common Errors

| Error Message | Cause |
|--------------|-------|
| "Please provide either UHID or Mobile number" | No parameters sent |
| "No patient found with the provided uhid" | Patient not in database |
| "No patient found with the provided mobile" | Mobile not in database |
| "Invalid request method" | Not GET/POST request |

## 🗄️ Database

**Table:** `central_opd`  
**Columns Used:** uhid, name, mobile, opd_no, age, gender, address, date

## 📁 Files

- `patient_details.php` - Main API endpoint
- `README.md` - Complete documentation
- `test_patient_api.html` - Interactive test interface
- `test_api.php` - Automated PHP tests
- `QUICK_REFERENCE.md` - This file
- `API_MANIFEST.md` - File listing

## 🧪 Testing

1. **Interactive Test:** Open `test_patient_api.html` in browser
2. **Automated Test:** Run `test_api.php` in browser
3. **Manual Test:** Use cURL or Postman

## 🔧 Troubleshooting

### API Returns Empty Response
- Check database connection in `model/dbconnect.php`
- Verify table `central_opd` exists
- Check PHP error logs

### "No patient found" Error
- Verify UHID/mobile exists in database
- Check data format (UHID: alphanumeric, Mobile: numeric)

### CORS Error
- Headers already set in API file
- If still occurs, check Apache/server configuration

## 📞 Support

For issues or questions, refer to:
- Full documentation: `README.md`
- File manifest: `API_MANIFEST.md`
- System architecture: `../COPILOT-INSTRUCTIONS.md`
