@include('layouts.header')

@section('head-extra')
    <link href="{{ asset('css/tinymce-content.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pagination.css') }}" rel="stylesheet">
@endsection

@auth
    @if (Auth::user()->role === 'admin')
        @include('layouts.admin-sidebar')
    @else
        @include('layouts.sidebar')
    @endif
@else
    @include('layouts.sidebar')
@endauth

<main id="main" class="main">
    <div class="container-fluid">
        <div class="row">
            <!-- Job Cards - Left Column (70%) -->
            <div class="col-lg-8">
                <!-- Job Cards -->
                <div class="row g-1">
                    @forelse($jobs as $job)
                        <div class="col-12">
                            <div class="card job-card" data-bs-toggle="offcanvas"
                                data-bs-target="#jobDetails{{ $job->id }}"
                                style="cursor: pointer; transition: all 0.3s ease;">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-9">
                                            <div class="d-flex align-items-start justify-content-between mb-2">
                                                <div>
                                                    @if ($job->created_at->diffInDays() <= 1)
                                                        <span class="badge bg-success mt-3">New</span>
                                                    @endif
                                                    <h5 class="card-title mb-1 fw-bold">{{ $job->title }}</h5>
                                                    <h6 class="text-primary mb-2">{{ $job->company_name }}</h6>
                                                    @if ($job->salary_min && $job->salary_max)
                                                        <p class="mb-1">
                                                            {{ App\Http\Controllers\PublicController::formatSalary($job->salary_min, $job->salary_max) }}
                                                        </p>
                                                    @endif
                                                    <p class="text-muted mb-2">
                                                        <i class="bi bi-geo-alt"></i> {{ $job->location }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="company-logo p-3">
                                                @if ($job->company_logo)
                                                    <img src="{{ asset('storage/' . $job->company_logo) }}"
                                                        alt="{{ $job->company_name }}"
                                                        style="width: 100px; height: 100px; object-fit: contain;">
                                                @else
                                                    <div
                                                        style="width: 100px; height: 100px; background: linear-gradient(45deg, #{{ sprintf('%06X', mt_rand(0, 0xffffff)) }}, #{{ sprintf('%06X', mt_rand(0, 0xffffff)) }}); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                                        <span
                                                            style="font-weight: bold; color: white; font-size: 36px;">{{ substr($job->company_name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <small class="text-muted">{{ $job->created_at->diffForHumans() }}</small>
                                            @auth
                                                @if ($job->applications()->where('user_id', Auth::id())->exists())
                                                    • <small class="text-success">Applied</small>
                                                @endif
                                            @endauth
                                        </div>
                                        <div class="col text-end">
                                            @auth
                                                <form action="{{ route('jobs.bookmark', $job->id) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link p-0 border-0"
                                                        style="font-size: 1.2rem;" onclick="event.stopPropagation();">
                                                        <i
                                                            class="bi {{ $job->bookmarks()->where('user_id', Auth::id())->exists() ? 'bi-bookmark-fill text-primary' : 'bi-bookmark text-muted' }}"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <i class="bi bi-bookmark text-muted"
                                                    style="font-size: 1.2rem; cursor: pointer;"
                                                    onclick="event.stopPropagation(); alert('Please login to bookmark jobs')"></i>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Offcanvas Job Details -->

                    @empty
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center py-5">
                                    <i class="bi bi-briefcase" style="font-size: 3rem; color: #6c757d;"></i>
                                    <h5 class="mt-3">No Jobs Available</h5>
                                    <p class="text-muted">There are currently no job openings. Please check back later.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($jobs->hasPages())
                    <div class="mt-4">
                        <!-- DataTables Style Pagination Info and Controls -->
                        <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12 col-md-5">
                                    <div class="dataTables_info" role="status" aria-live="polite">
                                        @if ($jobs->total() > 0)
                                            Showing {{ $jobs->firstItem() }} to {{ $jobs->lastItem() }} of
                                            {{ $jobs->total() }} jobs
                                        @else
                                            Showing 0 to 0 of 0 jobs
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_simple_numbers">
                                        {{ $jobs->appends(request()->query())->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Search and Filter Card - Right Column (30%) -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-search me-2"></i>Search & Filter Jobs
                        </h6>
                    </div>
                    <div class="card-body mt-3">
                        <form method="GET" action="{{ route('home') }}" id="searchFilterForm">
                            <!-- Search Input - Always Visible -->
                            <div class="mb-3">
                                <label for="search" class="form-label">
                                    <i class="bi bi-search text-primary"></i> Search Keywords
                                </label>
                                <input type="text" class="form-control" id="search" name="search"
                                    value="{{ request('search') }}" placeholder="Job title, company, location...">
                            </div>

                            <!-- Filter Toggle Button -->
                            <div class="mb-3">
                                <button type="button" class="btn btn-outline-primary btn-sm w-100" id="toggleFilters"
                                    data-bs-toggle="collapse" data-bs-target="#filterSection">
                                    <i class="bi bi-funnel me-2"></i>Advanced Filters
                                    <i class="bi bi-chevron-down ms-auto float-end" id="filterChevron"></i>
                                </button>
                            </div>

                            <!-- Collapsible Filter Section -->
                            <div class="collapse {{ request()->hasAny(['company', 'department', 'location', 'salary_min', 'salary_max']) ? 'show' : '' }}"
                                id="filterSection">
                                <!-- Company Filter -->
                                <div class="mb-3">
                                    <label for="company" class="form-label">
                                        <i class="bi bi-building text-primary"></i> Company
                                    </label>
                                    <select class="form-select" id="company" name="company">
                                        <option value="">All Companies</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company }}"
                                                {{ request('company') == $company ? 'selected' : '' }}>
                                                {{ $company }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Department Filter -->
                                <div class="mb-3">
                                    <label for="department" class="form-label">
                                        <i class="bi bi-briefcase text-primary"></i> Department
                                    </label>
                                    <select class="form-select" id="department" name="department">
                                        <option value="">All Departments</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department }}"
                                                {{ request('department') == $department ? 'selected' : '' }}>
                                                {{ $department }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Location Filter -->
                                <div class="mb-3">
                                    <label for="location" class="form-label">
                                        <i class="bi bi-geo-alt text-primary"></i> Location
                                    </label>
                                    <select class="form-select" id="location" name="location">
                                        <option value="">All Locations</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location }}"
                                                {{ request('location') == $location ? 'selected' : '' }}>
                                                {{ $location }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Salary Range -->
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-cash text-primary"></i> Salary Range
                                    </label>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="number" class="form-control form-control-sm"
                                                name="salary_min" value="{{ request('salary_min') }}"
                                                placeholder="Min" min="0">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" class="form-control form-control-sm"
                                                name="salary_max" value="{{ request('salary_max') }}"
                                                placeholder="Max" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Apply Filters
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i> Clear All
                                </a>
                            </div>

                            <!-- Active Filters Display -->
                            @if (request()->hasAny(['search', 'company', 'department', 'location', 'salary_min', 'salary_max']))
                                <div class="mt-3 pt-3 border-top">
                                    <h6 class="text-muted mb-2">Active Filters:</h6>
                                    <div class="d-flex flex-wrap gap-1">
                                        @if (request('search'))
                                            <span class="badge bg-primary">Search: {{ request('search') }}</span>
                                        @endif
                                        @if (request('company'))
                                            <span class="badge bg-info">Company: {{ request('company') }}</span>
                                        @endif
                                        @if (request('department'))
                                            <span class="badge bg-success">Department:
                                                {{ request('department') }}</span>
                                        @endif
                                        @if (request('location'))
                                            <span class="badge bg-warning">Location: {{ request('location') }}</span>
                                        @endif
                                        @if (request('salary_min') || request('salary_max'))
                                            <span class="badge bg-secondary">
                                                Salary: {{ number_format(request('salary_min', 0)) }} -
                                                {{ number_format(request('salary_max', 999999999)) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Detail Offcanvas -->
        @foreach ($jobs as $job)
            <div class="offcanvas offcanvas-end p-3" tabindex="-1" id="jobDetails{{ $job->id }}"
                style="width: 75vw;">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title">{{ $job->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body mt-2">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Company Info Header -->
                            <div class="company-info-header mb-4 p-4"
                                style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 15px; border-left: 5px solid #667eea;">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        <i class="bi bi-building text-primary" style="font-size: 1.8rem;"></i>
                                    </div>
                                    <div>
                                        <h5 class="text-primary mb-1 fw-bold">{{ $job->company_name }}</h5>
                                        <p class="text-muted mb-0">
                                            <i class="bi bi-geo-alt me-1"></i> {{ $job->location }}
                                        </p>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    @if ($job->salary_min && $job->salary_max)
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-currency-dollar text-success me-2"></i>
                                                <div>
                                                    <small class="text-muted d-block">Salary Range</small>
                                                    <span class="text-success fw-bold">
                                                        {{ App\Http\Controllers\PublicController::formatSalary($job->salary_min, $job->salary_max) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-clock text-info me-2"></i>
                                            <div>
                                                <small class="text-muted d-block">Posted</small>
                                                <span
                                                    class="fw-semibold">{{ $job->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Description Section -->
                            <div class="job-description-section mb-4">
                                <div class="section-header mb-3 p-3"
                                    style="background: #667eea; border-radius: 10px;">
                                    <h6 class="text-white mb-0 fw-bold">
                                        <i class="bi bi-file-text me-2"></i>Job Description
                                    </h6>
                                </div>
                                <div class="tinymce-content p-3"
                                    style="background: white; border-radius: 10px; border: 1px solid #e9ecef;">
                                    {!! $job->description !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="card p-3">
                                    <div class="card-body text-center">
                                        @if ($job->company_logo)
                                            <img src="{{ asset('storage/' . $job->company_logo) }}"
                                                alt="{{ $job->company_name }}"
                                                style="width: 80px; height: 80px; object-fit: contain; margin-bottom: 15px;">
                                        @else
                                            <div
                                                style="width: 80px; height: 80px; background: linear-gradient(45deg, #{{ sprintf('%06X', mt_rand(0, 0xffffff)) }}, #{{ sprintf('%06X', mt_rand(0, 0xffffff)) }}); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                                                <span
                                                    style="font-weight: bold; color: white; font-size: 24px;">{{ substr($job->company_name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <h6>{{ $job->company_name }}</h6>

                                        @auth
                                            @php
                                                $userDetail = Auth::user()->userDetail;
                                                $hasCV = $userDetail && $userDetail->cv_path;
                                            @endphp

                                            @if (!$hasCV)
                                                <div class="alert alert-warning alert-sm p-2 mb-3"
                                                    style="font-size: 0.8rem;">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                                    <strong>CV Required:</strong> <a href="{{ route('profile') }}"
                                                        class="alert-link">Upload CV</a> to apply
                                                </div>
                                            @endif
                                        @endauth

                                        @auth
                                            @if ($job->applications()->where('user_id', Auth::id())->exists())
                                                <button class="btn btn-success w-100 mb-2" disabled>
                                                    <i class="bi bi-check-circle"></i> Applied
                                                </button>
                                            @else
                                                @php
                                                    $userDetail = Auth::user()->userDetail;
                                                    $hasCV = $userDetail && $userDetail->cv_path;
                                                    $hasKTP = $userDetail && $userDetail->ktp_path;
                                                @endphp

                                                @if ($hasCV && $hasKTP)
                                                    <form action="{{ route('jobs.apply', $job->id) }}" method="POST"
                                                        style="display: inline;"
                                                        onsubmit="return confirm('Are you sure you want to apply for this job?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary w-100 mb-2">
                                                            <i class="bi bi-send me-1"></i> Apply Now
                                                        </button>
                                                    </form>
                                                @else
                                                    @php
                                                        $missingDocuments = [];
                                                        if (!$hasCV) {
                                                            $missingDocuments[] = 'CV/Resume';
                                                        }
                                                        if (!$hasKTP) {
                                                            $missingDocuments[] = 'KTP (ID Card)';
                                                        }
                                                        $missingText = implode(' and ', $missingDocuments);
                                                    @endphp
                                                    <button type="button" class="btn btn-warning w-100 mb-2"
                                                        onclick="if(confirm('You need to upload your {{ $missingText }} first.\n\nWould you like to go to your profile now?')) { window.location.href='{{ route('profile') }}'; }"
                                                        title="{{ $missingText }} required">
                                                        <i class="bi bi-exclamation-triangle me-1"></i> Upload
                                                        {{ $missingText }} Required
                                                    </button>
                                                @endif
                                            @endif

                                            <form action="{{ route('jobs.bookmark', $job->id) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                <button type="submit"
                                                    class="btn {{ $job->bookmarks()->where('user_id', Auth::id())->exists() ? 'btn-success' : 'btn-outline-secondary' }} w-100">
                                                    <i
                                                        class="bi {{ $job->bookmarks()->where('user_id', Auth::id())->exists() ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
                                                    {{ $job->bookmarks()->where('user_id', Auth::id())->exists() ? 'Saved' : 'Save Job' }}
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal"
                                                data-bs-target="#guestApplyModal"
                                                onclick="setGuestJobId({{ $job->id }})">
                                                <i class="bi bi-send me-1"></i> Apply Now
                                            </button>
                                            <button class="btn btn-outline-secondary w-100"
                                                onclick="alert('Please login to bookmark jobs')">
                                                <i class="bi bi-bookmark"></i> Save Job
                                            </button>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-0">
                                <div class="card p-3">
                                    <div class="d-flex align-items-center"
                                        style="border-left: 3px solid #667eea; padding-left: 15px;">
                                        <div class="me-3">
                                            <i class="bi bi-building text-primary" style="font-size: 1.25rem;"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted fw-semibold d-block"
                                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Department</small>
                                            <span class="fw-bold text-dark">{{ $job->department ?: 'General' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="card p-3">
                                    <div class="col-md-12">
                                        <div class="d-flex align-items-center"
                                            style="border-left: 3px solid #dc3545; padding-left: 15px;">
                                            <div class="me-3">
                                                <i class="bi bi-calendar-event text-danger"
                                                    style="font-size: 1.25rem;"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted fw-semibold d-block"
                                                    style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Application
                                                    Deadline</small>
                                                <span
                                                    class="fw-bold text-dark">{{ $job->expired_date->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</main>

<style>
    .job-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Filter Toggle Button Styling */
    #toggleFilters {
        border: 2px solid #667eea;
        color: #667eea;
        background: white;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    #toggleFilters:hover {
        background: #667eea;
        color: white;
        transform: translateY(-1px);
    }

    #toggleFilters.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    /* Chevron Animation */
    #filterChevron {
        transition: transform 0.3s ease;
        display: inline-block;
    }

    /* Collapse Animation Enhancement */
    .collapse {
        transition: all 0.35s ease;
    }

    .collapsing {
        transition: height 0.35s ease;
    }

    /* Search highlight styling */
    mark {
        background-color: #fff3cd;
        color: #856404;
        padding: 0.1rem 0.2rem;
        border-radius: 3px;
        font-weight: 600;
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .col-lg-8 {
            order: 2;
        }

        .col-lg-4 {
            order: 1;
        }
    }

    /* Info card hover effect */
    .card .row .col-md-6:hover>div {
        background-color: rgba(0, 0, 0, 0.02);
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .card .row .col-md-6>div {
        padding: 12px 15px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    /* Company info header styling */
    .company-info-header {
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
        transition: all 0.3s ease;
    }

    .company-info-header:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
    }

    /* Section headers */
    .section-header {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .section-header:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    /* Ensure header dropdown has higher z-index than search card */
    .header .dropdown-menu,
    .navbar .dropdown-menu,
    .dropdown-menu {
        z-index: 1055 !important;
    }

    /* Fix for Bootstrap dropdown positioning */
    .nav-item.dropdown {
        position: relative;
    }

    .nav-item.dropdown .dropdown-menu {
        position: absolute !important;
        top: 100% !important;
        right: 0 !important;
        left: auto !important;
        transform: none !important;
    }
</style>

@include('modal.guest-apply')

<script>
    // Simple function to set job ID in guest modal
    function setGuestJobId(jobId) {
        document.getElementById('guest_job_id').value = jobId;
    }

    // Show toast notifications for Laravel flash messages
    @if (session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif

    @if (session('error'))
        showToast('{{ session('error') }}', 'error');
        @if (session('error') == 'Please upload your CV/Resume in your profile before applying')
            // Show additional alert for CV requirement
            setTimeout(function() {
                if (confirm(
                        'You need to upload your CV/Resume first. Would you like to go to your profile now?')) {
                    window.location.href = '{{ route('profile') }}';
                }
            }, 3500);
        @endif
    @endif

    @if ($errors->any())
        showToast('{{ $errors->first() }}', 'error');
    @endif // Toast notification function
    function showToast(message, type = 'info') {
        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }

        // Create toast element
        const toastId = 'toast-' + Date.now();
        const bgClass = {
            'success': 'bg-success',
            'error': 'bg-danger',
            'warning': 'bg-warning',
            'info': 'bg-info'
        } [type] || 'bg-info';

        const toastHtml = `
        <div id="${toastId}" class="toast ${bgClass} text-white" role="alert">
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;

        toastContainer.insertAdjacentHTML('beforeend', toastHtml);

        // Initialize and show toast
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 3000
        });

        toast.show();

        // Remove toast element after it's hidden
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }

    // Search Filter Enhancement - Minimal JS for essential functionality only
    document.addEventListener('DOMContentLoaded', function() {
        // Filter Toggle Functionality - Essential for collapse/expand
        const toggleFiltersBtn = document.getElementById('toggleFilters');
        const filterChevron = document.getElementById('filterChevron');

        if (toggleFiltersBtn && filterChevron) {
            toggleFiltersBtn.addEventListener('click', function() {
                // Simple chevron rotation
                setTimeout(() => {
                    const chevron = document.getElementById('filterChevron');
                    if (chevron) {
                        const isRotated = chevron.style.transform === 'rotate(180deg)';
                        chevron.style.transform = isRotated ? 'rotate(0deg)' :
                            'rotate(180deg)';
                    }
                }, 150);
            });

            // Auto-expand if there are active filters
            const hasActiveFilters =
                {{ request()->hasAny(['company', 'department', 'location', 'salary_min', 'salary_max']) ? 'true' : 'false' }};
            if (hasActiveFilters) {
                setTimeout(() => {
                    filterChevron.style.transform = 'rotate(180deg)';
                }, 100);
            }
        }
    });
</script>

@include('layouts.foot')
