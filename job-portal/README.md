# 🚀 Job Portal Web Application

A comprehensive job portal web application built with Laravel 11, featuring CMS capabilities, REST API, and advanced security features.

## ✨ Features

### Core Features Implemented

-   🔐 **User Authentication** - Login, Register, Password Reset with Email
-   👥 **User Management** - Admin dashboard for complete user CRUD operations
-   💼 **Jobs Management** - Full CMS for job postings with WYSIWYG editor
-   🌐 **Public Job Portal** - Public job listings with search and filtering
-   📱 **REST API** - Secure API endpoints for third-party integrations
-   🎯 **Job Applications** - CV and ID card upload system

## 🔧 Requirements

Before installing this application, ensure your system meets the following requirements:

### System Requirements

-   **PHP**: 8.2 or higher
-   **Composer**: Latest version
-   **Node.js**: 18.x or higher
-   **NPM/Yarn**: Latest version

### Server Requirements

-   **Web Server**: Apache/Nginx
-   **Database**: MySQL 8.0+ or MariaDB 10.3+

### PHP Extensions Required

-   OpenSSL PHP Extension
-   PDO PHP Extension
-   Mbstring PHP Extension
-   Tokenizer PHP Extension
-   XML PHP Extension
-   Ctype PHP Extension
-   JSON PHP Extension
-   BCMath PHP Extension
-   Fileinfo PHP Extension
-   GD PHP Extension (for image processing)

## 🚀 Installation

Follow these step-by-step instructions to set up the Job Portal application:

### Step 1: Clone Repository

```bash
# Clone the repository
git clone https://github.com/nantalira/technical-decodes.git

# Navigate to job-portal directory
cd technical-decodes/job-portal
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies using Composer
composer install

# Install Node.js dependencies
npm install

# Build frontend assets
npm run build
```

### Step 3: Environment Configuration

```bash
# Copy environment example file
cp .env.example .env

# Generate Laravel application key
php artisan key:generate
```

### Step 4: Database Setup

#### Configure Database Connection

Edit your `.env` file and update the database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=job_portal
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

#### Run Migrations and Seeders

```bash
# Run database migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed

# Or run both commands together
php artisan migrate:fresh --seed
```

### Step 5: Storage Configuration

```bash
# Create symbolic link for file storage
php artisan storage:link

# Set proper permissions (Linux/Mac users)
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Step 6: API Setup

```bash
# Generate API keys for testing
php artisan api:generate-key demo-client

# Generate Swagger API documentation
php artisan l5-swagger:generate
```

### Step 7: Start Application

```bash
# Start Laravel development server
php artisan serve

# Application will be available at: http://localhost:8000
```

## ⚙️ Configuration

### Environment Variables

Configure these key variables in your `.env` file:

```env
# Application Settings
APP_NAME="Job Portal"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Password Reset Settings
PASSWORD_RESET_DEMO_MODE=true
PASSWORD_RESET_TOKEN_EXPIRE_HOURS=24

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=job_portal
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration (for password reset emails)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS="noreply@jobportal.com"

# API Security
API_KEYS="demo-key-123,production-key-456"

# File Storage
FILESYSTEM_DISK=public
```

### Mail Configuration

For development environments, change the `PASSWORD_RESET_DEMO_MODE` to `true`.

For production environments, configure proper SMTP settings:

```env
# Example: Gmail SMTP Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls

PASSWORD_RESET_DEMO_MODE=false
```

## 🏃‍♂️ Usage

### Default Login Credentials

After running the database seeders, you can login with these accounts:

**Admin Account:**

-   Email: `admin@jobportal.com`
-   Password: `password`

**Regular User Account:**

-   Email: `user@jobportal.com`
-   Password: `password`

### Available URLs

-   **Home Page**: `http://localhost:8000/` - Public job portal
-   **Login**: `http://localhost:8000/login` - User authentication
-   **Admin Dashboard**: `http://localhost:8000/admin` - Admin panel
-   **API Documentation**: `http://localhost:8000/api/documentation` - Swagger UI

### API Usage

All API endpoints require authentication. Here's how to use them:

