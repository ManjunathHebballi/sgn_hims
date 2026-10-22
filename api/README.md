# Patient Details API Documentation

## Overview
This API fetches patient details from the central OPD table based on UHID number or mobile number.

**Input Formats Supported:**
- ✅ JSON request body (Content-Type: application/json)
- ✅ URL parameters (GET/POST)

## Endpoint
```
POST: /api/patient_details.php (with JSON body)
GET:  /api/patient_details.php?uhid=xxx or ?mobile=xxx
```

## Request Formats

### JSON Request Body (Primary Method)
```json
{
  "uhid": "ALV2024001"
}
```
or
```json
{
  "mobile": "9876543210"
}
```

### URL Parameters (Backward Compatible)
```
?uhid=ALV2024001
?mobile=9876543210
```

## Request Parameters

| Parameter | Type   | Required | Description                    |
|-----------|--------|----------|--------------------------------|
| uhid      | string | No*      | Patient's UHID number          |
| mobile    | string | No*      | Patient's mobile number        |

**Note:** Either `uhid` OR `mobile` must be provided.

## Response Format

### Success Response
```json
{
    "success": true,
    "message": "Patient details retrieved successfully",
    "data": {
        "uhid": "ALV2024001",
        "name": "JOHN DOE",
        "mobile": "9876543210",
        "opd_no": "OP2024001",
        "age": "35",
        "gender": "Male",
        "address": "123 Main Street, City",
        "last_visit_date": "2024-01-15 10:30:00"
    }
}
```

### Error Response
```json
{
    "success": false,
    "message": "No patient found with the provided uhid",
    "data": null
}
```

## Usage Examples

### 1. Using cURL with JSON (by UHID)
```bash
curl -X POST "http://localhost/hospital/api/patient_details.php" \
  -H "Content-Type: application/json" \
  -d '{"uhid": "ALV2024001"}'
```

### 2. Using cURL with JSON (by Mobile)
```bash
curl -X POST "http://localhost/hospital/api/patient_details.php" \
  -H "Content-Type: application/json" \
  -d '{"mobile": "9876543210"}'
```

### 3. Using cURL with URL Parameters (backward compatible)
```bash
curl -X GET "http://localhost/hospital/api/patient_details.php?uhid=ALV2024001"
curl -X GET "http://localhost/hospital/api/patient_details.php?mobile=9876543210"
```

### 4. Using JavaScript (Fetch API) with JSON
```javascript
// Search by UHID with JSON body
fetch('http://localhost/hospital/api/patient_details.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        uhid: 'ALV2024001'
    })
})
.then(response => response.json())
.then(data => {
    if(data.success) {
        console.log('Patient Name:', data.data.name);
        console.log('Mobile:', data.data.mobile);
        console.log('UHID:', data.data.uhid);
    } else {
        console.log('Error:', data.message);
    }
});

// Search by Mobile with JSON body
fetch('http://localhost/hospital/api/patient_details.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        mobile: '9876543210'
    })
})
.then(response => response.json())
.then(data => {
    console.log(data);
});
```

### 5. Using jQuery AJAX with JSON
```javascript
// With JSON body
$.ajax({
    url: 'api/patient_details.php',
    type: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({ uhid: 'ALV2024001' }),
    dataType: 'json',
    success: function(response) {
        if(response.success) {
            alert('Patient: ' + response.data.name);
        } else {
            alert('Error: ' + response.message);
        }
    }
});

// With URL parameters (backward compatible)
$.ajax({
    url: 'api/patient_details.php',
    type: 'GET',
    data: { uhid: 'ALV2024001' },
    dataType: 'json',
    success: function(response) {
        if(response.success) {
            alert('Patient: ' + response.data.name);
        } else {
            alert('Error: ' + response.message);
        }
    }
});
```

### 6. Using PHP
```php
// Method 1: JSON Request
$data = [
    'uhid' => 'ALV2024001'
];

$options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode($data)
    ]
];

$context = stream_context_create($options);
$response = file_get_contents('http://localhost/hospital/api/patient_details.php', false, $context);
$result = json_decode($response, true);

if($result['success']) {
    echo "Patient Name: " . $result['data']['name'];
    echo "Mobile: " . $result['data']['mobile'];
    echo "UHID: " . $result['data']['uhid'];
}

// Method 2: URL Parameters (backward compatible)
$uhid = 'ALV2024001';
$url = "http://localhost/hospital/api/patient_details.php?uhid=" . urlencode($uhid);
$response = file_get_contents($url);
$data = json_decode($response, true);

if($data['success']) {
    echo "Patient Name: " . $data['data']['name'];
}
```

## Response Fields

| Field            | Type   | Description                        |
|------------------|--------|------------------------------------|
| success          | boolean| Operation status (true/false)      |
| message          | string | Status message                     |
| data             | object | Patient information (null on error)|
| data.uhid        | string | Patient's UHID                     |
| data.name        | string | Patient's full name                |
| data.mobile      | string | Patient's mobile number            |
| data.opd_no      | string | Latest OPD number                  |
| data.age         | string | Patient's age                      |
| data.gender      | string | Patient's gender                   |
| data.address     | string | Patient's address                  |
| data.last_visit_date | string | Last visit date and time      |

## Error Codes & Messages

| Message                                      | Cause                                |
|---------------------------------------------|--------------------------------------|
| "Please provide either UHID or Mobile number" | No parameters sent                |
| "No patient found with the provided [type]"  | Patient not found in database     |
| "Invalid search type"                        | Internal error                    |
| "Database query failed"                      | SQL error                         |
| "Invalid request method"                     | Not GET or POST request           |

## Security Notes
- The API uses `dbconnect1()` which doesn't require session authentication
- Input is sanitized using `mysqli_real_escape_string()`
- CORS headers are enabled for cross-origin requests
- For production, consider adding API key authentication

## Notes
- Returns the most recent record if multiple entries exist for the same UHID/mobile
- All patient names are stored in UPPERCASE in the database
- Date format: YYYY-MM-DD HH:MM:SS
