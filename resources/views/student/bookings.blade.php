@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">📅 Request Tutoring Session</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Booking Request Form -->
    <div class="card p-4 shadow mb-4">
        <h4 class="mb-3">New Booking Request</h4>
        <form method="POST" action="{{ route('student.bookings.store') }}">
            @csrf
            
            <div class="mb-3">
                <label for="subject_id" class="form-label">Subject</label>
                <select name="subject_id" id="subject_id" class="form-control" required>
                    <option value="">-- Select Subject --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">
                            {{ $subject->name }} ({{ $subject->education_level }})
                        </option>
                    @endforeach
                </select>
                @error('subject_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="tutor_id" class="form-label">Tutor</label>
                <select name="tutor_id" id="tutor_id" class="form-control" required>
                    <option value="">-- Select Tutor --</option>
                    @foreach($tutors as $tutor)
                        <option value="{{ $tutor->id }}">
                            {{ $tutor->name }}
                            @if($tutor->subjects->count() > 0)
                                - Teaches: {{ $tutor->subjects->pluck('name')->join(', ') }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('tutor_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="session_date" class="form-label">Preferred Session Date & Time</label>
                <input type="datetime-local" name="session_date" id="session_date" 
                       class="form-control" required 
                       min="{{ now()->format('Y-m-d\TH:i') }}">
                @error('session_date')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="note" class="form-label">Notes (Optional)</label>
                <textarea name="note" id="note" class="form-control" rows="3" 
                          placeholder="Any specific topics or questions you'd like to cover..."></textarea>
                @error('note')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Send Booking Request</button>
        </form>
    </div>

    <!-- My Bookings -->
    <div class="card p-4 shadow">
        <h4 class="mb-3">My Booking Requests</h4>
        
        @if($myBookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tutor</th>
                            <th>Subject</th>
                            <th>Session Date</th>
                            <th>Status</th>
                            <th>Requested On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($myBookings as $booking)
                            <tr>
                                <td>{{ $booking->tutor->name }}</td>
                                <td>{{ $booking->subject->name }}</td>
                                <td>{{ $booking->session_date ? $booking->session_date->format('M d, Y h:i A') : 'TBD' }}</td>
                                <td>
                                    @if($booking->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($booking->status === 'accepted')
                                        <span class="badge bg-success">Accepted</span>
                                    @elseif($booking->status === 'declined')
                                        <span class="badge bg-danger">Declined</span>
                                    @elseif($booking->status === 'completed')
                                        <span class="badge bg-info">Completed</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $booking->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if(in_array($booking->status, ['pending','accepted']) && Auth::id() === $booking->tutee_id)
                                        <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}" onsubmit="return confirm('Cancel this booking?')">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger">Cancel</button>
                                        </form>
                                    @endif

                                    @if($booking->status === 'completed' && !$booking->feedback)
                                        <a href="{{ route('feedback.create', $booking) }}" class="btn btn-sm btn-primary mt-1">
                                            <i class="bi bi-star-fill me-1"></i>Leave Feedback
                                        </a>
                                    @elseif($booking->feedback)
                                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Rated</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">No booking requests yet. Start by requesting a session above!</p>
        @endif
    </div>
</div>
@endsection
