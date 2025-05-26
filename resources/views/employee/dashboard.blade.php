<!-- resources/views/employee/dashboard.blade.php -->
@extends('layout.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Employee Dashboard</div>

                <div class="card-body">
                    <div class="row mb-4">
                        <!-- Statistics Cards -->
                        <div class="col-md-4">
                            <div class="card bg-primary text-white mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Leave Requests</h5>
                                    <p class="card-text display-4">{{ $stats['total_leaves'] }}</p>
                                    <p class="card-text">{{ $stats['pending_leaves'] }} pending</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Attendance</h5>
                                    <p class="card-text display-4">{{ $stats['total_attendance'] }}</p>
                                    <p class="card-text">Days recorded</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Quick Links</h5>
                                    <div class="list-group list-group-flush">
                                        <a href="{{ route('employee.requests.create') }}" class="list-group-item list-group-item-action bg-transparent text-white border-light">New Request</a>
                                        <a href="{{ route('employee.salary.index') }}" class="list-group-item list-group-item-action bg-transparent text-white border-light">View Salary</a>
                                        <a href="{{ route('employee.policies.index') }}" class="list-group-item list-group-item-action bg-transparent text-white border-light">Company Policies</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Recent Attendance -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">Recent Attendance</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Check In</th>
                                                    <th>Check Out</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recentAttendance as $attendance)
                                                    <tr>
                                                        <td>{{ $attendance->attendance_date->format('M d, Y') }}</td>
                                                        <td>{{ $attendance->check_in ? $attendance->check_in->format('H:i') : 'N/A' }}</td>
                                                        <td>{{ $attendance->check_out ? $attendance->check_out->format('H:i') : 'N/A' }}</td>
                                                        <td>
                                                            @if($attendance->status == 'present')
                                                                <span class="badge bg-success">Present</span>
                                                            @elseif($attendance->status == 'absent')
                                                                <span class="badge bg-danger">Absent</span>
                                                            @elseif($attendance->status == 'half_day')
                                                                <span class="badge bg-warning">Half Day</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ $attendance->status }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">No recent attendance records</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming Holidays -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">Upcoming Holidays</div>
                                <div class="card-body">
                                    <div class="list-group">
                                        @forelse($upcomingHolidays as $holiday)
                                            <div class="list-group-item">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1">{{ $holiday->name }}</h5>
                                                    <small>{{ $holiday->date->format('M d, Y') }}</small>
                                                </div>
                                                <p class="mb-1">{{ $holiday->description }}</p>
                                                <small>{{ $holiday->date->diffForHumans() }}</small>
                                            </div>
                                        @empty
                                            <div class="list-group-item">No upcoming holidays</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection