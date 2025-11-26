

@extends('layouts.app')
@section('content')
<div class="page-header mb-4 shadow-sm">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-calendar3 text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">My Session Calendar</h1>
    </div>
</div>
<div class="card card-modern mb-4 shadow-sm border-0">
    <div class="card-body">
        <div id="calendar"></div>
        <div id="calendar-empty" class="text-center text-muted py-5" style="display:none;">
            <div class="mb-3">
                <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/svg/1f4c5.svg" alt="No Sessions" style="width:64px;height:64px;opacity:0.7;">
            </div>
            <div class="fw-bold mb-2" style="font-size:1.2rem;">No sessions scheduled yet</div>
            <div class="mb-2">Your upcoming sessions will appear here as calendar events.</div>
            <div class="mb-2 small">Set your availability to get booked by tutees.</div>
            <div class="mt-3">
                <a href="{{ route('tutor.availabilities.index') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-calendar2-week"></i> Set Availability</a>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 600,
        events: '/api/availability/calendar-events',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        eventClick: function(info) {
            // Optionally, show booking modal or details
        },
        eventDidMount: function(info) {
            if (info.view.getCurrentData().eventStore && info.view.getCurrentData().eventStore.length > 0) {
                document.getElementById('calendar-empty').style.display = 'none';
            }
        },
        eventsSet: function(events) {
            if (events.length === 0) {
                document.getElementById('calendar-empty').style.display = '';
            } else {
                document.getElementById('calendar-empty').style.display = 'none';
            }
        }
    });
    calendar.render();
});
</script>
@endpush
@endsection
