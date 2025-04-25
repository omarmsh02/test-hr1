@extends('layout.app')

@section('content')
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
        background-color: #0d6efd !important; /* Blue */
        color: white;
    }

    .badge-company {
        background-color: #198754 !important; /* Green */
        color: white;
    }

    .badge-optional {
        background-color: #ffc107 !important; /* Yellow */
        color: white;
    }
</style>

<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">Holidays Management</h1>
        <a href="{{ route('admin.holidays.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Holiday
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Holidays Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 px-4">Holiday Name</th>
                            <th class="py-3 px-4">Date</th>
                            <th class="py-3 px-4">Type</th>
                            <th class="py-3 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($holidays as $holiday)
                        <tr>
                            <td class="py-3 px-4 align-middle">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ $holiday->name }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <small>{{ $holiday->date->format('l, F j, Y') }}</small>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                @switch($holiday->type)
                                    @case('public')
                                        <span class="badge badge-public">Public Holiday</span>
                                        @break
                                    @case('company')
                                        <span class="badge badge-company">Company Holiday</span>
                                        @break
                                    @case('optional')
                                        <span class="badge badge-optional">Optional Holiday</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <div class="d-flex">
                                    <a href="{{ route('admin.holidays.show', $holiday) }}" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.holidays.edit', $holiday) }}" class="btn btn-sm btn-outline-secondary me-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal"
                                            data-bs-form-action="{{ route('admin.holidays.destroy', $holiday) }}">
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
                Are you sure you want to delete this holiday? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Holiday</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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