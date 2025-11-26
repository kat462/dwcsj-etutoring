@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-chat-left-text text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Feedback Received</h1>
    </div>
</div>
<div class="card card-modern mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tutee</th>
                        <th>Session</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($feedbacks as $feedback)
                    <tr>
                        <td>{{ $feedback->tutee->name ?? '-' }}</td>
                        <td>{{ $feedback->booking->subject->name ?? '-' }}</td>
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
                            No feedback received yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
