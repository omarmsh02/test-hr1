@extends('layout.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark font-weight-medium">
                            <i class="fas fa-users mr-2 text-primary"></i>User Management
                        </h5>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                            <i class="fas fa-plus mr-1"></i> New User
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-lg mb-4 border-0 shadow-sm">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Simplified Filter Bar -->
                    <div class="bg-white p-3 rounded-lg shadow-sm mb-4">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="form-inline">
                            <div class="form-group mr-3">
                                <select name="department" id="department" class="form-control select2" style="min-width: 200px;">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mr-3">
                                <select name="role" id="role" class="form-control select2" style="min-width: 150px;">
                                    <option value="">All Roles</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-filter mr-1"></i> Filter
                            </button>
                            @if(request()->has('department') || request()->has('role'))
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-sync-alt mr-1"></i> Reset
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 text-muted small font-weight-bold">ID</th>
                                    <th class="border-0 text-muted small font-weight-bold">Name</th>
                                    <th class="border-0 text-muted small font-weight-bold">Email</th>
                                    <th class="border-0 text-muted small font-weight-bold">Department</th>
                                    <th class="border-0 text-muted small font-weight-bold">Role</th>
                                    <th class="border-0 text-muted small font-weight-bold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td class="text-secondary align-middle">#{{ $user->id }}</td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle-sm mr-3 bg-light-primary">
                                                <span class="initials">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                            <span class="font-weight-medium">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-secondary align-middle">{{ $user->email }}</td>
                                    <td class="align-middle">
                                        <span class="badge badge-light">{{ $user->department->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $roleClasses = [
                                                'admin' => 'badge-danger',
                                                'manager' => 'badge-warning',
                                                'employee' => 'badge-success'
                                            ];
                                        @endphp
                                        <span class="badge {{ $roleClasses[$user->role] ?? 'badge-secondary' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-right align-middle">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary rounded" title="View">
                                                <i class="far fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning rounded mx-1" title="Edit">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded" title="Delete" onclick="return confirm('Are you sure?')">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-user-slash fa-2x text-muted mb-3"></i>
                                        <h5 class="text-muted">No users found</h5>
                                        @if(request()->has('department') || request()->has('role'))
                                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-link text-primary">
                                                Clear filters
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Standard Bootstrap Pagination -->
                    @if($users->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted small">
                                Showing <span class="font-weight-bold">{{ $users->firstItem() }}</span> to 
                                <span class="font-weight-bold">{{ $users->lastItem() }}</span> of 
                                <span class="font-weight-bold">{{ $users->total() }}</span> users
                            </div>
                            <div>
                                {{ $users->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Card Styling */
    .card {
        border: none;
    }
    
    .rounded-lg {
        border-radius: 10px;
    }
    
    /* Avatar Circles */
    .avatar-circle-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    .initials {
        font-weight: 500;
        color: #0d6efd;
        font-size: 0.8rem;
    }
    
    /* Table Styling */
    .table {
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table thead th {
        border: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    /* Badge Styling */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }
    
    .badge-light {
        background-color: #f8f9fa;
        color: #6c757d;
    }
    
    /* Button Styling */
    .btn-outline-primary, 
    .btn-outline-warning, 
    .btn-outline-danger {
        border-width: 1px;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
    }
    
    /* Pagination Styling */
    .pagination .page-link {
        border: none;
        color: #6c757d;
        margin: 0 2px;
        border-radius: 6px !important;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        color: white;
    }
    
    .pagination .page-link:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }
    
    /* Filter Bar */
    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
</style>

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Select option",
            allowClear: true,
            width: 'style'
        });
        
        $('[title]').tooltip();
    });
</script>
@endpush
@endsection