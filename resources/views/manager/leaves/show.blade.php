@extends('layout.app')

@section('title', 'Leave Details')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Leave Request Details</h1>
        <a href="{{ route('manager.leaves.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Leaves
        </a>
    </div>

    <!-- Details Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Leave Information</h6>
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

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Employee Name:</strong></label>
                        <p class="form-control-static">{{ $leave->user->name }}</p>
                    </div>
                    <div class="form-group">
                        <label><strong>Leave Type:</strong></label>
                        <p class="form-control-static">{{ ucfirst($leave->type) }} Leave</p>
                    </div>
                    <div class="form-group">
                        <label><strong>Start Date:</strong></label>
                        <p class="form-control-static">{{ $leave->start_date->format('M d, Y') }}</p>
                    </div>
                    <div class="form-group">
                        <label><strong>Request Date:</strong></label>
                        <p class="form-control-static">{{ $leave->created_at->format('M d, Y H:i A') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Status:</strong></label>
                        <p class="form-control-static">
                            @if($leave->status == 'pending')
                                <span class="text-warning">Pending</span>
                            @elseif($leave->status == 'approved')
                                <span class="text-success">Approved</span>
                            @else
                                <span class="text-danger">Rejected</span>
                            @endif
                        </p>
                    </div>
                    <div class="form-group">
                        <label><strong>End Date:</strong></label>
                        <p class="form-control-static">{{ $leave->end_date->format('M d, Y') }}</p>
                    </div>
                    <div class="form-group">
                        <label><strong>Duration:</strong></label>
                        <p class="form-control-static">
                            @php
                                $days = $leave->end_date->diffInDays($leave->start_date) + 1;
                            @endphp
                            {{ $days }} {{ Str::plural('day', $days) }}
                        </p>
                    </div>
                    @if($leave->processed_at)
                        <div class="form-group">
                            <label><strong>Processed Date:</strong></label>
                            <p class="form-control-static">{{ $leave->processed_at->format('M d, Y H:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label><strong>Reason:</strong></label>
                <div class="card">
                    <div class="card-body">
                        {{ $leave->reason }}
                    </div>
                </div>
            </div>

            @if($leave->comment)
                <div class="form-group">
                    <label><strong>Manager's Comment:</strong></label>
                    <div class="card">
                        <div class="card-body">
                            {{ $leave->comment }}
                        </div>
                    </div>
                </div>
            @endif

            @if($leave->status == 'pending')
                <div class="mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">Approve Leave</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('manager.leaves.update-status', $leave->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <div class="form-group">
                                            <label for="approve_comment">Comment (Optional)</label>
                                            <textarea class="form-control" id="approve_comment" name="comment" rows="3" placeholder="Add any comments about the approval..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="fas fa-check"></i> Approve Leave
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0">Reject Leave</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('manager.leaves.update-status', $leave->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <div class="form-group">
                                            <label for="reject_comment">Reason for Rejection <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="reject_comment" name="comment" rows="3" required placeholder="Please provide a clear reason for rejecting this leave request..."></textarea>
                                            <small class="form-text text-muted">This reason will be visible to the employee.</small>
                                        </div>
                                        <button type="submit" class="btn btn-danger btn-block">
                                            <i class="fas fa-times"></i> Reject Leave
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection