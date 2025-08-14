# 🧪 Job Portal API Testing

## Quick Test Commands

### 1. Health Check

```bash
curl -X GET "http://localhost:8000/api/v1/health" \
  -H "X-API-Key: job-portal-key-123"
```

### 2. Get API Documentation

```bash
curl -X GET "http://localhost:8000/api/v1/docs" \
  -H "X-API-Key: job-portal-key-123"
```

### 3. Get All Jobs

```bash
curl -X GET "http://localhost:8000/api/v1/jobs" \
  -H "X-API-Key: job-portal-key-123"
```

### 4. Get Jobs with Search

```bash
curl -X GET "http://localhost:8000/api/v1/jobs?search=developer&per_page=5" \
  -H "X-API-Key: job-portal-key-123"
```

### 5. Get Single Job

```bash
curl -X GET "http://localhost:8000/api/v1/jobs/1" \
  -H "X-API-Key: job-portal-key-123"
```

### 6. Test Invalid API Key

```bash
curl -X GET "http://localhost:8000/api/v1/jobs" \
  -H "X-API-Key: invalid-key"
```

### 7. Test Rate Limiting

```bash
for i in {1..105}; do
  curl -X GET "http://localhost:8000/api/v1/health" \
    -H "X-API-Key: job-portal-key-123" &
done
```

## Test Job Application (with Files)

### Create Test Files First:

```bash
# Create dummy CV (Linux/Mac)
echo "John Doe - Software Developer CV" > test-cv.txt

# Create dummy ID card image (requires ImageMagick)
convert -size 300x200 xc:white -pointsize 20 -fill black \
  -annotate +50+100 "ID Card Photo" test-id.jpg
```

### Submit Application:

```bash
curl -X POST "http://localhost:8000/api/v1/jobs/apply" \
  -H "X-API-Key: job-portal-key-123" \
  -F "job_id=1" \
  -F "name=John Doe" \
  -F "email=john.doe@example.com" \
  -F "phone=+62812345678" \
  -F "address=Jakarta, Indonesia" \
  -F "cv_file=@test-cv.txt" \
  -F "id_card_photo=@test-id.jpg" \
  -F "cover_letter=I am very interested in this position"
```

## PowerShell Testing (Windows)

### Health Check:

```powershell
Invoke-RestMethod -Uri "http://localhost:8000/api/v1/health" `
  -Headers @{"X-API-Key"="job-portal-key-123"} `
  -Method GET
```

### Get Jobs:

```powershell
Invoke-RestMethod -Uri "http://localhost:8000/api/v1/jobs" `
  -Headers @{"X-API-Key"="job-portal-key-123"} `
  -Method GET | ConvertTo-Json -Depth 5
```

## Expected Response Formats

### Successful Job List:

```json
{
  "success": true,
  "message": "Jobs retrieved successfully",
  "data": {
    "jobs": [...],
    "pagination": {...}
  }
}
```

### Error Response:

```json
{
    "success": false,
    "message": "Invalid or missing API key",
    "error_code": "INVALID_API_KEY"
}
```

## Performance Testing

### Apache Bench (if installed):

```bash
ab -n 100 -c 10 -H "X-API-Key: job-portal-key-123" \
  http://localhost:8000/api/v1/jobs
```

### Simple Bash Loop Test:

```bash
#!/bin/bash
for i in {1..50}; do
  echo "Request $i"
  curl -s -X GET "http://localhost:8000/api/v1/health" \
    -H "X-API-Key: job-portal-key-123" | jq '.success'
  sleep 0.1
done
```

## Debugging

### Check API Logs:

```bash
tail -f storage/logs/api.log
```

### Check Laravel Logs:

```bash
tail -f storage/logs/laravel.log
```

### Check Rate Limit Cache:

```bash
php artisan tinker
>>> Cache::get('api_rate_limit_127.0.0.1')
```
