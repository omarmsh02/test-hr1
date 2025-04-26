@extends('layout.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">Admin Dashboard</h1>
        <div class="text-muted">
            <i class="fas fa-calendar-alt me-2"></i>
            <span id="currentDate">{{ now()->format('l, F j, Y') }}</span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-start border-primary border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Total Employees</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['total_employees'] }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.users.index') }}" class="text-decoration-none small">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-start border-success border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Total Managers</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['total_managers'] }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-user-tie fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.users.index') }}?role=manager" class="text-decoration-none small">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-start border-warning border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Pending Leaves</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['pending_leaves'] }}</h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-plane-departure fa-2x text-warning"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.leaves.index') }}?status=pending" class="text-decoration-none small">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Leave Requests Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Recent Leave Requests</h5>
                <a href="{{ route('admin.leaves.index') }}" class="btn btn-sm btn-outline-primary">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 px-4">Employee</th>
                            <th class="py-3 px-4">Type</th>
                            <th class="py-3 px-4">Dates</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLeaves as $leave)
                        <tr>
                            <td class="py-3 px-4 align-middle">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ $leave->user->name }}</h6>
                                        <small class="text-muted">{{ $leave->user->department->name ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <span class="badge bg-light text-dark">{{ ucfirst($leave->type) }}</span>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <small>{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</small>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                @if($leave->status == 'approved')
                                    <span class="badge bg-success bg-opacity-10 text-success">Approved</span>
                                @elseif($leave->status == 'rejected')
                                    <span class="badge bg-danger bg-opacity-10 text-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning">Pending</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <a href="{{ route('admin.leaves.show', $leave->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Additional Dashboard Widgets -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-bold">Attendance Overview</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 250px;">
                        <!-- Chart would go here -->
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <i class="fas fa-chart-bar me-2"></i> Attendance chart would display here
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!--Holidays -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-bold">Upcoming Holidays</h5>
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
                        <div class="list-group list-group-flush">
                            @foreach($displayHolidays as $holiday)
                            <div class="list-group-item border-0 py-3 px-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $holiday->name }}</h6>
                                        <small class="text-muted">
                                            {{ $holiday->date->format('l, F j, Y') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        {{ $holiday->date->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center p-4 text-muted">
                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                            <p>No upcoming holidays found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-radius: 10px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
    
    body {
        background-color: #f8f9fa;
    }
    
    .border-start {
        border-left-width: 4px !important;
    }
</style>

<script>
    // Update date display
    document.addEventListener('DOMContentLoaded', function() {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const today = new Date();
        document.getElementById('currentDate').textContent = today.toLocaleDateString('en-US', options);
    });
</script>
@endsection