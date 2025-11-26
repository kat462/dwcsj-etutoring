@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-people text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">All Users</h1>
    </div>
</div>
<div class="card card-modern mb-4 shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>{{ $u->student_id }}</td>
                            <td>{{ $u->name }}</td>
                            <td>
                                <span class="badge @if($u->role=='admin') bg-danger @elseif($u->role=='tutor') bg-primary @else bg-secondary @endif">
                                    {{ ucfirst($u->role) }}
                                </span>
                            </td>
                            <td>
                                @if($u->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.user.toggle',$u->id) }}" class="d-inline">@csrf
                                    <button class="btn btn-sm btn-outline-warning">{{ $u->is_active ? 'Deactivate' : 'Activate' }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.user.delete',$u->id) }}" class="d-inline delete-user-form">@csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#adminModal" data-user-id="{{ $u->id }}"><i class="bi bi-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('components.admin-modal')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var adminModal = document.getElementById('adminModal');
    var formToSubmit = null;
    adminModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        if (button && button.dataset.userId) {
            adminModal.querySelector('.modal-title').textContent = 'Delete User';
            adminModal.querySelector('.modal-body').innerHTML = 'Are you sure you want to delete this user?';
            formToSubmit = button.closest('form');
            var saveBtn = adminModal.querySelector('.btn-primary');
            saveBtn.textContent = 'Delete';
            saveBtn.classList.remove('btn-primary');
            saveBtn.classList.add('btn-danger');
        }
    });
    adminModal.querySelector('.btn-danger').addEventListener('click', function () {
        if (formToSubmit) formToSubmit.submit();
    });
});
</script>
@endpush
@endsection
