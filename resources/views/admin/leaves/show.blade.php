@extends('layout.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Leave Request Details</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Leave Details</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Employee Name</label>
                        <p class="form-control-static">{{ $leave->user->name }}</p>
                    </div>
                    
                    <div class="form-group">
                        <label>Leave Type</label>
                        <p class="form-control-static">{{ ucfirst($leave->type) }}</p>
                    </div>
                    
                    <div class="form-group">
                        <label>Start Date</label>
                        <p class="form-control-static">{{ $leave->start_date->format('d M Y') }}</p>
                    </div>
                    
                    <div class="form-group">
                        <label>End Date</label>
                        <p class="form-control-static">{{ $leave->end_date->format('d M Y') }}</p>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Duration</label>
                        <p class="form-control-static">
                            {{ $leave->end_date->diffInDays($leave->start_date) + 1 }} days
                        </p>
                    </div>
                    
                    <div class="form-group">
                        <label>Status</label>
                        <p class="form-control-static">
                            <span class="badge badge-{{ $leave->status == 'approved' ? 'success' : ($leave->status == 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($leave->status) }}
                            </span>
                        </p>
                    </div>
                    
                    @if($leave->comment)
                    <div class="form-group">
                        <label>Manager Comment</label>
                        <p class="form-control-static">{{ $leave->comment }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="form-group">
                <label>Reason</label>
                <p class="form-control-static">{{ $leave->reason }}</p>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('admin.leaves.index') }}" class="btn btn-secondary">Back</a>
                <a href="{{ route('admin.leaves.edit', $leave->id) }}" class="btn btn-primary">Edit</a>
                
                @if($leave->status == 'pending')
                <form action="{{ route('admin.leaves.update-status', $leave->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
                
                <form action="{{ route('admin.leaves.update-status', $leave->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="btn btn-danger">Reject</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection