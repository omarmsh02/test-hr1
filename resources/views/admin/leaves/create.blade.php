@extends('layout.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Create Leave Request</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">New Leave Request</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.leaves.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="user_id">Employee</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">Select Employee</option>
                        @foreach(User::where('role', 'employee')->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="type">Leave Type</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="">Select Leave Type</option>
                        <option value="annual">Annual Leave</option>
                        <option value="sick">Sick Leave</option>
                        <option value="personal">Personal Leave</option>
                        <option value="unpaid">Unpaid Leave</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="reason">Reason</label>
                    <textarea name="reason" id="reason" rows="3" class="form-control" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('admin.leaves.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection