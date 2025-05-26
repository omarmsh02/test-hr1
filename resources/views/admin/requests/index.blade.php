@extends('layout.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Employee Requests</h1>
    
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.requests.index') }}" method="GET" class="row g-3">
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
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary ms-2">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Requests List</h5>
            <small class="text-muted">
                Showing {{ $requests->firstItem() ?? 0 }} to {{ $requests->lastItem() ?? 0 }} 
                of {{ $requests->total() }} results
            </small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
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
                                <td>{{ $request->user->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $requestTypes[$request->type] ?? ucfirst($request->type) }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($request->description, 50) }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $request->status == 'approved' ? 'success' : 
                                        ($request->status == 'rejected' ? 'danger' : 'warning') 
                                    }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>{{ $request->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.requests.show', $request) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No requests found</h5>
                                        <p class="text-muted mb-0">Try adjusting your filters or check back later.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($requests->hasPages())
        <div class="card-footer">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small class="text-muted">
                        Showing {{ $requests->firstItem() }} to {{ $requests->lastItem() }} 
                        of {{ $requests->total() }} entries
                    </small>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        {{ $requests->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.pagination {
    margin: 0;
}

.page-link {
    color: #6c757d;
    border-color: #dee2e6;
}

.page-link:hover {
    color: #495057;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.75rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endsection