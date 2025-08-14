# 🚀 Job Portal REST API Documentation

## Base URL

```
http://your-domain.com/api/v1
```

## Authentication

All API endpoints require an API key for security.

### Methods:

1. **Header Authentication** (Recommended)

```bash
X-API-Key: your-api-key-here
```

2. **Query Parameter**

```bash
?api_key=your-api-key-here
```

## Rate Limits

-   **100 requests per minute** per IP address
-   Rate limit headers included in response

## Endpoints

### 1. 📋 Get All Jobs

**GET** `/jobs`

Retrieve paginated list of active jobs.

#### Parameters:

| Parameter    | Type    | Description                         | Default |
| ------------ | ------- | ----------------------------------- | ------- |
| `per_page`   | integer | Items per page (max 50)             | 10      |
| `search`     | string  | Search in title/description/company | -       |
| `department` | string  | Filter by department                | -       |
| `company`    | string  | Filter by company name              | -       |

#### Example Request:

```bash
curl -X GET "http://localhost:8000/api/v1/jobs?per_page=20&search=developer" \
  -H "X-API-Key: job-portal-key-123"
```

#### Example Response:

```json
{
    "success": true,
    "message": "Jobs retrieved successfully",
    "data": {
        "jobs": [
            {
                "id": 1,
                "title": "Senior Laravel Developer",
                "department": "IT",
                "company_name": "Tech Solutions Inc",
                "company_logo": "http://localhost:8000/storage/logos/company1.png",
                "description": "We are looking for an experienced Laravel developer...",
                "description_html": "<p>We are looking for an <strong>experienced</strong> Laravel developer...</p>",
                "location": "Jakarta",
                "published_date": "2025-08-14",
                "expired_date": "2025-09-14",
                "status": "active",
                "created_at": "2025-08-14T10:30:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 10,
            "total": 25,
            "total_pages": 3,
            "has_more_pages": true
        }
    }
}
```

### 2. 📄 Get Single Job

**GET** `/jobs/{id}`

Retrieve detailed information about a specific job.

#### Example Request:

```bash
curl -X GET "http://localhost:8000/api/v1/jobs/1" \
  -H "X-API-Key: job-portal-key-123"
```

#### Example Response:

```json
{
    "success": true,
    "message": "Job retrieved successfully",
    "data": {
        "id": 1,
        "title": "Senior Laravel Developer",
        "department": "IT",
        "company_name": "Tech Solutions Inc",
        "company_logo": "http://localhost:8000/storage/logos/company1.png",
        "description": "<p>Full job description with HTML formatting...</p>",
        "location": "Jakarta",
        "published_date": "2025-08-14",
        "expired_date": "2025-09-14",
        "status": "active",
        "created_at": "2025-08-14T10:30:00.000000Z"
    }
}
```

### 3. 📝 Submit Job Application

**POST** `/jobs/apply`

Submit a new job application.

#### Required Fields:

| Field           | Type    | Description         | Validation                      |
| --------------- | ------- | ------------------- | ------------------------------- |
| `job_id`        | integer | Job ID to apply for | Required, must exist            |
| `name`          | string  | Applicant full name | Required, max 255 chars         |
| `email`         | string  | Applicant email     | Required, valid email           |
| `phone`         | string  | Phone number        | Required, max 20 chars          |
| `address`       | string  | Full address        | Required                        |
| `cv_file`       | file    | CV document         | Required, PDF/DOC/DOCX, max 5MB |
| `id_card_photo` | file    | ID card photo       | Required, JPG/PNG, max 2MB      |
| `cover_letter`  | string  | Cover letter        | Optional, max 2000 chars        |

#### Example Request:

