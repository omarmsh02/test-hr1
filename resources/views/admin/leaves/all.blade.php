@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">All Leave Requests</h1>
                <div>
                    <a href="{{ route('admin.leaves.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-left"></i> Back to Current Leaves
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Period</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                        <tr>
                            <td>{{ $leave->id }}</td>
                            <td>{{ $leave->user->name }}</td>
                            <td>{{ ucfirst($leave->type) }}</td>
                            <td>
                                {{ $leave->start_date->format('M d, Y') }} - 
                                {{ $leave->end_date->format('M d, Y') }}
                            </td>
                            <td>{{ $leave->end_date->diffInDays($leave->start_date) + 1 }}</td>
                            <td>
                                <span class="badge badge-{{ $leave->status == 'approved' ? 'success' : ($leave->status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                            </td>
                            <td>{{ $leave->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.leaves.show', $leave->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No leave requests found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $leaves->links() }}
            </div>
        </div>
    </div>
</div>
@endsection