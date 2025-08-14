@include('layouts.header')
@include('layouts.admin-sidebar')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Delete Job</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.jobs') }}">Jobs</a></li>
                <li class="breadcrumb-item active">Delete</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="bi bi-exclamation-triangle display-1 text-danger"></i>
                            <h3 class="card-title text-danger">Confirm Job Deletion</h3>
                            <p class="text-muted">This action cannot be undone. Please confirm that you want to delete
                                this job.</p>
                        </div>

                        <!-- Job Details -->
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <div class="row mb-2">
                                            <div class="col-sm-4"><strong>Title:</strong></div>
                                            <div class="col-sm-8">{{ $job->title }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4"><strong>Company:</strong></div>
                                            <div class="col-sm-8">{{ $job->company_name }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4"><strong>Department:</strong></div>
                                            <div class="col-sm-8">{{ $job->department ?? 'N/A' }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4"><strong>Location:</strong></div>
                                            <div class="col-sm-8">{{ $job->location ?? 'N/A' }}</div>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6">
                                        <div class="row mb-2">
                                            <div class="col-sm-5"><strong>Status:</strong></div>
                                            <div class="col-sm-7">
                                                @if ($job->isActive())
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($job->isExpired())
                                                    <span class="badge bg-danger">Expired</span>
                                                @else
                                                    <span class="badge bg-warning">Scheduled</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-5"><strong>Applications:</strong></div>
                                            <div class="col-sm-7">
                                                <span
                                                    class="badge bg-primary">{{ $job->applications()->count() }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-5"><strong>Published:</strong></div>
                                            <div class="col-sm-7">{{ $job->published_date->format('d M Y') }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-5"><strong>Expires:</strong></div>
                                            <div class="col-sm-7">{{ $job->expired_date->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <strong>Description:</strong>
                                        <div class="mt-2 p-2 bg-white border rounded">
                                            {!! nl2br(e(Str::limit($job->description, 200))) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Warning Messages -->
                        @php
                            $applicationsCount = $job->applications()->count();
                        @endphp

                        @if ($applicationsCount > 0)
                            <div class="alert alert-warning mt-3" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <strong>Warning!</strong> This job has <strong>{{ $applicationsCount }}</strong>
                                {{ Str::plural('application', $applicationsCount) }}.
                                Deleting this job will also remove all associated applications and cannot be undone.
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="text-center mt-4">
                            <form action="{{ route('admin.jobs.delete', $job->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger me-2" id="confirmDeleteBtn">
                                    <i class="bi bi-trash"></i> Yes, Delete Job
                                </button>
                            </form>
                            <a href="{{ route('admin.jobs') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                        </div>

                        <!-- Additional Actions -->
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                Or you can
                                <a href="{{ route('admin.jobs.edit', $job->id) }}" class="text-decoration-none">
                                    edit this job
                                </a> instead
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

</main>

@include('layouts.foot')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteBtn = document.getElementById('confirmDeleteBtn');
        const applicationsCount = {{ $applicationsCount }};

        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();

            let confirmMessage = 'Are you sure you want to delete this job?';

            if (applicationsCount > 0) {
                confirmMessage =
                    `This job has ${applicationsCount} application(s). Deleting this job will permanently remove all applications. This action cannot be undone.\n\nAre you sure you want to continue?`;
            }

            if (confirm(confirmMessage)) {
                this.closest('form').submit();
            }
        });
    });
</script>

<style>
    .display-1 {
        font-size: 4rem;
        opacity: 0.7;
    }

    .card.bg-light {
        border-left: 4px solid #dc3545;
    }

    .alert-warning {
        border-left: 4px solid #ffc107;
    }
</style>
