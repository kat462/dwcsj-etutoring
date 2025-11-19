@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="mb-0 d-flex align-items-center">
        <i class="bi bi-person-workspace text-success me-3" style="font-size: 2rem;"></i>
        <span>Tutor Dashboard</span>
    </h1>
    <p class="text-muted mb-0 mt-2">Welcome back, {{ auth()->user()->name }}</p>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card card-modern border-start border-primary border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Pending Requests</h6>
                        <h2 class="mb-0 text-primary">{{ auth()->user()->bookingsAsTutor()->where('status', 'pending')->count() }}</h2>
                    </div>
                    <i class="bi bi-clock-history text-primary" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-modern border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Accepted Sessions</h6>
                        <h2 class="mb-0 text-success">{{ auth()->user()->bookingsAsTutor()->where('status', 'accepted')->count() }}</h2>
                    </div>
                    <i class="bi bi-check-circle text-success" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-modern border-start border-info border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">My Subjects</h6>
                        <h2 class="mb-0 text-info">{{ auth()->user()->subjects()->count() }}</h2>
                    </div>
                    <i class="bi bi-book text-info" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-md-8">
        <div class="card card-modern">
            <div class="card-body">
                <h5 class="card-title mb-4"><i class="bi bi-calendar-event text-primary me-2"></i>Upcoming Sessions</h5>
                @php
                    $upcomingSessions = auth()->user()->bookingsAsTutor()
                        ->where('status', 'accepted')
                        ->whereNotNull('session_date')
                        ->where('session_date', '>=', now())
                        ->orderBy('session_date')
                        ->with(['tutee', 'subject'])
                        ->take(5)
                        ->get();
                @endphp
                
                @if($upcomingSessions->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($upcomingSessions as $session)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $session->subject->name }}</h6>
                                        <small class="text-muted">
                                            <i class="bi bi-person me-1"></i>{{ $session->tutee->name }} • 
                                            <i class="bi bi-calendar ms-2 me-1"></i>{{ $session->session_date->format('M d, Y h:i A') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-success">Confirmed</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-4">No upcoming sessions scheduled</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-modern">
            <div class="card-body">
                <h5 class="card-title mb-4"><i class="bi bi-link-45deg text-primary me-2"></i>Quick Links</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('tutor.profile.show') }}" class="btn btn-outline-primary">
                        <i class="bi bi-person-badge me-2"></i>My Profile
                    </a>
                    <a href="{{ route('tutor.subjects') }}" class="btn btn-outline-primary">
                        <i class="bi bi-book me-2"></i>Manage Subjects
                    </a>
                    <a href="{{ route('tutor.bookings') }}" class="btn btn-outline-primary">
                        <i class="bi bi-calendar-check me-2"></i>View Requests
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
