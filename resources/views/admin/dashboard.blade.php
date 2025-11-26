@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-shield-check text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Admin Dashboard</h1>
    </div>
    <div class="text-muted">Welcome back, <span class="fw-semibold">{{ auth()->user()->name }}</span></div>
</div>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-modern h-100 shadow-sm border-0 position-relative">
            <div class="card-body">
                <div class="position-absolute top-0 end-0 p-2">
                    <span class="bg-primary bg-opacity-10 rounded-circle p-2"><i class="bi bi-people text-primary" style="font-size:1.5rem;"></i></span>
                </div>
                <h6 class="text-muted mb-1">Total Users</h6>
                <h2 class="fw-bold mb-0">{{ \App\Models\User::count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-modern h-100 shadow-sm border-0 position-relative">
            <div class="card-body">
                <div class="position-absolute top-0 end-0 p-2">
                    <span class="bg-success bg-opacity-10 rounded-circle p-2"><i class="bi bi-person-workspace text-success" style="font-size:1.5rem;"></i></span>
                </div>
                <h6 class="text-muted mb-1">Tutors</h6>
                <h2 class="fw-bold mb-0">{{ \App\Models\User::where('role', 'tutor')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-modern h-100 shadow-sm border-0 position-relative">
            <div class="card-body">
                <div class="position-absolute top-0 end-0 p-2">
                    <span class="bg-warning bg-opacity-10 rounded-circle p-2"><i class="bi bi-mortarboard text-warning" style="font-size:1.5rem;"></i></span>
                </div>
                <h6 class="text-muted mb-1">Students</h6>
                <h2 class="fw-bold mb-0">{{ \App\Models\User::where('role', 'tutee')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-modern h-100 shadow-sm border-0 position-relative">
            <div class="card-body">
                <div class="position-absolute top-0 end-0 p-2">
                    <span class="bg-info bg-opacity-10 rounded-circle p-2"><i class="bi bi-book text-info" style="font-size:1.5rem;"></i></span>
                </div>
                <h6 class="text-muted mb-1">Subjects</h6>
                <h2 class="fw-bold mb-0">{{ \App\Models\Subject::count() }}</h2>
            </div>
        </div>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-8 d-flex flex-column gap-3">
        <div class="card card-modern h-100">
            <div class="card-body">
                <h5 class="mb-3"><i class="bi bi-cash-coin text-success me-2"></i>Payment Analytics</h5>
                <canvas id="paymentChart" height="120"></canvas>
            </div>
        </div>
        <div class="card card-modern flex-fill d-flex flex-column">
            <div class="card-body d-flex flex-column flex-grow-1">
                <h5 class="mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Recent Activity</h5>
                <div class="flex-grow-1 d-flex align-items-center">
                    <p class="text-muted mb-0">System overview and statistics will appear here.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 d-flex flex-column gap-3">
        <div class="card card-modern">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title mb-3"><i class="bi bi-gear text-primary me-2"></i>Quick Actions</h5>
                <div class="d-grid gap-2 mt-auto">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary"><i class="bi bi-person-plus me-2"></i>Add User</a>
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-primary"><i class="bi bi-book-half me-2"></i>Manage Subjects</a>
                    <a href="{{ route('admin.analytics') }}" class="btn btn-outline-primary"><i class="bi bi-file-text me-2"></i>View Reports</a>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-success"><i class="bi bi-cash-coin me-2"></i>Payment Records</a>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('paymentChart').getContext('2d');
        var paymentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($monthlyPaymentsData['labels']),
                datasets: [{
                    label: 'Total Payments (₱)',
                    data: @json($monthlyPaymentsData['data']),
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 2,
                    borderRadius: 6,
                    maxBarThickness: 32
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush


@include('components.admin-modal')
@endsection
