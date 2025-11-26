@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-list-check text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Allowed Student IDs</h1>
    </div>
</div>
<div class="card card-modern mb-4">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.allowed-student-ids.store') }}">
            @csrf
            <div class="row g-2 align-items-end">
                <div class="col-md-7">
                    <label for="student_ids" class="form-label">Student IDs (paste multiple, separated by space, comma, or newline)</label>
                    <textarea class="form-control" id="student_ids" name="student_ids" rows="2" placeholder="e.g. 2023001 2023002 2023003"></textarea>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary-custom w-100">Add</button>
                </div>
            </div>
        </form>
        <hr>
        <div class="row">
            <div class="col-md-8">
                <div class="table-responsive mt-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Student ID</th>
                                <!-- Education Level column removed -->
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->student_id }}</td>
                                <!-- Education Level value removed -->
                                <td>
                                    @if($item->deleted_at)
                                        <span class="badge bg-danger">Revoked</span>
                                    @elseif($item->used)
                                        <span class="badge bg-warning text-dark">Used</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.allowed-student-ids.edit', $item) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $item->id }}">
                                        Delete
                                    </button>
                                    <!-- Modal for delete confirmation -->
                                    <x-admin-modal :id="'deleteModal-' . $item->id" title="Confirm Delete">
                                        <x-slot name="body">
                                            Are you sure you want to delete Student ID <strong>{{ $item->student_id }}</strong>?
                                        </x-slot>
                                        <x-slot name="footer">
                                            <form action="{{ route('admin.allowed-student-ids.destroy', $item) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </x-slot>
                                    </x-admin-modal>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">No allowed student IDs found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-center justify-content-center">
                <div>
                    <h6 class="mb-3">Allowed IDs Status</h6>
                    <canvas id="allowedIdsChart" height="160"></canvas>
                </div>
            </div>
        </div>
        @push('head')
            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        @endpush
        @push('scripts')
        <script>
            // Real chart for allowed IDs status
            (function() {
                const active = {{ $items->whereNull('deleted_at')->where('used', false)->count() }};
                const used = {{ $items->whereNull('deleted_at')->where('used', true)->count() }};
                const revoked = {{ $items->whereNotNull('deleted_at')->count() }};
                const total = active + used + revoked;
                let chartData, chartColors, chartLabels;
                if (total === 0) {
                    // Empty state: faded colors and placeholder values
                    chartLabels = ['Active', 'Used', 'Revoked'];
                    chartData = [1, 1, 1];
                    chartColors = ['rgba(16,185,129,0.15)', 'rgba(251,191,36,0.15)', 'rgba(239,68,68,0.15)'];
                } else {
                    chartLabels = ['Active', 'Used', 'Revoked'];
                    chartData = [active, used, revoked];
                    chartColors = ['#10b981', '#fbbf24', '#ef4444'];
                }
                new Chart(document.getElementById('allowedIdsChart'), {
                    type: 'pie',
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
    </div>
</div>
@endsection
