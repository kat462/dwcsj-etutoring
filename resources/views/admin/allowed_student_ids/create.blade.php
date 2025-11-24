@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3>Add Allowed Student ID</h3>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.allowed-student-ids.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Student ID</label>
        <input type="text" name="student_id" class="form-control" value="{{ old('student_id') }}" required />
    </div>
    <div class="mb-3">
        <label class="form-label">Education Level</label>
        <select name="education_level" class="form-select" required>
            <option value="">-- Select --</option>
            <option value="kindergarten">Kindergarten</option>
            <option value="elementary">Elementary</option>
            <option value="junior_high">Junior High School</option>
            <option value="senior_high">Senior High School</option>
            <option value="college">College</option>
            <option value="other">Other</option>
        </select>
    </div>
    <button class="btn btn-primary">Add</button>
    <a href="{{ route('admin.allowed-student-ids.index') }}" class="btn btn-link">Cancel</a>
</form>
@endsection
