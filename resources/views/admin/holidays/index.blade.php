@extends('layout.app')

@section('content')
<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
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

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-lg mt-4">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark font-weight-medium">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>Holidays Management
                        </h5>
                        <a href="{{ route('admin.holidays.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                            <i class="fas fa-plus me-1"></i> New Holiday
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

                    <!-- Holidays Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Holiday Name</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($holidays as $holiday)
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle-sm me-3 bg-light-primary">
                                                    <span class="initials">{{ strtoupper(substr($holiday->name, 0, 1)) }}</span>
                                                </div>
                                                <span>{{ $holiday->name }}</span>
                                            </div>
                                        </td>
                                        <td class="align-middle">{{ $holiday->date->format('l, F j, Y') }}</td>
                                        <td class="align-middle">
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
                                        <td class="text-end align-middle">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.holidays.show', $holiday->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.holidays.edit', $holiday->id) }}" class="btn btn-sm btn-outline-warning mx-1" title="Edit">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger delete-holiday-btn"
                                                        title="Delete"
                                                        data-holiday-id="{{ $holiday->id }}"
                                                        data-holiday-name="{{ $holiday->name }}"
                                                        data-delete-url="{{ route('admin.holidays.destroy', $holiday->id) }}">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-calendar-alt fa-2x text-muted mb-3"></i>
                                            <h5 class="text-muted">No holidays found</h5>
                                            <p class="mb-0">Create your first holiday to get started.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($holidays->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted small">
                                Showing <strong>{{ $holidays->firstItem() }}</strong> to 
                                <strong>{{ $holidays->lastItem() }}</strong> of 
                                <strong>{{ $holidays->total() }}</strong> holidays
                            </div>
                            <div>
                                {{ $holidays->links() }}
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
                <p>Are you sure you want to delete holiday <strong id="holidayNameToDelete"></strong>?</p>
                <p class="text-danger small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Holiday</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handle delete button clicks
    const deleteButtons = document.querySelectorAll('.delete-holiday-btn');
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const holidayNameSpan = document.getElementById('holidayNameToDelete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const holidayId = this.getAttribute('data-holiday-id');
            const holidayName = this.getAttribute('data-holiday-name');
            const deleteUrl = this.getAttribute('data-delete-url');

            // Set the form action
            deleteForm.action = deleteUrl;
            
            // Set the holiday name in the modal
            holidayNameSpan.textContent = holidayName;

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