@extends('layouts.app')

@section('content')
<div class="page-header mb-3">
    <h3>Reviews for {{ $user->name }} <small class="text-muted">({{ $user->student_id }})</small></h3>
    <p class="text-muted">All reviews from students. Filter by rating.</p>
</div>

<div class="mb-3">
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label class="mb-0">Filter:</label>
        <select name="rating" onchange="this.form.submit()" class="form-select w-auto">
            <option value="all" {{ ($rating ?? 'all') === 'all' ? 'selected' : '' }}>All ratings</option>
            <option value="5" {{ ($rating ?? '') === '5' ? 'selected' : '' }}>5 ★</option>
            <option value="4" {{ ($rating ?? '') === '4' ? 'selected' : '' }}>4 ★</option>
            <option value="3below" {{ ($rating ?? '') === '3below' ? 'selected' : '' }}>3 ★ and below</option>
        </select>
        <a href="{{ route('tutors.show', $user->id) }}" class="btn btn-sm btn-link ms-3">Back to profile</a>
    </form>
</div>

<div class="card">
    <div class="card-body">
        @forelse($reviews as $rev)
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
            <hr />
        @empty
            <p>No reviews found.</p>
        @endforelse

        <div class="mt-3">
            {{ $reviews->links() }}
        </div>
    </div>
</div>

@endsection
