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
                                        <h5 class="card-title text-center pb-0 fs-4">Lupa Password</h5>
                                        <p class="text-center small">Masukkan email Anda untuk reset password</p>
                                    </div>

                                    <form method="POST" action="{{ route('password.email') }}"
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
                                            <button class="btn btn-primary w-100" type="submit" id="resetBtn">
                                                Kirim Link Reset Password
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('resetBtn');
            const originalText = submitBtn.innerHTML;

            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Mengirim...';
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
