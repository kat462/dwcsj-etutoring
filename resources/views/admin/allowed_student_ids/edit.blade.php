@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3>Edit Allowed Student ID</h3>
</div>

<form method="POST" action="{{ route('admin.allowed-student-ids.update', $item) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Student ID</label>
        <input type="text" class="form-control" value="{{ $item->student_id }}" disabled />
    </div>
    <div class="mb-3">
        <label class="form-label">Education Level</label>
        <select name="education_level" class="form-select" required>
            <option value="kindergarten" {{ $item->education_level === 'kindergarten' ? 'selected' : '' }}>Kindergarten</option>
            <option value="elementary" {{ $item->education_level === 'elementary' ? 'selected' : '' }}>Elementary</option>
            <option value="junior_high" {{ $item->education_level === 'junior_high' ? 'selected' : '' }}>Junior High School</option>
            <option value="senior_high" {{ $item->education_level === 'senior_high' ? 'selected' : '' }}>Senior High School</option>
            <option value="college" {{ $item->education_level === 'college' ? 'selected' : '' }}>College</option>
            <option value="other" {{ $item->education_level === 'other' ? 'selected' : '' }}>Other</option>
        </select>
    </div>
    <div class="mb-3 form-check">
        <input type="hidden" name="used" value="0" />
        <input type="checkbox" class="form-check-input" id="used" name="used" value="1" {{ $item->used ? 'checked' : '' }} />
        <label for="used" class="form-check-label">Used (already registered)</label>
    </div>
    <button class="btn btn-primary">Save</button>
    <a href="{{ route('admin.allowed-student-ids.index') }}" class="btn btn-link">Cancel</a>
</form>
@endsection
