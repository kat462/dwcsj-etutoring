@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-cash-coin text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Payment Records</h1>
    </div>
    <div class="text-muted">All payment transactions in the system.</div>
</div>
<div class="card card-modern mb-4 shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Booking</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th>Optional</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>{{ $payment->user->name ?? '-' }}</td>
                        <td>{{ $payment->booking_id }}</td>
                        <td>₱{{ number_format($payment->amount, 2) }}</td>
                        <td><span class="badge @if($payment->status=='paid') bg-success @elseif($payment->status=='pending') bg-warning @else bg-danger @endif">{{ ucfirst($payment->status) }}</span></td>
                        <td>{{ $payment->method ?? '-' }}</td>
                        <td>{{ $payment->reference ?? '-' }}</td>
                        <td>{{ $payment->is_optional ? 'Yes' : 'No' }}</td>
                        <td>{{ $payment->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
