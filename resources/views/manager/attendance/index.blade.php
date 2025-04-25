@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Attendance</h1>
    <form action="{{ route('attendance.check-in') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">Check In</button>
    </form>
    <form action="{{ route('attendance.check-out') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Check Out</button>
    </form>
</div>
@endsection