<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ isset($deleteUser) ? route('admin.users.delete', $deleteUser->id) : '#' }}">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="bi bi-exclamation-triangle"></i> Delete User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">
                        <strong>Warning!</strong> This action cannot be undone.
                    </div>

                    <p>Are you sure you want to delete the following user?</p>

                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">{{ $deleteUser->name ?? '' }}</h6>
                            <p class="card-text">
                                <strong>Email:</strong> {{ $deleteUser->email ?? '' }}<br>
                                <strong>Role:</strong> {{ ucfirst($deleteUser->role ?? '') }}<br>
                                <strong>Phone:</strong> {{ $deleteUser->userDetail->phone ?? 'N/A' }}<br>
                                <strong>Created:</strong>
                                {{ isset($deleteUser) ? $deleteUser->created_at->format('Y-m-d H:i') : '' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if (isset($deleteUser))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
            myModal.show();
        });
    </script>
@endif
