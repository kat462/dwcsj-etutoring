// FullCalendar integration for admin calendar

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('admin-calendar');
    if (!calendarEl) return;
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 650,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        events: '/admin/calendar/events',
        eventClick: function(info) {
            var props = info.event.extendedProps;
            alert(
                'Session ID: ' + props.booking_id + '\n' +
                'Tutor: ' + props.tutor_name + '\n' +
                'Tutee: ' + props.tutee_name + '\n' +
                'Subject: ' + props.subject + '\n' +
                'Status: ' + props.status
            );
        },
        eventColor: '#16a34a',
        nowIndicator: true,
        selectable: false,
    });
    calendar.render();
});
