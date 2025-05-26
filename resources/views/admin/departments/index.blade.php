@extends('layout.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Departments</h1>
        <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Department
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 px-4">Department</th>
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-4">Manager</th>
                            <th class="py-3 px-4">Employee Count</th>
                            <th class="py-3 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                        <tr>
                            <td class="py-3 px-4 align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="department-icon me-3">
                                        <i class="fas fa-building text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $department->name }}</h6>
                                        <small class="text-muted">ID: #{{ $department->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                @if($department->description)
                                    <span class="text-muted" title="{{ $department->description }}">
                                        {{ Str::limit($department->description, 50) }}
                                    </span>
                                @else
                                    <span class="text-muted fst-italic">No description</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 align-middle">
                                @if($department->manager)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <span class="initials">{{ strtoupper(substr($department->manager->name, 0, 1)) }}</span>
                                        </div>
                                        <span>{{ $department->manager->name }}</span>
                                    </div>
                                @else
                                    <span class="badge bg-warning text-dark">Not assigned</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <span class="badge bg-info">
                                    {{ $department->users_count ?? $department->users->count() ?? 0 }} employees
                                </span>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <div class="d-flex">
                                    <a href="{{ route('admin.departments.show', $department->id) }}" 
                                       class="btn btn-sm btn-outline-primary me-2" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.departments.edit', $department->id) }}" 
                                       class="btn btn-sm btn-outline-secondary me-2"
                                       title="Edit Department">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger delete-department-btn"
                                            title="Delete Department"
                                            data-department-id="{{ $department->id }}"
                                            data-department-name="{{ $department->name }}"
                                            data-delete-url="{{ route('admin.departments.destroy', $department->id) }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center">
                                <div class="text-muted">
                                    <i class="fas fa-building fa-2x mb-3 opacity-50"></i>
                                    <h5>No departments found</h5>
                                    <p class="mb-0">Create your first department to get started.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($departments->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $departments->links('pagination::bootstrap-5') }}
        </div>
    @endif
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
                <p>Are you sure you want to delete department <strong id="departmentNameToDelete"></strong>?</p>
                <p class="text-danger small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Department</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border: none;
        border-radius: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e9ecef;
    }
    
    .badge {
        padding: 0.4em 0.75em;
        font-weight: 500;
        font-size: 0.75em;
    }
    
    .department-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .avatar-sm {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
        color: #333;
    }
    
    .btn-outline-primary:hover,
    .btn-outline-secondary:hover,
    .btn-outline-danger:hover {
        transform: translateY(-1px);
    }
    
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(0,123,255,0.05);
    }
    
    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }
    
    .modal-header {
        border-bottom: 1px solid #e9ecef;
        padding: 1.25rem 1.5rem;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 1rem 1.5rem;
    }
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handle delete button clicks
    const deleteButtons = document.querySelectorAll('.delete-department-btn');
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const departmentNameSpan = document.getElementById('departmentNameToDelete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const departmentId = this.getAttribute('data-department-id');
            const departmentName = this.getAttribute('data-department-name');
            const deleteUrl = this.getAttribute('data-delete-url');

            // Set the form action
            deleteForm.action = deleteUrl;
            
            // Set the department name in the modal
            departmentNameSpan.textContent = departmentName;

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