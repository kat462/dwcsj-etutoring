@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>User Details</h3>
            <p class="text-muted mb-0">Read-only view of user information.</p>
        </div>
        <div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Back to Users</a>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <div class="d-flex align-items-center gap-3">
            @if(optional($user->profile)->profile_image)
                <img src="/images/profile/{{ $user->profile->profile_image }}" alt="{{ $user->name }}" style="width:96px;height:96px;object-fit:cover;border-radius:50%;" />
            @else
                <div style="width:96px;height:96px;background:#1E40AF;color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:600;">
                    {{ $user->initials() }}
                </div>
            @endif
            <div>
                <h5 class="mb-0">{{ $user->name }} <small class="text-muted">({{ $user->student_id }})</small></h5>
                <div class="text-muted">{{ $user->education_level ? ucfirst($user->education_level) : '-' }}</div>
            </div>
        </div>
        <p class="mb-1"><strong>Education Level:</strong> {{ $user->education_level ? ucfirst($user->education_level) : '-' }}</p>
        <p class="mb-1"><strong>Current Role:</strong> {{ ucfirst($user->role) }}</p>
        <p class="mb-1"><strong>Status:</strong> {{ $user->deleted_at ? 'Trashed' : 'Active' }}</p>

        <hr>
        <h6>Contact Links</h6>
        <p class="mb-1"><strong>Email:</strong> {{ $user->email ?? '-' }}</p>
        <p class="mb-1"><strong>Facebook:</strong> @if($user->facebook_url) <a href="{{ $user->facebook_url }}" target="_blank">Profile</a> @else - @endif</p>
        <p class="mb-1"><strong>Instagram:</strong> @if($user->instagram_url) <a href="{{ $user->instagram_url }}" target="_blank">Profile</a> @else - @endif</p>
        <p class="mb-1"><strong>LinkedIn:</strong> @if($user->linkedin_url) <a href="{{ $user->linkedin_url }}" target="_blank">Profile</a> @else - @endif</p>

        <hr>
        <h6>Academic / Roles</h6>
        <p class="mb-1"><strong>Subjects:</strong>
            @if($user->subjects && $user->subjects->count())
                <ul class="mb-0">
                    @foreach($user->subjects as $s)
                        <li>{{ $s->name }}</li>
                    @endforeach
                </ul>
            @else
                -
            @endif
        </p>

        <hr>
        <h6>Activity Summary</h6>
        <p class="mb-1"><strong>Feedback given:</strong> {{ $feedbackGiven }}</p>
        <p class="mb-1"><strong>Feedback received:</strong> {{ $feedbackReceived }}</p>
        <p class="mb-1"><strong>Bookings as Tutee:</strong> {{ $bookingsAsTutee }}</p>
        <p class="mb-1"><strong>Sessions conducted as Tutor:</strong> {{ $bookingsAsTutor }}</p>
        <p class="mb-1"><strong>Total tutor availabilities:</strong> {{ $availabilities }}</p>
        <p class="mb-1"><strong>Completed sessions:</strong> {{ $completedSessions }}</p>
        <p class="mb-1"><strong>Cancelled sessions:</strong> {{ $cancelledSessions }}</p>
        @if(isset($lastActivity) && $lastActivity)
            <p class="mb-1"><strong>Last activity:</strong> {{ $lastActivity }}</p>
        @endif

        <hr>
        <h6>Review Summary</h6>
        <p class="mb-1"><strong>Average rating:</strong> {{ $avgRating ?? '-' }}</p>
        <p class="mb-1"><strong>Total reviews:</strong> {{ $totalReviews }}</p>
        <p class="mb-1"><strong>5★:</strong> {{ $count5 }} &nbsp; <strong>4★:</strong> {{ $count4 }} &nbsp; <strong>3★ and below:</strong> {{ $count3below }}</p>

        @if(isset($latestReviews) && $latestReviews->count())
            <hr>
            <h6>Latest reviews</h6>
            @foreach($latestReviews as $rev)
                <div class="mb-2">
                    <div><strong>{{ $rev->tutee ? $rev->tutee->name : 'Anonymous' }}</strong> <span class="text-muted">· {{ $rev->created_at->toDateString() }}</span></div>
                    <div class="text-warning">
                        @for($i=1;$i<=5;$i++) {!! $i <= $rev->rating ? '&#9733;' : '&#9734;' !!} @endfor
                        <span class="ms-2 text-muted">{{ $rev->rating }}/5</span>
                    </div>
                    <p class="mb-0">{{ $rev->comment }}</p>
                    <div class="mt-1">
                        @if(!$rev->trashed())
                            <form method="POST" action="{{ route('admin.feedback.destroy', $rev) }}" style="display:inline-block" onsubmit="return confirm('Delete review?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.feedback.restore', $rev->id) }}" style="display:inline-block">
                                @csrf
                                <button class="btn btn-sm btn-outline-success">Restore</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif

        <hr>
        <h6>Role Summary</h6>
        <p class="mb-1">
            @if($isTutor && $isTutee)
                <strong>Roles:</strong> Tutor, Tutee
            @elseif($isTutor)
                <strong>Roles:</strong> Tutor
            @elseif($isTutee)
                <strong>Roles:</strong> Tutee
            @else
                <strong>Roles:</strong> {{ ucfirst($user->role) }}
            @endif
        </p>

    </div>
    <div class="card-footer d-flex gap-2">
        @if(!$user->trashed())
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Soft-delete this user?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">Soft-delete</button>
            </form>
        @else
            <form method="POST" action="{{ route('admin.users.restore', $user->id) }}">
                @csrf
                <button class="btn btn-sm btn-success">Restore</button>
            </form>
        @endif
        @if($isTutor)
            <a href="{{ route('tutors.show', $user->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">View Public Profile</a>
            <a href="{{ route('tutors.reviews', $user->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">View All Reviews</a>
        @endif
    </div>
</div>

@endsection
