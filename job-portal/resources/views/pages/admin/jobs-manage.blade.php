@section('head-extra')
    <link href="{{ asset('css/admin-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pagination.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin-filter.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin-badges.css') }}" rel="stylesheet">
@endsection

@include('layouts.header')
@include('layouts.admin-sidebar')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Job Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                <li class="breadcrumb-item active">Jobs</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Job Management</h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary" id="toggleFilter">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addJobModal">
                                    <i class="bi bi-plus-circle"></i> Add Job
                                </button>
                            </div>
                        </div>

                        <!-- Filter Section -->
                        <div class="card bg-light mb-3" id="filterSection" style="display: none;">
                            <div class="card-body">
                                <form method="GET" action="{{ route('admin.jobs') }}" id="filterForm">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="filter_company" class="form-label">Company</label>
                                                <select class="form-select" id="filter_company" name="company">
                                                    <option value="">All Companies</option>
                                                    @foreach ($companies ?? [] as $company)
                                                        <option value="{{ $company }}"
                                                            {{ request('company') == $company ? 'selected' : '' }}>
                                                            {{ $company }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="filter_department" class="form-label">Department</label>
                                                <select class="form-select" id="filter_department" name="department">
                                                    <option value="">All Departments</option>
                                                    @foreach ($departments ?? [] as $department)
                                                        <option value="{{ $department }}"
                                                            {{ request('department') == $department ? 'selected' : '' }}>
                                                            {{ $department }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="filter_location" class="form-label">Location</label>
                                                <select class="form-select" id="filter_location" name="location">
                                                    <option value="">All Locations</option>
                                                    @foreach ($locations ?? [] as $location)
                                                        <option value="{{ $location }}"
                                                            {{ request('location') == $location ? 'selected' : '' }}>
                                                            {{ $location }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="filter_status" class="form-label">Status</label>
                                                <select class="form-select" id="filter_status" name="status">
                                                    <option value="">All Statuses</option>
                                                    <option value="active"
                                                        {{ request('status') == 'active' ? 'selected' : '' }}>
                                                        Active</option>
                                                    <option value="expired"
                                                        {{ request('status') == 'expired' ? 'selected' : '' }}>
                                                        Expired</option>
                                                    <option value="scheduled"
                                                        {{ request('status') == 'scheduled' ? 'selected' : '' }}>
                                                        Scheduled</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="filter_date_from" class="form-label">Date From</label>
                                                <input type="date" class="form-control" id="filter_date_from"
                                                    name="date_from" value="{{ request('date_from') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="filter_date_to" class="form-label">Date To</label>
                                                <input type="date" class="form-control" id="filter_date_to"
                                                    name="date_to" value="{{ request('date_to') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-search"></i> Apply Filters
                                            </button>
                                            <a href="{{ route('admin.jobs') }}" class="btn btn-outline-secondary">
                                                <i class="bi bi-arrow-counterclockwise"></i> Clear
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- DataTables style controls -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="me-2">Show</span>
                                <form method="GET" action="{{ route('admin.jobs') }}" class="d-inline">
                                    <select class="form-select form-select-sm d-inline-block" name="per_page"
                                        style="width: auto;" onchange="this.form.submit()">
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>
                                            10</option>
                                        <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>
                                            25</option>
                                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>
                                            50</option>
                                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>
                                            100</option>
                                    </select>
                                    <!-- Preserve other query parameters -->
                                    @foreach (request()->except(['per_page', 'page']) as $key => $value)
                                        <input type="hidden" name="{{ $key }}"
                                            value="{{ $value }}">
                                    @endforeach
                                </form>
                                <span class="ms-2">entries</span>
                            </div>
                            <div>
                                <form method="GET" action="{{ route('admin.jobs') }}" class="d-flex">
                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <input type="text" class="form-control" placeholder="Search jobs..."
                                            name="search" value="{{ request('search') }}">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                    <!-- Preserve other filters -->
                                    @foreach (request()->except(['search', 'page']) as $key => $value)
                                        <input type="hidden" name="{{ $key }}"
                                            value="{{ $value }}">
                                    @endforeach
                                </form>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                                class="text-white text-decoration-none">
                                                Title
                                                @if (request('sort') === 'title')
                                                    <i
                                                        class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'company_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                                class="text-white text-decoration-none">
                                                Company
                                                @if (request('sort') === 'company_name')
                                                    <i
                                                        class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col">Department</th>
                                        <th scope="col">Location</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Applications</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jobs as $index => $job)
                                        <tr>
                                            <td>{{ ($jobs->currentPage() - 1) * $jobs->perPage() + $index + 1 }}</td>
                                            <td>
                                                <div class="fw-bold">{{ $job->title }}</div>
                                                <small class="text-muted">
                                                    by {{ $job->creator->name ?? 'N/A' }}
                                                </small>
                                            </td>
                                            <td>{{ $job->company_name }}</td>
                                            <td>{{ $job->department ?? 'N/A' }}</td>
                                            <td>{{ $job->location ?? 'N/A' }}</td>
                                            <td>
                                                @if ($job->isActive())
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($job->isExpired())
                                                    <span class="badge bg-danger">Expired</span>
                                                @else
                                                    <span class="badge bg-warning">Scheduled</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-primary fs-6">
                                                    {{ $job->applications_count ?? 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.job.applications', $job->id) }}"
                                                        class="btn btn-sm btn-outline-info" title="View Applications">
                                                        <i class="bi bi-people"></i>
                                                    </a>
                                                    <a href="{{ route('admin.jobs.edit', $job->id) }}"
                                                        class="btn btn-sm btn-outline-primary" title="Edit Job">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="{{ route('admin.jobs.delete.confirm', $job->id) }}"
                                                        class="btn btn-sm btn-outline-danger" title="Delete Job">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox display-4"></i>
                                                    <div class="mt-2">
                                                        @if (request()->hasAny(['search', 'company', 'department', 'location', 'date_from', 'date_to']))
                                                            No jobs found matching your criteria.
                                                            <div class="mt-2">
                                                                <a href="{{ route('admin.jobs') }}"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    <i class="bi bi-arrow-counterclockwise"></i> Clear
                                                                    Filters
                                                                </a>
                                                            </div>
                                                        @else
                                                            No jobs available.
                                                            <div class="mt-2">
                                                                <button type="button" class="btn btn-sm btn-primary"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#addJobModal">
                                                                    <i class="bi bi-plus-circle"></i> Add First Job
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- DataTables Style Pagination Info and Controls -->
                        <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12 col-md-5">
                                    <div class="dataTables_info" role="status" aria-live="polite">
                                        @if ($jobs->total() > 0)
                                            Showing {{ $jobs->firstItem() }} to {{ $jobs->lastItem() }} of
                                            {{ $jobs->total() }} entries
                                        @else
                                            Showing 0 to 0 of 0 entries
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    @if ($jobs->hasPages())
                                        <div class="dataTables_paginate paging_simple_numbers">
                                            {{ $jobs->appends(request()->query())->links('pagination::bootstrap-4') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
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

<!-- Include Create Job Modal -->
@include('pages.admin.modals.create-job')

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                    const toggleFilterBtn = document.getElementById('toggleFilter');
                    const filterSection = document.getElementById('filterSection');

                    // Check if there are active filters
                    const hasActiveFilters =
                        {{ request()->hasAny(['company', 'department', 'location', 'status', 'date_from', 'date_to']) ? 'true' : 'false' }};

                    // Show filter section if there are active filters
                    if (hasActiveFilters) {
                        filterSection.style.display = 'block';
                        toggleFilterBtn.classList.add('active');
                        toggleFilterBtn.innerHTML = '<i class="bi bi-funnel-fill"></i> Hide Filter';
                    }

                    // Toggle filter section
                    toggleFilterBtn.addEventListener('click', function() {
                        if (filterSection.style.display === 'none' || filterSection.style.display === '') {
                            filterSection.style.display = 'block';
                            this.classList.add('active');
                            this.innerHTML = '<i class="bi bi-funnel-fill"></i> Hide Filter';
                        } else {
                            filterSection.style.display = 'none';
                            this.classList.remove('active');
                            this.innerHTML = '<i class="bi bi-funnel"></i> Filter';
                        }
                    });

                    // Auto-submit form when filter values change (optional)
                    const filterInputs = document.querySelectorAll('#filterForm select, #filterForm input');
                    filterInputs.forEach(input => {
                                input.addEventListener('change', function() {
                                    // Uncomment below line if you want auto-submit on change
                                    // document.getElementById('filterForm').submit();
                                });
    </script>
@endsection
