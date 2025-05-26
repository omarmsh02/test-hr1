@extends('layout.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">Current Salary Information</div>
                <div class="card-body">
                    @if($currentSalary)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light h-100">
                                    <div class="card-body">
                                        <h3 class="display-4">{{ number_format($currentSalary->amount) }} {{ $currentSalary->currency }}</h3>
                                        <p class="lead">Annual Salary</p>
                                        <p class="text-muted">Effective from: {{ $currentSalary->effective_date->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light h-100">
                                    <div class="card-body">
                                        <h3 class="display-4">{{ number_format($currentSalary->amount / 12, 2) }} {{ $currentSalary->currency }}</h3>
                                        <p class="lead">Monthly Salary</p>
                                        <p class="text-muted">
                                            @if($currentSalary->notes)
                                                Note: {{ $currentSalary->notes }}
                                            @else
                                                No additional notes
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No salary information available.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">Salary History</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Currency</th>
                                    <th>Effective Date</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salaries as $salary)
                                    <tr>
                                        <td>{{ number_format($salary->amount) }}</td>
                                        <td>{{ $salary->currency }}</td>
                                        <td>{{ $salary->effective_date->format('M d, Y') }}</td>
                                        <td>{{ $salary->notes ?: 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No salary records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection