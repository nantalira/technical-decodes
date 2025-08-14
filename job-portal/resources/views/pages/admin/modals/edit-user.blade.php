<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ isset($editUser) ? route('admin.users.update', $editUser->id) : '#' }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="edit_name" name="name" value="{{ old('name', $editUser->name ?? '') }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="edit_email" name="email" value="{{ old('email', $editUser->email ?? '') }}"
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_phone" class="form-label">Phone (Optional)</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="edit_phone" name="phone"
                                    value="{{ old('phone', $editUser->userDetail->phone ?? '') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_address" class="form-label">Address (Optional)</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="edit_address" name="address" rows="3">{{ old('address', $editUser->userDetail->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_cv_file" class="form-label">CV Upload</label>
                                <input type="file" class="form-control @error('cv_file') is-invalid @enderror"
                                    id="edit_cv_file" name="cv_file" accept=".pdf,.doc,.docx">
                                @error('cv_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if (isset($editUser) && $editUser->userDetail && $editUser->userDetail->cv_path)
                                    <div class="form-text">
                                        Current CV: <a href="{{ asset('storage/' . $editUser->userDetail->cv_path) }}"
                                            target="_blank">
                                            <i class="bi bi-file-earmark-pdf"></i> View Current CV
                                        </a>
                                    </div>
                                @endif
                                <div class="form-text">Accepted formats: PDF, DOC, DOCX. Max size: 2MB.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_ktp_file" class="form-label">KTP Upload</label>
                                <input type="file" class="form-control @error('ktp_file') is-invalid @enderror"
                                    id="edit_ktp_file" name="ktp_file" accept="image/*">
                                @error('ktp_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if (isset($editUser) && $editUser->userDetail && $editUser->userDetail->ktp_path)
                                    <div class="form-text">
                                        Current KTP: <a
                                            href="{{ asset('storage/' . $editUser->userDetail->ktp_path) }}"
                                            target="_blank">
                                            <i class="bi bi-image"></i> View Current KTP
                                        </a>
                                    </div>
                                @endif
                                <div class="form-text">Accepted formats: JPG, PNG, GIF. Max size: 2MB.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if (isset($editUser))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            myModal.show();
        });
    </script>
@endif