```bash
# Get all jobs
curl -H "X-API-Key: demo-key-123" \
     "http://localhost:8000/api/v1/jobs"

# Get job details
curl -H "X-API-Key: demo-key-123" \
     "http://localhost:8000/api/v1/jobs/1"

# Submit job application
curl -X POST \
  -H "X-API-Key: demo-key-123" \
  -F "job_id=1" \
  -F "name=John Doe" \
  -F "email=john@example.com" \
  -F "phone=+1234567890" \
  -F "address=123 Main St" \
  -F "cv_file=@/path/to/cv.pdf" \
  -F "id_card_photo=@/path/to/id.jpg" \
  "http://localhost:8000/api/v1/jobs/apply"
```

## 📚 API Documentation

### Interactive Documentation

Visit `http://localhost:8000/api/documentation` for interactive Swagger UI documentation where you can:

-   View all available endpoints
-   Test API calls directly in the browser
-   See request/response examples
-   Download API specifications

### API Endpoints

| Method | Endpoint             | Description                |
| ------ | -------------------- | -------------------------- |
| GET    | `/api/v1/jobs`       | Get paginated job listings |
| GET    | `/api/v1/jobs/{id}`  | Get specific job details   |
| POST   | `/api/v1/jobs/apply` | Submit job application     |
| GET    | `/api/v1/health`     | API health check           |

### Authentication

Use API key authentication:

-   **Header**: `X-API-Key: your-api-key`
-   **Query Parameter**: `?api_key=your-api-key`

### Rate Limits

-   **100 requests per minute** per IP address
-   Rate limit information included in response headers

## 🛠️ Development Commands

```bash
# Clear all caches
php artisan optimize:clear

# Generate new API key
php artisan api:generate-key client-name

# Regenerate Swagger documentation
php artisan l5-swagger:generate

# Run database migrations
php artisan migrate

# Reset database with fresh data
php artisan migrate:fresh --seed

# Build frontend assets
npm run dev              # Development build
npm run build           # Production build
npm run watch          # Watch for changes
```

## 🚀 Deployment

For production deployment:

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Configure proper database credentials
4. Set up SSL certificate
5. Configure web server (Apache/Nginx)
6. Set up cron jobs for Laravel scheduler
7. Configure mail server settings

## 📋 Task Requirements

1. Flowchart + ERD (Entity Relationship Diagram):
    - Create a Flowchart and ERD to accommodate the requirements based on your knowledge about job portal (reference: glints and jobstreet)
2. User Authentication:
    - Implement user authentication functionality.
    - Include features for user login, and logout.
3. User Management:
    - Create an admin dashboard where authenticated users can manage user accounts.
    - Admins should be able to view, create, update, and delete user accounts.
4. Jobs CMS:
    - Implement a jobs management system where authenticated users can create, update, and delete the jobs.
    - Job must consist of Department, Job Title, Description, Company Logo, Company Name, Published Date, and Expired Date, you can add additional column if it is necessary with your ERD.
    - Description must use a WYSIWYG editor and use the validation for all the data.
5. Public Job Portal Page:
    - Create a public page where visitors can view the list of jobs.
    - Display the list of jobs with the descending order.
    - Visitors can apply the job by filling the form about their data, submit the CV and ID Card Photo (KTP).
6. Job Portal API:
    - Please make a public REST API for display all the list of the available jobs, so it can be integrated in third party application.
    - Please make a REST API for submit the job applications for the visitor, so the visitors from the third’s party application can still apply the jobs.
    - Secure the API, so it cannot be tampering by the malicious person.
7. Bonus Features (Optional):
    - Implement forgot password for user, use email
    - Implement user roles with different permissions.
    - Implement search and filter options for jobs portal.

## Evaluation Criteria

1. Functionality (60%):

-   Proper flowchart and ERD for system requirements.
-   Successful implementation of user authentication.
-   Proper functioning of the admin panel with CRUD operations for user accounts.
-   Job Portal CMS allowing CRUD operations for create new job.
-   Public Job Portal page displaying the available jobs correctly based on status.

2. Code Quality (30%):

-   Clear and well-organized code structure.
-   Proper use of programming language features and libraries.
-   Error handling and validation for user inputs.
-   Proper code to secure the API and application.

3. Bonus Features (10%):

-   Implementation of any or all bonus features will be considered for this part of the evaluation.
