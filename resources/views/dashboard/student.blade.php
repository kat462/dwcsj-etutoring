@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="mb-0 d-flex align-items-center">
        <i class="bi bi-mortarboard text-primary me-3" style="font-size: 2rem;"></i>
        <span>Student Dashboard</span>
    </h1>
    <p class="text-muted mb-0 mt-2">Welcome back, {{ auth()->user()->name }}</p>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card card-modern border-start border-warning border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Pending Requests</h6>
                        <h2 class="mb-0 text-warning">{{ auth()->user()->bookingsAsTutee()->where('status', 'pending')->count() }}</h2>
                    </div>
                    <i class="bi bi-hourglass-split text-warning" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-modern border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Confirmed Sessions</h6>
                        <h2 class="mb-0 text-success">{{ auth()->user()->bookingsAsTutee()->where('status', 'accepted')->count() }}</h2>
                    </div>
                    <i class="bi bi-check2-circle text-success" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-modern border-start border-info border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Completed</h6>
                        <h2 class="mb-0 text-info">{{ auth()->user()->bookingsAsTutee()->where('status', 'completed')->count() }}</h2>
                    </div>
                    <i class="bi bi-trophy text-info" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-md-8">
        <div class="card card-modern">
            <div class="card-body">
                <h5 class="card-title mb-4"><i class="bi bi-calendar-event text-primary me-2"></i>My Upcoming Sessions</h5>
                @php
                    $upcomingSessions = auth()->user()->bookingsAsTutee()
                        ->where('status', 'accepted')
                        ->whereNotNull('session_date')
                        ->where('session_date', '>=', now())
                        ->orderBy('session_date')
                        ->with(['tutor', 'subject'])
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
                                            <i class="bi bi-person me-1"></i>Tutor: {{ $session->tutor->name }} • 
                                            <i class="bi bi-calendar ms-2 me-1"></i>{{ $session->session_date->format('M d, Y h:i A') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-success">Confirmed</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">No upcoming sessions scheduled</p>
                        <a href="{{ route('student.bookings') }}" class="btn btn-primary-custom">
                            <i class="bi bi-plus-circle me-2"></i>Request a Session
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-modern bg-gradient" style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);">
            <div class="card-body text-white">
                <h5 class="card-title mb-4"><i class="bi bi-star me-2"></i>Need Help?</h5>
                <p class="mb-4">Book a tutoring session with our qualified peer tutors!</p>
                <a href="{{ route('student.bookings') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-calendar-plus me-2"></i>Request Session
                </a>
            </div>
        </div>
        
        <div class="card card-modern mt-3">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-book text-primary me-2"></i>Available Subjects</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(\App\Models\Subject::take(6)->get() as $subject)
                        <span class="badge badge-custom bg-light text-dark">{{ $subject->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