```bash
curl -X POST "http://localhost:8000/api/v1/jobs/apply" \
  -H "X-API-Key: job-portal-key-123" \
  -F "job_id=1" \
  -F "name=John Doe" \
  -F "email=john@example.com" \
  -F "phone=+62812345678" \
  -F "address=Jakarta, Indonesia" \
  -F "cv_file=@/path/to/cv.pdf" \
  -F "id_card_photo=@/path/to/id.jpg" \
  -F "cover_letter=I am interested in this position..."
```

#### Example Response:

```json
{
    "success": true,
    "message": "Job application submitted successfully",
    "data": {
        "application_id": 123,
        "job_title": "Senior Laravel Developer",
        "company_name": "Tech Solutions Inc",
        "status": "pending",
        "applied_at": "2025-08-14T10:45:00.000000Z"
    }
}
```

### 4. 🔍 API Health Check

**GET** `/health`

Check API status and availability.

#### Example Response:

```json
{
    "success": true,
    "message": "Job Portal API is healthy",
    "version": "v1.0.0",
    "timestamp": "2025-08-14T10:30:00.000000Z"
}
```

### 5. 📖 API Documentation

**GET** `/docs`

Get complete API documentation in JSON format.

## Error Responses

### 401 - Unauthorized

```json
{
    "success": false,
    "message": "Invalid or missing API key",
    "error_code": "INVALID_API_KEY"
}
```

### 404 - Not Found

```json
{
    "success": false,
    "message": "Job not found or expired"
}
```

### 409 - Conflict

```json
{
    "success": false,
    "message": "You have already applied for this job"
}
```

### 422 - Validation Error

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."],
        "cv_file": ["The cv file must be a file of type: pdf, doc, docx."]
    }
}
```

### 429 - Rate Limit Exceeded

```json
{
    "success": false,
    "message": "Rate limit exceeded. Maximum 100 requests per minute.",
    "error_code": "RATE_LIMIT_EXCEEDED"
}
```

### 500 - Server Error

```json
{
    "success": false,
    "message": "Failed to retrieve jobs",
    "error": "Internal server error"
}
```

## Security Features

### 🔐 API Key Authentication

-   Required for all endpoints
-   Multiple API keys supported
-   Keys can be rotated

### 🛡️ Rate Limiting

-   100 requests per minute per IP
-   Prevents API abuse
-   Headers show remaining quota

### 📊 Request Logging

-   All API requests logged
-   IP address tracking
-   Usage analytics

### 🔒 Security Headers

-   XSS Protection
-   Content Type Options
-   Frame Options
-   Referrer Policy

### ✅ Input Validation

-   Comprehensive validation
-   File type restrictions
-   Size limitations
-   XSS prevention

## Getting Started

### 1. Generate API Key

```bash
php artisan api:generate-key your-app-name
```

### 2. Add to Environment

```bash
API_KEYS=existing-keys,your-new-key
```

### 3. Test API

```bash
curl -X GET "http://localhost:8000/api/v1/health" \
  -H "X-API-Key: your-api-key"
```

## Integration Examples

### JavaScript/AJAX

```javascript
const apiKey = "your-api-key";
const baseUrl = "http://localhost:8000/api/v1";

// Get jobs
fetch(`${baseUrl}/jobs`, {
    headers: {
        "X-API-Key": apiKey,
        "Content-Type": "application/json",
    },
})
    .then((response) => response.json())
    .then((data) => console.log(data));
```

### PHP/cURL

```php
$apiKey = 'your-api-key';
$baseUrl = 'http://localhost:8000/api/v1';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/jobs');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-API-Key: ' . $apiKey,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
```

### Python/Requests

```python
import requests

api_key = 'your-api-key'
base_url = 'http://localhost:8000/api/v1'

headers = {
    'X-API-Key': api_key,
    'Content-Type': 'application/json'
}

response = requests.get(f'{base_url}/jobs', headers=headers)
data = response.json()
```

## Support

For API key requests or technical support, contact the administrator.

### Rate Limits

Current implementation supports 100 requests per minute per IP. Contact admin if you need higher limits for your application.
