@extends('layouts.app')
@section('content')
<h4>Tutors for {{ $subject->name }}</h4>
<div class="row">
  @foreach($tutors as $t)
    <div class="col-md-4">
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title">{{ $t->name }}</h5>
          <p class="card-text">Course: {{ $t->course ?? 'N/A' }}</p>
          <a href="{{ route('tutor.avail',$t->id) }}" class="btn btn-sm btn-primary">View Availability</a>
        </div>
      </div>
    </div>
  @endforeach
</div>
@endsection
