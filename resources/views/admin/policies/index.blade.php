@extends('layout.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Company Policies</h1>
        <a href="{{ route('admin.policies.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Policy
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($policies as $policy)
                        <tr>
                            <td class="ps-4 align-middle">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ $policy->title }}</h6>
                                        <small class="text-muted">Last updated {{ $policy->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                @if($policy->category === 'HR')
                                    <span class="badge badge-public">Human Resources</span>
                                @elseif($policy->category === 'IT')
                                    <span class="badge badge-company">Information Technology</span>
                                @else
                                    <span class="badge badge-optional">{{ $policy->category }}</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if($policy->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end pe-4 align-middle">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.policies.show', $policy) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.policies.edit', $policy) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal"
                                        data-bs-form-action="{{ route('admin.policies.destroy', $policy) }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
                Are you sure you want to delete this policy? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Policy</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-radius: 10px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;
    }

    .avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        font-size: 0.875rem;
    }

    .avatar-text {
        font-weight: 600;
        color: white;
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
    }

    body {
        background-color: #f8f9fa;
    }

    /* Custom Badge Colors */
    .badge-public {
        background-color: #0d6efd !important;
        color: white;
    }

    .badge-company {
        background-color: #198754 !important;
        color: white;
    }

    .badge-optional {
        background-color: #ffc107 !important;
        color: white;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');

        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const formAction = button.getAttribute('data-bs-form-action');
            deleteForm.action = formAction;
        });
    });
</script>
@endsection