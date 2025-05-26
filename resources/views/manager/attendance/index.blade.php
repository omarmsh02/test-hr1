@extends('layout.app')

@section('title', 'Department Attendance')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Department Attendance</h1>
        <div class="d-flex">
            <a href="{{ route('manager.attendance.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-sync fa-sm text-white-50"></i> Refresh
            </a>
        </div>
    </div>

    <!-- Date Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Attendance</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('manager.attendance.index') }}" class="form-inline">
                <div class="form-group mr-3 mb-2">
                    <label for="date" class="mr-2">Select Date:</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ $date }}" max="{{ now()->toDateString() }}">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Filter</button>
                <a href="{{ route('manager.attendance.index') }}" class="btn btn-secondary mb-2 ml-2">Reset</a>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Present</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $attendanceData->where('status', 'present')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Late Arrivals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $attendanceData->where('status', 'late')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Absent</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $employees->count() - $attendanceData->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Department Strength</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $employees->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Attendance for {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</h6>
            <div>
                <span class="badge badge-success">Present</span>
                <span class="badge badge-warning ml-2">Late</span>
                <span class="badge badge-danger ml-2">Absent</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="attendanceTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Working Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            @php
                                $attendance = $attendanceData->where('user_id', $employee->id)->first();
                                $status = $attendance ? $attendance->status : 'absent';
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="font-weight-bold">{{ $employee->name }}</div>
                                            <div class="text-muted small">{{ $employee->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $employee->position ?? 'N/A' }}</td>
                                <td>
                                    @if($status == 'present')
                                        <span class="badge badge-success">Present</span>
                                    @elseif($status == 'late')
                                        <span class="badge badge-warning">Late</span>
                                    @else
                                        <span class="badge badge-danger">Absent</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $attendance && $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : 'N/A' }}
                                </td>
                                <td>
                                    {{ $attendance && $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') : 'N/A' }}
                                </td>
                                <td>
                                    @if($attendance && $attendance->check_in && $attendance->check_out)
                                        @php
                                            $checkIn = \Carbon\Carbon::parse($attendance->check_in);
                                            $checkOut = \Carbon\Carbon::parse($attendance->check_out);
                                            $hours = $checkOut->diffInHours($checkIn);
                                            $minutes = $checkOut->diffInMinutes($checkIn) % 60;
                                        @endphp
                                        {{ $hours }}h {{ $minutes }}m
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Details Modals -->
@foreach($attendanceData as $attendance)
<div class="modal fade" id="attendanceDetailsModal{{ $attendance->id }}" tabindex="-1" role="dialog" aria-labelledby="attendanceDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attendanceDetailsModalLabel">
                    Attendance Details - {{ $attendance->user->name }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Employee Name</label>
                            <input type="text" class="form-control" value="{{ $attendance->user->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('F j, Y') }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <input type="text" class="form-control" 
                                value="{{ ucfirst($attendance->status) }}" 
                                style="color: {{ $attendance->status == 'present' ? 'green' : ($attendance->status == 'late' ? 'orange' : 'red') }}"
                                readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Check In Time</label>
                            <input type="text" class="form-control" value="{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : 'N/A' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Check Out Time</label>
                            <input type="text" class="form-control" value="{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') : 'N/A' }}" readonly>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <h5 class="mb-3">Update Attendance Record</h5>
                <form action="{{ route('manager.attendance.update', $attendance->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="late" {{ $attendance->status == 'late' ? 'selected' : '' }}>Late</option>
                                    <option value="half_day" {{ $attendance->status == 'half_day' ? 'selected' : '' }}>Half Day</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="check_in">Check In</label>
                                <input type="time" class="form-control" id="check_in" name="check_in" value="{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="check_out">Check Out</label>
                                <input type="time" class="form-control" id="check_out" name="check_out" value="{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ $attendance->notes ?? '' }}</textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $attendance->id }})">
                            <i class="fas fa-trash"></i> Delete Record
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Record
                        </button>
                    </div>
                </form>
                
                <form id="deleteForm{{ $attendance->id }}" action="{{ route('manager.attendance.destroy', $attendance->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Add Attendance Modals -->
@foreach($employees as $employee)
<div class="modal fade" id="addAttendanceModal{{ $employee->id }}" tabindex="-1" role="dialog" aria-labelledby="addAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAttendanceModalLabel">Add Attendance for {{ $employee->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('manager.attendance.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="user_id" value="{{ $employee->id }}">
                    <input type="hidden" name="attendance_date" value="{{ $date }}">
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="present">Present</option>
                            <option value="late">Late</option>
                            <option value="half_day">Half Day</option>
                            <option value="absent">Absent</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="check_in">Check In Time</label>
                                <input type="time" class="form-control" id="check_in" name="check_in">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="check_out">Check Out Time</label>
                                <input type="time" class="form-control" id="check_out" name="check_out">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Optional notes about this attendance record"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Attendance</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach



@push('scripts')
<script>
    function confirmDelete(attendanceId) {
        if (confirm('Are you sure you want to delete this attendance record?')) {
            event.preventDefault();
            document.getElementById('deleteForm' + attendanceId).submit();
        }
    }
    
    // Initialize date picker to restrict future dates
    document.getElementById('date').max = new Date().toISOString().split("T")[0];
    

</script>
@endpush

@endsection