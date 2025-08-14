@include('layouts.header')

@section('head-extra')
    <link href="{{ asset('css/tinymce-content.css') }}" rel="stylesheet">
@endsection

@include('layouts.sidebar')

<main id="main" class="main">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="pagetitle mb-4">
            <h1>Saved Jobs</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Saved Jobs</li>
                </ol>
            </nav>
        </div>

        <!-- Saved Jobs -->
        <div class="row g-1">
            @forelse($savedJobs as $job)
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
                                            @if ($job->applications()->where('user_id', Auth::id())->exists())
                                                <span class="badge bg-info mt-3 ms-1">Applied</span>
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
                                    <small class="text-muted"> • Saved
                                        {{ $job->bookmarks()->where('user_id', Auth::id())->first()->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="col text-end">
                                    <form action="{{ route('jobs.bookmark', $job->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0 border-0"
                                            style="font-size: 1.2rem;" onclick="event.stopPropagation();">
                                            <i class="bi bi-bookmark-fill text-primary"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Offcanvas Job Details -->
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

                                            @php
                                                $userDetail = Auth::user()->userDetail;
                                                $hasCV = $userDetail && $userDetail->cv_path;
                                                $hasKTP = $userDetail && $userDetail->ktp_path;
                                            @endphp

                                            @if (!$hasCV || !$hasKTP)
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
                                                <div class="alert alert-warning alert-sm p-2 mb-3"
                                                    style="font-size: 0.8rem;">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                                    <strong>Documents Required:</strong> <a
                                                        href="{{ route('profile') }}" class="alert-link">Upload
                                                        {{ $missingText }}</a> to apply
                                                </div>
                                            @endif

                                            @if ($job->applications()->where('user_id', Auth::id())->exists())
                                                <button class="btn btn-success w-100 mb-2" disabled>
                                                    <i class="bi bi-check-circle"></i> Applied
                                                </button>
                                            @else
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
                                                <button type="submit" class="btn btn-outline-danger w-100">
                                                    <i class="bi bi-bookmark-fill"></i> Remove from Saved
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-0">
                                    <div class="card p-3">
                                        <div class="d-flex align-items-center"
                                            style="border-left: 3px solid #667eea; padding-left: 15px;">
                                            <div class="me-3">
                                                <i class="bi bi-building text-primary"
                                                    style="font-size: 1.25rem;"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted fw-semibold d-block"
                                                    style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Department</small>
                                                <span
                                                    class="fw-bold text-dark">{{ $job->department ?: 'General' }}</span>
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
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-bookmark" style="font-size: 3rem; color: #6c757d;"></i>
                            <h5 class="mt-3">No Saved Jobs</h5>
                            <p class="text-muted">You haven't saved any jobs yet. <a
                                    href="{{ route('home') }}">Browse
                                    jobs</a> and save the ones you're interested in.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($savedJobs->hasPages())
            <div class="mt-4">
                <!-- DataTables Style Pagination Info and Controls -->
                <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info" role="status" aria-live="polite">
                                @if ($savedJobs->total() > 0)
                                    Showing {{ $savedJobs->firstItem() }} to {{ $savedJobs->lastItem() }} of
                                    {{ $savedJobs->total() }} saved jobs
                                @else
                                    Showing 0 to 0 of 0 saved jobs
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers">
                                {{ $savedJobs->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</main>

<style>
    .job-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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

    @media (max-width: 768px) {
        .offcanvas-end {
            width: 90vw !important;
        }

        .card .row .col-md-6 {
            margin-bottom: 0.75rem;
        }
    }
</style>

<script>
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
                        'You need to upload your CV/Resume first.\n\nWould you like to go to your profile now?'
                    )) {
                    window.location.href = '{{ route('profile') }}';
                }
            }, 3500);
        @endif
    @endif

    @if ($errors->any())
        showToast('{{ $errors->first() }}', 'error');
    @endif

    // Toast notification function
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
</script>

@include('layouts.foot')
