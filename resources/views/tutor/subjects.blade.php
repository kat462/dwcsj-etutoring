

@extends('layouts.app')
@section('content')
<div class="page-header mb-4 shadow-sm">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-book text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">My Subjects</h1>
    </div>
</div>
<div class="card card-modern mb-4 shadow-sm border-0">
    <div class="card-body">
        <form method="POST" action="{{ route('tutor.subjects.update') }}">
            @csrf
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th></th>
                            <th>Subject</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($subjects as $subject)
                        <tr>
                            <td>
                                <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" @if(in_array($subject->id, $tutorSubjects)) checked @endif>
                            </td>
                            <td class="fw-semibold">{{ $subject->name }}</td>
                            <td>{{ $subject->description ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-5">
                                <i class="bi bi-book" style="font-size:2rem;"></i><br>
                                No subjects available.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save My Subjects</button>
            </div>
        </form>
    </div>
</div>
@endsection
