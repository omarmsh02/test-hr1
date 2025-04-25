@extends('layout.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Policy Details</h2>
                    <a href="{{ route('admin.policies.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
                </div>
                
                <div class="card-body">
                    <h3>{{ $policy->title }}</h3>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="badge bg-primary">{{ $policy->category }}</span>
                        <span class="badge {{ $policy->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $policy->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div class="policy-content bg-light p-4 rounded">
                        {!! nl2br(e($policy->content)) !!}
                    </div>
                </div>
                
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('admin.policies.edit', $policy) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('admin.policies.destroy', $policy) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection