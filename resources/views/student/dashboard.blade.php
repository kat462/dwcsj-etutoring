@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-mortarboard text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Tutee Dashboard</h1>
    </div>
    <div class="text-muted">Welcome back, <span class="fw-semibold">{{ auth()->user()->name }}</span></div>
</div>
<div class="row g-3 mb-4">
    <div class="col-12 mb-2">
        <div class="d-flex gap-2">
            <a href="{{ route('student.request_session') }}" class="btn btn-primary"><i class="bi bi-calendar-plus"></i> Request Session</a>
            <a href="{{ route('student.bookings') }}" class="btn btn-outline-primary"><i class="bi bi-journal-bookmark"></i> My Bookings</a>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-modern h-100 shadow-sm border-0 position-relative">
            <div class="card-body">
                <div class="position-absolute top-0 end-0 p-2">
                    <span class="bg-primary bg-opacity-10 rounded-circle p-2"><i class="bi bi-calendar-check text-primary" style="font-size:1.5rem;"></i></span>
                </div>
                <h6 class="text-muted mb-1">Total Sessions</h6>
                <h2 class="fw-bold mb-0">{{ $upcomingCount + $completedCount ?? 0 }}</h2>
                <div class="small text-success mt-1">{{ $completedCount ?? 0 }} completed</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-modern h-100 shadow-sm border-0 position-relative">
            <div class="card-body">
                <div class="position-absolute top-0 end-0 p-2">
                    <span class="bg-success bg-opacity-10 rounded-circle p-2"><i class="bi bi-hourglass-split text-success" style="font-size:1.5rem;"></i></span>
                </div>
                <h6 class="text-muted mb-1">Hours Learned</h6>
                <h2 class="fw-bold mb-0">{{ $hoursLearned ?? 0 }}</h2>
                <div class="small text-muted mt-1">Total study time</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-modern h-100 shadow-sm border-0 position-relative">
            <div class="card-body">
                <div class="position-absolute top-0 end-0 p-2">
                    <span class="bg-info bg-opacity-10 rounded-circle p-2"><i class="bi bi-cash-coin text-info" style="font-size:1.5rem;"></i></span>
                </div>
                <h6 class="text-muted mb-1">Money Spent</h6>
                <h2 class="fw-bold mb-0">₱{{ number_format($moneySpent ?? 0, 2) }}</h2>
                <div class="small text-warning mt-1"><i class="bi bi-hourglass text-warning"></i> ₱{{ number_format($moneyPending ?? 0, 2) }} pending</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-modern h-100 shadow-sm border-0 position-relative">
            <div class="card-body">
                <div class="position-absolute top-0 end-0 p-2">
                    <span class="bg-pink bg-opacity-10 rounded-circle p-2"><i class="bi bi-book text-pink" style="font-size:1.5rem;"></i></span>
                </div>
                <h6 class="text-muted mb-1">Favorite Subject</h6>
                <h2 class="fw-bold mb-0">{{ $favoriteSubject->name ?? '-' }}</h2>
                <div class="small text-muted mt-1">{{ $favoriteSubject->hours ?? 0 }}hrs · {{ $favoriteSubject->sessions ?? 0 }} sessions</div>
            </div>
        </div>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card card-modern h-100">
            <div class="card-body">
                <h5 class="mb-3">Sessions per Month</h5>
                <canvas id="sessionsChart" height="180"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-modern h-100">
            <div class="card-body">
                <h5 class="mb-3">Session Status Breakdown</h5>
                <canvas id="statusChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card card-modern mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Recent Activity</h5>
        @php
            $hasActivity = ($pendingBookings->count() + $scheduledBookings->count() + $completedBookings->count()) > 0;
        @endphp
        @if(!$hasActivity)
            <div class="text-center text-muted py-4">
                <i class="bi bi-emoji-neutral" style="font-size:2rem;"></i><br>
                <span>No recent activity yet. Book a session to get started!</span>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Session</th>
                            <th>Tutor</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingBookings as $booking)
                            <tr>
                                <td>{{ $booking->subject->name ?? '-' }}</td>
                                <td class="text-center">
                                    @if($booking->tutor && $booking->tutor->profile && $booking->tutor->profile->profile_image)
                                        <img src="{{ asset('images/profile/' . $booking->tutor->profile->profile_image) }}" class="rounded-circle border border-2 mb-1" width="32" height="32" alt="{{ $booking->tutor->name }} profile image"><br>
                                    @else
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light border mb-1" style="width:32px;height:32px;">
                                            <i class="bi bi-person-badge text-secondary" style="font-size:1rem;"></i>
                                        </span><br>
                                    @endif
                                    <span class="fw-semibold small">{{ $booking->tutor->name ?? '-' }}</span>
                                </td>
                                <td>{{ $booking->scheduled_at ? \Carbon\Carbon::parse($booking->scheduled_at)->format('M d, Y h:i A') : '-' }}</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                            </tr>
                        @endforeach
                        @foreach($scheduledBookings as $booking)
                            <tr>
                                <td>{{ $booking->subject->name ?? '-' }}</td>
                                <td class="text-center">
                                    @if($booking->tutor && $booking->tutor->profile && $booking->tutor->profile->profile_image)
                                        <img src="{{ asset('images/profile/' . $booking->tutor->profile->profile_image) }}" class="rounded-circle border border-2 mb-1" width="32" height="32" alt="{{ $booking->tutor->name }} profile image"><br>
                                    @else
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light border mb-1" style="width:32px;height:32px;">
                                            <i class="bi bi-person-badge text-secondary" style="font-size:1rem;"></i>
                                        </span><br>
                                    @endif
                                    <span class="fw-semibold small">{{ $booking->tutor->name ?? '-' }}</span>
                                </td>
                                <td>{{ $booking->scheduled_at ? \Carbon\Carbon::parse($booking->scheduled_at)->format('M d, Y h:i A') : '-' }}</td>
                                <td><span class="badge bg-info">Scheduled</span></td>
                            </tr>
                        @endforeach
                        @foreach($completedBookings as $booking)
                            <tr>
                                <td>{{ $booking->subject->name ?? '-' }}</td>
                                <td class="text-center">
                                    @if($booking->tutor && $booking->tutor->profile && $booking->tutor->profile->profile_image)
                                        <img src="{{ asset('images/profile/' . $booking->tutor->profile->profile_image) }}" class="rounded-circle border border-2 mb-1" width="32" height="32" alt="{{ $booking->tutor->name }} profile image"><br>
                                    @else
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light border mb-1" style="width:32px;height:32px;">
                                            <i class="bi bi-person-badge text-secondary" style="font-size:1rem;"></i>
                                        </span><br>
                                    @endif
                                    <span class="fw-semibold small">{{ $booking->tutor->name ?? '-' }}</span>
                                </td>
                                <td>{{ $booking->scheduled_at ? \Carbon\Carbon::parse($booking->scheduled_at)->format('M d, Y h:i A') : '-' }}</td>
                                <td><span class="badge bg-success">Completed</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush
