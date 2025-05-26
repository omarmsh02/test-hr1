@extends('layout.app')

@section('title', 'Leave Management')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Leave Management</h1>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Leaves</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('manager.leaves.index') }}" class="row">
                <div class="col-md-3 mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="type">Leave Type</label>
                    <select class="form-control" id="type" name="type">
                        <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="annual" {{ request('type') == 'annual' ? 'selected' : '' }}>Annual Leave</option>
                        <option value="sick" {{ request('type') == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                        <option value="personal" {{ request('type') == 'personal' ? 'selected' : '' }}>Personal Leave</option>
                        <option value="unpaid" {{ request('type') == 'unpaid' ? 'selected' : '' }}>Unpaid Leave</option>
                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="daterange">Date Range</label>
                    <input type="text" class="form-control" id="daterange" name="daterange" 
                           value="{{ request('daterange') }}" placeholder="Select Date Range">
                </div>
                <div class="col-md-3 d-flex align-items-end mb-3">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="{{ route('manager.leaves.index') }}" class="btn btn-secondary ml-2">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Leave Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Leave Requests</h6>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if($leaves->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="leavesTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaves as $leave)
                                <tr>
                                    <td>{{ $leave->id }}</td>
                                    <td>{{ $leave->user->name }}</td>
                                    <td>{{ ucfirst($leave->type) }}</td>
                                    <td>{{ $leave->start_date->format('M d, Y') }}</td>
                                    <td>{{ $leave->end_date->format('M d, Y') }}</td>
                                    <td>
                                        @php
                                            $days = $leave->end_date->diffInDays($leave->start_date) + 1;
                                        @endphp
                                        {{ $days }} {{ Str::plural('day', $days) }}
                                    </td>
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
                                        <a href="{{ route('manager.leaves.show', $leave->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center p-4">
                    <p class="text-muted">No leave requests found matching your filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize date range picker
    $('#daterange').daterangepicker({
        opens: 'right',
        locale: {
            format: 'YYYY-MM-DD'
        },
        autoUpdateInput: false
    });
    
    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });

    $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
});
</script>
@endpush