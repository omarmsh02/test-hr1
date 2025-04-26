@extends('layout.app')

@section('title', 'Create Salary Record')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Salary Record</h1>
        <a href="{{ route('admin.salaries.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Salaries
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Salary Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.salaries.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="user_id">Employee <span class="text-danger">*</span></label>
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">-- Select Employee --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" 
                                        {{ old('user_id', request('user_id')) == $employee->id ? 'selected' : '' }}
                                        data-current-salary="{{ $employee->currentSalary ? $employee->currentSalary->amount : '' }}"
                                        data-currency="{{ $employee->currentSalary ? $employee->currentSalary->currency : '' }}">
                                        {{ $employee->name }} ({{ $employee->department->name ?? 'No Department' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Salary Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                        id="amount" name="amount" step="0.01" min="0" value="{{ old('amount') }}" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency">Currency <span class="text-danger">*</span></label>
                                    <select class="form-control @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency }}" {{ old('currency') == $currency ? 'selected' : '' }}>
                                                {{ $currency }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="effective_date">Effective Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('effective_date') is-invalid @enderror" 
                                id="effective_date" name="effective_date" value="{{ old('effective_date', date('Y-m-d')) }}" required>
                            @error('effective_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Enter any relevant information about this salary change (e.g., promotion, annual increment, etc.)</small>
                        </div>
                        
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Salary Record
                            </button>
                            <button type="reset" class="btn btn-secondary ml-2">
                                <i class="fas fa-undo"></i> Reset Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4" id="currentSalaryCard" style="display: none;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Current Salary Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4 id="employeeName">Employee Name</h4>
                        <div id="currentSalaryAmount" class="h3 mb-0 font-weight-bold text-gray-800">0.00</div>
                        <div id="currentSalaryDate" class="text-xs font-weight-bold text-success">Effective from: N/A</div>
                    </div>
                    
                    <div class="text-center">
                        <button type="button" id="copyCurrentSalary" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-copy"></i> Use Current Values
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Information</h6>
                </div>
                <div class="card-body">
                    <p>Creating a new salary record will:</p>
                    <ul>
                        <li>Set a new salary for the selected employee</li>
                        <li>Keep the previous salary records for historical tracking</li>
                        <li>Use the effective date to determine when the new salary takes effect</li>
                    </ul>
                    <p class="mb-0 text-danger">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Make sure all information is correct before submitting.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Show current salary information when an employee is selected
        $('#user_id').change(function() {
            const selectedOption = $(this).find('option:selected');
            const currentSalary = selectedOption.data('current-salary');
            const currency = selectedOption.data('currency');
            const employeeName = selectedOption.text();
            
            if (currentSalary) {
                $('#employeeName').text(employeeName);
                $('#currentSalaryAmount').text(parseFloat(currentSalary).toLocaleString() + ' ' + currency);
                $('#currentSalaryCard').show();
            } else {
                $('#currentSalaryCard').hide();
            }
        });
        
        // Trigger change event if an employee is already selected (e.g., from a query parameter)
        if ($('#user_id').val()) {
            $('#user_id').trigger('change');
        }
        
        // Copy current salary values to the form
        $('#copyCurrentSalary').click(function() {
            const selectedOption = $('#user_id').find('option:selected');
            const currentSalary = selectedOption.data('current-salary');
            const currency = selectedOption.data('currency');
            
            $('#amount').val(currentSalary);
            $('#currency').val(currency);
        });
    });
</script>
@endsection