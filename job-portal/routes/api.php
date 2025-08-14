<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Job Portal API Routes
Route::prefix('v1')->middleware(['api.security'])->group(function () {

    // Jobs API
    Route::get('/jobs', [JobApiController::class, 'index'])->name('api.jobs.index');
    Route::get('/jobs/{id}', [JobApiController::class, 'show'])->name('api.jobs.show');

    // Job Applications API
    Route::post('/jobs/apply', [JobApiController::class, 'applyJob'])->name('api.jobs.apply');

    // API Health Check
    Route::get('/health', function () {
        return response()->json([
            'success' => true,
            'message' => 'Job Portal API is healthy',
            'version' => 'v1.0.0',
            'timestamp' => now()->toISOString(),
        ]);
    })->name('api.health');

    // API Documentation endpoint
    Route::get('/docs', function () {
        return response()->json([
            'success' => true,
            'message' => 'Job Portal API Documentation',
            'version' => 'v1.0.0',
            'base_url' => url('/api/v1'),
            'endpoints' => [
                'GET /jobs' => [
                    'description' => 'Get all available jobs with pagination',
                    'parameters' => [
                        'per_page' => 'Number of items per page (max 50, default 10)',
                        'search' => 'Search in title, description, company name',
                        'department' => 'Filter by department',
                        'company' => 'Filter by company name',
                    ]
                ],
                'GET /jobs/{id}' => [
                    'description' => 'Get single job details',
                    'parameters' => [
                        'id' => 'Job ID (required)'
                    ]
                ],
                'POST /jobs/apply' => [
                    'description' => 'Submit job application',
                    'parameters' => [
                        'job_id' => 'Job ID (required)',
                        'name' => 'Applicant name (required)',
                        'email' => 'Applicant email (required)',
                        'phone' => 'Phone number (required)',
                        'address' => 'Full address (required)',
                        'cv_file' => 'CV file (PDF/DOC/DOCX, max 5MB)',
                        'id_card_photo' => 'ID card photo (JPG/PNG, max 2MB)',
                        'cover_letter' => 'Cover letter (optional)',
                    ]
                ]
            ],
            'authentication' => [
                'type' => 'API Key',
                'header' => 'X-API-Key',
                'parameter' => 'api_key (query string)',
                'note' => 'Contact administrator for API key'
            ],
            'rate_limits' => [
                'requests_per_minute' => 100,
                'based_on' => 'IP address'
            ]
        ]);
    })->name('api.docs');
});
