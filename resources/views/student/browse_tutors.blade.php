@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-search text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Browse Tutors</h1>
    </div>
</div>

<form method="GET" class="mb-4" action="{{ route('student.tutors.browse') }}">
    <div class="row g-1 align-items-end flex-wrap">
        <div class="col-12 col-sm-5 col-md-5 col-lg-4">
            <label for="q" class="form-label mb-1">Search by name or subject</label>
            <input type="text" class="form-control form-control-sm" id="q" name="q" value="{{ request('q') }}" placeholder="Tutor name or subject">
        </div>
        <div class="col-8 col-sm-4 col-md-4 col-lg-3">
            <label for="subject" class="form-label mb-1">Subject</label>
            <select class="form-select form-select-sm" id="subject" name="subject_id">
                <option value="">All Subjects</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id')==$subject->id?'selected':'' }}>{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-4 col-sm-3 col-md-3 col-lg-2 d-flex align-items-end justify-content-end gap-1">
            <button type="button" class="btn btn-link p-0 align-self-stretch" id="toggleFilters" title="Show/hide more filters" style="font-size:1.1rem; line-height:1; width:2.5rem; height:32px; min-height:32px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-sliders" style="font-size:1.1rem;"></i>
            </button>
            <button type="submit" class="btn btn-primary btn-sm align-self-stretch" style="height:32px; min-height:32px;"><i class="bi bi-funnel"></i> Filter</button>
        </div>
    </div>
    <div class="mt-2" id="advancedFilters" style="display:none;">
        <div class="row g-1">
            <div class="col-6 col-md-4 col-lg-3">
                <label for="education" class="form-label mb-1">Education Level</label>
                @php use App\Helpers\EducationLevel; @endphp
                <select class="form-select form-select-sm" id="education" name="education_level">
                    <option value="">All Levels</option>
                    @foreach(EducationLevel::options() as $key => $label)
                        <option value="{{ $key }}" {{ request('education_level')==$key?'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <label for="payment" class="form-label mb-1">Payment</label>
                <select class="form-select form-select-sm" id="payment" name="payment">
                    <option value="">All</option>
                    <option value="paid" {{ request('payment')=='paid'?'selected':'' }}>Paid</option>
                    <option value="free" {{ request('payment')=='free'?'selected':'' }}>Free</option>
                </select>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <label for="min_rate" class="form-label mb-1">Minimum Rate (₱)</label>
                <input type="number" class="form-control form-control-sm mb-1" id="min_rate" name="min_rate" min="0" step="0.01" value="{{ request('min_rate') }}" placeholder="e.g. 100">
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <label for="min_hour" class="form-label mb-1">Hour per Session</label>
                <input type="number" class="form-control form-control-sm mb-1" id="min_hour" name="min_hour" min="0" step="0.1" value="{{ request('min_hour') }}" placeholder="e.g. 1">
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var toggleBtn = document.getElementById('toggleFilters');
        var advFilters = document.getElementById('advancedFilters');
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            advFilters.style.display = advFilters.style.display === 'none' ? 'block' : 'none';
        });
    });
    </script>
</form>
<!-- Tutor Cards -->
<div class="row g-3 mt-2">
    @forelse($tutors as $tutor)
        <div class="col-md-4">
            <div class="card card-modern h-100 shadow-sm border-0" style="background: #f8fafc; border-radius: 1.1rem;">
                <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                    <div class="d-flex flex-column align-items-center mb-3">
                        <img src="{{ $tutor->profile && $tutor->profile->profile_image ? asset('images/profile/'.$tutor->profile->profile_image) : asset('images/default-profile.png') }}" class="rounded-circle border border-2 mb-2" style="width:80px;height:80px;object-fit:cover;aspect-ratio:1/1;" alt="{{ $tutor->name }} profile image">
                        <h5 class="mb-0 text-dark fw-bold text-center" style="font-size:1.15rem;">
                            <a href="{{ route('tutors.show', $tutor->id) }}" class="text-decoration-none text-dark">{{ $tutor->name }}</a>
                        </h5>
                        <div class="small text-muted text-center">
                            @if($tutor->profile && $tutor->profile->education_level)
                                {{ EducationLevel::label($tutor->profile->education_level) }}
                            @endif
                        </div>
                    </div>
                    <div class="mb-2 text-center">
                        <span class="fw-semibold me-1">Subjects:</span>
                        <span class="small">
                            @forelse($tutor->subjects as $subject)
                                <span class="badge bg-light text-dark border">{{ $subject->name }}</span>
                            @empty
                                <span class="text-muted">No subjects listed</span>
                            @endforelse
                        </span>
                    </div>
                    <div class="mb-3 text-center">
                        <span class="fw-semibold me-1">Bio:</span>
                        <span class="small text-muted">{{ Str::limit($tutor->profile->bio ?? 'No bio provided.', 80) }}</span>
                    </div>
                    <div class="mb-2 text-center">
                        <span class="fw-semibold me-1">Rate:</span>
                        <span class="badge bg-info text-white" style="background-color:#0ea5e9;">₱{{ number_format($tutor->profile->rate ?? 0, 2) }}</span>
                    </div>
                    <div class="mb-2 text-center">
                        <span class="fw-semibold me-1">Hours:</span>
                        <span class="badge bg-primary text-white">{{ $tutor->profile->hours ?? 0 }}</span>
                        <span class="fw-semibold ms-3">Sessions:</span>
                        <span class="badge bg-success text-white">{{ $tutor->profile->sessions ?? 0 }}</span>
                    </div>
                    <a href="{{ route('tutors.show', $tutor->id) }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill" aria-label="View {{ $tutor->name }} profile"><i class="bi bi-person-lines-fill"></i> View Profile</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted py-5">
            <i class="bi bi-people" style="font-size:2rem;"></i><br>
            No tutors found matching your criteria.
        </div>
    @endforelse
</div>
<div class="mt-3">
    {{ $tutors->links() }}
</div>
@endsection
