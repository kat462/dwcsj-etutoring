@extends('layouts.app')

@section('title', 'Availability Calendar')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Your Availability Calendar</h1>
        <p class="mt-2 text-gray-600">Manage your tutoring availability. Drag to reschedule, click to delete.</p>
    </div>

    <!-- Calendar Container -->
    <div class="bg-white rounded-lg shadow p-6">
        <div id="tutorCalendar" class="fc-light"></div>
    </div>

    <!-- Add Availability Modal -->
    <div id="addAvailabilityModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add Availability</h3>
                <button onclick="closeAddAvailabilityModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('tutor.availabilities.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="availabilityDate" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" id="availabilityDate" name="date" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="availabilityStartTime" class="block text-sm font-medium text-gray-700">Start Time</label>
                        <input type="time" id="availabilityStartTime" name="start_time" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="availabilityEndTime" class="block text-sm font-medium text-gray-700">End Time</label>
                        <input type="time" id="availabilityEndTime" name="end_time" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeAddAvailabilityModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                        Add Availability
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script type="module">
    import { initTutorCalendar, closeAddAvailabilityModal } from '/resources/js/calendar.js';
    
    window.closeAddAvailabilityModal = closeAddAvailabilityModal;
    
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('tutorCalendar');
        initTutorCalendar(calendarEl);
    });
</script>
@endpush

<!-- Include FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/index.global.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.8/index.global.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.8/index.global.min.js"></script>

@endsection
