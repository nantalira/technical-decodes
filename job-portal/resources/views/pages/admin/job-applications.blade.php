@include('layouts.header')
@include('layouts.admin-sidebar')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Job Applications</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.jobs') }}">Jobs</a></li>
                <li class="breadcrumb-item active">Applications</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <!-- Job Info Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between align-items-center">
                            <span>{{ $job->title }}</span>
                            <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit Job
                            </a>
                        </h5>
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Company:</strong> {{ $job->company_name }}</p>
                                <p><strong>Department:</strong> {{ $job->department }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Location:</strong> {{ $job->location }}</p>
                                <p><strong>Published:</strong> {{ $job->published_date->format('d M Y') }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Expires:</strong> {{ $job->expired_date->format('d M Y') }}</p>
                                <p><strong>Salary:</strong> ${{ number_format($job->salary_min) }} -
                                    ${{ number_format($job->salary_max) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Job Applications ({{ $applications->total() }})</h5>

                        <!-- Filter Form -->
                        <form method="GET" class="mb-4">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="all"
                                            {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>
                                            All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="reviewing"
                                            {{ request('status') == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                                        <option value="accepted"
                                            {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                        <option value="rejected"
                                            {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="search" class="form-label">Search Applicant</label>
                                    <input type="text" class="form-control" id="search" name="search"
                                        value="{{ request('search') }}" placeholder="Search by name or email...">
                                </div>

                                <div class="col-md-2">
                                    <label for="sort_by" class="form-label">Sort By</label>
                                    <select class="form-select" id="sort_by" name="sort_by">
                                        <option value="applied_at"
                                            {{ request('sort_by') == 'applied_at' ? 'selected' : '' }}>Applied Date
                                        </option>
                                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>
                                            Name</option>
                                        <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>
                                            Status</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label for="sort_order" class="form-label">Order</label>
                                    <select class="form-select" id="sort_order" name="sort_order">
                                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>
                                            Descending</option>
                                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>
                                            Ascending</option>
                                    </select>
                                </div>

                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Applications Table -->
                        @if ($applications->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Applicant</th>
                                            <th>Contact</th>
                                            <th>Applied Date</th>
                                            <th>Status</th>
                                            <th>Documents</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($applications as $index => $application)
                                            <tr>
                                                <td>{{ $applications->firstItem() + $index }}</td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $application->name ?? $application->user->name }}</strong>
                                                        @if ($application->user)
                                                            <br><small class="text-muted">Registered User</small>
                                                        @else
                                                            <br><small class="text-muted">Guest Application</small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <i class="bi bi-envelope"></i>
                                                        {{ $application->email ?? $application->user->email }}
                                                        @if ($application->phone)
                                                            <br><i class="bi bi-telephone"></i>
                                                            {{ $application->phone }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ $application->applied_at ? $application->applied_at->format('d M Y H:i') : $application->created_at->format('d M Y H:i') }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge
                                                        @switch($application->status)
                                                            @case('pending') bg-warning @break
                                                            @case('reviewing') bg-info @break
                                                            @case('accepted') bg-success @break
                                                            @case('rejected') bg-danger @break
                                                            @default bg-secondary
                                                        @endswitch
                                                    ">
                                                        {{ ucfirst($application->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($application->cv_path)
                                                        <a href="{{ Storage::url($application->cv_path) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-file-earmark-pdf"></i> CV
                                                        </a>
                                                    @endif
                                                    @if ($application->ktp_path)
                                                        <a href="{{ Storage::url($application->ktp_path) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-info">
                                                            <i class="bi bi-card-image"></i> KTP
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown">
                                                            <i class="bi bi-gear"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <button class="dropdown-item change-status-btn"
                                                                    data-application-id="{{ $application->id }}"
                                                                    data-current-status="{{ $application->status }}">
                                                                    <i class="bi bi-pencil"></i> Change Status
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="viewDetails({{ json_encode($application) }})">
                                                                    <i class="bi bi-eye"></i> View Details
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- DataTables Style Pagination Info and Controls -->
                            <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <div class="row">
                                    <div class="col-sm-12 col-md-5">
                                        <div class="dataTables_info" role="status" aria-live="polite">
                                            @if ($applications->total() > 0)
                                                Showing {{ $applications->firstItem() }} to
                                                {{ $applications->lastItem() }} of
                                                {{ $applications->total() }} entries
                                            @else
                                                Showing 0 to 0 of 0 entries
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-7">
                                        @if ($applications->hasPages())
                                            <div class="dataTables_paginate paging_simple_numbers">
                                                {{ $applications->appends(request()->query())->links('pagination::bootstrap-4') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <h5 class="mt-3">No applications found</h5>
                                <p class="text-muted">No one has applied for this job yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Status Change Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Application Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_status" class="form-label">New Status</label>
                        <select class="form-select" id="new_status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="pending">Pending</option>
                            <option value="reviewing">Reviewing</option>
                            <option value="accepted">Accepted</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Application Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Application Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="applicationDetails">
                <!-- Details will be populated by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('layouts.foot')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentApplicationId = null;

        // Handle status change
        document.querySelectorAll('.change-status-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                currentApplicationId = this.dataset.applicationId;
                const currentStatus = this.dataset.currentStatus;

                document.getElementById('new_status').value = currentStatus;
                new bootstrap.Modal(document.getElementById('statusModal')).show();
            });
        });

        // Handle status form submission
        document.getElementById('statusForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const newStatus = formData.get('status');

            fetch(`/admin/applications/${currentApplicationId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the status');
                });
        });
    });

    // View application details
    function viewDetails(application) {
        const detailsHtml = `
        <div class="row">
            <div class="col-md-6">
                <h6>Personal Information</h6>
                <p><strong>Name:</strong> ${application.name || application.user?.name || 'N/A'}</p>
                <p><strong>Email:</strong> ${application.email || application.user?.email || 'N/A'}</p>
                <p><strong>Phone:</strong> ${application.phone || 'N/A'}</p>
                <p><strong>Birth Date:</strong> ${application.birth_date || 'N/A'}</p>
                <p><strong>Gender:</strong> ${application.gender || 'N/A'}</p>
                <p><strong>Address:</strong> ${application.address || 'N/A'}</p>
            </div>
            <div class="col-md-6">
                <h6>Application Information</h6>
                <p><strong>Applied Date:</strong> ${new Date(application.applied_at || application.created_at).toLocaleString()}</p>
                <p><strong>Status:</strong> <span class="badge bg-${getStatusColor(application.status)}">${application.status.charAt(0).toUpperCase() + application.status.slice(1)}</span></p>
                <p><strong>Application Type:</strong> ${application.user ? 'Registered User' : 'Guest Application'}</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <h6>Documents</h6>
                ${application.cv_path ? `<p><a href="/storage/${application.cv_path}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-pdf"></i> View CV</a></p>` : '<p>No CV uploaded</p>'}
                ${application.ktp_path ? `<p><a href="/storage/${application.ktp_path}" target="_blank" class="btn btn-sm btn-outline-info"><i class="bi bi-card-image"></i> View KTP</a></p>` : '<p>No KTP uploaded</p>'}
            </div>
        </div>
    `;

        document.getElementById('applicationDetails').innerHTML = detailsHtml;
        new bootstrap.Modal(document.getElementById('detailsModal')).show();
    }

    function getStatusColor(status) {
        switch (status) {
            case 'pending':
                return 'warning';
            case 'reviewing':
                return 'info';
            case 'accepted':
                return 'success';
            case 'rejected':
                return 'danger';
            default:
                return 'secondary';
        }
    }
</script>
