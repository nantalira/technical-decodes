<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Job;
use App\Models\JobApplication;

class JobApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/jobs",
     *     operationId="getJobsList",
     *     tags={"Jobs"},
     *     summary="Get list of available jobs",
     *     description="Returns paginated list of active jobs with optional filtering",
     *     security={{"ApiKeyAuth": {}}, {"ApiKeyQuery": {}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page (max 50)",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=50, default=10)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search in job title, description, or company name",
     *         required=false,
     *         @OA\Schema(type="string", example="developer")
     *     ),
     *     @OA\Parameter(
     *         name="department",
     *         in="query",
     *         description="Filter by department",
     *         required=false,
     *         @OA\Schema(type="string", example="IT")
     *     ),
     *     @OA\Parameter(
     *         name="company",
     *         in="query",
     *         description="Filter by company name",
     *         required=false,
     *         @OA\Schema(type="string", example="Tech Solutions")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Jobs retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Jobs retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="jobs",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/Job")
     *                 ),
     *                 @OA\Property(
     *                     property="pagination",
     *                     type="object",
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="per_page", type="integer", example=10),
     *                     @OA\Property(property="total", type="integer", example=25),
     *                     @OA\Property(property="total_pages", type="integer", example=3),
     *                     @OA\Property(property="has_more_pages", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            // Get query parameters
            $perPage = min($request->get('per_page', 10), 50); // Max 50 items per page
            $search = $request->get('search');
            $department = $request->get('department');
            $company = $request->get('company');

            // Build query - only show active jobs (not expired)
            $query = Job::where('expired_date', '>=', now())
                ->orderBy('published_date', 'desc');

            // Apply filters
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%");
                });
            }

            if ($department) {
                $query->where('department', 'like', "%{$department}%");
            }

            if ($company) {
                $query->where('company_name', 'like', "%{$company}%");
            }

            // Get paginated results
            $jobs = $query->paginate($perPage);

            // Transform data
            $transformedJobs = $jobs->getCollection()->map(function ($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'department' => $job->department,
                    'company_name' => $job->company_name,
                    'company_logo' => $job->company_logo ? asset('storage/' . $job->company_logo) : null,
                    'description' => strip_tags($job->description), // Remove HTML for API
                    'description_html' => $job->description, // Full HTML version
                    'location' => $job->location ?? null,
                    'salary_range' => $job->salary_min && $job->salary_max ?
                        number_format($job->salary_min, 0, ',', '.') . ' - ' . number_format($job->salary_max, 0, ',', '.') . ' IDR' :
                        'Negotiable',
                    'published_date' => $job->published_date,
                    'expired_date' => $job->expired_date,
                    'is_active' => $job->expired_date >= now(), // Dynamic active status
                    'created_at' => $job->created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Jobs retrieved successfully',
                'data' => [
                    'jobs' => $transformedJobs,
                    'pagination' => [
                        'current_page' => $jobs->currentPage(),
                        'per_page' => $jobs->perPage(),
                        'total' => $jobs->total(),
                        'total_pages' => $jobs->lastPage(),
                        'has_more_pages' => $jobs->hasMorePages(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve jobs',
                'error' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/jobs/{id}",
     *     operationId="getJobById",
     *     tags={"Jobs"},
     *     summary="Get job details by ID",
     *     description="Returns detailed information about a specific job",
     *     security={{"ApiKeyAuth": {}}, {"ApiKeyQuery": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Job ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Job retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Job retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/JobDetail")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Job not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $job = Job::where('id', $id)
                ->where('expired_date', '>=', now())
                ->first();

            if (!$job) {
                return response()->json([
                    'success' => false,
                    'message' => 'Job not found or expired'
                ], 404);
            }

            $jobData = [
                'id' => $job->id,
                'title' => $job->title,
                'department' => $job->department,
                'company_name' => $job->company_name,
                'company_logo' => $job->company_logo ? asset('storage/' . $job->company_logo) : null,
                'description' => $job->description,
                'location' => $job->location ?? null,
                'salary_range' => $job->salary_min && $job->salary_max ?
                    number_format($job->salary_min, 0, ',', '.') . ' - ' . number_format($job->salary_max, 0, ',', '.') . ' IDR' :
                    'Negotiable',
                'published_date' => $job->published_date,
                'expired_date' => $job->expired_date,
                'is_active' => $job->expired_date >= now(),
                'application_count' => $job->applications()->count(),
                'created_at' => $job->created_at,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Job retrieved successfully',
                'data' => $jobData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job',
                'error' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/jobs/apply",
     *     operationId="applyForJob",
     *     tags={"Applications"},
     *     summary="Submit job application",
     *     description="Submit a new job application with required documents",
     *     security={{"ApiKeyAuth": {}}, {"ApiKeyQuery": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Job application data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="job_id", type="integer", example=1, description="Job ID to apply for"),
     *                 @OA\Property(property="name", type="string", example="John Doe", description="Applicant full name"),
     *                 @OA\Property(property="email", type="string", format="email", example="john.doe@example.com", description="Applicant email"),
     *                 @OA\Property(property="phone", type="string", example="+62812345678", description="Phone number"),
     *                 @OA\Property(property="address", type="string", example="Jakarta, Indonesia", description="Full address"),
     *                 @OA\Property(property="cv_file", type="string", format="binary", description="CV file (PDF/DOC/DOCX, max 5MB)"),
     *                 @OA\Property(property="id_card_photo", type="string", format="binary", description="ID card photo (JPG/PNG, max 2MB)"),
     *                 @OA\Property(property="cover_letter", type="string", example="I am interested in this position...", description="Cover letter (optional)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Application submitted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Job application submitted successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="application_id", type="integer", example=123),
     *                 @OA\Property(property="job_title", type="string", example="Senior Laravel Developer"),
     *                 @OA\Property(property="company_name", type="string", example="Tech Solutions Inc"),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="applied_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Job not found or expired",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Already applied for this job",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function applyJob(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'job_id' => 'required|exists:jobs,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'cv_file' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
                'id_card_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048', // 2MB max
                'cover_letter' => 'nullable|string|max:2000',
            ], [
                'job_id.required' => 'Job ID is required',
                'job_id.exists' => 'Job not found',
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.email' => 'Invalid email format',
                'phone.required' => 'Phone number is required',
                'address.required' => 'Address is required',
                'cv_file.required' => 'CV file is required',
                'cv_file.mimes' => 'CV must be PDF, DOC, or DOCX format',
                'cv_file.max' => 'CV file size must not exceed 5MB',
                'id_card_photo.required' => 'ID card photo is required',
                'id_card_photo.image' => 'ID card must be an image',
                'id_card_photo.mimes' => 'ID card must be JPEG, PNG, or JPG format',
                'id_card_photo.max' => 'ID card photo size must not exceed 2MB',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if job exists and is active
            $job = Job::where('id', $request->job_id)
                ->where('expired_date', '>=', now())
                ->first();

            if (!$job) {
                return response()->json([
                    'success' => false,
                    'message' => 'Job not found or expired'
                ], 404);
            }

            // Check if user already applied for this job
            $existingApplication = JobApplication::where('job_id', $request->job_id)
                ->where('email', $request->email)
                ->first();

            if ($existingApplication) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already applied for this job'
                ], 409);
            }

            // Store uploaded files
            $cvPath = $request->file('cv_file')->store('applications/cv', 'public');
            $idCardPath = $request->file('id_card_photo')->store('applications/id-cards', 'public');

            // Create job application
            $application = JobApplication::create([
                'job_id' => $request->job_id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'cv_file' => $cvPath,
                'id_card_photo' => $idCardPath,
                'cover_letter' => $request->cover_letter,
                'status' => 'pending',
                'applied_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Job application submitted successfully',
                'data' => [
                    'application_id' => $application->id,
                    'job_title' => $job->title,
                    'company_name' => $job->company_name,
                    'status' => $application->status,
                    'applied_at' => $application->applied_at,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit job application',
                'error' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}
