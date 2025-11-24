@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Users</h3>
            <p class="text-muted mb-0">List of students and tutors.</p>
        </div>
    </div>
</div>

<div class="mb-3 d-flex gap-3 align-items-center">
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label class="mb-0">Show:</label>
        <select name="show" onchange="this.form.submit()" class="form-select w-auto">
            <option value="active" {{ $show === 'active' ? 'selected' : '' }}>Active</option>
            <option value="trashed" {{ $show === 'trashed' ? 'selected' : '' }}>Trashed</option>
            <option value="all" {{ $show === 'all' ? 'selected' : '' }}>All</option>
        </select>

        <label class="mb-0 ms-3">Role:</label>
        <select name="role" onchange="this.form.submit()" class="form-select w-auto">
            <option value="both" {{ ($roleFilter ?? 'both') === 'both' ? 'selected' : '' }}>Both</option>
            <option value="tutor" {{ ($roleFilter ?? '') === 'tutor' ? 'selected' : '' }}>Tutor</option>
            <option value="tutee" {{ ($roleFilter ?? '') === 'tutee' ? 'selected' : '' }}>Tutee</option>
        </select>

        <label class="mb-0 ms-3">Education:</label>
        <select name="education_level" onchange="this.form.submit()" class="form-select w-auto">
            <option value="">All</option>
            <option value="kindergarten" {{ (isset($education) && $education === 'kindergarten') ? 'selected' : '' }}>Kindergarten</option>
            <option value="elementary" {{ (isset($education) && $education === 'elementary') ? 'selected' : '' }}>Elementary</option>
            <option value="junior_high" {{ (isset($education) && $education === 'junior_high') ? 'selected' : '' }}>Junior High School</option>
            <option value="senior_high" {{ (isset($education) && $education === 'senior_high') ? 'selected' : '' }}>Senior High School</option>
            <option value="college" {{ (isset($education) && $education === 'college') ? 'selected' : '' }}>College</option>
            <option value="other" {{ (isset($education) && $education === 'other') ? 'selected' : '' }}>Other</option>
        </select>

        <label class="mb-0 ms-3">Search:</label>
        <input type="search" name="q" value="{{ isset($search) ? $search : '' }}" placeholder="Name or Student ID" class="form-control form-control-sm w-auto ms-2" />
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-striped">
<thead>
    <tr>
        <th>Student ID</th>
        <th>Name</th>
        <th>Education</th>
        <th>Role</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    @forelse($users as $user)
        <tr>
            <td>{{ $user->student_id }}</td>
            <td>
                <div class="d-flex align-items-center">
                    @if(optional($user->profile)->profile_image)
                        <img src="/images/profile/{{ $user->profile->profile_image }}" alt="{{ $user->name }}" style="width:40px;height:40px;object-fit:cover;border-radius:50%;margin-right:8px;" />
                    @else
                        <div style="width:40px;height:40px;background:#1E40AF;color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:600;margin-right:8px;">
                            {{ $user->initials() }}
                        </div>
                    @endif
                    <a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a>
                </div>
            </td>
            <td>{{ $user->education_level ? ucfirst($user->education_level) : '' }}</td>
            <td>{{ ucfirst($user->role) }}</td>
            <td>{{ $user->deleted_at ? 'Trashed' : 'Active' }}</td>
            <td>
                @if(!$user->trashed())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline-block" onsubmit="return confirm('Soft-delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.users.restore', $user->id) }}" style="display:inline-block">
                        @csrf
                        <button class="btn btn-sm btn-success">Restore</button>
                    </form>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="6">No users found.</td></tr>
    @endforelse
</tbody>
</table>

<div>
    {{ $users->links() }}
</div>

@endsection
