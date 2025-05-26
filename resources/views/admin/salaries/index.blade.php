@extends('layout.app')

@section('title', 'Employee Salaries')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Employee Salaries</h1>
        <div>
            <a href="{{ route('admin.salaries.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Salary
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Employee Salary Information</h6>
            <small class="text-muted">
                Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} 
                of {{ $employees->total() }} employees
            </small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Current Salary</th>
                            <th>Currency</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td>{{ $employee->id }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                <td>
                                    @if($employee->currentSalary)
                                        {{ number_format($employee->currentSalary->amount, 2) }}
                                    @else
                                        <span class="text-danger">Not Set</span>
                                    @endif
                                </td>
                                <td>{{ $employee->currentSalary->currency ?? 'N/A' }}</td>
                                <td>
                                    @if($employee->currentSalary)
                                        {{ $employee->currentSalary->effective_date->format('M d, Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if($employee->currentSalary)
                                            <a href="{{ route('admin.salaries.show', $employee->currentSalary->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.salaries.edit', $employee->currentSalary->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.salaries.create') }}?user_id={{ $employee->id }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-plus"></i> New Record
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No employee salary records found</h5>
                                        <p class="text-muted mb-0">Start by adding salary information for your employees.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($employees->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <small class="text-muted">
                        Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} 
                        of {{ $employees->total() }} entries
                    </small>
                </div>
                <div>
                    {{ $employees->links('pagination::bootstrap-4') }}
                </div>
            </div>
            @endif
        </div>
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

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Remove DataTable initialization since we're using Laravel pagination
        // $('#salaryTable').DataTable();
    });
</script>
@endsection