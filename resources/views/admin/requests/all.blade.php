@extends('layout.app')

@section('content')
<div class="container">
    <h1 class="mb-4">All Employee Requests</h1>
    
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="card bg-primary text-white mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Requests</h5>
                            <p class="h4">{{ $totalRequests }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Pending</h5>
                            <p class="h4">{{ $pendingCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Approved</h5>
                            <p class="h4">{{ $approvedCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Rejected</h5>
                            <p class="h4">{{ $rejectedCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.requests.all') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-select">
                        <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Types</option>
                        @foreach($requestTypes as $key => $type)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('admin.requests.all') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->user->name }}</td>
                                <td>{{ $requestTypes[$request->type] }}</td>
                                <td>{{ Str::limit($request->description, 50) }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $request->status == 'approved' ? 'success' : 
                                        ($request->status == 'rejected' ? 'danger' : 'warning') 
                                    }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>{{ $request->created_at }}</td>
                                <td>
                                    <a href="{{ route('admin.requests.show', $request) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    @if($request->status == 'pending')
                                    <div class="btn-group mt-1">
                                        <form action="{{ route('admin.requests.update-status', $request) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.requests.update-status', $request) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No requests found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($requests->hasPages())
            <div class="mt-4">
                {{ $requests->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection