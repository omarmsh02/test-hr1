<div class="sidebar" data-background-color="dark">
    <!-- Logo Header -->
    <div class="sidebar-header text-center py-3 border-bottom">
        <a href="{{ route('dashboard') }}" class="logo-text d-flex align-items-center justify-content-center text-decoration-none">
            <i class="fas fa-users-cog me-2 fs-4"></i>
            <span class="navbar-brand fw-bold fs-5 text-white">HR Management</span>
        </a>
    </div>

    <!-- Sidebar Navigation -->
    <ul class="nav flex-column px-2 mt-2">
        <!-- Dashboard Link (Common for All Roles) -->
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt me-2 fs-5"></i>
                <span class="fs-6">Dashboard</span>
            </a>
        </li>

        <!-- Admin-Specific Links -->
        @if(auth()->check() && auth()->user()->role === 'admin')
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users-cog me-2 fs-5"></i>
                    <span class="fs-6">Manage Users</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('admin.departments.index') }}">
                    <i class="fas fa-building me-2 fs-5"></i>
                    <span class="fs-6">Departments</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('admin.holidays.index') }}">
                    <i class="fas fa-calendar-day me-2 fs-5"></i>
                    <span class="fs-6">Holidays</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('admin.leaves.index') }}">
                    <i class="fas fa-plane-departure me-2 fs-5"></i>
                    <span class="fs-6">Leaves</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('admin.policies.index') }}">
                    <i class="fas fa-book me-2 fs-5"></i>
                    <span class="fs-6">Policies</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('admin.requests.index') }}">
                    <i class="fas fa-clipboard-list me-2 fs-5"></i>
                    <span class="fs-6">Requests</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('admin.salaries.index') }}">
                    <i class="fas fa-dollar-sign me-2 fs-5"></i>
                    <span class="fs-6">Salaries</span>
                </a>
            </li>
        @endif

        <!-- Employee-Specific Links -->
        @if(auth()->check() && auth()->user()->role === 'employee')
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('employee.attendance.index') }}">
                    <i class="fas fa-clock me-2 fs-5"></i>
                    <span class="fs-6">Attendance</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('holidays.index') }}">
                    <i class="fas fa-calendar-day me-2 fs-5"></i>
                    <span class="fs-6">Holidays</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('employee.leaves.index') }}">
                    <i class="fas fa-plane-departure me-2 fs-5"></i>
                    <span class="fs-6">Leaves</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('policies.employeeIndex') }}">
                    <i class="fas fa-book me-2 fs-5"></i>
                    <span class="fs-6">Policies</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('employee.requests.index') }}">
                    <i class="fas fa-file-alt me-2 fs-5"></i>
                    <span class="fs-6">Requests</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('employee.salary.index') }}">
                    <i class="fas fa-dollar-sign me-2 fs-5"></i>
                    <span class="fs-6">Salary</span>
                </a>
            </li>
        @endif

        <!-- Manager-Specific Links -->
        @if(auth()->check() && auth()->user()->role === 'manager')
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('manager.attendance.index') }}">
                    <i class="fas fa-clock me-2 fs-5"></i>
                    <span class="fs-6">Team Attendance</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('manager.leaves.index') }}">
                    <i class="fas fa-plane-departure me-2 fs-5"></i>
                    <span class="fs-6">Approve Leaves</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3" href="{{ route('manager.requests.index') }}">
                    <i class="fas fa-clipboard-list me-2 fs-5"></i>
                    <span class="fs-6">Team Requests</span>
                </a>
            </li>
        @endif
    </ul>

    <!-- Logout Button -->
    <div class="sidebar-footer px-2 py-3 border-top">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-light w-100 py-2 rounded-3 d-flex align-items-center justify-content-center">
                <i class="fas fa-sign-out-alt me-2"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>

<style>
    .sidebar {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        color: white;
        height: 100vh;
        position: fixed;
        width: 250px;
        transition: all 0.3s;
        box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
    }

    .sidebar-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.8);
        transition: all 0.2s;
        margin-bottom: 2px;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        transform: translateX(3px);
    }

    .nav-link.active {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        font-weight: 500;
    }

    .nav-link i {
        width: 20px;
        text-align: center;
        font-size: 0.9rem;
    }

    .sidebar-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn-outline-light {
        border-color: rgba(255, 255, 255, 0.2);
        transition: all 0.2s;
    }

    .btn-outline-light:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
</style>