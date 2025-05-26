@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Leave Request Details</h1>
                <div>
                    <a href="{{ route('admin.leaves.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-left"></i> Back to Leaves
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Request Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Employee:</strong> {{ $leave->user->name }}</p>
                            <p><strong>Department:</strong> {{ $leave->user->department->name ?? 'N/A' }}</p>
                            <p><strong>Leave Type:</strong> {{ ucfirst($leave->type) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Start Date:</strong> {{ $leave->start_date->format('M d, Y') }}</p>
                            <p><strong>End Date:</strong> {{ $leave->end_date->format('M d, Y') }}</p>
                            <p><strong>Total Days:</strong> {{ $leave->end_date->diffInDays($leave->start_date) + 1 }}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>Reason:</strong></p>
                            <div class="border p-3 rounded bg-light">
                                {{ $leave->reason }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Status Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <span class="badge badge-{{ $leave->status == 'approved' ? 'success' : ($leave->status == 'rejected' ? 'danger' : 'warning') }} p-2" style="font-size: 1rem;">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </div>

                    @if($leave->status != 'pending')
                    <div class="mt-3">
                        <p><strong>Processed By:</strong> {{ $leave->processedBy->name ?? 'System' }}</p>
                        <p><strong>Processed At:</strong> {{ $leave->processed_at->format('M d, Y H:i') }}</p>
                        @if($leave->comment)
                        <p><strong>Comment:</strong></p>
                        <div class="border p-2 rounded bg-light">
                            {{ $leave->comment }}
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($leave->status == 'pending')
                    <div class="mt-4">
                        <form action="{{ route('admin.leaves.update-status', $leave->id) }}" method="POST" class="mb-2">
                            @csrf
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </form>

                        <form action="{{ route('admin.leaves.update-status', $leave->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <div class="form-group">
                                <textarea name="comment" class="form-control" placeholder="Reason for rejection (optional)" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Employee Leave Balance</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Annual Leave
                            <span class="badge badge-primary badge-pill">{{ $leaveBalance['annual'] ?? 0 }} days</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Sick Leave
                            <span class="badge badge-primary badge-pill">{{ $leaveBalance['sick'] ?? 0 }} days</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Personal Leave
                            <span class="badge badge-primary badge-pill">{{ $leaveBalance['personal'] ?? 0 }} days</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection