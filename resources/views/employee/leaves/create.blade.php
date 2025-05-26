<!-- employee/leaves/create.blade.php -->
@extends('layout.app')

@section('title', 'Request Leave')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Request Leave</h4>
                    <a href="{{ route('employee.leaves.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Leaves
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h5><i class="fas fa-info-circle"></i> Available Leave Balance</h5>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="border rounded p-3 bg-primary text-white text-center">
                                            <h6>Annual Leave</h6>
                                            <h2>{{ $leaveBalance['annual'] }} days</h2>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border rounded p-3 bg-success text-white text-center">
                                            <h6>Sick Leave</h6>
                                            <h2>{{ $leaveBalance['sick'] }} days</h2>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border rounded p-3 bg-info text-white text-center">
                                            <h6>Personal Leave</h6>
                                            <h2>{{ $leaveBalance['personal'] }} days</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('employee.leaves.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Leave Type <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="">Select Leave Type</option>
                                        @foreach($leaveTypes as $value => $label)
                                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="leaveTypeHelp" class="form-text mt-2"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-light border" id="leaveSummary" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p><strong>Duration:</strong> <span id="leaveDuration">0</span> working day(s)</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Balance After Leave:</strong> <span id="remainingBalance">0</span> day(s)</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Status:</strong> <span id="leaveStatus" class="badge bg-success">Valid</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Leave <span class="text-danger">*</span></label>
                            <textarea name="reason" id="reason" rows="4" class="form-control @error('reason') is-invalid @enderror" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label d-block">Acknowledgments</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="acknowledgment" required>
                                        <label class="form-check-label" for="acknowledgment">
                                            I understand that this leave request will be subject to manager approval, and I agree to the company leave policy.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" id="submitButton">
                                <i class="fas fa-paper-plane"></i> Submit Leave Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const leaveTypeHelp = document.getElementById('leaveTypeHelp');
        const leaveSummary = document.getElementById('leaveSummary');
        const leaveDuration = document.getElementById('leaveDuration');
        const remainingBalance = document.getElementById('remainingBalance');
        const leaveStatus = document.getElementById('leaveStatus');
        const acknowledgmentCheckbox = document.getElementById('acknowledgment');
        const submitButton = document.getElementById('submitButton');
        
        // Leave balance from PHP
        const leaveBalance = {
            annual: {{ $leaveBalance['annual'] }},
            sick: {{ $leaveBalance['sick'] }},
            personal: {{ $leaveBalance['personal'] }},
            unpaid: 0,
            other: 0
        };
        
        // Function to update leave type help text
        function updateLeaveTypeHelp() {
            const selectedType = typeSelect.value;
            
            if (selectedType === 'annual') {
                leaveTypeHelp.innerHTML = '<i class="fas fa-info-circle"></i> Annual leave is for planned vacation time or personal days off.';
            } else if (selectedType === 'sick') {
                leaveTypeHelp.innerHTML = '<i class="fas fa-info-circle"></i> Sick leave is for illness or medical appointments.';
            } else if (selectedType === 'personal') {
                leaveTypeHelp.innerHTML = '<i class="fas fa-info-circle"></i> Personal leave is for handling personal matters.';
            } else if (selectedType === 'unpaid') {
                leaveTypeHelp.innerHTML = '<i class="fas fa-info-circle"></i> Unpaid leave is for when you have exhausted your paid leave balance.';
            } else if (selectedType === 'other') {
                leaveTypeHelp.innerHTML = '<i class="fas fa-info-circle"></i> Other leave types require specific approval from management.';
            } else {
                leaveTypeHelp.innerHTML = '';
            }
            
            calculateLeaveDuration();
        }
        
        // Function to calculate business days between two dates (excluding weekends)
        function calculateBusinessDays(startDate, endDate) {
            let days = 0;
            let currentDate = new Date(startDate);
            const end = new Date(endDate);
            
            while (currentDate <= end) {
                const dayOfWeek = currentDate.getDay();
                if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                    days++;
                }
                currentDate.setDate(currentDate.getDate() + 1);
            }
            
            return days;
        }
        
        // Function to calculate leave duration and update summary
        function calculateLeaveDuration() {
            const selectedType = typeSelect.value;
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            
            if (selectedType && startDate && endDate) {
                const businessDays = calculateBusinessDays(startDate, endDate);
                const currentBalance = leaveBalance[selectedType] || 0;
                const remainingDays = currentBalance - businessDays;
                
                leaveDuration.textContent = businessDays;
                remainingBalance.textContent = remainingDays;
                
                if (selectedType === 'unpaid' || selectedType === 'other' || remainingDays >= 0) {
                    leaveStatus.textContent = 'Valid';
                    leaveStatus.className = 'badge bg-success';
                    submitButton.disabled = !acknowledgmentCheckbox.checked;
                } else {
                    leaveStatus.textContent = 'Insufficient Balance';
                    leaveStatus.className = 'badge bg-danger';
                    submitButton.disabled = true;
                }
                
                leaveSummary.style.display = 'block';
            } else {
                leaveSummary.style.display = 'none';
            }
        }
        
        // Enable submit button when acknowledgment is checked
        acknowledgmentCheckbox.addEventListener('change', function() {
            if (typeSelect.value && startDateInput.value && endDateInput.value) {
                if (typeSelect.value === 'unpaid' || typeSelect.value === 'other') {
                    submitButton.disabled = !this.checked;
                } else {
                    const businessDays = calculateBusinessDays(startDateInput.value, endDateInput.value);
                    const currentBalance = leaveBalance[typeSelect.value] || 0;
                    const remainingDays = currentBalance - businessDays;
                    
                    submitButton.disabled = !this.checked || remainingDays < 0;
                }
            } else {
                submitButton.disabled = true;
            }
        });
        
        // Event listeners
        typeSelect.addEventListener('change', updateLeaveTypeHelp);
        
        startDateInput.addEventListener('change', function() {
            if (endDateInput.value && new Date(endDateInput.value) < new Date(this.value)) {
                endDateInput.value = this.value;
            }
            endDateInput.min = this.value;
            calculateLeaveDuration();
        });
        
        endDateInput.addEventListener('change', calculateLeaveDuration);
    });
</script>
@endsection