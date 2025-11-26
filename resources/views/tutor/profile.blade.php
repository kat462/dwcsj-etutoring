

@extends('layouts.app')

@section('content')

@section('content')

@section('content')

@section('content')

<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-person-badge text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">My Profile</h1>
    </div>
</div>
<div class="bg-light py-4" style="min-height: 100vh;">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card border border-2 rounded-3 mb-4" style="background: #fff;">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row align-items-center mb-4 gap-3">
                            <div class="flex-shrink-0">
                                @if($profile && $profile->profile_image)
                                    <img src="{{ asset('images/profile/' . $profile->profile_image) }}" class="rounded-circle border border-2" style="width:100px;height:100px;object-fit:cover;">
                                @else
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light border border-2" style="width:100px;height:100px;">
                                        <i class="bi bi-person-badge text-secondary" style="font-size:2.5rem;"></i>
                                    </span>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h2 class="fw-bold mb-1 text-dark">{{ Auth::user()->name }}</h2>
                                <div class="text-muted mb-2">{{ Auth::user()->email }}</div>
                                <div class="mb-2">
                                    @if($profile && $profile->facebook)
                                        <a href="{{ $profile->facebook }}" class="me-2 text-decoration-none" target="_blank"><i class="bi bi-facebook"></i> Facebook</a>
                                    @endif
                                    @if($profile && $profile->instagram)
                                        <a href="{{ $profile->instagram }}" class="me-2 text-decoration-none" target="_blank"><i class="bi bi-instagram"></i> Instagram</a>
                                    @endif
                                    @if($profile && $profile->other_link)
                                        <a href="{{ $profile->other_link }}" class="me-2 text-decoration-none" target="_blank"><i class="bi bi-link-45deg"></i> Other</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <strong>Bio:</strong>
                            <div class="text-muted">{{ $profile->bio ?? 'No bio provided.' }}</div>
                        </div>
                        <div class="mb-3">
                            <strong>Education Level:</strong>
                            <span class="text-muted">
                                @if($profile && $profile->education_level)
                                    {{ App\Helpers\EducationLevel::label($profile->education_level) }}
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        <div class="mb-3">
                            <strong>Session Rate:</strong>
                            <span class="text-muted">
                                @if($profile && $profile->rate)
                                    ₱{{ number_format($profile->rate, 2) }}
                                @else
                                    Free
                                @endif
                            </span>
                        </div>
                        <div class="mb-3">
                            <strong>Facebook:</strong>
                            <span class="text-muted">
                                @if($profile && $profile->facebook)
                                    <a href="{{ $profile->facebook }}" target="_blank">{{ $profile->facebook }}</a>
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        <div class="mb-3">
                            <strong>Instagram:</strong>
                            <span class="text-muted">
                                @if($profile && $profile->instagram)
                                    <a href="{{ $profile->instagram }}" target="_blank">{{ $profile->instagram }}</a>
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        <div class="mb-3">
                            <strong>Other Link:</strong>
                            <span class="text-muted">
                                @if($profile && $profile->other_link)
                                    <a href="{{ $profile->other_link }}" target="_blank">{{ $profile->other_link }}</a>
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        <a href="{{ route('tutor.profile.edit') }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
