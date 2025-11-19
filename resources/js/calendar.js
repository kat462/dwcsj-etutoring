import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';
import bootstrap5Plugin from '@fullcalendar/bootstrap5';

/**
 * Initialize Tutor Availability Calendar
 * Allows drag/drop, create, edit, and delete operations
 */
export function initTutorCalendar(calendarEl, options = {}) {
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin, bootstrap5Plugin],
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today addEventButton',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
        },
        height: 'auto',
        slotDuration: '00:30:00',
        slotLabelInterval: '00:30:00',
        eventSources: [
            {
                url: '/api/availability/calendar-events',
                failure: () => console.error('Failed to load calendar events'),
            },
        ],
        editable: true,
        eventDurationEditable: true,
        selectConstraint: 'businessHours',
        eventConstraint: 'businessHours',
        
        // Allow editing via drag-drop
        eventDrop: function (info) {
            const { id, start, end } = info.event;
            updateAvailability(id, start, end);
        },
        
        // Allow resizing via edge drag
        eventResize: function (info) {
            const { id, start, end } = info.event;
            updateAvailability(id, start, end);
        },
        
        // Handle event click (delete option)
        eventClick: function (info) {
            handleEventClick(info.event);
        },
        
        // Allow date/time selection to create new availability
        selectConstraint: 'businessHours',
        select: function (info) {
            openAddAvailabilityModal(info.startStr, info.endStr);
        },
        selectable: true,
        
        ...options,
    });

    calendar.render();
    
    // Add custom "Add Event" button
    const addButton = document.querySelector('.fc-addEventButton-button');
    if (addButton) {
        addButton.addEventListener('click', () => {
            openAddAvailabilityModal();
        });
    }
    
    return calendar;
}

/**
 * Initialize Read-Only Student Calendar (view tutor availability)
 */
export function initStudentCalendar(calendarEl, tutorId, options = {}) {
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, bootstrap5Plugin],
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay',
        },
        height: 'auto',
        slotDuration: '00:30:00',
        slotLabelInterval: '00:30:00',
        eventSources: [
            {
                url: `/api/tutor/${tutorId}/calendar-events`,
                failure: () => console.error('Failed to load calendar events'),
            },
        ],
        editable: false,
        eventDurationEditable: false,
        
        // Handle event click (book slot)
        eventClick: function (info) {
            const event = info.event;
            openBookingModal(
                event.startStr,
                event.endStr,
                event.extendedProps.availability_id
            );
        },
        
        ...options,
    });

    calendar.render();
    return calendar;
}

/**
 * Initialize Admin Calendar (view all sessions with color coding)
 */
export function initAdminCalendar(calendarEl, options = {}) {
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, bootstrap5Plugin],
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
        },
        height: 'auto',
        slotDuration: '00:30:00',
        slotLabelInterval: '00:30:00',
        eventSources: [
            {
                url: '/api/bookings/calendar-events',
                failure: () => console.error('Failed to load calendar events'),
            },
        ],
        editable: false,
        eventDurationEditable: false,
        
        // Handle event click (view booking details)
        eventClick: function (info) {
            const { extendedProps } = info.event;
            showBookingDetails(extendedProps);
        },
        
        ...options,
    });

    calendar.render();
    return calendar;
}

/**
 * Update availability via API (after drag/resize)
 */
function updateAvailability(id, start, end) {
    const startStr = start.toISOString();
    const endStr = end.toISOString();

    fetch(`/api/availability/${id}/update-time`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ start: startStr, end: endStr }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Availability updated successfully');
            } else {
                alert('Failed to update availability');
            }
        })
        .catch(error => {
            console.error('Error updating availability:', error);
            alert('Error updating availability');
        });
}

/**
 * Handle event click (show delete/edit options)
 */
function handleEventClick(event) {
    if (confirm(`Delete "${event.title}" on ${event.startStr}?`)) {
        deleteAvailability(event.id);
    }
}

/**
 * Delete availability
 */
function deleteAvailability(id) {
    fetch(`/availability/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Failed to delete availability');
            }
        })
        .catch(error => {
            console.error('Error deleting availability:', error);
            alert('Error deleting availability');
        });
}

/**
 * Open modal to add new availability
 */
function openAddAvailabilityModal(startStr = null, endStr = null) {
    // Show modal or redirect to create form
    const modal = document.getElementById('addAvailabilityModal');
    if (modal) {
        if (startStr && endStr) {
            document.getElementById('availabilityDate').value = startStr.split('T')[0];
            document.getElementById('availabilityStartTime').value = startStr.split('T')[1];
            document.getElementById('availabilityEndTime').value = endStr.split('T')[1];
        }
        modal.classList.remove('hidden');
    } else {
        window.location.href = '/tutor/availabilities/create';
    }
}

/**
 * Close modal
 */
export function closeAddAvailabilityModal() {
    const modal = document.getElementById('addAvailabilityModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

/**
 * Open booking modal (student)
 */
function openBookingModal(startStr, endStr, availabilityId) {
    const modal = document.getElementById('bookingModal');
    if (modal) {
        document.getElementById('bookingStartTime').textContent = startStr;
        document.getElementById('bookingEndTime').textContent = endStr;
        document.getElementById('bookingAvailabilityId').value = availabilityId;
        modal.classList.remove('hidden');
    } else {
        alert('Booking modal not found');
    }
}

/**
 * Close booking modal
 */
export function closeBookingModal() {
    const modal = document.getElementById('bookingModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

/**
 * Show booking details (admin)
 */
function showBookingDetails(props) {
    const details = `
        <strong>Tutee:</strong> ${props.tutee_name}<br>
        <strong>Tutor:</strong> ${props.tutor_name}<br>
        <strong>Subject:</strong> ${props.subject}<br>
        <strong>Status:</strong> ${props.status}
    `;
    alert(details);
}
