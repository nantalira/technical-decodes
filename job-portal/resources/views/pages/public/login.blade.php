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
                                        <h5 class="card-title text-center pb-0 fs-4">Masuk</h5>
                                        <p class="text-center small">Selamat Datang di Golek Gawe Job Portal</p>
                                    </div>

                                    <form method="POST" action="{{ route('login') }}" class="row g-3 needs-validation"
                                        novalidate>
                                        @csrf
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
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="email" placeholder="email" name="email"
                                                    value="{{ old('email') }}" required />
                                                <label for="email">Email</label>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @else
                                                    <div class="invalid-feedback">Masukkan email yang valid</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating mb-1 position-relative">
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror pe-5"
                                                    id="password" placeholder="Password" name="password" required />
                                                <label for="password">Password</label>
                                                <button type="button"
                                                    class="btn btn-link position-absolute top-50 end-0 translate-middle-y me-2 p-1 text-muted"
                                                    id="togglePassword"
                                                    style="z-index: 10; border: none; background: none;">
                                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                                </button>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @else
                                                    <div class="invalid-feedback">Masukkan password</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="remember"
                                                    name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="remember">
                                                    Ingat saya
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit" id="loginBtn">
                                                Login
                                            </button>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-center">
                                                <a href="{{ route('password.request') }}" class="small text-muted">Lupa
                                                    Password?</a>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <p class="small mb-0 text-center">Belum punya akun? <a
                                                    href="{{ route('register') }}">Buat akun anda</a></p>
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

    <script>
        // Minimal JavaScript - prevent double submit, auto-focus, dan toggle password
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form');
            const loginBtn = document.getElementById('loginBtn');

            // Prevent double submit
            loginForm.addEventListener('submit', function() {
                loginBtn.disabled = true;
                loginBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Masuk...';

                // Re-enable after 5 seconds as fallback
                setTimeout(() => {
                    loginBtn.disabled = false;
                    loginBtn.innerHTML = 'Login';
                }, 5000);
            });

            // Auto-focus email field
            document.getElementById('email').focus();

            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            togglePassword.addEventListener('click', function() {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);

                // Toggle icon
                if (type === 'text') {
                    eyeIcon.className = 'bi bi-eye-slash';
                } else {
                    eyeIcon.className = 'bi bi-eye';
                }
            });
        });
    </script>
