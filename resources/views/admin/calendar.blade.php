@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-calendar3 text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Admin Calendar</h1>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card card-modern mb-4 shadow-sm border-0">
            <div class="card-body">
                <div id="admin-calendar"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-modern mb-4 shadow-sm border-0 h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center" style="min-height: 350px;">
                <h5 class="mb-3">Booking Status Overview</h5>
                <canvas id="calendarStatusChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

@push('head')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="/js/admin-calendar.js"></script>
    <script>
        (function() {
            const pending = {{ $statusCounts['pending'] ?? 0 }};
            const accepted = {{ $statusCounts['accepted'] ?? 0 }};
            const completed = {{ $statusCounts['completed'] ?? 0 }};
            const cancelled = {{ $statusCounts['cancelled'] ?? 0 }};
            const declined = {{ $statusCounts['declined'] ?? 0 }};
            const total = pending + accepted + completed + cancelled + declined;
            let chartData, chartColors, chartLabels;
            if (total === 0) {
                chartLabels = ['Pending', 'Accepted', 'Completed', 'Cancelled', 'Declined'];
                chartData = [1, 1, 1, 1, 1];
                chartColors = [
                    'rgba(251,191,36,0.15)', 'rgba(96,165,250,0.15)', 'rgba(16,185,129,0.15)', 'rgba(239,68,68,0.15)', 'rgba(107,114,128,0.15)'
                ];
            } else {
                chartLabels = ['Pending', 'Accepted', 'Completed', 'Cancelled', 'Declined'];
                chartData = [pending, accepted, completed, cancelled, declined];
                chartColors = [
                    '#fbbf24', '#60a5fa', '#10b981', '#ef4444', '#6b7280'
                ];
            }
            new Chart(document.getElementById('calendarStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: chartLabels,
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
