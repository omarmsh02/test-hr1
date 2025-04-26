@extends('layout.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Create New Request</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.requests.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="type" class="form-label">Request Type</label>
                    <select name="type" id="type" class="form-select" required>
                        @foreach($requestTypes as $key => $type)
                            <option value="{{ $key }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="5" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Request</button>
            </form>
        </div>
    </div>
</div>
@endsection
