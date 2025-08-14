<!-- Create User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.users.create') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 position-relative">
                                <label for="create_user_password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror pe-5"
                                    id="create_user_password" name="password" required>
                                <button type="button"
                                    class="btn btn-link position-absolute top-50 end-0 translate-middle-y me-2 p-1 text-muted mt-3"
                                    id="toggleCreateUserPassword" data-password-toggle
                                    data-target="create_user_password" data-icon="eyeIconCreateUserPassword"
                                    style="z-index: 10; border: none; background: none;">
                                    <i class="bi bi-eye" id="eyeIconCreateUserPassword"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone (Optional)</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address (Optional)</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cv_file" class="form-label">CV Upload (Optional)</label>
                                <input type="file" class="form-control @error('cv_file') is-invalid @enderror"
                                    id="cv_file" name="cv_file" accept=".pdf,.doc,.docx">
                                @error('cv_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Accepted formats: PDF, DOC, DOCX. Max size: 2MB.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ktp_file" class="form-label">KTP Upload (Optional)</label>
                                <input type="file" class="form-control @error('ktp_file') is-invalid @enderror"
                                    id="ktp_file" name="ktp_file" accept="image/*">
                                @error('ktp_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Accepted formats: JPG, PNG, GIF. Max size: 2MB.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->any() && !isset($editUser) && !isset($deleteUser))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('addUserModal'));
            myModal.show();
        });
    </script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize password toggle for create user modal
        function initCreateUserPasswordToggle() {
            const toggle = document.getElementById('toggleCreateUserPassword');
            const passwordField = document.getElementById('create_user_password');
            const eyeIcon = document.getElementById('eyeIconCreateUserPassword');

            if (toggle && passwordField && eyeIcon) {
                // Remove existing event listeners
                toggle.replaceWith(toggle.cloneNode(true));
                const newToggle = document.getElementById('toggleCreateUserPassword');

                newToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Create user password toggle clicked');

                    const currentType = passwordField.getAttribute('type');
                    const newType = currentType === 'password' ? 'text' : 'password';

                    passwordField.setAttribute('type', newType);

                    if (newType === 'text') {
                        eyeIcon.className = 'bi bi-eye-slash';
                    } else {
                        eyeIcon.className = 'bi bi-eye';
                    }

                    console.log('Password type changed to:', newType);
                });

                console.log('Create user password toggle initialized');
            }
        }

        // Initialize on page load
        initCreateUserPasswordToggle();

        // Re-initialize when modal is shown
        const createUserModal = document.getElementById('addUserModal');
        if (createUserModal) {
            createUserModal.addEventListener('shown.bs.modal', function() {
                console.log('Create user modal shown, initializing password toggle');
                setTimeout(initCreateUserPasswordToggle, 100);
            });
        }
    });
</script>
