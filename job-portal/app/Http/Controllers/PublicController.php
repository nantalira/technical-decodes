<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use App\Models\JobBookmark;
use App\Models\JobApplication;

class PublicController extends Controller
{
    /**
     * Display home page with paginated jobs
     */
    public function index(Request $request)
    {
        $query = Job::with(['creator', 'bookmarks', 'applications'])
            ->where('expired_date', '>', now());

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('company_name', 'LIKE', "%{$search}%")
                    ->orWhere('location', 'LIKE', "%{$search}%")
                    ->orWhere('department', 'LIKE', "%{$search}%");
            });
        }

        // Filter by company
        if ($request->has('company') && !empty($request->company)) {
            $query->where('company_name', $request->company);
        }

        // Filter by department
        if ($request->has('department') && !empty($request->department)) {
            $query->where('department', $request->department);
        }

        // Filter by location
        if ($request->has('location') && !empty($request->location)) {
            $query->where('location', $request->location);
        }

        // Filter by salary range
        if ($request->has('salary_min') && !empty($request->salary_min)) {
            $query->where('salary_min', '>=', $request->salary_min);
        }

        if ($request->has('salary_max') && !empty($request->salary_max)) {
            $query->where('salary_max', '<=', $request->salary_max);
        }

        $jobs = $query->orderBy('published_date', 'desc')->paginate(10);

        // Get filter data for dropdowns
        $companies = Job::whereNotNull('company_name')
            ->where('expired_date', '>', now())
            ->distinct()
            ->pluck('company_name')
            ->sort()
            ->values();

        $departments = Job::whereNotNull('department')
            ->where('expired_date', '>', now())
            ->distinct()
            ->pluck('department')
            ->sort()
            ->values();

        $locations = Job::whereNotNull('location')
            ->where('expired_date', '>', now())
            ->distinct()
            ->pluck('location')
            ->sort()
            ->values();

        return view('pages.public.home', compact('jobs', 'companies', 'departments', 'locations'));
    }

    /**
     * Show job details
     */
    public function showJob(Job $job)
    {
        $job->load(['creator', 'bookmarks', 'applications']);

        $isBookmarked = false;
        $isApplied = false;

        if (Auth::check()) {
            $isBookmarked = JobBookmark::where('user_id', Auth::id())
                ->where('job_id', $job->id)
                ->exists();

            $isApplied = JobApplication::where('user_id', Auth::id())
                ->where('job_id', $job->id)
                ->exists();
        }

        return view('pages.public.job-detail', compact('job', 'isBookmarked', 'isApplied'));
    }

    /**
     * Bookmark/Unbookmark a job
     */
    public function toggleBookmark(Job $job)
    {
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Please login to bookmark jobs');
        }

        $bookmark = JobBookmark::where('user_id', Auth::id())
            ->where('job_id', $job->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return redirect()->back()->with('success', 'Job removed from bookmarks');
        } else {
            JobBookmark::create([
                'user_id' => Auth::id(),
                'job_id' => $job->id,
            ]);
            return redirect()->back()->with('success', 'Job bookmarked successfully');
        }
    }

    /**
     * Apply for a job
     */
    public function applyJob(Request $request, Job $job)
    {
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Please login first');
        }

        $user = Auth::user();

        // Check if already applied
        $existingApplication = JobApplication::where('user_id', $user->id)
            ->where('job_id', $job->id)
            ->first();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this job');
        }

        // Get user details
        $userDetail = $user->userDetail;

        // Check if user has CV and KTP
        if (!$userDetail || !$userDetail->cv_path) {
            return redirect()->back()->with('error', 'Please upload your CV/Resume in your profile before applying');
        }

        if (!$userDetail->ktp_path) {
            return redirect()->back()->with('error', 'Please upload your KTP (ID Card) in your profile before applying');
        }

        JobApplication::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $userDetail->phone ?? null,
            'birth_date' => $userDetail->birth_date ?? null,
            'address' => $userDetail->address ?? null,
            'gender' => $userDetail->gender ?? null,
            'cv_path' => $userDetail->cv_path ?? null,
            'ktp_path' => $userDetail->ktp_path ?? null,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Application submitted successfully');
    }

    /**
     * Apply for a job as guest (non-authenticated user)
     */
    public function applyJobGuest(Request $request)
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'gender' => 'nullable|in:male,female',
            'cv_path' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max - REQUIRED
            'ktp_path' => 'required|file|mimes:jpeg,jpg,png,pdf|max:2048', // 2MB max - REQUIRED
        ]);

        $job = Job::findOrFail($validated['job_id']);

        // Check if guest already applied with this email
        $existingApplication = JobApplication::where('email', $validated['email'])
            ->where('job_id', $job->id)
            ->first();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this job with this email');
        }

        // Handle CV upload
        $cvPath = null;
        if ($request->hasFile('cv_path')) {
            $file = $request->file('cv_path');
            $filename = time() . '_cv_' . $file->getClientOriginalName();
            $cvPath = $file->storeAs('guest_applications', $filename, 'public');
        } else {
            return redirect()->back()->with('error', 'CV/Resume is required');
        }

        // Handle KTP upload
        $ktpPath = null;
        if ($request->hasFile('ktp_path')) {
            $file = $request->file('ktp_path');
            $filename = time() . '_ktp_' . $file->getClientOriginalName();
            $ktpPath = $file->storeAs('guest_applications', $filename, 'public');
        } else {
            return redirect()->back()->with('error', 'KTP (ID Card) is required');
        }

        // Create guest application
        JobApplication::create([
            'job_id' => $job->id,
            'user_id' => null, // Guest application
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'birth_date' => $validated['birth_date'],
            'address' => $validated['address'],
            'gender' => $validated['gender'],
            'cv_path' => $cvPath,
            'ktp_path' => $ktpPath,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Application submitted successfully! We will contact you soon.');
    }

    /**
     * Show user's bookmarked jobs
     */
    public function savedJobs()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $savedJobs = Job::whereHas('bookmarks', function ($query) {
            $query->where('user_id', Auth::id());
        })->with(['creator', 'bookmarks', 'applications'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.public.saved-jobs', compact('savedJobs'));
    }

    /**
     * Show user's applied jobs
     */
    public function appliedJobs()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $appliedJobs = Job::whereHas('applications', function ($query) {
            $query->where('user_id', Auth::id());
        })->with(['creator', 'bookmarks', 'applications' => function ($query) {
            $query->where('user_id', Auth::id());
        }])->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.public.applied-jobs', compact('appliedJobs'));
    }

    /**
     * Format salary for display (simple format with IDR)
     */
    public static function formatSalary($min, $max)
    {
        $min = number_format($min);
        $max = number_format($max);

        return 'Rp ' . $min . ' - Rp ' . $max;
    }
}
