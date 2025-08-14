@include('layouts.header')
@include('layouts.admin-sidebar')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Job</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.jobs') }}">Jobs</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Job: {{ $job->title }}</h5>

                        <!-- Job Edit Form -->
                        <form action="{{ route('admin.jobs.update', $job->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="title" class="col-md-4 col-lg-3 col-form-label">Job Title <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8 col-lg-9">
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title', $job->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="description" class="col-md-4 col-lg-3 col-form-label">Description <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-12 mb-3">
                                    <textarea class="form-control tinymce-editor @error('description') is-invalid @enderror" id="description"
                                        name="description" rows="4" required data-required="true">{{ old('description', $job->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="company_name" class="col-md-4 col-lg-3 col-form-label">Company Name <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8 col-lg-9">
                                    <input type="text"
                                        class="form-control @error('company_name') is-invalid @enderror"
                                        id="company_name" name="company_name"
                                        value="{{ old('company_name', $job->company_name) }}" required>
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="company_logo" class="col-md-4 col-lg-3 col-form-label">Company Logo</label>
                                <div class="col-md-8 col-lg-9">
                                    @if ($job->company_logo)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $job->company_logo) }}" alt="Current Logo"
                                                class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                            <small class="text-muted d-block">Current logo</small>
                                        </div>
                                    @endif
                                    <input type="file"
                                        class="form-control @error('company_logo') is-invalid @enderror"
                                        id="company_logo" name="company_logo" accept="image/*">
                                    <small class="form-text text-muted">Upload new logo to replace current one. Leave
                                        empty to keep current logo.</small>
                                    @error('company_logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="department" class="col-md-4 col-lg-3 col-form-label">Department</label>
                                <div class="col-md-8 col-lg-9">
                                    <input type="text" class="form-control @error('department') is-invalid @enderror"
                                        id="department" name="department"
                                        value="{{ old('department', $job->department) }}">
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="location" class="col-md-4 col-lg-3 col-form-label">Location</label>
                                <div class="col-md-8 col-lg-9">
                                    <input type="text" class="form-control @error('location') is-invalid @enderror"
                                        id="location" name="location" value="{{ old('location', $job->location) }}">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="published_date" class="col-md-4 col-lg-3 col-form-label">Published Date
                                    <span class="text-danger">*</span></label>
                                <div class="col-md-8 col-lg-9">
                                    <input type="date"
                                        class="form-control @error('published_date') is-invalid @enderror"
                                        id="published_date" name="published_date"
                                        value="{{ old('published_date', $job->published_date->format('Y-m-d')) }}"
                                        required>
                                    @error('published_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="expired_date" class="col-md-4 col-lg-3 col-form-label">Expired Date <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8 col-lg-9">
                                    <input type="date"
                                        class="form-control @error('expired_date') is-invalid @enderror"
                                        id="expired_date" name="expired_date"
                                        value="{{ old('expired_date', $job->expired_date->format('Y-m-d')) }}"
                                        required>
                                    @error('expired_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" onclick="tinyMCE.triggerSave()">
                                    <i class="bi bi-check-circle"></i> Update Job
                                </button>
                                <a href="{{ route('admin.jobs') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Job Info Card -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Job Information</h5>

                        <div class="row mb-2">
                            <div class="col-sm-5">
                                <strong>Status:</strong>
                            </div>
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
                            <div class="col-sm-5">
                                <strong>Created By:</strong>
                            </div>
                            <div class="col-sm-7">
                                {{ $job->creator->name ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-5">
                                <strong>Created At:</strong>
                            </div>
                            <div class="col-sm-7">
                                {{ $job->created_at->format('d M Y') }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-5">
                                <strong>Applications:</strong>
                            </div>
                            <div class="col-sm-7">
                                <span class="badge bg-primary">{{ $job->applications()->count() }}</span>
                            </div>
                        </div>

                        <hr>

                        <div class="text-center">
                            <a href="{{ route('admin.jobs.delete.confirm', $job->id) }}"
                                class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i> Delete Job
                            </a>
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
    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const publishedDate = document.getElementById('published_date');
        const expiredDate = document.getElementById('expired_date');

        function validateDates() {
            if (publishedDate.value && expiredDate.value) {
                if (new Date(expiredDate.value) <= new Date(publishedDate.value)) {
                    expiredDate.setCustomValidity('Expired date must be after published date');
                } else {
                    expiredDate.setCustomValidity('');
                }
            }
        }

        publishedDate.addEventListener('change', validateDates);
        expiredDate.addEventListener('change', validateDates);
    });
</script>
