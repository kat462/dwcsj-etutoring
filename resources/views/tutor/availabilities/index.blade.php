@extends('layouts.app')
@section('content')
<div class="page-header mb-4 shadow-sm">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-calendar2-week text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">My Availabilities</h1>
    </div>
</div>
<div class="card card-modern mb-4 shadow-sm border-0">
    <div class="card-body">
        <a href="{{ route('tutor.availabilities.create') }}" class="btn btn-primary mb-3"><i class="bi bi-plus"></i> Add Availability</a>
        @if($availabilities->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($availabilities as $availability)
                    <tr>
                        <td>{{ $availability->date->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($availability->start_time)->format('h:i A') }}</td>
                        <td>{{ \Carbon\Carbon::parse($availability->end_time)->format('h:i A') }}</td>
                        <td>
                            <span class="badge {{ $availability->is_booked ? 'bg-danger' : 'bg-success' }}">
                                {{ $availability->is_booked ? 'Booked' : 'Available' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('tutor.availabilities.edit', $availability->id) }}" class="btn btn-outline-primary btn-sm" title="Edit" @if($availability->is_booked) disabled @endif><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('tutor.availabilities.destroy', $availability->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this availability?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" @if($availability->is_booked) disabled @endif title="Remove"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center text-muted py-5">
            <i class="bi bi-calendar2-week" style="font-size:2rem;"></i><br>
            <span class="fw-semibold">No availabilities set yet.</span><br>
            <span class="small">Click "Add Availability" to create your first slot.</span>
        </div>
        @endif
    </div>
</div>
@endsection
