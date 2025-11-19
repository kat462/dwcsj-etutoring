@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h3>Incoming Booking Requests</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($bookings->count())
        <table class="table">
            <thead><tr><th>Tutee</th><th>Scheduled</th><th>Status</th><th>Notes</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($bookings as $b)
                    <tr>
                        <td>{{ $b->tutee ? $b->tutee->name : 'Anonymous' }}</td>
                        <td>{{ optional($b->scheduled_at)->toDayDateTimeString() }}</td>
                        <td>{{ ucfirst($b->status) }}</td>
                        <td>{{ $b->notes }}</td>
                        <td>
                            @if($b->status === 'pending')
                                <form method="POST" action="{{ route('tutor.requests.accept', $b->id) }}" style="display:inline">@csrf<button class="btn btn-sm btn-success">Accept</button></form>
                                <form method="POST" action="{{ route('tutor.requests.decline', $b->id) }}" style="display:inline">@csrf<button class="btn btn-sm btn-danger">Decline</button></form>
                            @elseif($b->status === 'accepted')
                                <form method="POST" action="{{ route('tutor.requests.complete', $b->id) }}" style="display:inline">@csrf<button class="btn btn-sm btn-primary">Mark Completed</button></form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No booking requests yet.</p>
    @endif
</div>
@endsection
