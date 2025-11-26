@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-graph-up text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Analytics</h1>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card card-modern h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-3">Bookings per Month</h5>
                <canvas id="bookingsChart" height="180"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-modern h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-3">User Roles Distribution</h5>
                <canvas id="rolesChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card card-modern h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-3">Subjects Offered</h5>
                <canvas id="subjectsChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>
@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush
@push('scripts')
    <script>
        // Real data from backend
        // Bookings per Month (Line Chart)
        (function() {
            const labels = @json($monthlySessionsData['labels']);
            const data = @json($monthlySessionsData['data']);
            const total = data.reduce((a, b) => a + b, 0);
            let chartData, chartColor;
            if (total === 0) {
                chartData = [1];
                chartColor = 'rgba(22,163,74,0.15)';
            } else {
                chartData = data;
                chartColor = 'rgba(22,163,74,0.7)';
            }
            new Chart(document.getElementById('bookingsChart'), {
                type: 'line',
                data: {
                    labels: labels.length && total !== 0 ? labels : ['No Data'],
                    datasets: [{
                        label: 'Bookings',
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

        // User Roles Distribution (Doughnut Chart)
        (function() {
            const data = @json($rolesData['data']);
            const total = data.reduce((a, b) => a + b, 0);
            let chartData, chartColors;
            if (total === 0) {
                chartData = [1, 1, 1];
                chartColors = ['rgba(239,68,68,0.15)', 'rgba(37,99,235,0.15)', 'rgba(245,158,11,0.15)'];
            } else {
                chartData = data;
                chartColors = ['#ef4444', '#2563eb', '#f59e0b'];
            }
            new Chart(document.getElementById('rolesChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($rolesData['labels']),
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

        // Subjects Offered (Bar Chart)
        (function() {
            const data = @json($subjectsData['data']);
            const total = data.reduce((a, b) => a + b, 0);
            let chartData, chartColors;
            if (total === 0) {
                chartData = [1, 1, 1, 1, 1];
                chartColors = [
                    'rgba(37,99,235,0.15)',
                    'rgba(16,185,129,0.15)',
                    'rgba(245,158,11,0.15)',
                    'rgba(139,92,246,0.15)',
                    'rgba(239,68,68,0.15)'
                ];
            } else {
                chartData = data;
                chartColors = [
                    'rgba(37,99,235,0.7)',
                    'rgba(16,185,129,0.7)',
                    'rgba(245,158,11,0.7)',
                    'rgba(139,92,246,0.7)',
                    'rgba(239,68,68,0.7)'
                ];
            }
            new Chart(document.getElementById('subjectsChart'), {
                type: 'bar',
                data: {
                    labels: @json($subjectsData['labels']),
                    datasets: [{
                        label: 'Subjects',
                        data: chartData,
                        backgroundColor: chartColors,
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
    </script>
@endpush
@endsection
