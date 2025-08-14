<table class="table datatable">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Role</th>
            <th scope="col">Phone</th>
            <th scope="col">Created Date</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $index => $user)
            <tr>
                <th scope="row">{{ $index + 1 }}</th>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'secondary' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td>{{ $user->userDetail->phone ?? 'N/A' }}</td>
                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                        data-bs-target="#editUserModal" data-user-id="{{ $user->id }}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteUser({{ $user->id }})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No users found</td>
            </tr>
        @endforelse
    </tbody>
</table>
