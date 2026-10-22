# API Folder - File Manifest

## Files Created

### 1. patient_details.php
**Purpose:** Main API endpoint to fetch patient details by UHID or Mobile number

**Features:**
- Accepts GET or POST requests
- **Supports JSON request body (Content-Type: application/json)**
- **Supports URL parameters (backward compatible)**
- Searches central_opd table
- Returns JSON response
- Includes CORS headers for cross-origin requests
- Uses dbconnect1() for session-less access

**Input Formats:**

**JSON Body (Primary):**
```json
{"uhid": "ALV2024001"}
or
{"mobile": "9876543210"}
```

**URL Parameters (Backward Compatible):**
```
?uhid=ALV2024001
?mobile=9876543210
```

**Output Fields:**
- `success` (boolean) - Operation status
- `message` (string) - Status message
- `data` (object) - Patient details
  - `uhid` - Patient UHID
  - `name` - Patient full name
  - `mobile` - Patient mobile number
  - `opd_no` - Latest OPD number
  - `age` - Patient age
  - `gender` - Patient gender
  - `address` - Patient address
  - `last_visit_date` - Last visit date/time

### 2. README.md
**Purpose:** Complete API documentation

**Contains:**
- API overview and endpoint details
- Request/response formats
- Usage examples (cURL, JavaScript, jQuery, PHP)
- Response field descriptions
- Error codes and messages
- Security notes

### 3. test_patient_api.html
**Purpose:** Interactive testing interface for the API (URL parameters method)

**Features:**
- Clean, responsive UI design
- Search by UHID or Mobile (mutual exclusion)
- Real-time API testing
- Formatted result display
- Loading indicators
- Error handling
- Mobile-friendly

### 4. test_json_api.html
**Purpose:** Interactive testing interface for JSON request body method

**Features:**
- Test API with JSON request body
- Example JSON templates
- Syntax highlighting for JSON
- Real-time validation
- Full request/response display
- Error handling

### 5. QUICK_REFERENCE.md
**Purpose:** Quick Developer Guide

**Contains:**
- One-page reference for common tasks
- Code snippets ready to copy-paste
- Both JSON and URL parameter examples

### Example Response

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

## Integration Examples

### JavaScript/jQuery Integration (JSON Body)
```javascript
// With JSON body
$.ajax({
    url: 'api/patient_details.php',
    type: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({ uhid: 'ALV2024001' }),
    success: function(response) {
        if(response.success) {
            console.log('Patient:', response.data.name);
        }
    }
});

// With URL parameters (backward compatible)
$.ajax({
    url: 'api/patient_details.php',
    type: 'GET',
    data: { uhid: 'ALV2024001' },
    success: function(response) {
        if(response.success) {
            console.log('Patient:', response.data.name);
        }
    }
});
```

### PHP Integration (JSON Request)
```php
// With JSON body
$options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode(['uhid' => 'ALV2024001'])
    ]
];
$context = stream_context_create($options);
$response = file_get_contents('http://localhost/hospital/api/patient_details.php', false, $context);
$data = json_decode($response, true);

// With URL parameters (backward compatible)
$url = "http://localhost/hospital/api/patient_details.php?uhid=ALV2024001";
$response = file_get_contents($url);
$data = json_decode($response, true);
```

## Database Table
The API queries the `central_opd` table in the hospital database.

## Security Considerations
- Input sanitization using mysqli_real_escape_string()
- No session required (uses dbconnect1)
- CORS enabled
- Consider adding API key authentication for production use

## Future Enhancements
- API key authentication
- Rate limiting
- Multiple patient results for mobile numbers
- Advanced search filters
- Patient history endpoints
