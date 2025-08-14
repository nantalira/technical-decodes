@include('layouts.header')
@include('layouts.admin-sidebar')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Dashboard Admin</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    <!-- Total Users Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Users</h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($stats['total_users']) }}</h6>
                                        <span class="text-success small pt-1 fw-bold">Registered</span> <span
                                            class="text-muted small pt-2 ps-1">users</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Total Users Card -->

                    <!-- Total Jobs Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Jobs</h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-briefcase"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($stats['total_jobs']) }}</h6>
                                        <span class="text-primary small pt-1 fw-bold">Available</span> <span
                                            class="text-muted small pt-2 ps-1">positions</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Total Jobs Card -->

                    <!-- Applications Card -->
                    <div class="col-xxl-4 col-xl-12">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Applications</h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($stats['total_applications']) }}</h6>
                                        <span
                                            class="text-danger small pt-1 fw-bold">{{ $stats['pending_applications'] }}</span>
                                        <span class="text-muted small pt-2 ps-1">pending</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Applications Card -->

                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Recent Users <span>| Latest</span></h5>

                        <div class="activity">
                            @forelse($stats['recent_users'] as $user)
                                <div class="activity-item d-flex">
                                    <div class="activite-label">{{ $user->created_at->diffForHumans() }}</div>
                                    <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                    <div class="activity-content">
                                        New user <strong>{{ $user->name }}</strong> registered
                                        <br><small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="activity-item d-flex">
                                    <div class="activity-content">No recent users</div>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div><!-- End Recent Activity -->

            </div><!-- End Right side columns -->

        </div>
    </section>

</main>
@include('layouts.foot')
