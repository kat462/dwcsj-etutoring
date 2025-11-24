@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Your Availabilities</h3>
        <a href="{{ route('tutor.availabilities.create') }}" class="btn btn-primary">Add Availability</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($availabilities->count())
        <table class="table">
            <thead>
                <tr><th>Date</th><th>Time</th><th>Booked</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($availabilities as $a)
                    <tr>
                        <td>{{ $a->date->toDateString() }}</td>
                        <td>{{ $a->start_time }} - {{ $a->end_time }}</td>
                        <td>{{ $a->is_booked ? 'Yes' : 'No' }}</td>
                        <td>
                            <form method="POST" action="{{ route('tutor.availabilities.destroy', $a->id) }}" onsubmit="return confirm('Delete?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No availabilities yet.</p>
    @endif
</div>

@endsection
