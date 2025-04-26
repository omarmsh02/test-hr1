@extends('layout.app')

@section('title', 'Salary Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Salary Details</h1>
        <div>
            <a href="{{ route('admin.salaries.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <a href="{{ route('admin.salaries.edit', $salary->id) }}" class="btn btn-primary ml-2">
                <i class="fas fa-edit"></i> Edit Record
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Salary Information</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 30%">Employee Name</th>
                                <td>{{ $salary->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Employee ID</th>
                                <td>{{ $salary->user_id }}</td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td>{{ $salary->user->department->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Position</th>
                                <td>{{ $salary->user->position ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Salary Amount</th>
                                <td>{{ number_format($salary->amount, 2) }} {{ $salary->currency }}</td>
                            </tr>
                            <tr>
                                <th>Effective Date</th>
                                <td>{{ $salary->effective_date->format('F d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Record Created</th>
                                <td>{{ $salary->created_at->format('F d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated</th>
                                <td>{{ $salary->updated_at->format('F d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Notes</th>
                                <td>{{ $salary->notes ?: 'No notes available' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('admin.salaries.edit', $salary->id) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-edit fa-fw"></i> Edit Salary Record
                        </a>
                    </div>
                    
                    <div class="mb-3">
                        <a href="{{ route('admin.users.show', $salary->user_id) }}" class="btn btn-info btn-block">
                            <i class="fas fa-user fa-fw"></i> View Employee Profile
                        </a>
                    </div>
                    
                    <div class="mb-3">
                        <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#generatePayslipModal">
                            <i class="fas fa-file-invoice-dollar fa-fw"></i> Generate Payslip
                        </button>
                    </div>
                    
                    <form action="{{ route('admin.salaries.destroy', $salary->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this salary record? This action cannot be undone.')">
                            <i class="fas fa-trash fa-fw"></i> Delete Record
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Salary History</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse($salary->user->salaries->sortByDesc('effective_date')->take(5) as $historySalary)
                            <a href="{{ route('admin.salaries.show', $historySalary->id) }}" 
                               class="list-group-item list-group-item-action {{ $historySalary->id == $salary->id ? 'active' : '' }}">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ number_format($historySalary->amount, 2) }} {{ $historySalary->currency }}</h6>
                                    <small>{{ $historySalary->effective_date->format('M d, Y') }}</small>
                                </div>
                                <small>{{ $historySalary->notes ? Str::limit($historySalary->notes, 50) : 'No notes' }}</small>
                            </a>
                        @empty
                            <div class="list-group-item">No salary history found</div>
                        @endforelse
                    </div>
                    
                    @if($salary->user->salaries->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.salaries.all', ['user_id' => $salary->user_id]) }}" class="btn btn-sm btn-outline-primary">
                                View All History
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Payslip Modal -->
<div class="modal fade" id="generatePayslipModal" tabindex="-1" role="dialog" aria-labelledby="generatePayslipModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generatePayslipModalLabel">Generate Payslip</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.salaries.generate-payslips') }}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $salary->user_id }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="month">Month</label>
                        <select class="form-control" id="month" name="month">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year">Year</label>
                        <select class="form-control" id="year" name="year">
                            @for($i = date('Y'); $i >= date('Y') - 3; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection