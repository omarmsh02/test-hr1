@extends('layout.app')

@section('title', 'Employee Requests')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Employee Requests</h1>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Requests</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('manager.requests.index') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="type">Request Type</label>
                    <select class="form-control" id="type" name="type">
                        <option value="all" {{ $type == 'all' ? 'selected' : '' }}>All Types</option>
                        @foreach($requestTypes as $value => $label)
                            <option value="{{ $value }}" {{ $type == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end mb-3">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="{{ route('manager.requests.index') }}" class="btn btn-secondary ml-2">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Requests Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $requests->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Approved Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $requests->where('status', 'approved')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejected Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $requests->where('status', 'rejected')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $requests->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Employee Requests</h6>
        </div>
        <div class="card-body">
            @if($requests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="requestsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td>{{ $request->user->name }}</td>
                                    <td>{{ $requestTypes[$request->type] ?? ucfirst($request->type) }}</td>
                                    <td>{{ \Str::limit($request->description, 50) }}</td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="badge bg-warning-subtle text-warning-emphasis">Pending</span>
                                        @elseif($request->status == 'approved')
                                            <span class="badge bg-success-subtle text-success-emphasis">Approved</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger-emphasis">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('manager.requests.show', $request->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center">No requests found matching your criteria.</p>
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        $('#requestsTable').DataTable({
            "order": [[ 5, "desc" ]]
        });
    });
</script>
@endsection
@endsection