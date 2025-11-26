
@extends('layouts.app')
@section('content')
<div class="page-header mb-4 shadow-sm">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-people text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Browse Student Requests</h1>
    </div>
</div>
<!-- Summary Cards & Filter -->
<div class="row g-3 mb-4 align-items-end">
    <div class="col-12 col-md-6 order-1 order-md-0">
        <form method="GET" class="row g-2 align-items-end w-100" action="{{ route('tutor.requests.browse') }}">
            <div class="col-md-5">
                <label for="q" class="form-label mb-1">Search by student or subject</label>
                <input type="text" class="form-control form-control-sm" id="q" name="q" value="{{ request('q') }}" placeholder="Student name or subject">
            </div>
            <div class="col-md-4">
                <label for="subject" class="form-label mb-1">Subject</label>
                <select class="form-select form-select-sm" id="subject" name="subject_id">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id')==$subject->id?'selected':'' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="date" class="form-label mb-1">Date</label>
                <input type="date" class="form-control form-control-sm" id="date" name="date" value="{{ request('date') }}">
            </div>
            <div class="col-md-3">
                <label for="payment" class="form-label mb-1">Payment</label>
                <select class="form-select form-select-sm" id="payment" name="payment">
                    <option value="">All</option>
                    <option value="paid" {{ request('payment')=='paid'?'selected':'' }}>Paid</option>
                    <option value="free" {{ request('payment')=='free'?'selected':'' }}>Free</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="min_rate" class="form-label mb-1">Minimum Rate (₱)</label>
                <input type="number" class="form-control form-control-sm mb-1" id="min_rate" name="min_rate" min="0" step="0.01" value="{{ request('min_rate') }}" placeholder="e.g. 100">
            </div>
            <div class="col-12 col-md-12 mt-2 d-grid">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-funnel"></i> Filter</button>
            </div>
        </form>
    </div>
    <div class="col-12 col-md-3 order-2 order-md-1">
        <div class="card card-modern h-100 shadow-sm border-0" style="background: #e0f2fe;">
            <div class="card-body d-flex flex-column align-items-start justify-content-between">
                <div class="d-flex align-items-center mb-2">
                    <span class="bg-info bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2" style="width:2.5rem;height:2.5rem;"><i class="bi bi-eye text-info" style="font-size:1.5rem;"></i></span>
                    <span class="fw-semibold">Showing</span>
                </div>
                <div class="fs-3 fw-bold text-info mb-1">{{ $requests->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3 order-3 order-md-2">
        <div class="card card-modern h-100 shadow-sm border-0" style="background: #fffbe6;">
            <div class="card-body d-flex flex-column align-items-start justify-content-between">
                <div class="d-flex align-items-center mb-2">
                    <span class="bg-warning bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2" style="width:2.5rem;height:2.5rem;"><i class="bi bi-inbox text-warning" style="font-size:1.5rem;"></i></span>
                    <span class="fw-semibold">Total Requests</span>
                </div>
                <div class="fs-3 fw-bold text-warning mb-1">{{ $requests->total() }}</div>
            </div>
        </div>
    </div>
</div>
<!-- Request Cards -->
<div class="row g-3">
    @forelse($requests as $request)
        <div class="col-md-4">
            <div class="card card-modern h-100 shadow-sm border-0" style="background: #f8fafc; border-radius: 1.1rem;">
                <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                    <div class="d-flex flex-column align-items-center mb-3">
                        <img src="{{ $request->student->profile && $request->student->profile->profile_image ? asset('images/profile/'.$request->student->profile->profile_image) : asset('images/default-profile.png') }}" class="rounded-circle border border-2 mb-2" width="80" height="80" alt="Profile">
                        <h6 class="mb-0 text-dark fw-bold text-center" style="font-size:1.15rem;">{{ $request->student->name }}</h6>
                        <div class="small text-muted text-center">{{ $request->date->format('M d, Y') }} | {{ $request->start_time }} - {{ $request->end_time }}</div>
                    </div>
                    <div class="mb-2">
                        <span class="fw-semibold me-1">Subject:</span> <span class="badge bg-light text-dark border px-2 py-1">{{ $request->subject->name ?? '-' }}</span>
                    </div>
                    <div class="mb-3">
                        <span class="fw-semibold me-1">Status:</span> <span class="badge px-2 py-1 {{ $request->status == 'pending' ? 'bg-warning text-dark' : ($request->status == 'accepted' ? 'bg-success' : 'bg-secondary') }}">{{ ucfirst($request->status) }}</span>
                    </div>
                    <a href="{{ route('tutor.bookings') }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill"><i class="bi bi-journal-bookmark"></i> View Booking</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted py-5">
            <i class="bi bi-calendar-x" style="font-size:2rem;"></i><br>
            No student requests found matching your criteria.
        </div>
    @endforelse
</div>
<div class="mt-3">
    {{ $requests->links() }}
</div>
@endsection
