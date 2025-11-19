@extends('layouts.app')

@section('title', 'All Sessions Calendar')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">All Sessions Calendar</h1>
        <p class="mt-2 text-gray-600">Monitor all tutoring sessions across the platform.</p>
    </div>

    <!-- Legend -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <h3 class="text-sm font-medium text-gray-900 mb-4">Session Status</h3>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-yellow-400 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Pending</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-blue-400 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Accepted</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-400 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Completed</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-400 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Cancelled</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-gray-400 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Declined</span>
            </div>
        </div>
    </div>

    <!-- Calendar Container -->
    <div class="bg-white rounded-lg shadow p-6">
        <div id="adminCalendar" class="fc-light"></div>
    </div>
</div>

@push('scripts')
<script type="module">
    import { initAdminCalendar } from '/resources/js/calendar.js';
    
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('adminCalendar');
        initAdminCalendar(calendarEl);
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
