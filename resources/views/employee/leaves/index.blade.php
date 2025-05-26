<!-- employee/leaves/index.blade.php -->
@extends('layout.app')

@section('title', 'My Leave Requests')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">My Leave Balance</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5>Annual Leave</h5>
                                    <h2 class="mt-2">{{ $leaveBalance['annual'] }} days</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>Sick Leave</h5>
                                    <h2 class="mt-2">{{ $leaveBalance['sick'] }} days</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5>Personal Leave</h5>
                                    <h2 class="mt-2">{{ $leaveBalance['personal'] }} days</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">My Leave Requests</h4>
                    <a href="{{ route('employee.leaves.create') }}" class="btn btn-primary">Request Leave</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Days</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaves as $leave)
                                <tr>
                                    <td>
                                        @if($leave->type == 'annual')
                                            <span class="badge bg-primary">Annual Leave</span>
                                        @elseif($leave->type == 'sick')
                                            <span class="badge bg-success">Sick Leave</span>
                                        @elseif($leave->type == 'personal')
                                            <span class="badge bg-info">Personal Leave</span>
                                        @elseif($leave->type == 'unpaid')
                                            <span class="badge bg-warning">Unpaid Leave</span>
                                        @else
                                            <span class="badge bg-secondary">Other</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</td>
                                    <td>
                                        @php
                                            $start = \Carbon\Carbon::parse($leave->start_date);
                                            $end = \Carbon\Carbon::parse($leave->end_date);
                                            $days = 0;
                                            for ($date = clone $start; $date->lte($end); $date->addDay()) {
                                                if (!in_array($date->dayOfWeek, [0, 6])) { // Skip weekends
                                                    $days++;
                                                }
                                            }
                                        @endphp
                                        {{ $days }} day(s)
                                    </td>
                                    <td>{{ Str::limit($leave->reason, 30) }}</td>
                                    <td>
                                        @if($leave->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($leave->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($leave->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>                                        <td>
                                            <a href="{{ route('employee.leaves.show', $leave->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No leave requests found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Date validation
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
        });
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            const leaveType = document.getElementById('type').value;
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            
            // Calculate days between dates (excluding weekends)
            let days = 0;
            for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
                if (d.getDay() !== 0 && d.getDay() !== 6) { // Skip weekends (0 = Sunday, 6 = Saturday)
                    days++;
                }
            }
            
            // Check leave balance based on type
            if (leaveType === 'annual' && days > {{ $leaveBalance['annual'] }}) {
                event.preventDefault();
                alert('You do not have enough annual leave balance for this request.');
            } else if (leaveType === 'sick' && days > {{ $leaveBalance['sick'] }}) {
                event.preventDefault();
                alert('You do not have enough sick leave balance for this request.');
            } else if (leaveType === 'personal' && days > {{ $leaveBalance['personal'] }}) {
                event.preventDefault();
                alert('You do not have enough personal leave balance for this request.');
            }
        });
    });
</script>
@endsection