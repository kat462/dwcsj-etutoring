@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-person-lines-fill text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Tutor Profile</h1>
    </div>
</div>
<div class="card card-modern mb-4 shadow-sm border-0">
    <div class="card-body position-relative p-4">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary position-absolute top-0 end-0 mt-3 me-3" style="z-index:2;"><i class="bi bi-arrow-left"></i> Back</a>
        <div class="d-flex flex-column flex-md-row align-items-center mb-4 gap-4 pb-3 border-bottom">
            <div class="flex-shrink-0">
                @if($user->profile && $user->profile->profile_image)
                    <img src="{{ asset('images/profile/' . $user->profile->profile_image) }}" class="rounded-circle border border-2" style="width:100px;height:100px;object-fit:cover;">
                @else
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light border border-2" style="width:100px;height:100px;">
                        <i class="bi bi-person-badge text-secondary" style="font-size:2.5rem;"></i>
                    </span>
                @endif
            </div>
            <div class="flex-grow-1">
                <h2 class="fw-bold mb-1 text-dark">{{ $user->name }}</h2>
                <div class="text-muted mb-2">{{ $user->email }}</div>
                <div class="mb-2">
                    @if($user->profile && $user->profile->facebook)
                        <a href="{{ $user->profile->facebook }}" class="me-2 text-decoration-none" target="_blank"><i class="bi bi-facebook"></i> Facebook</a>
                    @endif
                    @if($user->profile && $user->profile->instagram)
                        <a href="{{ $user->profile->instagram }}" class="me-2 text-decoration-none" target="_blank"><i class="bi bi-instagram"></i> Instagram</a>
                    @endif
                    @if($user->profile && $user->profile->other_link)
                        <a href="{{ $user->profile->other_link }}" class="me-2 text-decoration-none" target="_blank"><i class="bi bi-link-45deg"></i> Other</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="mb-4 mt-4 pb-2 border-bottom">
            <strong>Bio:</strong>
            <div class="text-muted">{{ $user->profile->bio ?? 'No bio provided.' }}</div>
        </div>
        <div class="mb-4 pb-2 border-bottom">
            <strong>Education Level:</strong>
            <span class="text-muted">
                @if($user->profile && $user->profile->education_level)
                    {{ App\Helpers\EducationLevel::label($user->profile->education_level) }}
                @else
                    N/A
                @endif
            </span>
        </div>
        <div class="mb-4 pb-2 border-bottom">
            <strong>Session Rate:</strong>
            <span class="text-muted">
                @if($user->profile && $user->profile->rate)
                    ₱{{ number_format($user->profile->rate, 2) }}
                @else
                    Free
                @endif
            </span>
        </div>
        <div class="mb-4 pb-2 border-bottom">
            <strong>Subjects:</strong>
            <span class="text-muted">
                @if($user->subjects && $user->subjects->count())
                    @foreach($user->subjects as $subject)
                        <span class="badge bg-light border text-dark me-1 mb-1">{{ $subject->name }}</span>
                    @endforeach
                @else
                    N/A
                @endif
            </span>
        </div>
        <div class="mb-4 pb-2 border-bottom">
            <strong>Facebook:</strong>
            <span class="text-muted">
                @if($user->profile && $user->profile->facebook)
                    <a href="{{ $user->profile->facebook }}" target="_blank">{{ $user->profile->facebook }}</a>
                @else
                    N/A
                @endif
            </span>
        </div>
        <div class="mb-4 pb-2 border-bottom">
            <strong>Instagram:</strong>
            <span class="text-muted">
                @if($user->profile && $user->profile->instagram)
                    <a href="{{ $user->profile->instagram }}" target="_blank">{{ $user->profile->instagram }}</a>
                @else
                    N/A
                @endif
            </span>
        </div>
        <div class="mb-3">
            <strong>Other Link:</strong>
            <span class="text-muted">
                @if($user->profile && $user->profile->other_link)
                    <a href="{{ $user->profile->other_link }}" target="_blank">{{ $user->profile->other_link }}</a>
                @else
                    N/A
                @endif
            </span>
        </div>
        <div class="d-flex flex-wrap gap-2 mt-4 justify-content-end">
            <a href="{{ route('student.request_session', ['tutor_id' => $user->id]) }}" class="btn btn-primary px-4"><i class="bi bi-calendar-plus"></i> Request Session</a>
        </div>
    </div>
</div>
@endsection
