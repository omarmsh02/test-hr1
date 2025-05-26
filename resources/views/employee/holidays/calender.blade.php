@extends('layout.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4" style="color: black;">Holidays Calendar - {{ $year }}</h2>

    <!-- Holidays Table -->
    <div class="card shadow-sm">
        <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #ddd;">
            <h5 class="mb-0" style="color: black;">Company Holidays</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="color: black;">
                            <th width="30%" style="color: black;">Holiday Name</th>
                            <th width="20%" style="color: black;">Date</th>
                            <th width="20%" style="color: black;">Day</th>
                            <th width="15%" style="color: black;">Type</th>
                            <th width="15%" style="color: black;">Status</th>
                        </tr>
                    </thead>
                    <tbody style="color: black;">
                        @forelse($holidays as $holiday)
                            <tr>
                                <td style="color: black;">{{ $holiday->name }}</td>
                                <td style="color: black;">{{ $holiday->date->format('d M Y') }}</td>
                                <td style="color: black;">{{ $holiday->date->format('l') }}</td>
                                <td style="color: black;">
                                    @if($holiday->type == 'public')
                                        Public
                                    @elseif($holiday->type == 'company')
                                        Company
                                    @else
                                        Optional
                                    @endif
                                </td>
                                <td style="color: black;">
                                    @if($holiday->date->isPast())
                                        Passed
                                    @else
                                        Upcoming
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center" style="color: black;">No holidays found for this year</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($holidays->hasPages())
        <div class="card-footer" style="color: black;">
            {{ $holidays->links() }}
        </div>
        @endif
    </div>

    <!-- Upcoming Holidays Highlight -->
    @if($holidays->where('date', '>=', now())->count() > 0)
    <div class="mt-4" style="color: black; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        <h5 style="color: black;">Next Holiday: {{ $holidays->where('date', '>=', now())->first()->name }}</h5>
        <p class="mb-0" style="color: black;">
            On {{ $holidays->where('date', '>=', now())->first()->date->format('l, F j, Y') }} 
            ({{ $holidays->where('date', '>=', now())->first()->date->diffForHumans() }})
        </p>
    </div>
    @endif
</div>
@endsection