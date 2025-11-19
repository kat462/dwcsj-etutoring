@extends('layouts.app')

@section('title', 'Book Tutor - ' . $tutor->name)

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ $tutor->name }}'s Availability</h1>
        <p class="mt-2 text-gray-600">Click on a time slot to book a session.</p>
    </div>

    <!-- Tutor Info Card -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-12 w-12 rounded-full bg-indigo-600">
                    <span class="text-white font-bold text-lg">
                        {{ strtoupper(substr($tutor->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $tutor->name)[1] ?? '', 0, 1)) }}
                    </span>
                </div>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-gray-900">{{ $tutor->name }}</h3>
                <p class="text-sm text-gray-500">{{ $tutor->education_level ?? 'Tutor' }}</p>
            </div>
        </div>
    </div>

    <!-- Calendar Container -->
    <div class="bg-white rounded-lg shadow p-6">
        <div id="studentCalendar" class="fc-light"></div>
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Book Session</h3>
                <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('student.bookings.store', $tutor->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Time Slot</label>
                    <p class="mt-1 text-sm text-gray-600">
                        <span id="bookingStartTime"></span> - <span id="bookingEndTime"></span>
                    </p>
                </div>

                <input type="hidden" id="bookingAvailabilityId" name="availability_id">

                <div class="mb-4">
                    <label for="bookingSubject" class="block text-sm font-medium text-gray-700">Subject</label>
                    <select id="bookingSubject" name="subject_id" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a subject</option>
                        @foreach($tutor->subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="bookingNotes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                    <textarea id="bookingNotes" name="notes" rows="3" 
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                              placeholder="Any specific topics or requests..."></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeBookingModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                        Request Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script type="module">
    import { initStudentCalendar, closeBookingModal } from '/resources/js/calendar.js';
    
    window.closeBookingModal = closeBookingModal;
    
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('studentCalendar');
        initStudentCalendar(calendarEl, {{ $tutor->id }});
    });
</script>
@endpush

<!-- Include FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/index.global.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.8/index.global.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.8/index.global.min.js"></script>

@endsection
