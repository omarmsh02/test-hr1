<!-- resources/views/employee/requests/create.blade.php -->
@extends('layout.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">New Request</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('employee.requests.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="type" class="form-label">Request Type</label>
                            <select id="type" name="type" class="form-control @error('type') is-invalid @enderror" required>
                                <option value="">Select Type</option>
                                @foreach($requestTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" rows="5" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">Please provide detailed information about your request.</small>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('employee.requests.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection