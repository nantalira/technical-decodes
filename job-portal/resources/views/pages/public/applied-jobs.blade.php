@include('layouts.header')

@section('head-extra')
    <link href="{{ asset('css/tinymce-content.css') }}" rel="stylesheet">
@endsection

@include('layouts.sidebar')

<main id="main" class="main">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="pagetitle mb-4">
            <h1>Applied Jobs</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Applied Jobs</li>
                </ol>
            </nav>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Applied Jobs -->
        <div class="row g-1">
            @forelse($appliedJobs as $job)
                @php
                    $application = $job->applications->first();
                    $currentStatus = $application ? $application->status : 'pending';
                    $borderColors = [
                        'pending' => '#ffc107', // yellow
                        'reviewing' => '#17a2b8', // blue
                        'accepted' => '#28a745', // green
                        'rejected' => '#dc3545', // red
                    ];
                    $borderColor = $borderColors[$currentStatus];
                @endphp
                <div class="col-12">
                    <div class="card job-card" data-bs-toggle="offcanvas"
                        data-bs-target="#jobDetails{{ $job->id }}"
                        style="cursor: pointer; transition: all 0.3s ease; border-left: 4px solid {{ $borderColor }};">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-9">
                                    <div class="d-flex align-items-start justify-content-between mb-2">
                                        <div>
                                            @php
                                                $application = $job->applications->first();
                                                $currentStatus = $application ? $application->status : 'pending';
                                                $badgeConfig = [
                                                    'pending' => [
                                                        'class' => 'bg-warning text-dark',
                                                        'text' => 'Pending',
                                                    ],
                                                    'reviewing' => ['class' => 'bg-info', 'text' => 'Reviewing'],
                                                    'accepted' => ['class' => 'bg-success', 'text' => 'Accepted'],
                                                    'rejected' => ['class' => 'bg-danger', 'text' => 'Not Selected'],
                                                ];
                                                $badgeStyle = $badgeConfig[$currentStatus];
                                            @endphp
                                            <span
                                                class="badge {{ $badgeStyle['class'] }} mt-3">{{ $badgeStyle['text'] }}</span>
                                            @if ($job->bookmarks()->where('user_id', Auth::id())->exists())
                                                <span class="badge bg-primary mt-3 ms-1">Saved</span>
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
                                    <small class="text-muted"> • Applied
                                        {{ $job->applications()->where('user_id', Auth::id())->first()->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="col text-end">
                                    <form action="{{ route('jobs.bookmark', $job->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0 border-0"
                                            style="font-size: 1.2rem;" onclick="event.stopPropagation();">
                                            <i
                                                class="bi {{ $job->bookmarks()->where('user_id', Auth::id())->exists() ? 'bi-bookmark-fill text-primary' : 'bi-bookmark text-muted' }}"></i>
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
                                <!-- Application Status Alert -->
                                <div class="alert alert-success mb-4" role="alert">
                                    <i class="bi bi-check-circle me-2"></i>
                                    <strong>Application Submitted!</strong><br>
                                    You applied for this job on
                                    {{ $job->applications()->where('user_id', Auth::id())->first()->created_at->format('d M Y \a\t H:i') }}
                                </div>

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

                                            <button class="btn btn-success w-100 mb-2" disabled>
                                                <i class="bi bi-check-circle"></i> Applied
                                            </button>

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
                                        </div>
                                    </div>
                                </div>

                                <!-- Application Status Card -->
                                <div class="row mt-0">
                                    @php
                                        $application = $job->applications->first(); // Get user's application for this job
$statusConfig = [
    'pending' => [
        'color' => '#ffc107', // yellow
        'bg_color' => 'warning',
        'icon' => 'bi-clock-history',
        'text' => 'Pending Review',
        'description' => 'Your application is waiting to be reviewed',
    ],
    'reviewing' => [
        'color' => '#17a2b8', // blue
        'bg_color' => 'info',
        'icon' => 'bi-eye',
        'text' => 'Under Review',
        'description' => 'Your application is currently being reviewed',
    ],
    'accepted' => [
        'color' => '#28a745', // green
        'bg_color' => 'success',
        'icon' => 'bi-check-circle',
        'text' => 'Accepted',
        'description' => 'Congratulations! Your application has been accepted',
    ],
    'rejected' => [
        'color' => '#dc3545', // red
        'bg_color' => 'danger',
        'icon' => 'bi-x-circle',
        'text' => 'Not Selected',
        'description' => 'Unfortunately, your application was not selected',
    ],
];

$currentStatus = $application ? $application->status : 'pending';
                                        $config = $statusConfig[$currentStatus];
                                    @endphp

                                    <div class="card p-3" style="border-left: 3px solid {{ $config['color'] }};">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <i class="bi {{ $config['icon'] }} text-{{ $config['bg_color'] }}"
                                                        style="font-size: 1.25rem;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted fw-semibold d-block"
                                                        style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Application
                                                        Status</small>
                                                    <span class="fw-bold text-dark">{{ $config['text'] }}</span>
                                                    <small
                                                        class="text-muted d-block">{{ $config['description'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
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
                            <i class="bi bi-briefcase-fill" style="font-size: 3rem; color: #6c757d;"></i>
                            <h5 class="mt-3">No Applied Jobs</h5>
                            <p class="text-muted">You haven't applied to any jobs yet. <a
                                    href="{{ route('home') }}">Browse jobs</a> and start applying to opportunities
                                that
                                match your skills.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($appliedJobs->hasPages())
            <div class="mt-4">
                <!-- DataTables Style Pagination Info and Controls -->
                <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info" role="status" aria-live="polite">
                                @if ($appliedJobs->total() > 0)
                                    Showing {{ $appliedJobs->firstItem() }} to {{ $appliedJobs->lastItem() }} of
                                    {{ $appliedJobs->total() }} applied jobs
                                @else
                                    Showing 0 to 0 of 0 applied jobs
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers">
                                {{ $appliedJobs->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</main>

<script>
    function toggleBookmark(jobId) {
        fetch(`/jobs/${jobId}/bookmark`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update bookmark icon in job card
                    const bookmarkIcon = document.querySelector(`[data-job-id="${jobId}"]`);
                    if (bookmarkIcon) {
                        if (data.bookmarked) {
                            bookmarkIcon.className = 'bi bi-bookmark-fill text-primary bookmark-icon';
                        } else {
                            bookmarkIcon.className = 'bi bi-bookmark text-muted bookmark-icon';
                        }
                    }

                    // Update bookmark button in offcanvas
                    const offcanvasButton = document.querySelector(`#jobDetails${jobId} .btn-outline-secondary`);
                    if (offcanvasButton) {
                        const icon = offcanvasButton.querySelector('i');
                        if (data.bookmarked) {
                            icon.className = 'bi bi-bookmark-fill';
                            offcanvasButton.innerHTML = '<i class="bi bi-bookmark-fill"></i> Saved';
                        } else {
                            icon.className = 'bi bi-bookmark';
                            offcanvasButton.innerHTML = '<i class="bi bi-bookmark"></i> Save Job';
                        }
                    }

                    // Update saved badge in job card after form submission
                    const savedBadge = document.querySelector(
                        `[data-bs-target="#jobDetails${jobId}"] .badge-primary`);
                    const isCurrentlyBookmarked = savedBadge !== null;

                    if (!isCurrentlyBookmarked) {
                        // Add saved badge
                        const appliedBadge = document.querySelector(
                            `[data-bs-target="#jobDetails${jobId}"] .badge-success`);
                        if (appliedBadge) {
                            appliedBadge.insertAdjacentHTML('afterend',
                                ' <span class="badge bg-primary mb-2 badge-primary">Saved</span>');
                        }
                    } else {
                        // Remove saved badge
                        savedBadge.remove();
                    }

                    // Submit form for server-side processing
                    form.submit();
                } else {
                    console.error('Error: Job ID not found');
                }
            });
    }

    // Add hover effects to job cards
    document.addEventListener('DOMContentLoaded', function() {
        const jobCards = document.querySelectorAll('.job-card');
        jobCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '';
            });
        });
    });
</script>

@include('layouts.foot')
