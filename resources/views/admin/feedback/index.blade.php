@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Feedback Moderation</h3>
            <p class="text-muted mb-0">View, search, and moderate student reviews.</p>
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

        <label class="mb-0 ms-3">Rating:</label>
        <select name="rating" onchange="this.form.submit()" class="form-select w-auto">
            <option value="all" {{ $rating === 'all' ? 'selected' : '' }}>All</option>
            <option value="5" {{ $rating === '5' ? 'selected' : '' }}>5 ★</option>
            <option value="4" {{ $rating === '4' ? 'selected' : '' }}>4 ★</option>
            <option value="3below" {{ $rating === '3below' ? 'selected' : '' }}>3 ★ and below</option>
        </select>

        <input type="search" name="q" value="{{ $q ?? '' }}" placeholder="Search tutor, tutee or comment" class="form-control form-control-sm ms-2" />
    </form>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<table class="table table-striped">
<thead>
    <tr>
        <th>Tutor</th>
        <th>Tutee</th>
        <th>Rating</th>
        <th>Comment</th>
        <th>Status</th>
        <th>Created</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    @forelse($items as $it)
        <tr>
            <td>{{ $it->tutor ? $it->tutor->name : '-' }}</td>
            <td>{{ $it->tutee ? $it->tutee->name : '-' }}</td>
            <td>{{ $it->rating ?? '-' }}</td>
            <td style="max-width:400px">{{ Str::limit($it->comment, 200) }}</td>
            <td>{{ $it->deleted_at ? 'Trashed' : $it->status }}</td>
            <td>{{ $it->created_at }}</td>
            <td>
                @if(!$it->trashed())
                    <form method="POST" action="{{ route('admin.feedback.destroy', $it) }}" style="display:inline-block" onsubmit="return confirm('Delete review?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.feedback.restore', $it->id) }}" style="display:inline-block">
                        @csrf
                        <button class="btn btn-sm btn-success">Restore</button>
                    </form>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="7">No reviews found.</td></tr>
    @endforelse
</tbody>
</table>

<div>{{ $items->links() }}</div>

@endsection
