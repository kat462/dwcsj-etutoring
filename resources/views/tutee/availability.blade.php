@extends('layouts.app')
@section('content')
<h4>Availability - {{ $tutor->name }}</h4>
<table class="table table-striped">
  <thead><tr><th>Date</th><th>Time</th><th>Action</th></tr></thead>
  <tbody>
    @foreach($slots as $s)
      <tr>
        <td>{{ $s->date }}</td>
        <td>{{ $s->start_time }} - {{ $s->end_time }}</td>
        <td>
          <form method="POST" action="{{ route('book.slot',$s->id) }}">@csrf
            <input name="note" placeholder="Note (optional)" class="form-control mb-1">
            <button class="btn btn-sm btn-success">Book</button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection
