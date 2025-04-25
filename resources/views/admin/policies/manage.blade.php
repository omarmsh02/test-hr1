@extends('layout.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Manage Policies</h1>
            
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>Policy Status Management</h4>
                        <a href="{{ route('admin.policies.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($policies as $policy)
                            <tr>
                                <td>{{ $policy->title }}</td>
                                <td>{{ $policy->category }}</td>
                                <td>
                                    <form action="{{ route('admin.policies.update', $policy) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="title" value="{{ $policy->title }}">
                                        <input type="hidden" name="content" value="{{ $policy->content }}">
                                        <input type="hidden" name="category" value="{{ $policy->category }}">
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="is_active" value="0">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                {{ $policy->is_active ? 'checked' : '' }}
                                                onchange="this.form.submit()">
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('admin.policies.edit', $policy) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection