

@extends('layouts.app')
@section('content')
<div class="page-header mb-4 shadow-sm">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-calendar-check text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Booking Requests</h1>
    </div>
</div>
<div class="mb-4">
    <!-- Visual Flow Guide -->
    <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 mb-3">
        <span class="badge bg-primary"><i class="bi bi-inbox me-1"></i> 1. View Requests</span>
        <i class="bi bi-arrow-right text-secondary"></i>
        <span class="badge bg-primary"><i class="bi bi-check2-square me-1"></i> 2. Accept/Decline</span>
        <i class="bi bi-arrow-right text-secondary"></i>
        <span class="badge bg-primary"><i class="bi bi-cash-coin me-1"></i> 3. Confirm Payment</span>
        <i class="bi bi-arrow-right text-secondary"></i>
        <span class="badge bg-primary"><i class="bi bi-camera-video me-1"></i> 4. Conduct Session</span>
        <i class="bi bi-arrow-right text-secondary"></i>
        <span class="badge bg-primary"><i class="bi bi-star me-1"></i> 5. Await Feedback</span>
    </div>
</div>
<div class="card card-modern mb-4 shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Session</th>
                        <th>Tutee</th>
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
                @forelse($bookings as $booking)
                    <tr>
                        <td class="fw-semibold">{{ $booking->subject->name ?? '-' }}</td>
                        <td>{{ $booking->tutee->name ?? '-' }}</td>
                        <td>{{ $booking->scheduled_at ? \Carbon\Carbon::parse($booking->scheduled_at)->format('M d, Y h:i A') : '-' }}</td>
                        <td>
                            <span class="badge @if($booking->status=='pending') bg-warning text-dark @elseif($booking->status=='accepted') bg-info text-dark @elseif($booking->status=='completed') bg-success @elseif($booking->status=='cancelled') bg-danger @else bg-secondary @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>
                            @if($booking->is_paid)
                                @if($booking->payment_status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    <span class="ms-2">₱{{ number_format($booking->rate, 2) }}</span>
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
                        <td style="max-width:180px; white-space:pre-line; word-break:break-word;">
                            {{ $booking->notes ? Str::limit($booking->notes, 120) : '-' }}
                        </td>
                        <td>
                            @if($booking->status=='pending')
                                <form method="POST" action="{{ route('tutor.bookings.accept', $booking->id) }}" class="d-inline">@csrf
                                    <button class="btn btn-success btn-md"><i class="bi bi-check2-circle me-1"></i> Accept</button>
                                </form>
                                <form method="POST" action="{{ route('tutor.bookings.decline', $booking->id) }}" class="d-inline">@csrf
                                    <button class="btn btn-outline-danger btn-md"><i class="bi bi-x-circle me-1"></i> Decline</button>
                                </form>
                            @elseif($booking->status=='accepted')
                                <form method="POST" action="{{ route('tutor.bookings.complete', $booking->id) }}" class="d-inline">@csrf
                                    <button class="btn btn-primary btn-md"><i class="bi bi-check-circle me-1"></i> Mark Complete</button>
                                </form>
                            @elseif($booking->status=='completed')
                                <span class="text-success small"><i class="bi bi-check-circle"></i> Completed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-calendar-x" style="font-size:2rem;"></i><br>
                            No booking requests at the moment. New requests from tutees will appear here.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
