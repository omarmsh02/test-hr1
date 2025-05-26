@extends('layout.app')

@section('title', 'Request Details')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Request Details</h1>
        <a href="{{ route('manager.requests.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Requests
        </a>
    </div>

    <!-- Details Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Request Information</h6>
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
                        <p class="form-control-static">{{ $request->user->name }}</p>
                    </div>
                    <div class="form-group">
                        <label><strong>Request Type:</strong></label>
                        <p class="form-control-static">{{ ucwords(str_replace('_', ' ', $request->type)) }}</p>
                    </div>
                    <div class="form-group">
                        <label><strong>Employee Department:</strong></label>
                        <p class="form-control-static">{{ $request->user->department->name ?? 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <label><strong>Request Date:</strong></label>
                        <p class="form-control-static">{{ $request->created_at->format('M d, Y H:i A') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Status:</strong></label>
                        <p class="form-control-static">
                            @if($request->status == 'pending')
                                <span class="text-warning">Pending</span>
                            @elseif($request->status == 'approved')
                                <span class="text-success">Approved</span>
                            @else
                                <span class="text-danger">Rejected</span>
                            @endif
                        </p>
                    </div>
                    <div class="form-group">
                        <label><strong>Priority:</strong></label>
                        <p class="form-control-static">
                            @if($request->type == 'equipment' || $request->type == 'software')
                                <span class="text-info">High</span>
                            @elseif($request->type == 'training')
                                <span class="text-primary">Medium</span>
                            @else
                                <span class="text-secondary">Normal</span>
                            @endif
                        </p>
                    </div>
                    @if($request->processed_at)
                        <div class="form-group">
                            <label><strong>Processed Date:</strong></label>
                            <p class="form-control-static">{{ $request->processed_at->format('M d, Y H:i A') }}</p>
                        </div>
                    @endif
                    @if($request->processed_by)
                        <div class="form-group">
                            <label><strong>Processed By:</strong></label>
                            <p class="form-control-static">{{ $request->processedBy->name ?? 'N/A' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label><strong>Description:</strong></label>
                <div class="card">
                    <div class="card-body">
                        {{ $request->description }}
                    </div>
                </div>
            </div>

            @if($request->comment)
                <div class="form-group">
                    <label><strong>Manager's Comment:</strong></label>
                    <div class="card">
                        <div class="card-body">
                            {{ $request->comment }}
                        </div>
                    </div>
                </div>
            @endif

            @if($request->status == 'pending')
                <div class="mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">Approve Request</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('manager.requests.updateStatus', $request->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <div class="form-group">
                                            <label for="approve_comment">Comment (Optional)</label>
                                            <textarea class="form-control" id="approve_comment" name="comment" rows="3" placeholder="Add any comments about the approval..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="fas fa-check"></i> Approve Request
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0">Reject Request</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('manager.requests.updateStatus', $request->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <div class="form-group">
                                            <label for="reject_comment">Reason for Rejection <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="reject_comment" name="comment" rows="3" required placeholder="Please provide a clear reason for rejecting this request..."></textarea>
                                            <small class="form-text text-muted">This reason will be visible to the employee.</small>
                                        </div>
                                        <button type="submit" class="btn btn-danger btn-block">
                                            <i class="fas fa-times"></i> Reject Request
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