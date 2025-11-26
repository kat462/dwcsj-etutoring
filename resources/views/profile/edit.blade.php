@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-gear text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Edit Profile</h1>
    </div>
</div>
<div class="card card-modern mb-4">
    <div class="card-body">
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                @if($user->role === 'admin')
                    <div class="col-md-6">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="student_id" class="form-label">Admin ID</label>
                        <input type="text" class="form-control" id="student_id" name="student_id" value="{{ old('student_id', $user->student_id) }}">
                    </div>
                @else
                    ...existing code for non-admin fields...
                @endif
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Changes</button>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
