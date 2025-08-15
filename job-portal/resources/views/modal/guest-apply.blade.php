<!-- Apply Job Modal for Non-authenticated Users -->
<div class="modal fade" id="guestApplyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="guestApplyForm" action="{{ route('jobs.apply.guest') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Apply for Job</h5>
                    <div id="submissionProgress" class="d-none">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="text-primary">Processing...</span>
                    </div>
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
                    <button type="submit" class="btn btn-primary" id="submitGuestApplication">
                        <i class="bi bi-send me-2"></i>Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Set job ID for guest application
    function setGuestJobId(jobId) {
        document.getElementById('guest_job_id').value = jobId;
    }

    // Function to show toast (reusable across pages)
    function showToast(message, type = 'info') {
        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }

        // Create toast element
        const toastId = 'toast-' + Date.now();
        const bgClass = {
            'success': 'bg-success',
            'error': 'bg-danger',
            'warning': 'bg-warning',
            'info': 'bg-info'
        } [type] || 'bg-info';

        const iconClass = {
            'success': 'bi-check-circle-fill',
            'error': 'bi-exclamation-triangle-fill',
            'warning': 'bi-exclamation-triangle-fill',
            'info': 'bi-info-circle-fill'
        } [type] || 'bi-info-circle-fill';

        const toastHtml = `
        <div id="${toastId}" class="toast ${bgClass} text-white" role="alert">
            <div class="toast-body d-flex align-items-center">
                <i class="bi ${iconClass} me-2"></i>
                <span>${message}</span>
            </div>
        </div>
    `;

        toastContainer.insertAdjacentHTML('beforeend', toastHtml);

        // Initialize and show toast
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 4000 // 4 seconds for success messages
        });

        toast.show();

        // Remove toast element after it's hidden
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }

    // Handle guest application form submission
    document.addEventListener('DOMContentLoaded', function() {
        const guestForm = document.getElementById('guestApplyForm');
        if (guestForm) {
            guestForm.addEventListener('submit', function(e) {
                // Show progress in modal header
                const progress = document.getElementById('submissionProgress');
                const modalTitle = document.querySelector('#guestApplyModal .modal-title');
                if (progress && modalTitle) {
                    progress.classList.remove('d-none');
                    modalTitle.textContent = 'Submitting Application...';
                }

                // Show loading toast
                showToast('📤 Submitting your application...', 'info');

                // Disable submit button to prevent double submission
                const submitBtn = this.querySelector('#submitGuestApplication');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        '<div class="spinner-border spinner-border-sm me-2" role="status"><span class="visually-hidden">Loading...</span></div>Processing...';
                }

                // Disable close button during submission
                const closeBtn = this.querySelector('.btn-close');
                if (closeBtn) {
                    closeBtn.disabled = true;
                }
            });

            // Add file size validation
            const cvInput = document.getElementById('guest_cv');
            const ktpInput = document.getElementById('guest_ktp');

            if (cvInput) {
                cvInput.addEventListener('change', function() {
                    validateFileSize(this, 5120, 'CV/Resume'); // 5MB
                });
            }

            if (ktpInput) {
                ktpInput.addEventListener('change', function() {
                    validateFileSize(this, 2048, 'KTP/ID Card'); // 2MB
                });
            }
        }

        // Reset modal when it's closed
        const modal = document.getElementById('guestApplyModal');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function() {
                resetModalState();
            });
        }
    });

    // Reset modal state function
    function resetModalState() {
        const progress = document.getElementById('submissionProgress');
        const modalTitle = document.querySelector('#guestApplyModal .modal-title');
        const submitBtn = document.getElementById('submitGuestApplication');
        const closeBtn = document.querySelector('#guestApplyModal .btn-close');

        if (progress) progress.classList.add('d-none');
        if (modalTitle) modalTitle.textContent = 'Apply for Job';
        if (closeBtn) closeBtn.disabled = false;

        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Submit Application';
        }
    }

    // File size validation function
    function validateFileSize(input, maxSizeKB, fieldName) {
        if (input.files.length > 0) {
            const file = input.files[0];
            const fileSizeKB = file.size / 1024;

            if (fileSizeKB > maxSizeKB) {
                const maxSizeMB = maxSizeKB / 1024;
                showToast(
                    `${fieldName} file size must be less than ${maxSizeMB}MB. Current size: ${(fileSizeKB/1024).toFixed(2)}MB`,
                    'warning');
                input.value = ''; // Clear the invalid file
                return false;
            }
        }
        return true;
    }

    // Check for Laravel flash messages and show toast
    @if (session('job_application_success'))
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a bit to ensure modal is fully loaded
            setTimeout(function() {
                showToast('✅ Application Submitted Successfully!', 'success');

                // Close the modal if it's open
                const modal = document.getElementById('guestApplyModal');
                if (modal) {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        setTimeout(function() {
                            modalInstance.hide();
                        }, 2000); // Give time to read the toast
                    }
                }

                // Reset form and modal state
                const guestForm = document.getElementById('guestApplyForm');
                if (guestForm) {
                    guestForm.reset();
                }
                resetModalState();

                // Show detailed success message after a brief delay
                setTimeout(function() {
                    showToast('📧 {{ session('job_application_success') }}', 'success');
                }, 2500);
            }, 500);
        });
    @endif

    @if (session('job_application_error'))
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                showToast('❌ {{ session('job_application_error') }}', 'error');
                resetModalState(); // Reset modal state on error
            }, 300);
        });
    @endif

    @if ($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                showToast('⚠️ {{ $errors->first() }}', 'error');
                resetModalState(); // Reset modal state on validation errors
            }, 300);
        });
    @endif
</script>
