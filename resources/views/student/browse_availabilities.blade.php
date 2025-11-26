@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-calendar2-week text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Browse Tutor Availabilities</h1>
    </div>
</div>
<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-modern text-white h-100" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="card-body">
                <h6 class="text-white-50 mb-2">Total Availabilities</h6>
                <h2 class="mb-0">{{ $availabilities->total() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-modern text-white h-100" style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);">
            <div class="card-body">
                <h6 class="text-white-50 mb-2">Showing</h6>
                <h2 class="mb-0">{{ $availabilities->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 d-flex align-items-center justify-content-end">
        <form method="GET" class="row g-2 align-items-end w-100" action="{{ route('student.availabilities.browse') }}">
            <div class="col-md-4">
                <label for="q" class="form-label mb-1">Search by tutor or subject</label>
                <input type="text" class="form-control form-control-sm" id="q" name="q" value="{{ request('q') }}" placeholder="Tutor name or subject">
            </div>
            <div class="col-md-3">
                <label for="subject" class="form-label mb-1">Subject</label>
                <select class="form-select form-select-sm" id="subject" name="subject_id">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id')==$subject->id?'selected':'' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="date" class="form-label mb-1">Date</label>
                <input type="date" class="form-control form-control-sm" id="date" name="date" value="{{ request('date') }}">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-funnel"></i> Filter</button>
            </div>
        </form>
    </div>
</div>
<!-- Availability Cards -->
<div class="row g-3">
    @forelse($availabilities as $availability)
        <div class="col-md-4">
            <div class="card card-modern h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <img src="{{ $availability->tutor->profile && $availability->tutor->profile->profile_image ? asset('images/profile/'.$availability->tutor->profile->profile_image) : asset('images/default-profile.png') }}" class="rounded-circle me-3 border" width="48" height="48" alt="{{ $availability->tutor->name }} profile image">
                        <div>
                            <h6 class="mb-0"><a href="{{ route('tutors.show', $availability->tutor->id) }}" class="text-decoration-none text-dark">{{ $availability->tutor->name }}</a></h6>
                            <div class="small text-muted">{{ $availability->date->format('M d, Y') }} | {{ $availability->start_time }} - {{ $availability->end_time }}</div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <strong>Subject:</strong> <span class="badge bg-light text-dark border">{{ $availability->subject->name ?? '-' }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Status:</strong> <span class="badge {{ $availability->is_booked ? 'bg-danger' : 'bg-success' }}">{{ $availability->is_booked ? 'Booked' : 'Available' }}</span>
                    </div>
                    @if(!$availability->is_booked)
                        <a href="{{ route('student.request_session', ['availability_id' => $availability->id]) }}" class="btn btn-outline-primary btn-sm rounded-pill"><i class="bi bi-calendar-plus"></i> Request Session</a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted py-5">
            <i class="bi bi-calendar-x" style="font-size:2rem;"></i><br>
            No availabilities found matching your criteria.
        </div>
    @endforelse
</div>
<div class="mt-3">
    {{ $availabilities->links() }}
</div>
@endsection
