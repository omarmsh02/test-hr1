@extends('layout.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Edit Holiday</h1>
            
            <form action="{{ route('admin.holidays.update', $holiday) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Holiday Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $holiday->name) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $holiday->date->format('Y-m-d')) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type" required>
                                @foreach($holidayTypes as $key => $type)
                                    <option value="{{ $key }}" {{ $holiday->type == $key ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $holiday->description) }}</textarea>
                        </div>
                    </div>
                    
                    <div class="card-footer text-end">
                        <a href="{{ route('admin.holidays.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Holiday</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection