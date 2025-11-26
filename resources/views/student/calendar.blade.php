@extends('layouts.app')
@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-calendar3 text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">My Session Calendar</h1>
    </div>
</div>
<div class="card card-modern mb-4">
    <div class="card-body">
        <div id="calendar"></div>
        <div id="calendar-empty" class="text-center text-muted py-5" style="display:none;">
            <div class="fw-bold mb-2" style="font-size:1.2rem;">No sessions scheduled yet</div>
            <div class="mb-2">Your booked sessions will appear here as calendar events.</div>
            <div class="mb-2 small">Stay tuned! Once you book a session, it will show up here.</div>
            <div class="mt-3 d-flex flex-column flex-sm-row justify-content-center gap-2">
                <a href="{{ route('student.request_session') }}" class="btn btn-outline-primary btn-sm" aria-label="Request a new session"><i class="bi bi-calendar-plus"></i> Request a Session</a>
                <a href="{{ route('student.bookings') }}" class="btn btn-outline-secondary btn-sm" aria-label="View my bookings"><i class="bi bi-journal-bookmark"></i> My Bookings</a>
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
        events: function(fetchInfo, successCallback, failureCallback) {
            fetch('/api/student/calendar/events')
                .then(response => response.json())
                .then(events => {
                    if (!events || events.length === 0) {
                        // Show a placeholder event if no real events
                        const today = new Date();
                        events = [{
                            title: 'No sessions yet',
                            start: today.toISOString().slice(0, 10),
                            allDay: true,
                            display: 'background',
                            backgroundColor: '#f3f4f6',
                            borderColor: '#e5e7eb',
                            textColor: '#9ca3af'
                        }];
                    }
                    successCallback(events);
                })
                .catch(failureCallback);
        },
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        eventClick: function(info) {
            // Optionally, show booking modal or details
        },
        eventDidMount: function(info) {
            // If there are events, hide empty state
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
