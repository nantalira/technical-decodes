<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <!-- Saved Jobs Nav -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('jobs.saved') ? '' : 'collapsed' }}"
                href="{{ route('jobs.saved') }}">
                <i class="bi bi-bookmark"></i>
                <span>Saved Jobs</span>
            </a>
        </li>

        <!-- Applied Jobs Nav -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('jobs.applied') ? '' : 'collapsed' }}"
                href="{{ route('jobs.applied') }}">
                <i class="bi bi-briefcase"></i>
                <span>Applied Jobs</span>
            </a>
        </li>
        <!-- End Tables Nav -->
    </ul>
</aside>
