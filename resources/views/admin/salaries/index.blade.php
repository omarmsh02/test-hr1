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
            <a href="{{ route('admin.salaries.generate-payslips') }}" class="btn btn-success ml-2">
                <i class="fas fa-file-invoice-dollar"></i> Generate Payslips
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Employee Salary Information</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="salaryTable" width="100%" cellspacing="0">
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
                                <td colspan="7" class="text-center">No employee salary records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#salaryTable').DataTable();
    });
</script>
@endsection