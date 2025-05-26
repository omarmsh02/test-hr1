<!-- resources/views/employee/requests/index.blade.php -->
@extends('layout.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>My Requests</span>
                    <a href="{{ route('employee.requests.create') }}" class="btn btn-primary btn-sm">New Request</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $request)
                                    <tr>
                                        <td>
                                            @if($request->type == 'equipment')
                                                <span class="badge bg-primary">Equipment</span>
                                            @elseif($request->type == 'software')
                                                <span class="badge bg-success">Software</span>
                                            @elseif($request->type == 'training')
                                                <span class="badge bg-info">Training</span>
                                            @elseif($request->type == 'document')
                                                <span class="badge bg-warning">Document</span>
                                            @else
                                                <span class="badge bg-secondary">Other</span>
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($request->description, 50) }}</td>
                                        <td>
                                            @if($request->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($request->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($request->status == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at}}</td>
                                        <td>
                                            <a href="{{ route('employee.requests.show', $request->id) }}" class="btn btn-sm btn-info">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No requests found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection