@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-calendar3 text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Tutor Calendar</h1>
    </div>
    <div class="text-muted">Viewing availability for <span class="fw-semibold">{{ $tutor->name }}</span></div>
</div>
<div class="card card-modern mb-4">
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 600,
        events: '/api/tutor/{{ $tutor->id }}/calendar/events',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        eventClick: function(info) {
            // Optionally, show booking modal or details
        }
    });
    calendar.render();
});
</script>
@endpush
@endsection
