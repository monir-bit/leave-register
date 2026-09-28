<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="app-brand-logo demo" height="32" />
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Employees</span>
        </li>
        <li class="menu-item {{ request()->routeIs('employees.create') ? 'active' : '' }}">
            <a href="{{ route('employees.create') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-plus"></i>
                <div>Create Employee</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('employees.index') ? 'active' : '' }}">
            <a href="{{ route('employees.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div>Employees</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Leave Management</span>
        </li>
        <li class="menu-item {{ request()->routeIs('leave-registers.create') ? 'active' : '' }}">
            <a href="{{ route('leave-registers.create') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar-plus"></i>
                <div>Create Leave Register</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('leave-registers.index') ? 'active' : '' }}">
            <a href="{{ route('leave-registers.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar"></i>
                <div>Leave Registers</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('leave-types.index') ? 'active' : '' }}">
            <a href="{{ route('leave-types.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-list-ul"></i>
                <div>Leave Types</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('reports.leave-register.create') ? 'active' : '' }}">
            <a href="{{ route('reports.leave-register.create') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div>Generate Report</div>
            </a>
        </li>
    </ul>
</aside>
