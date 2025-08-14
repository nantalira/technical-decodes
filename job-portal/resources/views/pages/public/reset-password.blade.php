@include('layouts.head')

<body>

    <main>
        <div class="container">

            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a href="{{ route('home') }}" class="logo d-flex align-items-center w-auto">
                                    <span class="d-none d-lg-block">Golek Gawe</span>
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Reset Password</h5>
                                        <p class="text-center small">Masukkan password baru Anda</p>
                                    </div>

                                    <form method="POST" action="{{ route('password.update') }}"
                                        class="row g-3 needs-validation" novalidate>
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <input type="hidden" name="email" value="{{ $email }}">

                                        @if (session('error'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ session('error') }}
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="alert"></button>
                                            </div>
                                        @endif

                                        @if (session('success'))
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                {{ session('success') }}
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="alert"></button>
                                            </div>
                                        @endif

                                        <div class="col-12">
                                            <div class="form-floating mb-1">
                                                <input type="email" class="form-control" id="email_display"
                                                    value="{{ $email }}" disabled />
                                                <label for="email_display">Email</label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating mb-1 position-relative">
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror pe-5"
                                                    id="password" placeholder="Password" name="password" required />
                                                <label for="password">Password Baru</label>
                                                <button type="button"
                                                    class="btn btn-link position-absolute top-50 end-0 translate-middle-y me-2 p-1 text-muted password-toggle"
                                                    style="z-index: 10; border: none; background: none;">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @else
                                                    <div class="invalid-feedback">Password minimal 8 karakter</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating mb-1 position-relative">
                                                <input type="password"
                                                    class="form-control @error('password_confirmation') is-invalid @enderror pe-5"
                                                    id="password_confirmation" placeholder="Konfirmasi Password"
                                                    name="password_confirmation" required />
                                                <label for="password_confirmation">Konfirmasi Password</label>
                                                <button type="button"
                                                    class="btn btn-link position-absolute top-50 end-0 translate-middle-y me-2 p-1 text-muted password-toggle"
                                                    style="z-index: 10; border: none; background: none;">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                @error('password_confirmation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @else
                                                    <div class="invalid-feedback">Konfirmasi password harus sama</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit" id="resetBtn">
                                                Reset Password
                                            </button>
                                        </div>

                                        <div class="col-12">
                                            <div class="text-center">
                                                <a href="{{ route('login') }}" class="small text-muted">Kembali ke
                                                    Login</a>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main><!-- End #main -->

    @include('layouts.foot')

    <style>
        body {
            font-family: "Open Sans", sans-serif;
            background: #f6f9ff;
            color: #444;
        }

        .logo span {
            font-size: 26px;
            font-weight: 700;
            color: #012970;
            font-family: "Nunito", sans-serif;
        }

        .card {
            margin-bottom: 30px;
            border: none;
            border-radius: 5px;
            box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1);
        }

        .form-floating {
            position: relative;
        }

        .form-floating>.form-control:focus~label,
        .form-floating>.form-control:not(:placeholder-shown)~label {
            opacity: .65;
            transform: scale(.85) translateY(-0.5rem) translateX(0.15rem);
        }

        .btn-primary {
            background: #4154f1;
            border: 0;
            padding: 10px 30px;
            color: #fff;
            transition: 0.4s;
            border-radius: 4px;
        }

        .btn-primary:hover {
            background: #5969f3;
        }

        .form-control:focus {
            border-color: #4154f1;
            box-shadow: 0 0 0 0.2rem rgba(65, 84, 241, 0.25);
        }

        .form-control:disabled {
            background-color: #f8f9fa;
            border-color: #e9ecef;
            opacity: 0.7;
        }

        .text-muted:hover {
            color: #4154f1 !important;
        }

        .alert {
            border-radius: 4px;
        }

        /* Loading animation */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* Password strength indicator */
        .password-strength {
            height: 5px;
            border-radius: 2px;
            margin-top: 5px;
            transition: all 0.3s ease;
        }

        .strength-weak {
            background-color: #dc3545;
            width: 33%;
        }

        .strength-medium {
            background-color: #ffc107;
            width: 66%;
        }

        .strength-strong {
            background-color: #28a745;
            width: 100%;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('resetBtn');
            const originalText = submitBtn.innerHTML;
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');

            // Password toggle functionality
            document.querySelectorAll('.password-toggle').forEach(function(toggle) {
                toggle.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.className = 'bi bi-eye-slash';
                    } else {
                        input.type = 'password';
                        icon.className = 'bi bi-eye';
                    }
                });
            });

            // Form submission
            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Mereset...';
            });

            // Password strength indicator
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;

                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password)) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;

                // Remove existing strength indicator
                const existingIndicator = this.parentElement.querySelector('.password-strength');
                if (existingIndicator) {
                    existingIndicator.remove();
                }

                // Add new strength indicator
                if (password.length > 0) {
                    const strengthDiv = document.createElement('div');
                    strengthDiv.className = 'password-strength';

                    if (strength <= 2) {
                        strengthDiv.classList.add('strength-weak');
                    } else if (strength <= 3) {
                        strengthDiv.classList.add('strength-medium');
                    } else {
                        strengthDiv.classList.add('strength-strong');
                    }

                    this.parentElement.appendChild(strengthDiv);
                }
            });

            // Password confirmation validation
            confirmPasswordInput.addEventListener('input', function() {
                if (this.value !== passwordInput.value) {
                    this.setCustomValidity('Password tidak sama');
                } else {
                    this.setCustomValidity('');
                }
            });

            // Re-enable button if there's an error (page reload)
            if (document.querySelector('.alert-danger')) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    </script>

</body>

</html>
