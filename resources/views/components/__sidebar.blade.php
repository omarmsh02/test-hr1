<div class="sidebar" data-background-color="dark">
    <!-- Logo Header -->
    <div class="sidebar-header text-center py-3 border-bottom">
        <a href="{{ route('dashboard') }}" class="logo-text d-flex align-items-center justify-content-center text-decoration-none">
            <div class="logo-container me-2">
                <i class="fas fa-users-cog fs-4"></i>
            </div>
            <span class="navbar-brand fw-bold fs-5 text-white">WorkWise</span>
        </a>
    </div>

    <!-- Sidebar Navigation -->
    <div class="nav-scroll">
        <ul class="nav flex-column px-2 mt-2">
            <!-- Dashboard Link (Common for All Roles) -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('dashboard') }}">
                    <div class="icon-container me-3">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <span class="fs-6">Dashboard</span>
                </a>
            </li>

            <!-- Role-Specific Sections -->
            @if(auth()->check())
                <!-- Admin-Specific Links -->
                @if(auth()->user()->role === 'admin')
                    <li class="nav-section mt-3 mb-1">
                        <span class="nav-section-title">Administration</span>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('admin.users.index') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-users-cog"></i>
                            </div>
                            <span class="fs-6">Manage Users</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('admin.departments.index') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-building"></i>
                            </div>
                            <span class="fs-6">Departments</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('admin.holidays.index') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <span class="fs-6">Holidays</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('admin.policies.index') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-book"></i>
                            </div>
                            <span class="fs-6">Policies</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('admin.requests.index') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <span class="fs-6">Requests</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('admin.salaries.index') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <span class="fs-6">Salaries</span>
                        </a>
                    </li>
                @endif

                <!-- Employee-Specific Links -->
                @if(auth()->user()->role === 'employee')
                    <li class="nav-section mt-3 mb-1">
                        <span class="nav-section-title">My Workspace</span>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('employee.attendance.index') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <span class="fs-6">Attendance</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('employee.holidays.calender') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <span class="fs-6">Holidays</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('employee.leaves.index') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-plane-departure"></i>
                            </div>
                            <span class="fs-6">Leaves</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('employee.policies.index') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-book"></i>
                            </div>
                            <span class="fs-6">Policies</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('employee.requests.index') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <span class="fs-6">Requests</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('employee.salary.index') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <span class="fs-6">Salary</span>
                        </a>
                    </li>
                @endif

                <!-- Manager-Specific Links -->
                @if(auth()->user()->role === 'manager')
                    <li class="nav-section mt-3 mb-1">
                        <span class="nav-section-title">Team Management</span>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('manager.attendance.index') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <span class="fs-6">Team Attendance</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('manager.leaves.index') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-plane-departure"></i>
                            </div>
                            <span class="fs-6">Approve Leaves</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('manager.requests.index') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <span class="fs-6">Team Requests</span>
                        </a>
                    </li>
                    
                    <li class="nav-section mt-3 mb-1">
                        <span class="nav-section-title">My Workspace</span>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('manager.policies.index') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-book"></i>
                            </div>
                            <span class="fs-6">Policies</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('manager.salary.index') }}">
                            <div class="icon-container me-3">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <span class="fs-6">Salary</span>
                        </a>
                    </li>
                @endif
            @endif
        </ul>
    </div>

    <!-- Logout Button -->
    <div class="sidebar-footer px-3 py-3 border-top">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-logout w-100 py-2 rounded-3 d-flex align-items-center justify-content-center">
                <i class="fas fa-sign-out-alt me-2"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>

<style>
    :root {
    --sidebar-bg: linear-gradient(160deg, #1a2a6c, #2a5298);
    --sidebar-width: 260px;
    --sidebar-collapsed-width: 80px;
    --primary-color: #4e73df;
    --secondary-color: #f8f9fc;
    --text-color: #d1d3e2;
    --text-active: #ffffff;
    --nav-item-spacing: 0.25rem;
    --transition-speed: 0.3s;
    }

    .sidebar {
        background: var(--sidebar-bg);
        color: var(--text-color);
        height: 100vh;
        width: var(--sidebar-width);
        position: fixed;
        left: 0;
        top: 0;
        transition: all var(--transition-speed) ease;
        box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        display: flex;
        flex-direction: column;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Force all text inside sidebar (except icons) to white */
    .sidebar,
    .sidebar a,
    .sidebar span,
    .sidebar .nav-link {
        color: #fff !important;
    }

    /* Icons container and the <i> tags inside it to be white */
    .icon-container,
    .icon-container i {
        color: #fff !important;
        transition: all var(--transition-speed) ease;
        position: relative;
    }

    /* Hover and active states keep icons white */
    .nav-link:hover .icon-container,
    .nav-link:hover .icon-container i,
    .nav-link.active .icon-container,
    .nav-link.active .icon-container i {
        color: #fff !important;
    }

    /* Active nav-link background and text */
    .nav-link.active {
        color: var(--text-active);
        background: rgba(255, 255, 255, 0.15);
        font-weight: 500;
    }

    /* Sidebar header */
    .sidebar-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .logo-container {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all var(--transition-speed) ease;
    }

    .logo-text:hover .logo-container {
        transform: scale(1.1);
        background: rgba(255, 255, 255, 0.2);
    }

    .nav-scroll {
        overflow-y: auto;
        flex-grow: 1;
        padding: 0.5rem 0;
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
    }

    .nav-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .nav-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .nav-scroll::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
    }

    .nav-section {
        padding: 0.5rem 1.5rem;
    }

    .nav-section-title {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: rgba(255, 255, 255, 0.5);
        font-weight: 600;
    }

    .nav-link {
        margin: 0 var(--nav-item-spacing);
        transition: all var(--transition-speed) ease;
        position: relative;
        display: flex;
        align-items: center;
        text-decoration: none;
        /* color forced above */
    }

    .nav-link:hover {
        color: var(--text-active);
        background: rgba(255, 255, 255, 0.1);
    }

    .nav-link.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: var(--primary-color);
        border-radius: 0 3px 3px 0;
    }

    /* Sidebar footer */
    .sidebar-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(0, 0, 0, 0.1);
    }

    .btn-logout {
        background: transparent;
        color: red !important;
        border: none;
        transition: all var(--transition-speed) ease;
    }

    .btn-logout:hover {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-active);
    }

    .btn-logout i {
        color: red !important;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .sidebar {
            width: var(--sidebar-collapsed-width);
            overflow: hidden;
        }
        
        .logo-text span,
        .nav-link span,
        .nav-section-title,
        .btn-logout span {
            display: none;
        }
        
        .logo-container,
        .icon-container {
            margin-right: 0;
        }
        
        .nav-link {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        
        .sidebar:hover {
            width: var(--sidebar-width);
        }
        
        .sidebar:hover .logo-text span,
        .sidebar:hover .nav-link span,
        .sidebar:hover .nav-section-title,
        .sidebar:hover .btn-logout span {
            display: inline;
        }
    }

    /* Pulse animation for active icon */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(78, 115, 223, 0.4);
        }
        70% {
            box-shadow: 0 0 0 8px rgba(78, 115, 223, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(78, 115, 223, 0);
        }
    }

    .nav-link.active .icon-container::after {
        content: '';
        position: absolute;
        width: 8px;
        height: 8px;
        background: var(--primary-color);
        border-radius: 50%;
        top: -2px;
        right: -2px;
        animation: pulse 2s infinite;
    }
</style>