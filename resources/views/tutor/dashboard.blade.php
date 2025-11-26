

@extends('layouts.app')
@section('content')
<div class="page-header mb-4 shadow-sm">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-speedometer2 text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Tutor Dashboard</h1>
    </div>
</div>
<!-- Stat Cards Grid -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-3">
        <div class="card card-modern h-100 shadow-sm border-0" style="background: #fffbe6;">
            <div class="card-body d-flex flex-column align-items-start justify-content-between">
                <div class="d-flex align-items-center mb-2">
                    <span class="bg-warning bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2" style="width:2.5rem;height:2.5rem;"><i class="bi bi-inbox text-warning" style="font-size:1.5rem;"></i></span>
                    <span class="fw-semibold">Pending Bookings</span>
                </div>
                <div class="fs-3 fw-bold text-warning mb-1">0</div>
                <div class="small text-muted">Awaiting response</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="card card-modern h-100 shadow-sm border-0" style="background: #f0fdf4;">
            <div class="card-body d-flex flex-column align-items-start justify-content-between">
                <div class="d-flex align-items-center mb-2">
                    <span class="bg-success bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2" style="width:2.5rem;height:2.5rem;"><i class="bi bi-clock-history text-success" style="font-size:1.5rem;"></i></span>
                    <span class="fw-semibold">Available Slots</span>
                </div>
                <div class="fs-3 fw-bold text-success mb-1">0</div>
                <div class="small text-muted">Active availability</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="card card-modern h-100 shadow-sm border-0" style="background: #f0f9ff;">
            <div class="card-body d-flex flex-column align-items-start justify-content-between">
                <div class="d-flex align-items-center mb-2">
                    <span class="bg-info bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2" style="width:2.5rem;height:2.5rem;"><i class="bi bi-currency-dollar text-info" style="font-size:1.5rem;"></i></span>
                    <span class="fw-semibold">This Month</span>
                </div>
                <div class="fs-3 fw-bold text-info mb-1">₱0.00</div>
                <div class="small text-success">₱450.00 pending</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="card card-modern h-100 shadow-sm border-0" style="background: #f3f4f6;">
            <div class="card-body d-flex flex-column align-items-start justify-content-between">
                <div class="d-flex align-items-center mb-2">
                    <span class="bg-primary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2" style="width:2.5rem;height:2.5rem;"><i class="bi bi-calendar-event text-primary" style="font-size:1.5rem;"></i></span>
                    <span class="fw-semibold">Next Session</span>
                </div>
                <div class="fs-6 fw-bold text-muted mb-1">No upcoming</div>
            </div>
        </div>
    </div>
</div>
<!-- Main Content Cards -->
<div class="row g-3">
    <div class="col-12 col-lg-8">
        <div class="card card-modern h-100 shadow-sm border-0 mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold"><i class="bi bi-inbox me-2"></i>Pending Bookings</span>
                    <a href="{{ route('tutor.bookings') }}" class="btn btn-outline-primary btn-sm px-4 py-2 fw-semibold border-2">View All</a>
                </div>
                <div class="text-center py-4">
                    <i class="bi bi-inbox" style="font-size:2.5rem;color:#eab308;"></i>
                    <div class="mt-2 mb-2">No pending bookings</div>
                    <a href="{{ route('tutor.requests.browse') }}" class="btn btn-primary btn-sm px-4 py-2 fw-semibold border-2"><i class="bi bi-list-task"></i> View Open Requests</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card card-modern h-100 shadow-sm border-0 mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold"><i class="bi bi-clock-history me-2"></i>My Availability</span>
                    <a href="{{ route('tutor.availabilities.index') }}" class="btn btn-outline-primary btn-sm px-4 py-2 fw-semibold border-2">Manage</a>
                </div>
                <div class="alert alert-info py-2 mb-3">
                    <i class="bi bi-info-circle me-1"></i> Keep your availability updated so students can find you easily.
                </div>
                <div class="text-center">
                    <div class="mb-2">No availability slots set</div>
                    <a href="{{ route('tutor.availabilities.create') }}" class="btn btn-primary btn-sm px-4 py-2 fw-semibold border-2"><i class="bi bi-plus-circle"></i> Add Slots</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

