@extends('layout.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Request Details</h2>
            <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary">Back</a>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Employee Information</h5>
                    <p><strong>Name:</strong> {{ $request->user->name }}</p>
                    <p><strong>Email:</strong> {{ $request->user->email }}</p>
                    <p><strong>Department:</strong> {{ $request->user->department }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Request Information</h5>
                    <p><strong>Type:</strong> {{ $request->type }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ 
                            $request->status == 'approved' ? 'success' : 
                            ($request->status == 'rejected' ? 'danger' : 'warning') 
                        }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </p>
                    <p><strong>Submitted:</strong> {{ $request->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
            
            <div class="mb-4">
                <h5>Description</h5>
                <div class="border p-3 rounded bg-light">
                    {{ $request->description }}
                </div>
            </div>
            
            @if($request->comment)
            <div class="mb-4">
                <h5>Admin Comment</h5>
                <div class="border p-3 rounded bg-light">
                    {{ $request->comment }}
                </div>
            </div>
            @endif
            
            @if($request->status == 'pending')
            <div class="mt-4">
                <h5>Update Status</h5>
                <form action="{{ route('admin.requests.update-status', $request) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="approved">Approve</option>
                            <option value="rejected">Reject</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment (Optional)</label>
                        <textarea name="comment" id="comment" rows="3" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection