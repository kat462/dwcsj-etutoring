@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-green-400 via-emerald-500 to-teal-600 rounded-lg p-8 text-white">
                <h1 class="text-4xl font-bold mb-2">Welcome back, {{ Auth::user()->first_name }}! 👋</h1>
                <p class="text-green-100">Track your learning progress and manage your tutoring sessions</p>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Upcoming Sessions -->
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Upcoming Sessions</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $upcomingCount }}</p>
                    </div>
                    <svg class="w-12 h-12 text-blue-500 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Pending Requests</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $pendingCount }}</p>
                    </div>
                    <svg class="w-12 h-12 text-yellow-500 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Completed Sessions -->
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Completed Sessions</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $completedCount }}</p>
                    </div>
                    <svg class="w-12 h-12 text-green-500 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Feedback Given -->
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Feedback Given</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $feedbackGivenCount }}</p>
                    </div>
                    <svg class="w-12 h-12 text-purple-500 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Monthly Sessions Chart -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Sessions Per Month</h2>
                <canvas id="monthlyChart" height="80"></canvas>
            </div>

            <!-- Sessions by Status Chart -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Sessions by Status</h2>
                <div class="flex justify-center">
                    <canvas id="statusChart" height="80" style="max-width: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <a href="/tutors" class="bg-gradient-to-r from-green-400 to-emerald-500 hover:from-green-500 hover:to-emerald-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 text-center">
                📚 Book a Tutor
            </a>
            <a href="/tutors" class="bg-gradient-to-r from-blue-400 to-indigo-500 hover:from-blue-500 hover:to-indigo-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 text-center">
                🔍 Browse Tutors
            </a>
            <a href="{{ route('student.bookings') }}" class="bg-gradient-to-r from-purple-400 to-pink-500 hover:from-purple-500 hover:to-pink-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 text-center">
                📅 View Calendar
            </a>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Pending Bookings (left, 2 cols) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">⏳ Pending Requests</h2>
                    @if($pendingBookings->isEmpty())
                        <p class="text-gray-500 text-center py-8">No pending requests</p>
                    @else
                        <div class="space-y-3">
                            @foreach($pendingBookings as $booking)
                                <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-4">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $booking->tutor->first_name }} {{ $booking->tutor->last_name }}</p>
                                            <p class="text-sm text-gray-600">📚 {{ $booking->subject->name }}</p>
                                        </div>
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-800">Pending</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">{{ $booking->notes ?? 'No notes' }}</p>
                                    <p class="text-xs text-gray-500">Requested: {{ $booking->created_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Scheduled Bookings -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">📅 Scheduled Sessions</h2>
                    @if($scheduledBookings->isEmpty())
                        <p class="text-gray-500 text-center py-8">No scheduled sessions</p>
                    @else
                        <div class="space-y-3">
                            @foreach($scheduledBookings as $booking)
                                <div class="border border-blue-200 bg-blue-50 rounded-lg p-4">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $booking->tutor->first_name }} {{ $booking->tutor->last_name }}</p>
                                            <p class="text-sm text-gray-600">📚 {{ $booking->subject->name }}</p>
                                        </div>
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-200 text-blue-800">Scheduled</span>
                                    </div>
                                    <p class="text-sm text-gray-700 font-medium">
                                        🕐 {{ $booking->scheduled_at->format('M d, Y \a\t g:ia') }}
                                    </p>
                                    @if($booking->notes)
                                        <p class="text-sm text-gray-600 mt-2">{{ $booking->notes }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Completed Sessions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">✅ Completed Sessions</h2>
                    @if($completedBookings->isEmpty())
                        <p class="text-gray-500 text-center py-8">No completed sessions yet</p>
                    @else
                        <div class="space-y-3">
                            @foreach($completedBookings as $booking)
                                <div class="border border-green-200 bg-green-50 rounded-lg p-4">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $booking->tutor->first_name }} {{ $booking->tutor->last_name }}</p>
                                            <p class="text-sm text-gray-600">📚 {{ $booking->subject->name }}</p>
                                        </div>
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-200 text-green-800">Completed</span>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        Completed: {{ $booking->completed_at ? $booking->completed_at->format('M d, Y') : 'N/A' }}
                                    </p>
                                    @if($booking->feedback)
                                        <div class="mt-2 pt-2 border-t border-green-200">
                                            <div class="flex items-center gap-1 mb-1">
                                                @for($i = 0; $i < $booking->feedback->rating; $i++)
                                                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"></path></svg>
                                                @endfor
                                                <span class="text-sm text-gray-600">{{ $booking->feedback->rating }}/5</span>
                                            </div>
                                            @if($booking->feedback->comment)
                                                <p class="text-sm text-gray-700 italic">{{ Str::limit($booking->feedback->comment, 100) }}</p>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-sm text-orange-600 mt-2">⏳ Awaiting tutor feedback</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Tutors (right, 1 col) -->
            <div>
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-20">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">👥 Recent Tutors</h2>
                    @if($recentTutors->isEmpty())
                        <p class="text-gray-500 text-center py-8">No tutors yet</p>
                    @else
                        <div class="space-y-4">
                            @foreach($recentTutors as $tutor)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-green-300 transition">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($tutor->first_name, 0, 1)) }}{{ strtoupper(substr($tutor->last_name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-800">{{ $tutor->first_name }} {{ $tutor->last_name }}</p>
                                            @if($tutor->tutor_profile)
                                                <p class="text-xs text-gray-600">
                                                    ⭐ {{ number_format($tutor->tutor_profile->average_rating ?? 0, 1) }}/5
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('tutors.show', $tutor->id) }}" class="block w-full text-center bg-green-100 hover:bg-green-200 text-green-800 font-medium py-2 rounded transition">
                                        View Profile / Book
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Browse Tutors Button -->
                    <a href="/tutors" class="block w-full mt-4 bg-gradient-to-r from-green-400 to-emerald-500 hover:from-green-500 hover:to-emerald-600 text-white font-bold py-2 rounded-lg text-center transition">
                        View All Tutors
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
    // Monthly Sessions Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyData['labels']) !!},
            datasets: [{
                label: 'Completed Sessions',
                data: {!! json_encode($monthlyData['data']) !!},
                borderColor: 'rgba(34, 197, 94, 1)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgba(34, 197, 94, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: Math.max(...{!! json_encode($monthlyData['data']) !!}) + 2,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($statusData['labels']) !!},
            datasets: [{
                data: {!! json_encode($statusData['data']) !!},
                backgroundColor: {!! json_encode($statusData['colors']) !!},
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 13
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
