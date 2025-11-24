@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="card p-4">
        <h3>Add Availability</h3>
        <form method="POST" action="{{ route('tutor.availabilities.store') }}">
            @csrf
            <div class="mb-3">
                <label>Date</label>
                <input type="date" name="date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Start Time</label>
                <input type="time" name="start_time" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>End Time</label>
                <input type="time" name="end_time" class="form-control" required>
            </div>
            <button class="btn btn-success">Save</button>
            <a href="{{ route('tutor.availabilities.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
