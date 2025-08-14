<!-- Apply Job Modal for Non-authenticated Users -->
<div class="modal fade" id="guestApplyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="guestApplyForm" action="{{ route('jobs.apply.guest') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Apply for Job</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Please fill in your details to apply for this job.
                    </div>

                    <input type="hidden" id="guest_job_id" name="job_id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="guest_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="guest_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="guest_email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="guest_email" name="email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="guest_phone" class="form-label">Phone *</label>
                                <input type="tel" class="form-control" id="guest_phone" name="phone" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="guest_birth_date" class="form-label">Birth Date</label>
                                <input type="date" class="form-control" id="guest_birth_date" name="birth_date">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="guest_address" class="form-label">Address</label>
                        <textarea class="form-control" id="guest_address" name="address" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="guest_gender" class="form-label">Gender</label>
                                <select class="form-select" id="guest_gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="guest_cv" class="form-label">CV/Resume *</label>
                                <input type="file" class="form-control" id="guest_cv" name="cv_path"
                                    accept=".pdf,.doc,.docx" required>
                                <small class="text-muted">PDF, DOC, DOCX (Max 5MB)</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="guest_ktp" class="form-label">KTP (ID Card) *</label>
                                <input type="file" class="form-control" id="guest_ktp" name="ktp_path"
                                    accept=".jpg,.jpeg,.png,.pdf" required>
                                <small class="text-muted">JPG, PNG, PDF (Max 2MB)</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-2"></i>Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
