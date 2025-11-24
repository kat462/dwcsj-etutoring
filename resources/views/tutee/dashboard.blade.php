@extends('layouts.app')
@section('content')
<h4>Welcome, {{ auth()->user()->name }} (Tutee)</h4>
<div class="row">
  <div class="col-md-8">
    <h5>Subjects</h5>
    <div class="list-group">
      @foreach($subjects as $s)
        <a href="{{ route('subject.tutors',$s->id) }}" class="list-group-item list-group-item-action">{{ $s->name }}</a>
      @endforeach
    </div>
  </div>
  <div class="col-md-4">
    <h5>Your Bookings</h5>
    <ul class="list-group">
      @foreach($myBookings as $b)
        <li class="list-group-item">
          {{ $b->availability->date }} {{ $b->availability->start_time }} - Tutor: {{ $b->availability->tutor->name }}
          <br>Status: {{ $b->status }}
          @if($b->status=='accepted')
            <a class="btn btn-sm btn-primary mt-2" href="{{ route('booking.feedback',$b->id) }}">Leave Feedback</a>
          @endif
        </li>
      @endforeach
    </ul>
  </div>
</div>
@endsection
