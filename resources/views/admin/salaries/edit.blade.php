@extends('layout.app')

@section('title', 'Edit Salary Record')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Salary Record</h1>
        <div>
            <a href="{{ route('admin.salaries.show', $salary->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Details
            </a>
            <a href="{{ route('admin.salaries.index') }}" class="btn btn-outline-secondary ml-2">
                <i class="fas fa-list"></i> All Salaries
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Salary Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.salaries.update', $salary->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label>Employee</label>
                            <input type="text" class="form-control" value="{{ $salary->user->name }}" readonly>
                            <small class="text-muted">Employee cannot be changed. Create a new salary record if needed.</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Salary Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                        id="amount" name="amount" step="0.01" min="0" 
                                        value="{{ old('amount', $salary->amount) }}" required>
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
                                            <option value="{{ $currency }}" 
                                                {{ old('currency', $salary->currency) == $currency ? 'selected' : '' }}>
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
                                id="effective_date" name="effective_date" 
                                value="{{ old('effective_date', $salary->effective_date->format('Y-m-d')) }}" required>
                            @error('effective_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $salary->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Enter any relevant information about this salary record</small>
                        </div>
                        
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Salary Record
                            </button>
                            <a href="{{ route('admin.salaries.show', $salary->id) }}" class="btn btn-secondary ml-<a href="{{ route('admin.salaries.show', $salary->id) }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Employee Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img class="img-profile rounded-circle mb-3" src="{{ asset('img/undraw_profile.svg') }}" width="100">
                        <h5>{{ $salary->user->name }}</h5>
                        <div class="text-muted">{{ $salary->user->position ?? 'No Position' }}</div>
                        <div class="badge badge-primary">{{ $salary->user->department->name ?? 'No Department' }}</div>
                    </div>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="mb-1">Joined</div>
                            <div class="font-weight-bold">{{ $salary->user->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="col-6">
                            <div class="mb-1">Status</div>
                            <div class="font-weight-bold">
                                <span class="badge badge-success">Active</span>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <a href="{{ route('admin.users.show', $salary->user_id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-user"></i> View Profile
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Record Information</h6>
                </div>
                <div class="card-body">
                    <div class="small mb-2">
                        <strong>Record ID:</strong> {{ $salary->id }}
                    </div>
                    <div class="small mb-2">
                        <strong>Created:</strong> {{ $salary->created_at->format('M d, Y h:i A') }}
                    </div>
                    <div class="small mb-2">
                        <strong>Last Updated:</strong> {{ $salary->updated_at->format('M d, Y h:i A') }}
                    </div>
                    <div class="small mb-2">
                        <strong>Created By:</strong> Administrator
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <form action="{{ route('admin.salaries.destroy', $salary->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this salary record? This cannot be undone.')">
                                <i class="fas fa-trash"></i> Delete Record
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Form confirmation before leaving
        const form = $('form');
        let originalFormData = form.serialize();
        
        $(window).on('beforeunload', function() {
            if (form.serialize() !== originalFormData) {
                return "You have unsaved changes. Are you sure you want to leave?";
            }
        });
        
        // Disable warning when form is submitted
        form.on('submit', function() {
            $(window).off('beforeunload');
        });
    });
</script>
@endsection