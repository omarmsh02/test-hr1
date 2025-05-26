<!-- resources/views/employee/requests/show.blade.php -->
@extends('layout.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Request Details</span>
                    <a href="{{ route('employee.requests.index') }}" class="btn btn-secondary btn-sm">Back to Requests</a>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h4>
                            @if($request->type == 'equipment')
                                <span class="badge bg-primary">Equipment Request</span>
                            @elseif($request->type == 'software')
                                <span class="badge bg-success">Software Request</span>
                            @elseif($request->type == 'training')
                                <span class="badge bg-info">Training Request</span>
                            @elseif($request->type == 'document')
                                <span class="badge bg-warning">Document Request</span>
                            @else
                                <span class="badge bg-secondary">Other Request</span>
                            @endif
                        </h4>
                        <div class="mt-3">
                            <p><strong>Status:</strong> 
                                @if($request->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($request->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($request->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </p>
                            <p><strong>Created:</strong> {{ $request->created_at }}</p>
                            <p><strong>Last Updated:</strong> {{ $request->updated_at}}</p>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">Description</div>
                        <div class="card-body">
                            <p class="card-text">{{ $request->description }}</p>
                        </div>
                    </div>

                    @if($request->status != 'pending')
                        <div class="card">
                            <div class="card-header">Manager Response</div>
                            <div class="card-body">
                                @if($request->comment)
                                    <p class="card-text">{{ $request->comment }}</p>
                                @else
                                    <p class="card-text text-muted">No comments provided</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection