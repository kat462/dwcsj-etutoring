@extends('layouts.app')

@section('content')
<div class="page-header mb-3">
    <div class="d-flex align-items-center gap-3">
        <div>
            @if(optional($user->profile)->profile_image)
                <img src="/images/profile/{{ $user->profile->profile_image }}" alt="{{ $user->name }}" style="width:120px;height:120px;object-fit:cover;border-radius:50%;" />
            @else
                <div style="
                    width:120px;
                    height:120px;
                    background:#1E40AF;
                    color:white;
                    border-radius:50%;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:36px;
                    font-weight:600;
                    ">
                    {{ $user->initials() }}
                </div>
            @endif
        </div>
        <div>
            <h3 class="mb-0">{{ $user->name }} <small class="text-muted">({{ $user->student_id }})</small></h3>
            <div class="text-muted">{{ $user->education_level ? ucfirst($user->education_level) : '-' }}</div>
            <div class="mt-1">
                @if(isset($avgRating) && $avgRating)
                    <strong>{{ $avgRating }}/5</strong>
                    <span class="text-warning">
                        @for($i=1;$i<=5;$i++)
                            {!! $i <= round($avgRating) ? '&#9733;' : '&#9734;' !!}
                        @endfor
                    </span>
                    <span class="text-muted">({{ $reviews->count() }} reviews)</span>
                @else
                    <span class="text-muted">No ratings yet</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-body">
                <h5>About</h5>
                <p>{{ $user->profile->bio ?? 'No bio provided.' }}</p>

                <h6>Subjects</h6>
                @if($user->subjects && $user->subjects->count())
                    <div class="mb-2">
                        @foreach($user->subjects as $s)
                            <span class="badge bg-secondary me-1">{{ $s->name }}</span>
                        @endforeach
                    </div>
                @else
                    <p>-</p>
                @endif

                <h6 class="mt-3">Availability</h6>
                @if($user->availabilities && $user->availabilities->count())
                    <ul class="list-unstyled">
                        @foreach($user->availabilities as $a)
                            <li class="mb-1">{{ $a->date }} — {{ $a->start_time }} to {{ $a->end_time }} @if($a->is_booked) <em>(booked)</em> @endif</li>
                        @endforeach
                    </ul>
                @else
                    <p>No availabilities listed.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Reviews</h5>
                    <a href="{{ route('tutors.reviews', $user->id) }}" class="btn btn-sm btn-link">View all reviews</a>
                </div>
                @if($reviews && $reviews->count())
                    @foreach($reviews as $rev)
                        <div class="mb-3">
                            <div><strong>{{ $rev->tutee ? $rev->tutee->name : 'Anonymous' }}</strong> <span class="text-muted">· {{ $rev->created_at->toDateString() }}</span></div>
                            <div class="text-warning">
                                @for($i=1;$i<=5;$i++) {!! $i <= $rev->rating ? '&#9733;' : '&#9734;' !!} @endfor
                                <span class="ms-2 text-muted">{{ $rev->rating }}/5</span>
                            </div>
                            @if($rev->comment)
                                <p class="mb-0">{{ $rev->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="mb-0">No reviews yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @include('bookings.create')

        <div class="card mb-3">
            <div class="card-body">
                <h6>Contact</h6>
                <p class="mb-1"><strong>Email:</strong> {{ $user->email ?? '-' }}</p>
                @if(optional($user->profile)->facebook)
                    <a href="{{ $user->profile->facebook }}" class="btn btn-sm btn-outline-primary d-block mb-1" target="_blank">Facebook</a>
                @endif
                @if(optional($user->profile)->instagram)
                    <a href="{{ $user->profile->instagram }}" class="btn btn-sm btn-outline-danger d-block mb-1" target="_blank">Instagram</a>
                @endif
                @if(optional($user->profile)->other_link)
                    <a href="{{ $user->profile->other_link }}" class="btn btn-sm btn-outline-secondary d-block mb-1" target="_blank">Other link</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
