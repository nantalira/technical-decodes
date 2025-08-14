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
        <h1>User Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">User Management</h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary" id="toggleFilter">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addUserModal">
                                    <i class="bi bi-plus-circle"></i> Add User
                                </button>
                            </div>
                        </div>

                        <!-- Filter Section -->
                        <div class="card bg-light mb-3" id="filterSection" style="display: none;">
                            <div class="card-body">
                                <form method="GET" action="{{ route('admin.users') }}" id="filterForm">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="filter_role" class="form-label">Role</label>
                                                <select class="form-select" id="filter_role" name="role">
                                                    <option value="">All Roles</option>
                                                    <option value="admin"
                                                        {{ request('role') == 'admin' ? 'selected' : '' }}>Admin
                                                    </option>
                                                    <option value="user"
                                                        {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="filter_documents" class="form-label">Documents
                                                    Status</label>
                                                <select class="form-select" id="filter_documents" name="documents">
                                                    <option value="">All Documents</option>
                                                    <option value="complete"
                                                        {{ request('documents') == 'complete' ? 'selected' : '' }}>
                                                        Complete (CV + KTP)</option>
                                                    <option value="partial"
                                                        {{ request('documents') == 'partial' ? 'selected' : '' }}>
                                                        Partial (CV or KTP)</option>
                                                    <option value="none"
                                                        {{ request('documents') == 'none' ? 'selected' : '' }}>No
                                                        Documents</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="filter_date_from" class="form-label">Registration Date
                                                    From</label>
                                                <input type="date" class="form-control" id="filter_date_from"
                                                    name="date_from" value="{{ request('date_from') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="filter_date_to" class="form-label">Registration Date
                                                    To</label>
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
                                            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
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
                                <form method="GET" action="{{ route('admin.users') }}" class="d-inline">
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
                                <form method="GET" action="{{ route('admin.users') }}" class="d-flex">
                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <input type="text" class="form-control" placeholder="Search users..."
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
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                                class="text-white text-decoration-none">
                                                Name
                                                @if (request('sort') === 'name')
                                                    <i
                                                        class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'email', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                                class="text-white text-decoration-none">
                                                Email
                                                @if (request('sort') === 'email')
                                                    <i
                                                        class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'role', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                                class="text-white text-decoration-none">
                                                Role
                                                @if (request('sort') === 'role')
                                                    <i
                                                        class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Documents</th>
                                        <th scope="col">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                                class="text-white text-decoration-none">
                                                Registration Date
                                                @if (request('sort') === 'created_at')
                                                    <i
                                                        class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $index => $user)
                                        <tr>
                                            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                                            <td>
                                                <div class="fw-bold">{{ $user->name }}</div>
                                            </td>
                                            <td>
                                                <div>{{ $user->email }}</div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'secondary' }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($user->userDetail && $user->userDetail->phone)
                                                    <div>{{ $user->userDetail->phone }}</div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($user->userDetail)
                                                    <div class="d-flex gap-1">
                                                        @if ($user->userDetail->cv_path)
                                                            <span class="badge bg-success"
                                                                title="CV Available">CV</span>
                                                        @endif
                                                        @if ($user->userDetail->ktp_path)
                                                            <span class="badge bg-info"
                                                                title="KTP Available">KTP</span>
                                                        @endif
                                                        @if (!$user->userDetail->cv_path && !$user->userDetail->ktp_path)
                                                            <span class="badge bg-light text-dark">No docs</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="badge bg-light text-dark">No docs</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        onclick="editUser({{ $user->id }})" title="Edit User">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-info"
                                                        onclick="viewUser({{ $user->id }})" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    @if ($user->role !== 'admin')
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="deleteUser({{ $user->id }})"
                                                            title="Delete User">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bi bi-person-x display-4"></i>
                                                    <div class="mt-2">
                                                        @if (request()->hasAny(['search', 'role', 'documents', 'date_from', 'date_to']))
                                                            No users found matching your criteria.
                                                            <div class="mt-2">
                                                                <a href="{{ route('admin.users') }}"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    <i class="bi bi-arrow-counterclockwise"></i> Clear
                                                                    Filters
                                                                </a>
                                                            </div>
                                                        @else
                                                            No users available.
                                                            <div class="mt-2">
                                                                <button type="button" class="btn btn-sm btn-primary"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#addUserModal">
                                                                    <i class="bi bi-plus-circle"></i> Add First User
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
                                        @if ($users->total() > 0)
                                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of
                                            {{ $users->total() }} entries
                                        @else
                                            Showing 0 to 0 of 0 entries
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    @if ($users->hasPages())
                                        <div class="dataTables_paginate paging_simple_numbers">
                                            {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
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

<!-- Include Create User Modal -->
@include('pages.admin.modals.create-user')

<!-- Include Edit User Modal if editing -->
@if (isset($editUser))
    @include('pages.admin.modals.edit-user')
@endif

<!-- Include Delete User Modal if deleting -->
@if (isset($deleteUser))
    @include('pages.admin.modals.delete-user')
@endif

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleFilterBtn = document.getElementById('toggleFilter');
            const filterSection = document.getElementById('filterSection');

            // Check if there are active filters
            const hasActiveFilters =
                {{ request()->hasAny(['role', 'documents', 'date_from', 'date_to']) ? 'true' : 'false' }};

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
        });

        // User management functions
        function editUser(userId) {
            // Implementation for editing user
            console.log('Edit user:', userId);
            // You can implement modal opening or redirect to edit page
        }

        function viewUser(userId) {
            // Implementation for viewing user details
            console.log('View user:', userId);
            // You can implement modal opening or redirect to view page
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                // Implementation for deleting user
                console.log('Delete user:', userId);
                // You can implement AJAX deletion or form submission
            }
        }
    </script>
@endsection

@include('layouts.foot')
