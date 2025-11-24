@extends('layouts.app')

@section('title', 'Tutor Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Welcome, {{ Auth::user()->name }}! 👋</h1>
            <p class="mt-2 text-gray-600">Here's your tutoring overview for today</p>
        </div>

        <!-- Key Metrics Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Hours -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-12 w-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Hours</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalHours }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Earnings -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Earnings</p>
                        <p class="text-2xl font-bold text-gray-900">${{ $totalEarnings }}</p>
                    </div>
                </div>
            </div>

            <!-- Average Rating -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-12 w-12 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Average Rating</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $averageRating == 0 ? 'N/A' : $averageRating . '/5' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-12 w-12 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending Requests</p>
                        <p class="text-2xl font-bold text-gray-900">{{ count($bookingRequests) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Weekly Activity Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Weekly Activity</h2>
                <canvas id="weeklyChart" height="300"></canvas>
            </div>

            <!-- Subject Popularity Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Subject Popularity</h2>
                <canvas id="subjectChart" height="300"></canvas>
            </div>
        </div>

        <!-- Monthly Trend Chart -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Monthly Trend (Last 6 Months)</h2>
            <canvas id="monthlyChart" height="100"></canvas>
        </div>

        <!-- Quick Actions Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('tutor.availabilities.index') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white hover:shadow-lg transition">
                <svg class="h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <h3 class="text-lg font-semibold">Set Availability</h3>
                <p class="text-sm text-blue-100">Add or edit your time slots</p>
            </a>

            <a href="{{ route('tutor.calendar') }}" class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow p-6 text-white hover:shadow-lg transition">
                <svg class="h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-semibold">View Calendar</h3>
                <p class="text-sm text-indigo-100">Manage your schedule visually</p>
            </a>

            <a href="{{ route('tutor.subjects') }}" class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow p-6 text-white hover:shadow-lg transition">
                <svg class="h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path>
                </svg>
                <h3 class="text-lg font-semibold">Manage Subjects</h3>
                <p class="text-sm text-green-100">Edit your specializations</p>
            </a>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Upcoming Sessions (Wider) -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Upcoming Sessions (Next 7 Days)</h2>
                
                @if($upcomingSessions->count() > 0)
                    <div class="space-y-4">
                        @foreach($upcomingSessions as $session)
                            <div class="border-l-4 border-indigo-500 bg-indigo-50 p-4 rounded">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $session->tutee->name }}</h3>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-book mr-1"></i>
                                            {{ $session->subject ? $session->subject->name : 'No Subject' }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ \Carbon\Carbon::parse($session->scheduled_at)->format('M d, Y - g:i A') }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($session->status === 'accepted') bg-green-100 text-green-800
                                        @elseif($session->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($session->status === 'completed') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($session->status) }}
                                    </span>
                                </div>
                                @if($session->notes)
                                    <p class="mt-2 text-sm text-gray-600 italic">{{ $session->notes }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No upcoming sessions scheduled</p>
                @endif
            </div>

            <!-- Pending Requests Sidebar -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Booking Requests</h2>
                
                @if($bookingRequests->count() > 0)
                    <div class="space-y-3">
                        @foreach($bookingRequests as $request)
                            <div class="border border-yellow-200 bg-yellow-50 p-3 rounded">
                                <p class="font-semibold text-gray-900 text-sm">{{ $request->tutee->name }}</p>
                                <p class="text-xs text-gray-600 mt-1">
                                    {{ $request->subject ? $request->subject->name : 'General' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $request->created_at->diffForHumans() }}
                                </p>
                                <div class="flex gap-2 mt-3">
                                    <form action="{{ route('tutor.requests.accept', $request->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full text-xs bg-green-500 text-white py-1 rounded hover:bg-green-600">
                                            Accept
                                        </button>
                                    </form>
                                    <form action="{{ route('tutor.requests.decline', $request->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full text-xs bg-red-500 text-white py-1 rounded hover:bg-red-600">
                                            Decline
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8 text-sm">No pending requests</p>
                @endif
            </div>
        </div>

        <!-- Recent Feedback Section -->
        @if($recentFeedback->count() > 0)
            <div class="mt-8 bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Feedback</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($recentFeedback as $feedback)
                        <div class="border border-gray-200 rounded p-4">
                            <div class="flex items-center mb-3">
                                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100">
                                    <span class="text-indigo-600 font-bold">
                                        {{ strtoupper(substr($feedback->tutee->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <p class="font-semibold text-gray-900">{{ $feedback->tutee->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $feedback->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            
                            @if($feedback->rating)
                                <div class="flex mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-4 w-4 @if($i <= $feedback->rating) text-yellow-400 @else text-gray-300 @endif" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                            @endif
                            
                            @if($feedback->comment)
                                <p class="text-gray-700 text-sm italic">{{ $feedback->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Performance Metrics -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Metrics</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Session Completion Rate</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $completionRate }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $completionRate }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Booking Acceptance Rate</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $acceptanceRate }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $acceptanceRate }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Specializations</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($subjects as $subject)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $subject->name }}
                            @if($subject->education_level)
                                <span class="ml-2 text-xs text-blue-600">({{ $subject->education_level }})</span>
                            @endif
                        </span>
                    @empty
                        <p class="text-gray-500 text-sm">No subjects added yet. <a href="{{ route('tutor.subjects') }}" class="text-indigo-600 hover:text-indigo-700">Add subjects</a></p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
    // Weekly Activity Chart
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($weeklyData['labels']) !!},
            datasets: [{
                label: 'Sessions',
                data: {!! json_encode($weeklyData['data']) !!},
                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // Subject Popularity Chart
    const subjectCtx = document.getElementById('subjectChart').getContext('2d');
    new Chart(subjectCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($subjectStats['labels']) !!},
            datasets: [{
                data: {!! json_encode($subjectStats['data']) !!},
                backgroundColor: [
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 159, 64, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Monthly Trend Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyTrend['labels']) !!},
            datasets: [{
                label: 'Sessions',
                data: {!! json_encode($monthlyTrend['data']) !!},
                borderColor: 'rgba(99, 102, 241, 1)',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>

@endsection

