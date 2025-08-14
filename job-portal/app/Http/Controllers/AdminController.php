<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Use raw queries for better performance
        $stats = [
            'total_users' => DB::table('users')->count(),
            'total_jobs' => DB::table('jobs')->count(),
            'total_applications' => DB::table('job_applications')->count(),
            'pending_applications' => DB::table('job_applications')->where('status', 'pending')->count(),
            'recent_users' => User::select('id', 'name', 'email', 'created_at')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('pages.admin.dashboard', compact('stats'));
    }

    public function users(Request $request)
    {
        $query = User::with(['userDetail' => function ($q) {
            $q->select('user_id', 'phone', 'address', 'cv_path', 'ktp_path');
        }]);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhereHas('userDetail', function ($subq) use ($search) {
                        $subq->where('phone', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Filter by role
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }

        // Filter by documents status
        if ($request->has('documents') && !empty($request->documents)) {
            switch ($request->documents) {
                case 'complete':
                    $query->whereHas('userDetail', function ($q) {
                        $q->whereNotNull('cv_path')->whereNotNull('ktp_path');
                    });
                    break;
                case 'partial':
                    $query->whereHas('userDetail', function ($q) {
                        $q->where(function ($subq) {
                            $subq->whereNotNull('cv_path')->whereNull('ktp_path')
                                ->orWhereNull('cv_path')->whereNotNull('ktp_path');
                        });
                    });
                    break;
                case 'none':
                    $query->where(function ($q) {
                        $q->whereDoesntHave('userDetail')
                            ->orWhereHas('userDetail', function ($subq) {
                                $subq->whereNull('cv_path')->whereNull('ktp_path');
                            });
                    });
                    break;
            }
        }

        // Filter by phone availability
        if ($request->has('phone') && !empty($request->phone)) {
            if ($request->phone === 'yes') {
                $query->whereHas('userDetail', function ($q) {
                    $q->whereNotNull('phone');
                });
            } else {
                $query->where(function ($q) {
                    $q->whereDoesntHave('userDetail')
                        ->orWhereHas('userDetail', function ($subq) {
                            $subq->whereNull('phone');
                        });
                });
            }
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $allowedSorts = ['name', 'email', 'role', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $users = $query->paginate($perPage);

        return view('pages.admin.users-manage', compact('users'));
    }

    public function editUser(Request $request, User $user)
    {
        // Get filtered users for the main table using the same logic as users() method
        $query = User::with(['userDetail' => function ($q) {
            $q->select('user_id', 'phone', 'address', 'cv_path', 'ktp_path');
        }]);

        // Apply the same filters as in users() method
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhereHas('userDetail', function ($subq) use ($search) {
                        $subq->where('phone', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Filter by role
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }

        // Filter by documents status
        if ($request->has('documents') && !empty($request->documents)) {
            switch ($request->documents) {
                case 'complete':
                    $query->whereHas('userDetail', function ($q) {
                        $q->whereNotNull('cv_path')->whereNotNull('ktp_path');
                    });
                    break;
                case 'partial':
                    $query->whereHas('userDetail', function ($q) {
                        $q->where(function ($subq) {
                            $subq->whereNotNull('cv_path')->whereNull('ktp_path')
                                ->orWhereNull('cv_path')->whereNotNull('ktp_path');
                        });
                    });
                    break;
                case 'none':
                    $query->where(function ($q) {
                        $q->whereDoesntHave('userDetail')
                            ->orWhereHas('userDetail', function ($subq) {
                                $subq->whereNull('cv_path')->whereNull('ktp_path');
                            });
                    });
                    break;
            }
        }

        // Filter by phone availability
        if ($request->has('phone') && !empty($request->phone)) {
            if ($request->phone === 'yes') {
                $query->whereHas('userDetail', function ($q) {
                    $q->whereNotNull('phone');
                });
            } else {
                $query->where(function ($q) {
                    $q->whereDoesntHave('userDetail')
                        ->orWhereHas('userDetail', function ($subq) {
                            $subq->whereNull('phone');
                        });
                });
            }
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $allowedSorts = ['name', 'email', 'role', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $users = $query->paginate($perPage);

        // Pass editUser for modal
        $editUser = $user;
        $editUser->load('userDetail');

        return view('pages.admin.users-manage', compact('users', 'editUser'));
    }

    public function deleteUserConfirm(Request $request, User $user)
    {
        // Get filtered users for the main table using the same logic as users() method
        $query = User::with(['userDetail' => function ($q) {
            $q->select('user_id', 'phone', 'address', 'cv_path', 'ktp_path');
        }]);

        // Apply the same filters as in users() method
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhereHas('userDetail', function ($subq) use ($search) {
                        $subq->where('phone', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Filter by role
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }

        // Filter by documents status
        if ($request->has('documents') && !empty($request->documents)) {
            switch ($request->documents) {
                case 'complete':
                    $query->whereHas('userDetail', function ($q) {
                        $q->whereNotNull('cv_path')->whereNotNull('ktp_path');
                    });
                    break;
                case 'partial':
                    $query->whereHas('userDetail', function ($q) {
                        $q->where(function ($subq) {
                            $subq->whereNotNull('cv_path')->whereNull('ktp_path')
                                ->orWhereNull('cv_path')->whereNotNull('ktp_path');
                        });
                    });
                    break;
                case 'none':
                    $query->where(function ($q) {
                        $q->whereDoesntHave('userDetail')
                            ->orWhereHas('userDetail', function ($subq) {
                                $subq->whereNull('cv_path')->whereNull('ktp_path');
                            });
                    });
                    break;
            }
        }

        // Filter by phone availability
        if ($request->has('phone') && !empty($request->phone)) {
            if ($request->phone === 'yes') {
                $query->whereHas('userDetail', function ($q) {
                    $q->whereNotNull('phone');
                });
            } else {
                $query->where(function ($q) {
                    $q->whereDoesntHave('userDetail')
                        ->orWhereHas('userDetail', function ($subq) {
                            $subq->whereNull('phone');
                        });
                });
            }
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $allowedSorts = ['name', 'email', 'role', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $users = $query->paginate($perPage);

        // Pass deleteUser for modal
        $deleteUser = $user;
        $deleteUser->load('userDetail');

        return view('pages.admin.users-manage', compact('users', 'deleteUser'));
    }

    public function updateUser(Request $request, User $user)
    {
        try {
            // Validate request (no password field) with file uploads
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'ktp_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Update user
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Get existing user detail or prepare new data
            $userDetail = $user->userDetail;
            $updateData = [
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ];

            // Handle CV file upload
            if ($request->hasFile('cv_file')) {
                // Delete old CV file if exists
                if ($userDetail && $userDetail->cv_path && Storage::disk('public')->exists($userDetail->cv_path)) {
                    Storage::disk('public')->delete($userDetail->cv_path);
                }
                $updateData['cv_path'] = $request->file('cv_file')->store('user-documents/cv', 'public');
            }

            // Handle KTP file upload
            if ($request->hasFile('ktp_file')) {
                // Delete old KTP file if exists
                if ($userDetail && $userDetail->ktp_path && Storage::disk('public')->exists($userDetail->ktp_path)) {
                    Storage::disk('public')->delete($userDetail->ktp_path);
                }
                $updateData['ktp_path'] = $request->file('ktp_file')->store('user-documents/ktp', 'public');
            }

            // Update or create user detail
            $user->userDetail()->updateOrCreate(
                ['user_id' => $user->id],
                $updateData
            );

            return redirect()->route('admin.users')->with('success', 'User updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('admin.users.edit', $user->id)
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('admin.users.edit', $user->id)
                ->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    public function deleteUser(User $user)
    {
        try {
            // Prevent admin from deleting themselves
            if (Auth::id() === $user->id) {
                return redirect()->back()->with('error', 'You cannot delete your own account.');
            }

            // Delete user detail first (if exists)
            if ($user->userDetail) {
                $user->userDetail->delete();
            }

            // Delete user
            $user->delete();

            return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
    public function jobs(Request $request)
    {
        $query = Job::query();

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

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('published_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('published_date', '<=', $request->date_to);
        }

        // Get per page setting
        $perPage = $request->get('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        // Validate sort column
        $allowedSorts = ['title', 'company_name', 'created_at', 'published_date', 'expired_date'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        // Validate direction
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        // Get paginated results with application counts
        $jobs = $query->withCount('applications')
            ->orderBy($sort, $direction)
            ->paginate($perPage);

        // Get filter data for dropdowns
        $companies = Job::whereNotNull('company_name')
            ->distinct()
            ->pluck('company_name')
            ->sort()
            ->values();

        $departments = Job::whereNotNull('department')
            ->distinct()
            ->pluck('department')
            ->sort()
            ->values();

        $locations = Job::whereNotNull('location')
            ->distinct()
            ->pluck('location')
            ->sort()
            ->values();

        return view('pages.admin.jobs-manage', compact('jobs', 'companies', 'departments', 'locations'));
    }

    public function createUser(Request $request)
    {
        try {
            // Validate request with optimized unique rule and file uploads
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    'unique:users,email'
                ],
                'password' => 'required|string|min:8',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'ktp_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Use database transaction for data consistency
            DB::beginTransaction();

            try {
                // Create user
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => 'user'
                ]);

                // Handle file uploads
                $cvPath = null;
                $ktpPath = null;

                if ($request->hasFile('cv_file')) {
                    $cvPath = $request->file('cv_file')->store('user-documents/cv', 'public');
                }

                if ($request->hasFile('ktp_file')) {
                    $ktpPath = $request->file('ktp_file')->store('user-documents/ktp', 'public');
                }

                // Create user detail
                $user->userDetail()->create([
                    'phone' => $validated['phone'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'cv_path' => $cvPath,
                    'ktp_path' => $ktpPath,
                    'birth_date' => null
                ]);

                DB::commit();
                return redirect()->route('admin.users')->with('success', 'User created successfully!');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating user: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Job Management CRUD Methods (selain jobs() yang sudah ada)
    public function editJob(Job $job)
    {
        return view('pages.admin.jobs-edit', compact('job'));
    }

    public function deleteJobConfirm(Job $job)
    {
        return view('pages.admin.jobs-delete', compact('job'));
    }

    public function createJobPost(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'company_name' => 'required|string|max:255',
                'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'department' => 'nullable|string|max:100',
                'location' => 'nullable|string|max:255',
                'published_date' => 'required|date',
                'expired_date' => 'required|date|after:published_date',
            ]);

            // Handle company logo upload
            if ($request->hasFile('company_logo')) {
                $file = $request->file('company_logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('company_logos', $filename, 'public');
                $validated['company_logo'] = $path;
            }

            $validated['created_by'] = Auth::id();

            Job::create($validated);

            return redirect()->route('admin.jobs')->with('success', 'Job created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating job: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateJob(Request $request, Job $job)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'company_name' => 'required|string|max:255',
                'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'department' => 'nullable|string|max:100',
                'location' => 'nullable|string|max:255',
                'published_date' => 'required|date',
                'expired_date' => 'required|date|after:published_date',
            ]);

            // Handle company logo upload
            if ($request->hasFile('company_logo')) {
                // Delete old logo if exists
                if ($job->company_logo) {
                    Storage::disk('public')->delete($job->company_logo);
                }

                $file = $request->file('company_logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('company_logos', $filename, 'public');
                $validated['company_logo'] = $path;
            }

            $job->update($validated);

            return redirect()->route('admin.jobs')->with('success', 'Job updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('admin.jobs.edit', $job->id)
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('admin.jobs.edit', $job->id)
                ->with('error', 'Error updating job: ' . $e->getMessage());
        }
    }

    public function deleteJob(Job $job)
    {
        try {
            // Check if job has applications
            $applicationsCount = $job->applications()->count();
            if ($applicationsCount > 0) {
                return redirect()->back()->with(
                    'error',
                    "Cannot delete this job because it has {$applicationsCount} applications."
                );
            }

            // Delete related bookmarks first
            $job->bookmarks()->delete();

            // Delete the job
            $job->delete();

            return redirect()->route('admin.jobs')->with('success', 'Job deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting job: ' . $e->getMessage());
        }
    }

    // Job Applications Management Methods
    public function jobApplications(Request $request, Job $job)
    {
        $query = $job->applications()->with('user');

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by applicant name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'applied_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $applications = $query->paginate(15)->withQueryString();

        return view('pages.admin.job-applications', compact('job', 'applications'));
    }

    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,reviewing,accepted,rejected'
            ]);

            $application->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Application status updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating application status: ' . $e->getMessage()
            ], 500);
        }
    }
}
