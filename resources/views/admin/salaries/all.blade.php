@extends('layout.app')

@section('title', 'All Salary Records')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">All Salary Records</h1>
        <div>
            <a href="{{ route('admin.salaries.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Overview
            </a>
            <a href="{{ route('admin.salaries.create') }}" class="btn btn-primary ml-2">
                <i class="fas fa-plus"></i> Add New Salary
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Complete Salary History</h6>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="filterDropdown" data-toggle="dropdown">
                    Filter by Department
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.salaries.all') }}">All Departments</a>
                    @foreach($departments ?? [] as $department)
                        <a class="dropdown-item" href="{{ route('admin.salaries.all', ['department' => $department->id]) }}">
                            {{ $department->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="salaryHistoryTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Amount</th>
                            <th>Currency</th>
                            <th>Effective Date</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaries ?? [] as $salary)
                            <tr>
                                <td>{{ $salary->id }}</td>
                                <td>{{ $salary->user->name }}</td>
                                <td>{{ number_format($salary->amount, 2) }}</td>
                                <td>{{ $salary->currency }}</td>
                                <td>{{ $salary->effective_date->format('M d, Y') }}</td>
                                <td>{{ $salary->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.salaries.show', $salary->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.salaries.edit', $salary->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.salaries.destroy', $salary->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No salary records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                @if(isset($salaries) && method_exists($salaries, 'links'))
                    <div class="mt-4">
                        {{ $salaries->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#salaryHistoryTable').DataTable({
            "order": [[ 4, "desc" ]],
            "pageLength": 25
        });
    });
</script>
@endsection