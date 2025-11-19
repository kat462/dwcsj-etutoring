@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">📬 Manage Booking Requests</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Pending Requests -->
    <div class="card p-4 shadow mb-4">
        <h4 class="mb-3">⏳ Pending Requests</h4>
        
        @if($pendingBookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Preferred Date</th>
                            <th>Notes</th>
                            <th>Requested</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingBookings as $booking)
                            <tr>
                                <td>
                                    <strong>{{ $booking->tutee->name }}</strong><br>
                                    <small class="text-muted">{{ $booking->tutee->email }}</small>
                                </td>
                                <td>{{ $booking->subject->name }}</td>
                                <td>{{ $booking->session_date ? $booking->session_date->format('M d, Y h:i A') : 'Not specified' }}</td>
                                <td>
                                    @if($booking->note)
                                        <small>{{ Str::limit($booking->note, 50) }}</small>
                                    @else
                                        <small class="text-muted">No notes</small>
                                    @endif
                                </td>
                                <td>{{ $booking->created_at->diffForHumans() }}</td>
                                <td>
                                    <form method="POST" action="{{ route('tutor.bookings.accept', $booking) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">✓ Accept</button>
                                    </form>
                                    
                                    <form method="POST" action="{{ route('tutor.bookings.decline', $booking) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Are you sure you want to decline this request?')">
                                            ✗ Decline
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">No pending booking requests at the moment.</p>
        @endif
    </div>

    <!-- All Bookings History -->
    <div class="card p-4 shadow">
        <h4 class="mb-3">📋 Booking History</h4>
        
        @if($allBookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Session Date</th>
                            <th>Status</th>
                            <th>Date Requested</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allBookings as $booking)
                            <tr>
                                <td>{{ $booking->tutee->name }}</td>
                                <td>{{ $booking->subject->name }}</td>
                                <td>{{ $booking->session_date ? $booking->session_date->format('M d, Y h:i A') : 'TBD' }}</td>
                                <td>
                                    @if($booking->status === 'accepted')
                                        <span class="badge bg-success">Accepted</span>
                                    @elseif($booking->status === 'declined')
                                        <span class="badge bg-danger">Declined</span>
                                    @elseif($booking->status === 'completed')
                                        <span class="badge bg-info">Completed</span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="badge bg-secondary">Cancelled</span>
                                    @else
                                        <span class="badge bg-warning">{{ ucfirst($booking->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $booking->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($booking->status === 'accepted')
                                        <form method="POST" action="{{ route('tutor.bookings.complete', $booking) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">Mark Complete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">No booking history yet.</p>
        @endif
    </div>
</div>
@endsection
