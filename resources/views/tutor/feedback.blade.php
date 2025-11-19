@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Student Feedback</h2>

    <div class="mb-4 p-3 border rounded bg-light">
        <h4>Average Rating: 
            @if($averageRating)
                <span class="text-warning">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="bi {{ $i <= round($averageRating) ? 'bi-star-fill text-warning' : 'bi-star text-secondary' }}"></i>
                    @endfor
                    {{ $averageRating }} / 5
                </span>
            @else
                <span class="text-muted">No Ratings Yet</span>
            @endif
        </h4>

        @if($feedbacks->count() > 0)
            <div class="mt-3">
                @foreach($ratingCounts as $stars => $count)
                    <div class="d-flex align-items-center mb-1">
                        <span style="width: 50px;">{{ $stars }}★</span>
                        <div class="progress flex-grow-1" style="height: 10px;">
                            <div class="progress-bar bg-warning" role="progressbar" 
                                 style="width: {{ $feedbacks->count() ? ($count / $feedbacks->count()) * 100 : 0 }}%;">
                            </div>
                        </div>
                        <span class="ms-2 text-muted">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <h5 class="mt-5 mb-3">All Feedback</h5>
    @if($feedbacks->count() > 0)
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Student</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($feedbacks as $feedback)
                    <tr>
                        <td>{{ $feedback->booking?->tutee?->name }}</td>
                        <td>
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $feedback->rating ? 'bi-star-fill text-warning' : 'bi-star text-secondary' }}"></i>
                            @endfor
                        </td>
                        <td>{{ $feedback->comment }}</td>
                        <td>{{ $feedback->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No feedback yet.</p>
    @endif
</div>
@endsection
