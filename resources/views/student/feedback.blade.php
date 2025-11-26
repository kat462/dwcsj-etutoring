@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-chat-left-text text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">My Feedback</h1>
    </div>
</div>
<div class="card card-modern mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Session</th>
                        <th class="text-center">Tutor</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($feedbacks as $feedback)
                    <tr>
                        <td>{{ $feedback->booking->subject->name ?? '-' }}</td>
                        <td class="text-center">
                            @if($feedback->tutor && $feedback->tutor->profile && $feedback->tutor->profile->profile_image)
                                <img src="{{ asset('images/profile/' . $feedback->tutor->profile->profile_image) }}" class="rounded-circle border border-2 mb-1" width="40" height="40" alt="{{ $feedback->tutor->name }} profile image"><br>
                            @else
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light border mb-1" style="width:40px;height:40px;">
                                    <i class="bi bi-person-badge text-secondary" style="font-size:1.2rem;"></i>
                                </span><br>
                            @endif
                            <span class="fw-semibold small">{{ $feedback->tutor->name ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="d-flex align-items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $feedback->rating ? '-fill text-warning' : '' }}"></i>
                                @endfor
                                <span class="ms-1 small text-muted">({{ $feedback->rating }}/5)</span>
                            </span>
                        </td>
                        <td>{{ $feedback->comment }}</td>
                        <td>{{ $feedback->created_at ? $feedback->created_at->format('M d, Y') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-chat-left-dots" style="font-size:2rem;"></i><br>
                            No feedback found. <span class="d-block mt-2">You can give feedback after completing a session.</span>
                            <div class="mt-3">
                                <a href="{{ route('student.bookings') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-journal-bookmark"></i> My Bookings</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
