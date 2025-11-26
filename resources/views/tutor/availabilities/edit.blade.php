@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h2 class="fw-bold mb-4">Edit Availability</h2>
                    <form method="POST" action="{{ route('tutor.availabilities.update', $availability->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $availability->date ? $availability->date->format('Y-m-d') : null) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time', $availability->start_time) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time', $availability->end_time) }}" required>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Changes</button>
                            <a href="{{ route('tutor.availabilities.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
