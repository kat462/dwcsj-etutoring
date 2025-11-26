@extends('layouts.app')
@section('content')
<div class="page-header mb-4 shadow-sm">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-calendar-plus text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Add Availability</h1>
    </div>
</div>
<div class="card card-modern mb-4 shadow-sm border-0">
    <div class="card-body">
        <form method="POST" action="{{ route('tutor.availabilities.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="col-md-4">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input type="time" class="form-control" id="start_time" name="start_time" required>
                </div>
                <div class="col-md-4">
                    <label for="end_time" class="form-label">End Time</label>
                    <input type="time" class="form-control" id="end_time" name="end_time" required>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Availability</button>
                <a href="{{ route('tutor.availabilities.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
