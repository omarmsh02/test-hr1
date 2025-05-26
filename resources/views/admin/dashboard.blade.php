@extends('layout.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Admin Dashboard</h1>
        <div class="text-muted">
            <i class="fas fa-calendar-alt me-2"></i>
            <span id="currentDate">{{ now()->format('l, F j, Y') }}</span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <!-- Total Employees Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Employees</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_employees'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.users.index') }}" class="text-decoration-none small text-primary">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Managers Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Managers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_managers'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.users.index') }}?role=manager" class="text-decoration-none small text-success">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Leaves Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Leaves</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['pending_leaves'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-plane-departure fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.leaves.index') }}?status=pending" class="text-decoration-none small text-warning">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Upcoming Holidays -->
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Upcoming Holidays</h6>
                </div>
                <div class="card-body p-0">
                    @php
                        // Use the $Holidays variable if it exists from controller
                        // Otherwise, fall back to querying directly (but properly namespaced)
                        $displayHolidays = $Holidays ?? \App\Models\Holiday::where('date', '>=', now())
                            ->orderBy('date')
                            ->take(5)
                            ->get();
                    @endphp
                    
                    @if($displayHolidays->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    @foreach($displayHolidays as $holiday)
                                    <tr>
                                        <td class="border-0 py-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1 font-weight-bold">{{ $holiday->name }}</h6>
                                                    <small class="text-muted">
                                                        {{ $holiday->date->format('l, F j, Y') }}
                                                    </small>
                                                </div>
                                                <span class="badge bg-info-subtle text-info-emphasis">
                                                    {{ $holiday->date->diffForHumans() }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center p-4 text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <p class="mb-0">No upcoming holidays found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles to match manager dashboard */
.card {
    border: none !important;
    border-radius: 0.35rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.card-header {
    background-color: #f8f9fc !important;
    border-bottom: 1px solid #e3e6f0 !important;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}

.font-weight-bold {
    font-weight: 700 !important;
}

.text-xs {
    font-size: 0.7rem;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

.text-info-emphasis {
    color: #055160 !important;
}

body {
    background-color: #f8f9fc;
    color: #5a5c69;
}

.container-fluid {
    padding: 0 1.5rem;
}

/* Hover effects */
.card:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

/* Badge styling */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.35em 0.65em;
}

/* Link styling */
a {
    color: #4e73df;
    text-decoration: none;
}

a:hover {
    color: #224abe;
    text-decoration: underline;
}

/* Table styling */
.table-borderless td {
    border: none !important;
}

.table-responsive {
    border-radius: 0.35rem;
}

/* Icon spacing */
.fas {
    margin-right: 0.25rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container-fluid {
        padding: 0 1rem;
    }
    
    .d-sm-flex {
        flex-direction: column !important;
        align-items: flex-start !important;
    }
    
    .mb-4 {
        margin-bottom: 1rem !important;
    }
}
</style>

<script>
// Update date display
document.addEventListener('DOMContentLoaded', function() {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const today = new Date();
    const dateElement = document.getElementById('currentDate');
    if (dateElement) {
        dateElement.textContent = today.toLocaleDateString('en-US', options);
    }
});
</script>
@endsection