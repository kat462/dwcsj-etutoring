@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-calendar-plus text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Request a Tutoring Session</h1>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
        <span class="badge bg-primary"><i class="bi bi-search me-1"></i> 1. Browse Tutors</span>
        <i class="bi bi-arrow-right text-secondary"></i>
        <span class="badge bg-primary"><i class="bi bi-calendar-plus me-1"></i> 2. Request</span>
        <i class="bi bi-arrow-right text-secondary"></i>
        <span class="badge bg-primary"><i class="bi bi-hourglass-split me-1"></i> 3. Await</span>
        <i class="bi bi-arrow-right text-secondary"></i>
        <span class="badge bg-primary"><i class="bi bi-cash-coin me-1"></i> 4. Pay</span>
        <i class="bi bi-arrow-right text-secondary"></i>
        <span class="badge bg-primary"><i class="bi bi-camera-video me-1"></i> 5. Attend</span>
        <i class="bi bi-arrow-right text-secondary"></i>
        <span class="badge bg-primary"><i class="bi bi-star me-1"></i> 6. Feedback</span>
    </div>
</div>
@if(isset($myPendingBookings) && count($myPendingBookings) > 0)
<div class="mb-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom-0 pb-2">
            <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-warning"></i>My Pending Requests</h5>
        </div>
        <div class="card-body pt-3">
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Session</th>
                            <th class="text-center">Tutor</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Notes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($myPendingBookings as $booking)
                        <tr>
                            <td class="fw-semibold">{{ $booking->subject->name ?? '-' }}</td>
                            <td class="text-center">
                                @if($booking->tutor && $booking->tutor->profile && $booking->tutor->profile->profile_image)
                                    <img src="{{ asset('images/profile/' . $booking->tutor->profile->profile_image) }}" class="rounded-circle border border-2 mb-1" width="36" height="36" alt="{{ $booking->tutor->name }} profile image"><br>
                                @else
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light border mb-1" style="width:36px;height:36px;">
                                        <i class="bi bi-person-badge text-secondary" style="font-size:1.1rem;"></i>
                                    </span><br>
                                @endif
                                <span class="fw-semibold small">{{ $booking->tutor->name ?? '-' }}</span>
                            </td>
                            <td>{{ $booking->scheduled_at ? \Carbon\Carbon::parse($booking->scheduled_at)->format('M d, Y h:i A') : '-' }}</td>
                            <td>
                                <span class="badge d-flex align-items-center gap-1 bg-warning text-dark"><i class="bi bi-clock"></i> Pending</span>
                            </td>
                            <td>
                                @if($booking->is_paid)
                                    @if($booking->payment_status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                        <span class="ms-2">₱{{ number_format($booking->rate, 2) }}</span>
                                        <form method="POST" action="{{ route('payments.store') }}" class="d-inline d-flex flex-column flex-md-row align-items-start gap-2 mt-2">
                                            @csrf
                                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                            <select name="method" class="form-select form-select-sm w-auto" required style="min-width:110px;">
                                                <option value="">Select Method</option>
                                                <option value="cash">Cash</option>
                                                <option value="gcash">GCash</option>
                                                <option value="paymaya">PayMaya</option>
                                                <option value="bank">Bank Transfer</option>
                                            </select>
                                            <input type="text" name="reference" class="form-control form-control-sm w-auto" placeholder="Reference/Receipt # (if any)">
                                            <button type="submit" class="btn btn-sm btn-success">Pay Now</button>
                                        </form>
                                    @elseif($booking->payment_status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                        <span class="ms-2">₱{{ number_format($booking->rate, 2) }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($booking->payment_status) }}</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Free</span>
                                @endif
                            </td>
                            <td>
                                @php $payment = $booking->payments()->latest()->first(); @endphp
                                @if($payment && $payment->method)
                                    <span class="badge bg-info text-dark text-uppercase">{{ $payment->method }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($payment && $payment->reference)
                                    <span class="text-break">{{ $payment->reference }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $booking->notes ? Str::limit($booking->notes, 120) : '-' }}</td>
                            <td>
                                <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}" class="d-inline">@csrf
                                    <button class="btn btn-sm btn-outline-danger">Cancel</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom-0 pb-2">
        <h5 class="fw-bold mb-0"><i class="bi bi-calendar-plus text-primary me-2"></i>Book a New Session</h5>
    </div>
    <div class="card-body pt-3">
        @if($subjects->isEmpty() || $tutors->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="bi bi-emoji-frown" style="font-size:2.5rem;"></i><br>
                <span>
                    @if($subjects->isEmpty() && $tutors->isEmpty())
                        No subjects or tutors available for booking at this time.
                    @elseif($subjects->isEmpty())
                        No subjects available for booking at this time.
                    @else
                        No tutors available for booking at this time.
                    @endif
                </span>
                <div class="mt-3">
                    <a href="{{ route('student.dashboard') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
                </div>
            </div>
        @else
        <form method="POST" action="{{ route('student.bookings.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="subject_id" class="form-label">Subject</label>
                    <select id="subject_id" name="subject_id" class="form-select" required @if(isset($selectedSubject)) readonly disabled @endif>
                        <option value="">📚 Select subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" @if(isset($selectedSubject) && $selectedSubject == $subject->id) selected @endif>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="tutor_id" class="form-label">Tutor</label>
                    <select id="tutor_id" name="tutor_id" class="form-select" required onchange="showTutorPaymentInfo()" @if(isset($selectedTutor)) readonly disabled @endif>
                        <option value="">👤 Select tutor</option>
                        @foreach($tutors as $tutor)
                            <option value="{{ $tutor->id }}" data-paid="{{ $tutor->profile && $tutor->profile->is_paid ? 1 : 0 }}" data-rate="{{ $tutor->profile->rate ?? '' }}" @if(isset($selectedTutor) && $selectedTutor == $tutor->id) selected @endif>{{ $tutor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="scheduled_at" class="form-label">Date & Time</label>
                    <input type="datetime-local" id="scheduled_at" name="scheduled_at" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="notes" class="form-label">Notes <span class="text-muted small">(optional)</span></label>
                    <textarea id="notes" name="notes" class="form-control" rows="2" placeholder="Anything you want your tutor to know..."></textarea>
                </div>
                <div class="col-12">
                    <div id="tutor-payment-info" class="mb-2"></div>
                </div>
            </div>
            <div class="mt-4 d-flex flex-wrap gap-2 align-items-center">
                <button type="submit" class="btn btn-primary btn-lg px-4"><i class="bi bi-calendar-plus me-1"></i> Request Session</button>
                <a href="{{ route('student.bookings') }}" class="btn btn-outline-primary btn-lg ms-2"><i class="bi bi-journal-bookmark me-1"></i> My Bookings</a>
            </div>
            <script>
            function showTutorPaymentInfo() {
                var select = document.getElementById('tutor_id');
                var infoDiv = document.getElementById('tutor-payment-info');
                var selected = select.options[select.selectedIndex];
                var isPaid = selected.getAttribute('data-paid');
                var rate = selected.getAttribute('data-rate');
                if (isPaid === '1') {
                    infoDiv.innerHTML = '<span class="badge bg-success">Paid</span>' + (rate ? ' <span class="ms-2">Rate: <strong>₱' + parseFloat(rate).toFixed(2) + '</strong> per session</span>' : '');
                } else if (isPaid === '0') {
                    infoDiv.innerHTML = '<span class="badge bg-secondary">Free</span>';
                } else {
                    infoDiv.innerHTML = '';
                }
            }
            document.addEventListener('DOMContentLoaded', function() {
                showTutorPaymentInfo();
            });
            </script>
        </form>
        @endif
    </div>
</div>
@endsection
