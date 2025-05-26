<!-- employee/leaves/show.blade.php -->
@extends('layout.app')

@section('title', 'Leave Request Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Leave Request Details</h4>
                    <a href="{{ route('employee.leaves.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Leaves
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Leave Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="35%">Leave Type</th>
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
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($leave->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($leave->status == 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($leave->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Start Date</th>
                                            <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y (l)') }}</td>
                                        </tr>
                                        <tr>
                                            <th>End Date</th>
                                            <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y (l)') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Duration</th>
                                            <td>
                                                @php
                                                    $start = \Carbon\Carbon::parse($leave->start_date);
                                                    $end = \Carbon\Carbon::parse($leave->end_date);
                                                    $days = 0;
                                                    $weekendDays = 0;
                                                    
                                                    for ($date = clone $start; $date->lte($end); $date->addDay()) {
                                                        if (in_array($date->dayOfWeek, [0, 6])) { // 0 = Sunday, 6 = Saturday
                                                            $weekendDays++;
                                                        } else {
                                                            $days++;
                                                        }
                                                    }
                                                    
                                                    $totalDays = $end->diffInDays($start) + 1;
                                                @endphp
                                                
                                                <span class="fw-bold">{{ $days }} working day(s)</span>
                                                <small class="text-muted d-block">
                                                    Total: {{ $totalDays }} calendar day(s) including {{ $weekendDays }} weekend day(s)
                                                </small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Request Date</th>
                                            <td>{{ $leave->created_at->format('M d, Y H:i A') }}</td>
                                        </tr>
                                        @if($leave->status != 'pending')
                                        <tr>
                                            <th>Response Date</th>
                                            <td>{{ $leave->updated_at->format('M d, Y H:i A') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Leave Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <h6 class="fw-bold">Reason for Leave</h6>
                                        <p class="border p-3 bg-light">{{ $leave->reason }}</p>
                                    </div>
                                    
                                    @if($leave->status != 'pending' && $leave->comment)
                                    <div class="mb-4">
                                        <h6 class="fw-bold">Manager's Comment</h6>
                                        <p class="border p-3 {{ $leave->status == 'approved' ? 'bg-success-light' : 'bg-danger-light' }}">
                                            {{ $leave->comment }}
                                        </p>
                                    </div>
                                    @endif
                                    
                                    @if($leave->status == 'pending')
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Your leave request is pending approval from your manager.
                                    </div>
                                    </form>
                                    @elseif($leave->status == 'approved')
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle"></i> Your leave request has been approved.
                                    </div>
                                    @elseif($leave->status == 'rejected')
                                    <div class="alert alert-danger">
                                        <i class="fas fa-times-circle"></i> Your leave request has been rejected.
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Leave Balance</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $balance = [
                                            'annual' => 15,
                                            'sick' => 10,
                                            'personal' => 5
                                        ];
                                        
                                        $usedLeaves = App\Models\Leave::where('user_id', auth()->id())
                                            ->where('status', 'approved')
                                            ->get()
                                            ->groupBy('type')
                                            ->map(function ($leaves) {
                                                return $leaves->sum(function ($leave) {
                                                    $start = \Carbon\Carbon::parse($leave->start_date);
                                                    $end = \Carbon\Carbon::parse($leave->end_date);
                                                    $days = 0;
                                                    for ($date = clone $start; $date->lte($end); $date->addDay()) {
                                                        if (!in_array($date->dayOfWeek, [0, 6])) {
                                                            $days++;
                                                        }
                                                    }
                                                    return $days;
                                                });
                                            });
                                        
                                        foreach ($balance as $type => $days) {
                                            $balance[$type] -= $usedLeaves[$type] ?? 0;
                                        }
                                    @endphp
                                    
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Annual Leave</th>
                                            <td>{{ $balance['annual'] }} days remaining</td>
                                        </tr>
                                        <tr>
                                            <th>Sick Leave</th>
                                            <td>{{ $balance['sick'] }} days remaining</td>
                                        </tr>
                                        <tr>
                                            <th>Personal Leave</th>
                                            <td>{{ $balance['personal'] }} days remaining</td>
                                        </tr>
                                    </table>
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