

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
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr style="background: #f8fafc;">
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($availabilities as $availability)
                    <tr>
                        <td class="fw-semibold">{{ $availability->date->format('M d, Y') }}</td>
                        <td>{{ $availability->start_time }}</td>
                        <td>{{ $availability->end_time }}</td>
                        <td>
                            <span class="badge @if($availability->is_booked) bg-danger @else bg-success @endif">
                                {{ $availability->is_booked ? 'Booked' : 'Available' }}
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('tutor.availabilities.destroy', $availability->id) }}" class="d-inline">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" @if($availability->is_booked) disabled @endif>Remove</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-calendar-x" style="font-size:2rem;"></i><br>
                            No availabilities set yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