@push('scripts')
<script>
// Sessions per Month (Line Chart)
(function() {
    const labels = @json($monthlyData['labels']);
    const data = @json($monthlyData['data']);
    const total = data.reduce((a, b) => a + b, 0);
    let chartData, chartColor;
    if (total === 0) {
        chartData = [1];
        chartColor = 'rgba(37,99,235,0.15)';
    } else {
        chartData = data;
        chartColor = 'rgba(37,99,235,0.7)';
    }
    new Chart(document.getElementById('sessionsChart'), {
        type: 'line',
        data: {
            labels: labels.length && total !== 0 ? labels : ['No Data'],
            datasets: [{
                label: 'Sessions',
                data: chartData,
                backgroundColor: chartColor,
                borderColor: chartColor,
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {display: false},
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (total === 0) return 'No data';
                            return context.dataset.label + ': ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {beginAtZero: true, ticks: {stepSize: 1}}
            }
        }
    });
})();

// Session Status Breakdown (Doughnut Chart)
(function() {
    const data = @json($statusData['data']);
    const total = data.reduce((a, b) => a + b, 0);
    let chartData, chartColors;
    if (total === 0) {
        chartData = [1, 1, 1, 1];
        chartColors = [
            'rgba(251,191,36,0.15)',
            'rgba(59,130,246,0.15)',
            'rgba(34,197,94,0.15)',
            'rgba(239,68,68,0.15)'
        ];
    } else {
        chartData = data;
        chartColors = @json($statusData['colors']);
    }
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: @json($statusData['labels']),
            datasets: [{
                data: chartData,
                backgroundColor: chartColors,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {position: 'bottom'},
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (total === 0) return 'No data';
                            return context.label + ': ' + context.parsed;
                        }
                    }
                }
            }
        }
    });
})();
</script>
@endpush
@endsection
