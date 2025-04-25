@extends('layout.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Holiday Details</h2>
                    <a href="{{ route('admin.holidays.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
                </div>
                
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <td>{{ $holiday->name }}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ $holiday->date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>
                                @switch($holiday->type)
                                    @case('public')
                                        <span class="badge bg-primary">Public Holiday</span>
                                        @break
                                    @case('company')
                                        <span class="badge bg-success">Company Holiday</span>
                                        @break
                                    @case('optional')
                                        <span class="badge bg-info">Optional Holiday</span>
                                        @break
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $holiday->description ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('admin.holidays.edit', $holiday) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('admin.holidays.destroy', $holiday) }}" method="POST">
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