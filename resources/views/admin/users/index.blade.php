@extends('layout.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark font-weight-medium">
                            <i class="fas fa-users me-2 text-primary"></i>User Management
                        </h5>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                            <i class="fas fa-plus me-1"></i> New User
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-lg mb-4 border-0 shadow-sm" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show rounded-lg mb-4 border-0 shadow-sm" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Filter Bar -->
                    <div class="bg-white p-3 rounded-lg shadow-sm mb-4">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="form-inline">
                            <div class="form-group me-3">
                                <select name="department" id="department" class="form-control select2" style="min-width: 200px;">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group me-3">
                                <select name="role" id="role" class="form-control select2" style="min-width: 150px;">
                                    <option value="">All Roles</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            @if(request()->has('department') || request()->has('role'))
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-sync-alt me-1"></i> Reset
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td class="align-middle">#{{ $user->id }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle-sm me-3 bg-light-primary">
                                                    <span class="initials">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                </div>
                                                <span>{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="align-middle">{{ $user->email }}</td>
                                        <td class="align-middle">{{ $user->department->name ?? 'N/A' }}</td>
                                        <td class="align-middle">{{ ucfirst($user->role) }}</td>
                                        <td class="text-end align-middle">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning mx-1" title="Edit">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger delete-user-btn"
                                                        title="Delete"
                                                        data-user-id="{{ $user->id }}"
                                                        data-user-name="{{ $user->name }}"
                                                        data-delete-url="{{ route('admin.users.destroy', $user->id) }}">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-user-slash fa-2x text-muted mb-3"></i>
                                            <h5 class="text-muted">No users found</h5>
                                            @if(request()->has('department') || request()->has('role'))
                                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-link text-primary">Clear filters</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted small">
                                Showing <strong>{{ $users->firstItem() }}</strong> to 
                                <strong>{{ $users->lastItem() }}</strong> of 
                                <strong>{{ $users->total() }}</strong> users
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete user <strong id="userNameToDelete"></strong>?</p>
                <p class="text-danger small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .avatar-circle-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    }
    .bg-light-primary {
        background-color: #e3f2fd !important;
        color: #1976d2;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Select2 (check if jQuery is available)
    if (typeof $ !== 'undefined') {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Select option",
            allowClear: true,
            width: 'style'
        });
    }

    // Handle delete button clicks
    const deleteButtons = document.querySelectorAll('.delete-user-btn');
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const userNameSpan = document.getElementById('userNameToDelete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            const deleteUrl = this.getAttribute('data-delete-url');

            // Set the form action
            deleteForm.action = deleteUrl;
            
            // Set the user name in the modal
            userNameSpan.textContent = userName;

            // Show the modal
            const modal = new bootstrap.Modal(deleteModal);
            modal.show();
        });
    });

    // Handle form submission with loading state
    deleteForm.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';
    });
});
</script>
@endpush