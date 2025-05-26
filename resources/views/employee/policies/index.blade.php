<!-- resources/views/employee/policies/index.blade.php -->
@extends('layout.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Company Policies</div>

                <div class="card-body">
                    <div class="mb-3">
                        <form method="GET" action="{{ route('employee.policies.index') }}" class="d-flex">
                            <select name="category" class="form-select me-2">
                                <option value="all" {{ $category == 'all' ? 'selected' : '' }}>All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>
                                        @if($cat == 'HR')
                                            Human Resources
                                        @elseif($cat == 'IT')
                                            Information Technology
                                        @elseif($cat == 'Finance')
                                            Finance
                                        @elseif($cat == 'Safety')
                                            Health & Safety
                                        @else
                                            {{ $cat }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                    </div>

                    <div class="accordion" id="policiesAccordion">
                        @forelse($policies as $policy)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $policy->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $policy->id }}" aria-expanded="false" aria-controls="collapse{{ $policy->id }}">
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <span>{{ $policy->title }}</span>
                                            <span class="badge bg-secondary">{{ $policy->category }}</span>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{ $policy->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $policy->id }}" data-bs-parent="#policiesAccordion">
                                    <div class="accordion-body">
                                        <div class="mb-3">
                                            <small class="text-muted">Last updated: {{ $policy->updated_at->format('M d, Y') }}</small>
                                        </div>
                                        <div>
                                            {!! $policy->content !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                No policies found for the selected category.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection