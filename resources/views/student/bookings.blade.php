@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-calendar-plus text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">My Bookings</h1>
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
                            <th>Date</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($myBookings as $booking)
                    <tr>
                        <td>{{ $booking->subject->name ?? '-' }}</td>
                        <td class="text-center">
                            @if($booking->tutor && $booking->tutor->profile && $booking->tutor->profile->profile_image)
                                <img src="{{ asset('images/profile/' . $booking->tutor->profile->profile_image) }}" class="rounded-circle border border-2 mb-1" width="40" height="40" alt="{{ $booking->tutor->name }} profile image"><br>
                            @else
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light border mb-1" style="width:40px;height:40px;">
                                    <i class="bi bi-person-badge text-secondary" style="font-size:1.2rem;"></i>
                                </span><br>
                            @endif
                            <span class="fw-semibold small">{{ $booking->tutor->name ?? '-' }}</span>
                        </td>
                        <td>{{ $booking->scheduled_at ? \Carbon\Carbon::parse($booking->scheduled_at)->format('M d, Y h:i A') : '-' }}</td>
                        <td>
                            @php
                                $statusIcon = [
                                    'pending' => 'clock',
                                    'accepted' => 'calendar-check',
                                    'completed' => 'check-circle',
                                    'cancelled' => 'x-circle',
                                ][$booking->status] ?? 'question-circle';
                            @endphp
                            <span class="badge d-flex align-items-center gap-1 @if($booking->status=='pending') bg-warning @elseif($booking->status=='accepted') bg-info @elseif($booking->status=='completed') bg-success @elseif($booking->status=='cancelled') bg-danger @else bg-secondary @endif">
                                <i class="bi bi-{{ $statusIcon }}"></i> {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                            <td>
                                @if($booking->is_paid)
                                    @if($booking->payment_status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
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
                        <td>
                            @if($booking->status=='pending')
                                <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}" class="d-inline">@csrf
                                    <button class="btn btn-sm btn-outline-danger">Cancel</button>
                                </form>
                            @elseif($booking->status=='completed' && !$booking->feedback)
                                <a href="{{ route('feedback.create', $booking->id) }}" class="btn btn-sm btn-outline-primary">Give Feedback</a>
                            @elseif($booking->status=='completed' && $booking->feedback)
                                <span class="text-success small"><i class="bi bi-star-fill"></i> Feedback Given</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-calendar-x" style="font-size:2rem;"></i><br>
                            No bookings yet. All your requested sessions will appear here.<br>
                            <a href="{{ route('student.request_session') }}" class="btn btn-outline-primary btn-sm mt-2"><i class="bi bi-calendar-plus"></i> Request a Session</a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
