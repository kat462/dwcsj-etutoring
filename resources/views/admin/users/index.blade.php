@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-people text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">All Users</h1>
    </div>
</div>
<div class="card card-modern mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3 align-items-end" action="{{ route('admin.users.index') }}">
            <div class="col-md-4">
                <label for="q" class="form-label mb-1">Search</label>
                <input type="text" class="form-control form-control-sm" id="q" name="q" value="{{ request('q') }}" placeholder="Name or Student ID">
            </div>
            <div class="col-md-3">
                <label for="role" class="form-label mb-1">Role</label>
                <select class="form-select form-select-sm" id="role" name="role">
                    <option value="both" {{ request('role','both')=='both'?'selected':'' }}>All Roles</option>
                    <option value="tutor" {{ request('role')=='tutor'?'selected':'' }}>Tutor</option>
                    <option value="tutee" {{ request('role')=='tutee'?'selected':'' }}>Tutee</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="education" class="form-label mb-1">Education Level</label>
                @php use App\Helpers\EducationLevel; @endphp
                <select class="form-select form-select-sm" id="education" name="education_level">
                    <option value="" {{ request('education_level')==''?'selected':'' }}>All Levels</option>
                    @foreach(EducationLevel::options() as $key => $label)
                        <option value="{{ $key }}" {{ request('education_level')==$key?'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
            </div>
        </form>
        <div class="row">
            <div class="col-md-9">
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Active Users</h5>
                    <form method="POST" id="bulk-user-action-form">
                        @csrf
                        <div class="d-flex mb-2 gap-2">
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal"><i class="bi bi-trash"></i> Bulk Delete</button>
                        </div>
                        <div class="table-responsive mb-2">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th><input type="checkbox" id="select-all-users"></th>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($users->whereNull('deleted_at') as $u)
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="{{ $u->id }}"></td>
                                        <td>{{ $u->student_id ?? '-' }}</td>
                                        <td>{{ $u->name }}</td>
                                        <td>
                                            <span class="badge @if($u->role=='admin') bg-danger-subtle text-danger @elseif($u->role=='tutor') bg-primary-subtle text-primary @else bg-warning-subtle text-warning @endif" style="font-weight:500;">
                                                {{ ucfirst($u->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($u->is_active)
                                                <span class="badge bg-success-subtle text-success">Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal-{{ $u->id }}"><i class="bi bi-trash"></i> Delete</button>
                                            <!-- Modal for single user delete -->
                                            <x-admin-modal :id="'deleteUserModal-' . $u->id" title="Delete User">
                                                <x-slot name="body">
                                                    Are you sure you want to delete user <strong>{{ $u->name }}</strong> ({{ $u->student_id ?? 'No ID' }})?
                                                </x-slot>
                                                <x-slot name="footer">
                                                    <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" class="d-inline">
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
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="bi bi-people" style="font-size:2rem;"></i><br>
                                            No users found. All registered users will appear here.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="mt-4">
                    <h5 class="fw-bold mb-3">Deleted Users</h5>
                    <form method="POST" id="bulk-user-restore-form">
                        @csrf
                        <div class="d-flex mb-2 gap-2">
                            <button type="submit" formaction="{{ route('admin.users.bulkRestore') }}" class="btn btn-success btn-sm" onclick="return confirm('Restore selected users?')"><i class="bi bi-arrow-clockwise"></i> Bulk Restore</button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#bulkPermanentDeleteModal"><i class="bi bi-x-circle"></i> Permanent Delete</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th><input type="checkbox" id="select-all-deleted"></th>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($users->whereNotNull('deleted_at') as $u)
                                    <tr>
                                        <td></td>
                                        <td>{{ $u->student_id ?? '-' }}</td>
                                        <td>{{ $u->name }}</td>
                                        <td>
                                            <span class="badge @if($u->role=='admin') bg-danger-subtle text-danger @elseif($u->role=='tutor') bg-primary-subtle text-primary @else bg-warning-subtle text-warning @endif" style="font-weight:500;">
                                                {{ ucfirst($u->role) }}
                                            </span>
                                        </td>
                                        <td><span class="badge bg-danger-subtle text-danger">Deleted</span></td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.users.restore', $u->id) }}" class="d-inline">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#restoreUserModal-{{ $u->id }}"><i class="bi bi-arrow-clockwise"></i> Restore</button>
                                            <!-- Modal for single user restore -->
                                            <x-admin-modal :id="'restoreUserModal-' . $u->id" title="Restore User">
                                                <x-slot name="body">
                                                    Are you sure you want to restore user <strong>{{ $u->name }}</strong> ({{ $u->student_id ?? 'No ID' }})?
                                                </x-slot>
                                                <x-slot name="footer">
                                                    <form method="POST" action="{{ route('admin.users.restore', $u->id) }}" class="d-inline">
                                                        @csrf
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">Restore</button>
                                                    </form>
                                                </x-slot>
                                            </x-admin-modal>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#permanentDeleteUserModal-{{ $u->id }}"><i class="bi bi-x-circle"></i> Delete</button>
                                            <!-- Modal for single user permanent delete -->
                                            <x-admin-modal :id="'permanentDeleteUserModal-' . $u->id" title="Permanently Delete User">
                                                <x-slot name="body">
                                                    Are you sure you want to permanently delete user <strong>{{ $u->name }}</strong> ({{ $u->student_id ?? 'No ID' }})? This action cannot be undone.
                                                </x-slot>
                                                <x-slot name="footer">
                                                    <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </x-slot>
                                            </x-admin-modal>
                                                        <!-- Bulk Delete Modal -->
                                                        <x-admin-modal id="bulkDeleteModal" title="Bulk Delete Users">
                                                            <x-slot name="body">
                                                                Are you sure you want to delete all selected users?
                                                            </x-slot>
                                                            <x-slot name="footer">
                                                                <form method="POST" id="bulkDeleteForm" action="{{ route('admin.users.bulkDelete') }}">
                                                                    @csrf
                                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                </form>
                                                            </x-slot>
                                                        </x-admin-modal>
                                                        <!-- Bulk Permanent Delete Modal -->
                                                        <x-admin-modal id="bulkPermanentDeleteModal" title="Permanently Delete Users">
                                                            <x-slot name="body">
                                                                Are you sure you want to permanently delete all selected users? This action cannot be undone.
                                                            </x-slot>
                                                            <x-slot name="footer">
                                                                <form method="POST" id="bulkPermanentDeleteForm" action="{{ route('admin.users.bulkDelete') }}">
                                                                    @csrf
                                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                </form>
                                                            </x-slot>
                                                        </x-admin-modal>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">No deleted users found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <script>
                    document.getElementById('select-all-users').addEventListener('change', function() {
                        const checkboxes = document.querySelectorAll('#bulk-user-action-form input[name="ids[]"]');
                        for (const cb of checkboxes) { cb.checked = this.checked; }
                    });
                    document.getElementById('select-all-deleted').addEventListener('change', function() {
                        const checkboxes = document.querySelectorAll('#bulk-user-restore-form input[name="ids[]"]');
                        for (const cb of checkboxes) { cb.checked = this.checked; }
                    });
                </script>
            </div>
            <div class="col-md-3">
                <div class="card card-modern mb-3">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center p-3">
                        <h6 class="mb-2" style="font-size: 1rem;">User Roles</h6>
                        <canvas id="userRolesChart" height="100" style="max-width:220px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @push('head')
            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        @endpush
        @push('scripts')
        <script>
            (function() {
                const admin = {{ $users->where('role', 'admin')->count() ?? 0 }};
                const tutor = {{ $users->where('role', 'tutor')->count() ?? 0 }};
                const tutee = {{ $users->where('role', 'tutee')->count() ?? 0 }};
                const total = admin + tutor + tutee;
                let chartData, chartColors, chartLabels;
                if (total === 0) {
                    chartLabels = ['Admin', 'Tutor', 'Tutee'];
                    chartData = [1, 1, 1];
                    chartColors = ['rgba(239,68,68,0.15)', 'rgba(37,99,235,0.15)', 'rgba(245,158,11,0.15)'];
                } else {
                    chartLabels = ['Admin', 'Tutor', 'Tutee'];
                    chartData = [admin, tutor, tutee];
                    chartColors = ['#ef4444', '#2563eb', '#f59e0b'];
                }
                new Chart(document.getElementById('userRolesChart'), {
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
    </div>
</div>
@endsection
