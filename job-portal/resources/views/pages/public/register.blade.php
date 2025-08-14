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
                                        <h5 class="card-title text-center pb-0 fs-4">Registrasi</h5>
                                        <p class="text-center small">Daftarkan diri anda di Golek Gawe Job Portal</p>
                                    </div>

                                    <form method="POST" action="{{ route('register') }}"
                                        class="row g-3 needs-validation" novalidate>
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
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    id="name" name="name" placeholder="name"
                                                    value="{{ old('name') }}" required />
                                                <label for="name">Nama Lengkap</label>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @else
                                                    <div class="invalid-feedback">Masukkan nama anda</div>
                                                @enderror
                                            </div>
                                        </div>

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
                                            <div class="form-floating mb-1">
                                                <input type="text"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    id="phone" name="phone" placeholder="phone"
                                                    value="{{ old('phone') }}" />
                                                <label for="phone">Nomor HP</label>
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @else
                                                    <div class="invalid-feedback">Masukkan nomor hp anda</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating mb-1">
                                                <textarea class="form-control @error('address') is-invalid @enderror" placeholder="alamat" id="address" name="address"
                                                    style="height: 100px">{{ old('address') }}</textarea>
                                                <label for="address">Alamat</label>
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @else
                                                    <div class="invalid-feedback">Masukkan alamat anda</div>
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
                                                    <i class="bi bi-eye" id="eyeIconPassword"></i>
                                                </button>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @else
                                                    <div class="invalid-feedback">Masukkan password minimal 8 karakter</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating mb-1 position-relative">
                                                <input type="password"
                                                    class="form-control @error('password_confirmation') is-invalid @enderror pe-5"
                                                    id="password_confirmation" placeholder="Confirm Password"
                                                    name="password_confirmation" required />
                                                <label for="password_confirmation">Konfirmasi Password</label>
                                                <button type="button"
                                                    class="btn btn-link position-absolute top-50 end-0 translate-middle-y me-2 p-1 text-muted"
                                                    id="togglePasswordConfirm"
                                                    style="z-index: 10; border: none; background: none;">
                                                    <i class="bi bi-eye" id="eyeIconConfirm"></i>
                                                </button>
                                                @error('password_confirmation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @else
                                                    <div class="invalid-feedback">Konfirmasi password harus sama</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Daftar</button>
                                        </div>
                                        <div class="col-12">
                                            <p class="small mb-0 text-center">Sudah Punya Akun? <a
                                                    href="{{ route('login') }}">Masuk</a></p>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Password Visibility
            function togglePasswordVisibility(toggleId, fieldId, iconId) {
                const toggle = document.getElementById(toggleId);
                const field = document.getElementById(fieldId);
                const icon = document.getElementById(iconId);

                if (toggle && field && icon) {
                    toggle.addEventListener('click', function() {
                        const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
                        field.setAttribute('type', type);

                        if (type === 'text') {
                            icon.className = 'bi bi-eye-slash';
                        } else {
                            icon.className = 'bi bi-eye';
                        }
                    });
                }
            }

            // Initialize toggles for both password fields
            togglePasswordVisibility('togglePassword', 'password', 'eyeIconPassword');
            togglePasswordVisibility('togglePasswordConfirm', 'password_confirmation', 'eyeIconConfirm');

            // Auto-focus name field
            document.getElementById('name').focus();

            // Form submit loading state
            const form = document.querySelector('form');
            const submitBtn = form.querySelector('button[type="submit"]');

            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>Mendaftar...';

                // Re-enable after 10 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Daftar';
                }, 10000);
            });
        });
    </script>
