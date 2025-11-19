@extends('layouts.app')
@section('content')
<h4>Feedback for {{ $booking->availability->tutor->name }}</h4>
<form method="POST" action="{{ route('booking.feedback.submit',$booking->id) }}">@csrf
  <div class="mb-3">
    <label>Rating (1-5)</label>
    <input type="number" name="rating" min="1" max="5" class="form-control">
  </div>
  <div class="mb-3">
    <label>Comment</label>
    <textarea name="comment" class="form-control"></textarea>
  </div>
  <button class="btn btn-success">Submit Feedback</button>
</form>
@endsection
