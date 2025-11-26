@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-book-half text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Subjects Management</h1>
    </div>
    <div class="text-muted">Manage all subjects here.</div>
</div>
<div class="card card-modern mb-4 shadow-sm border-0">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.subjects.store') }}" class="row g-2 align-items-end mb-3">
            @csrf
            <div class="col-md-8">
                <input type="text" class="form-control" name="name" placeholder="New Subject Name" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Add Subject</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($subjects as $subject)
                    <tr>
                        <td>{{ $subject->id }}</td>
                        <td>{{ $subject->name }}</td>
                        <td>
                            <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" class="d-inline delete-subject-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#adminModal" data-subject-id="{{ $subject->id }}">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">No subjects found.</td></tr>
                @endforelse
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
        if (button && button.dataset.subjectId) {
            // Set modal title and body for delete confirmation
            adminModal.querySelector('.modal-title').textContent = 'Delete Subject';
            adminModal.querySelector('.modal-body').innerHTML = 'Are you sure you want to delete this subject?';
            // Find the form for this subject
            formToSubmit = button.closest('form');
            // Change Save button to Delete
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
