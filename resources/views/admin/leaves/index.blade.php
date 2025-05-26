@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Leave Requests Management</h1>
                <div>
                    <a href="{{ route('admin.leaves.all') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-history"></i> View All Leaves
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ $status == 'all' ? 'active' : '' }}" href="{{ route('admin.leaves.index') }}">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status == 'pending' ? 'active' : '' }}" href="{{ route('admin.leaves.index', ['status' => 'pending']) }}">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status == 'approved' ? 'active' : '' }}" href="{{ route('admin.leaves.index', ['status' => 'approved']) }}">Approved</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status == 'rejected' ? 'active' : '' }}" href="{{ route('admin.leaves.index', ['status' => 'rejected']) }}">Rejected</a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Period</th>
                            <th>Days</th>
                            <th>Reason</th>
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
                            <td>{{ Str::limit($leave->reason, 30) }}</td>
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
                                @if($leave->status == 'pending')
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-success approve-btn" data-id="{{ $leave->id }}">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger reject-btn" data-id="{{ $leave->id }}">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No leave requests found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Approve/Reject Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="statusForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Update Leave Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="status" id="modalStatus">
                    <div class="form-group">
                        <label for="comment">Comment (Optional)</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.approve-btn').click(function() {
        const leaveId = $(this).data('id');
        const formAction = "{{ route('admin.leaves.update-status', ':id') }}".replace(':id', leaveId);
        
        $('#statusForm').attr('action', formAction);
        $('#modalStatus').val('approved');
        $('#statusModalLabel').text('Approve Leave Request');
        $('#statusModal').modal('show');
    });

    $('.reject-btn').click(function() {
        const leaveId = $(this).data('id');
        const formAction = "{{ route('admin.leaves.update-status', ':id') }}".replace(':id', leaveId);
        
        $('#statusForm').attr('action', formAction);
        $('#modalStatus').val('rejected');
        $('#statusModalLabel').text('Reject Leave Request');
        $('#statusModal').modal('show');
    });
});
</script>
@endpush