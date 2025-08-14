<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.dashboard') ? '' : 'collapsed' }}"
                href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.users') ? '' : 'collapsed' }}"
                href="{{ route('admin.users') }}">
                <i class="bi bi-people"></i>
                <span>User Management</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.jobs') ? '' : 'collapsed' }}"
                href="{{ route('admin.jobs') }}">
                <i class="bi bi-briefcase"></i>
                <span>Job Management</span>
            </a>
        </li>
    </ul>
</aside>
