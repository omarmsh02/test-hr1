<!-- employee/attendance/index.blade.php -->
@extends('layout.app')

@section('title', 'My Attendance')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Today's Attendance</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5>Attendance Stats</h5>
                                    <div class="row mt-3">
                                        <div class="col-md-4 text-center">
                                            <div class="p-2 bg-success text-white rounded mb-2">
                                                <h3>{{ $attendanceHistory->where('status', 'present')->count() }}</h3>
                                            </div>
                                            <span>Present</span>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="p-2 bg-warning text-white rounded mb-2">
                                                <h3>{{ $attendanceHistory->where('status', 'late')->count() }}</h3>
                                            </div>
                                            <span>Late</span>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="p-2 bg-danger text-white rounded mb-2">
                                                @php
                                                    $workdays = 0;
                                                    $startDate = now()->startOfMonth();
                                                    $endDate = now();
                                                    
                                                    for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
                                                        if (!$date->isWeekend()) {
                                                            $workdays++;
                                                        }
                                                    }
                                                    
                                                    $absent = $workdays - $attendanceHistory->where('attendance_date', '>=', now()->startOfMonth()->toDateString())->count();
                                                    $absent = max(0, $absent);
                                                @endphp
                                                <h3>{{ $absent }}</h3>
                                            </div>
                                            <span>Absent</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Attendance History</h4>
                    <div>
                        <select id="monthFilter" class="form-select form-select-sm">
                            @for ($i = 0; $i < 6; $i++)
                                @php $month = now()->subMonths($i); @endphp
                                <option value="{{ $month->format('Y-m') }}" {{ $i === 0 ? 'selected' : '' }}>
                                    {{ $month->format('F Y') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendanceHistory as $attendance)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('M d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('l') }}</td>
                                    <td>
                                        @if($attendance->check_in)
                                            {{ \Carbon\Carbon::parse($attendance->check_in)->format('g:i A') }}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->check_out)
                                            {{ \Carbon\Carbon::parse($attendance->check_out)->format('g:i A') }}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->status == 'present')
                                            <span class="badge bg-success">Present</span>
                                        @elseif($attendance->status == 'late')
                                            <span class="badge bg-warning">Late</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No attendance records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check In Button
        const checkInBtn = document.getElementById('checkInBtn');
        if(checkInBtn) {
            checkInBtn.addEventListener('click', function() {
                axios.post('{{ route("attendance.checkIn") }}')
                .then(function (response) {
                    if(response.data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(function (error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            });
        }

        // Break Button
        const breakBtn = document.getElementById('breakBtn');
        if(breakBtn) {
            breakBtn.addEventListener('click', function() {
                axios.post('{{ route("attendance.break") }}')
                .then(function (response) {
                    if(response.data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(function (error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            });
        }

        // Check Out Button
        const checkOutBtn = document.getElementById('checkOutBtn');
        if(checkOutBtn) {
            checkOutBtn.addEventListener('click', function() {
                axios.post('{{ route("attendance.checkOut") }}')
                .then(function (response) {
                    if(response.data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(function (error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            });
        }

        // Month Filter
        const monthFilter = document.getElementById('monthFilter');
        if(monthFilter) {
            monthFilter.addEventListener('change', function() {
                window.location.href = `{{ route('employee.attendance.index') }}?month=${this.value}`;
            });
        }
    });
</script>
@endsection