@extends('layout.app')

@section('title', 'Manager Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Error Alert -->
    @if(isset($error))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> {{ $error }}
    </div>
    @endif

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manager Dashboard</h1>
        @if(isset($department))
        <div class="alert alert-secondary py-1 mb-0">
            Department: {{ $department->name }}
        </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <!-- Department Employees Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Department Employees</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['department_employees'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Leaves Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Leave Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['pending_leaves'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Attendance Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Today's Attendance</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['today_attendance'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Absence Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Today's Absences</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ ($stats['department_employees'] ?? 0) - ($stats['today_attendance'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Recent Leave Requests -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Leave Requests</h6>
                    <a href="{{ route('manager.leaves.index') }}" class="btn btn-sm btn-primary shadow-sm">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recentLeaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Type</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentLeaves as $leave)
                                    <tr>
                                        <td>{{ $leave->user->name }}</td>
                                        <td>{{ ucfirst($leave->type) }}</td>
                                        <td>{{ $leave->start_date->format('M d, Y') }}</td>
                                        <td>{{ $leave->end_date->format('M d, Y') }}</td>
                                        <td>
                                            @if($leave->status == 'pending')
                                                <span class="badge bg-warning-subtle text-warning-emphasis">Pending</span>
                                            @elseif($leave->status == 'approved')
                                                <span class="badge bg-success-subtle text-success-emphasis">Approved</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger-emphasis">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('manager.leaves.show', $leave->id) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No recent leave requests found.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Department Employees List -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Department Employees</h6>
                    <span class="badge badge-primary">
                        {{ $departmentUsers->where('role', 'employee')->count() }} Employees
                    </span>
                </div>
                <div class="card-body">
                    @if($departmentUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($departmentUsers->where('role', 'employee') as $employee)
                                    <tr>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->email }}</td>
                                        <td>
                                            @if($employee->attendances->isNotEmpty())
                                                @php
                                                    $attendance = $employee->attendances->first();
                                                @endphp
                                                <span class="badge badge-{{ 
                                                    $attendance->status == 'present' ? 'success' : 
                                                    ($attendance->status == 'late' ? 'warning' : 'info') 
                                                }}">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            @else
                                                <span class="badge badge-danger">Absent</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No employees in your department.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection