<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Job Portal API Documentation",
 *     description="REST API for Job Portal application with job listings and applications",
 *     @OA\Contact(
 *         email="admin@golekgawe.com",
 *         name="Job Portal Admin"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Job Portal API Server (Dynamic from APP_URL)"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local Development Server"
 * )
 *
 * @OA\Server(
 *     url="http://localhost",
 *     description="Local Server (Port 80)"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="ApiKeyAuth",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-Key",
 *     description="API Key untuk mengakses endpoints"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="ApiKeyQuery",
 *     type="apiKey",
 *     in="query",
 *     name="api_key",
 *     description="API Key sebagai query parameter"
 * )
 *
 * @OA\Tag(
 *     name="Jobs",
 *     description="Job management endpoints"
 * )
 *
 * @OA\Tag(
 *     name="Applications",
 *     description="Job application endpoints"
 * )
 *
 * @OA\Tag(
 *     name="System",
 *     description="System and health check endpoints"
 * )
 *
 * @OA\Schema(
 *     schema="Job",
 *     type="object",
 *     title="Job",
 *     description="Job listing information",
 *     @OA\Property(property="id", type="integer", example=1, description="Job unique identifier"),
 *     @OA\Property(property="title", type="string", example="Senior Laravel Developer", description="Job title"),
 *     @OA\Property(property="company_name", type="string", example="Tech Solutions Inc", description="Company name"),
 *     @OA\Property(property="location", type="string", example="Jakarta, Indonesia", description="Job location"),
 *     @OA\Property(property="job_type", type="string", enum={"full_time", "part_time", "contract", "freelance"}, example="full_time"),
 *     @OA\Property(property="salary_range", type="string", example="10-15 million IDR", description="Salary range"),
 *     @OA\Property(property="experience_required", type="string", example="3-5 years", description="Required experience"),
 *     @OA\Property(property="skills_required", type="array", @OA\Items(type="string"), example={"Laravel", "PHP", "MySQL"}),
 *     @OA\Property(property="is_active", type="boolean", example=true, description="Job availability status"),
 *     @OA\Property(property="application_deadline", type="string", format="date", example="2024-12-31"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="JobDetail",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/Job")
 *     },
 *     @OA\Property(property="description", type="string", example="We are looking for an experienced Laravel developer..."),
 *     @OA\Property(property="requirements", type="array", @OA\Items(type="string"), example={"Bachelor degree in IT", "3+ years Laravel experience"}),
 *     @OA\Property(property="benefits", type="array", @OA\Items(type="string"), example={"Health insurance", "Flexible working hours"}),
 *     @OA\Property(property="application_count", type="integer", example=15, description="Number of applications received")
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     title="Error Response",
 *     description="Standard error response format",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Resource not found"),
 *     @OA\Property(property="error_code", type="string", example="RESOURCE_NOT_FOUND"),
 *     @OA\Property(property="timestamp", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="ValidationErrorResponse",
 *     type="object",
 *     title="Validation Error Response",
 *     description="Validation error response format",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="The given data was invalid"),
 *     @OA\Property(property="errors", type="object",
 *         @OA\Property(property="email", type="array", @OA\Items(type="string"), example={"The email field is required."}),
 *         @OA\Property(property="cv_file", type="array", @OA\Items(type="string"), example={"The cv file must be a file of type: pdf, doc, docx."})
 *     ),
 *     @OA\Property(property="timestamp", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="PaginatedJobsResponse",
 *     type="object",
 *     title="Paginated Jobs Response",
 *     description="Paginated job listings response",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Job")),
 *     @OA\Property(
 *         property="pagination",
 *         type="object",
 *         @OA\Property(property="current_page", type="integer", example=1),
 *         @OA\Property(property="total_pages", type="integer", example=5),
 *         @OA\Property(property="per_page", type="integer", example=20),
 *         @OA\Property(property="total_records", type="integer", example=87),
 *         @OA\Property(property="has_next", type="boolean", example=true),
 *         @OA\Property(property="has_prev", type="boolean", example=false)
 *     )
 * )
 */
abstract class Controller
{
    //
}
