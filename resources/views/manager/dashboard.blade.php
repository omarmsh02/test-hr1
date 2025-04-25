@extends('layout.app')

@section('content')
<div class="container">
    <h1>Manager Dashboard</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Department Employees</h5>
                    <p class="card-text">{{ $stats['department_employees'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pending Leaves</h5>
                    <p class="card-text">{{ $stats['pending_leaves'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Today's Attendance</h5>
                    <p class="card-text">{{ $stats['today_attendance'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection