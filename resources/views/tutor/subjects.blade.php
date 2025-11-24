@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card p-4 shadow">
        <h3 class="mb-3">Select Subjects You Can Teach</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('tutor.subjects.update') }}">
            @csrf
            <div class="row">
                @foreach($subjects->groupBy('education_level') as $level => $group)
                    <div class="col-md-6 mb-4">
                        <h5 class="text-primary">{{ $level ?? 'Uncategorized' }}</h5>
                        @foreach($group as $subject)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="subjects[]"
                                       value="{{ $subject->id }}"
                                       id="subject_{{ $subject->id }}"
                                       {{ in_array($subject->id, $tutorSubjects) ? 'checked' : '' }}>
                                <label class="form-check-label" for="subject_{{ $subject->id }}">
                                    {{ $subject->name }}
                                    @if($subject->code)
                                        <small class="text-muted">({{ $subject->code }})</small>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success">Save Subjects</button>
                <a href="{{ route('tutor.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </form>
    </div>
</div>
@endsection
