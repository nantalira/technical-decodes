@include('layouts.head')
<!-- ======= Header ======= -->

<body>

    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="logo d-flex align-items-center">
                <span class="d-none d-lg-block">GolekGawe</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>
        <!-- End Logo -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown">
                        <span class="d-none d-md-block dropdown-toggle ps-2">
                            @auth
                                {{ Auth::user()->name }}
                            @else
                                Guest
                            @endauth
                        </span>
                        <span class="d-block d-md-none">
                            Account
                        </span>
                    </a>
                    <!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>
                                @auth
                                    {{ Auth::user()->name }}
                                @else
                                    Guest User
                                @endauth
                            </h6>
                            <span>
                                @auth
                                    {{ ucfirst(Auth::user()->role) }}
                                @else
                                    Not Logged In
                                @endauth
                            </span>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>

                        @auth
                            <!-- Menu untuk user yang sudah login -->
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}">
                                    <i class="bi bi-person"></i>
                                    <span>Profil Saya</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center"
                                        style="border: none; background: none; width: 100%; text-align: left;">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Log Out</span>
                                    </button>
                                </form>
                            </li>
                        @else
                            <!-- Menu untuk guest user (belum login) -->
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                    <span>Login</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('register') }}">
                                    <i class="bi bi-person-plus"></i>
                                    <span>Register</span>
                                </a>
                            </li>
                        @endauth
                    </ul>
                    <!-- End Profile Dropdown Items -->
                </li>
                <!-- End Profile Nav -->
            </ul>
        </nav>
        <!-- End Icons Navigation -->
    </header>
    <!-- End Header -->
