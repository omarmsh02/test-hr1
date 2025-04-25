@extends('layout.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Department Details</h1>
        <div>
            <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar avatar-lg me-4">
                            <span class="avatar-text bg-primary rounded-circle">
                                {{ substr($department->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h2 class="mb-0">{{ $department->name }}</h2>
                            <p class="text-muted mb-0">{{ $department->location ?? 'No location specified' }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="mb-3">Description</h5>
                        <p class="text-muted">{{ $department->description ?? 'No description provided' }}</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h5 class="mb-2">Manager</h5>
                            @if($department->manager)
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <span class="avatar-text bg-success rounded-circle">
                                            {{ substr($department->manager->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="mb-0">{{ $department->manager->name }}</p>
                                        <small class="text-muted">Manager</small>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">No manager assigned</p>
                            @endif
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <h5 class="mb-2">Budget</h5>
                            <p class="{{ $department->budget ? 'fw-bold' : 'text-muted' }}">
                                {{ $department->budget ? '$' . number_format($department->budget) : 'No budget set' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-bold">Department Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Total Employees</h6>
                        <h4 class="fw-bold">0</h4>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Active Projects</h6>
                        <h4 class="fw-bold">0</h4>
                    </div>
                    <div>
                        <h6 class="text-muted mb-2">Created On</h6>
                        <p class="mb-0">{{ $department->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-bold">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">No recent activity</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-text {
        font-weight: 600;
        color: white;
    }
    
    .avatar-sm {
        width: 36px;
        height: 36px;
        font-size: 0.875rem;
    }
    
    .avatar-lg {
        width: 64px;
        height: 64px;
        font-size: 1.5rem;
    }
    
    .card {
        border: none;
        border-radius: 10px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
</style>
@endsection