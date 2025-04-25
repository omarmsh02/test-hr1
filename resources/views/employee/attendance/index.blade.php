@extends('layout.app')

@section('content')
<div class="container">
    <h1>Attendance History</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Clock In</th>
                <th>Clock Out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendanceHistory as $attendance)
            <tr>
                <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('M d, Y') }}</td>
                <td>{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('h:i A') : 'N/A' }}</td>
                <td>{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('h:i A') : 'Not yet' }}</td>
                <td>{{ ucfirst($attendance->status ?? 'N/A') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No attendance records found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection