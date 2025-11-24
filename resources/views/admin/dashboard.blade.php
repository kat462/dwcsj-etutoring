@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-red-500 via-orange-500 to-yellow-500 rounded-lg p-8 text-white">
                <h1 class="text-4xl font-bold mb-2">Admin Dashboard 🎛️</h1>
                <p class="text-orange-100">Platform analytics, user management, and system health monitoring</p>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-8">
            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                <p class="text-gray-500 text-sm font-medium">Total Users</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ $totalTutors }} tutors + {{ $totalStudents }} students</p>
            </div>

            <!-- Total Tutors -->
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
                <p class="text-gray-500 text-sm font-medium">Active Tutors</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalTutors }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ round(($totalTutors / $totalUsers) * 100, 0) }}% of users</p>
            </div>

            <!-- Total Students -->
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                <p class="text-gray-500 text-sm font-medium">Active Students</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalStudents }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ round(($totalStudents / $totalUsers) * 100, 0) }}% of users</p>
            </div>

            <!-- Total Sessions -->
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-indigo-500">
                <p class="text-gray-500 text-sm font-medium">Total Sessions</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalSessions }}</p>
                <p class="text-xs text-gray-400 mt-2">Pending: {{ $sessionsByStatus['pending'] }}</p>
            </div>

            <!-- Total Feedback -->
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
                <p class="text-gray-500 text-sm font-medium">Total Feedback</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalFeedback }}</p>
                <p class="text-xs text-gray-400 mt-2">Reviews submitted</p>
            </div>

            <!-- Platform Rating -->
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-pink-500">
                <p class="text-gray-500 text-sm font-medium">Avg Rating</p>
                <p class="text-3xl font-bold text-gray-800">{{ $averagePlatformRating }}/5</p>
                <div class="flex items-center gap-1 mt-1">
                    @for($i = 0; $i < 5; $i++)
                        @if($i < floor($averagePlatformRating))
                            <svg class="w-3 h-3 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"></path></svg>
                        @else
                            <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"></path></svg>
                        @endif
                    @endfor
                </div>
            </div>
        </div>

        <!-- Session Status Summary -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                <p class="text-yellow-800 font-semibold text-2xl">{{ $sessionsByStatus['pending'] }}</p>
                <p class="text-yellow-600 text-sm">Pending Sessions</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                <p class="text-blue-800 font-semibold text-2xl">{{ $sessionsByStatus['accepted'] }}</p>
                <p class="text-blue-600 text-sm">Scheduled Sessions</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                <p class="text-green-800 font-semibold text-2xl">{{ $sessionsByStatus['completed'] }}</p>
                <p class="text-green-600 text-sm">Completed Sessions</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                <p class="text-red-800 font-semibold text-2xl">{{ $sessionsByStatus['cancelled'] }}</p>
                <p class="text-red-600 text-sm">Cancelled Sessions</p>
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

        <!-- Top Subjects Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Top 5 Most Requested Subjects</h2>
            <canvas id="subjectsChart" height="50"></canvas>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <a href="#" class="bg-gradient-to-r from-red-500 to-orange-500 hover:from-red-600 hover:to-orange-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200 text-center">
                👥 Manage Users
            </a>
            <a href="#" class="bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200 text-center">
                📚 Manage Subjects
            </a>
            <a href="/admin/allowed-ids" class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200 text-center">
                🔐 Allowed IDs
            </a>
            <a href="#" class="bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200 text-center">
                📊 Export Data
            </a>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Bookings (left, 2 cols) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">📅 Recent Bookings</h2>
                    @if($recentBookings->isEmpty())
                        <p class="text-gray-500 text-center py-8">No bookings yet</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b-2 border-gray-200">
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Student</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Tutor</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Subject</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBookings as $booking)
                                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                                            <td class="py-3 px-4 text-gray-800">{{ $booking->student->first_name }}</td>
                                            <td class="py-3 px-4 text-gray-800">{{ $booking->tutor->first_name }}</td>
                                            <td class="py-3 px-4 text-gray-600">{{ $booking->subject->name }}</td>
                                            <td class="py-3 px-4">
                                                @if($booking->status === 'pending')
                                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                                @elseif($booking->status === 'accepted')
                                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Scheduled</span>
                                                @elseif($booking->status === 'completed')
                                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                                @else
                                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 text-gray-600 text-xs">{{ $booking->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Top Tutors (right, 1 col) -->
            <div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">🏆 Top Tutors</h2>
                    @if($topTutors->isEmpty())
                        <p class="text-gray-500 text-center py-8">No tutors yet</p>
                    @else
                        <div class="space-y-3">
                            @foreach($topTutors as $tutor)
                                <div class="border border-gray-200 rounded-lg p-3 hover:border-orange-300 transition">
                                    <p class="font-semibold text-gray-800 text-sm">{{ $tutor->name }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="flex items-center gap-1">
                                            @for($i = 0; $i < 5; $i++)
                                                @if($i < floor($tutor->rating))
                                                    <svg class="w-3 h-3 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"></path></svg>
                                                @else
                                                    <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"></path></svg>
                                                @endif
                                            @endfor
                                            <span class="text-xs font-semibold text-gray-700">{{ $tutor->rating }}</span>
                                        </div>
                                        <span class="text-xs text-gray-500">({{ $tutor->feedbacks }} reviews)</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bottom Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
            <!-- Most Requested Subjects -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">📚 Most Requested Subjects</h2>
                @if($topSubjects->isEmpty())
                    <p class="text-gray-500 text-center py-8">No subjects yet</p>
                @else
                    <div class="space-y-3">
                        @foreach($topSubjects as $subject)
                            <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $subject->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $subject->education_level }}</p>
                                </div>
                                <div class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $subject->bookings }} bookings
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Education Level Breakdown -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">🎓 Tutors by Education Level</h2>
                @if($educationBreakdown->isEmpty())
                    <p class="text-gray-500 text-center py-8">No tutors yet</p>
                @else
                    <div class="space-y-3">
                        @foreach($educationBreakdown as $edu)
                            <div class="flex items-center justify-between">
                                <p class="text-gray-700 font-medium">{{ $edu->level ?? 'Unknown' }}</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-orange-400 to-red-500" style="width: {{ ($edu->count / $totalTutors) * 100 }}%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-800 w-8">{{ $edu->count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
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
            labels: {!! json_encode($monthlySessionsData['labels']) !!},
            datasets: [{
                label: 'Total Sessions',
                data: {!! json_encode($monthlySessionsData['data']) !!},
                borderColor: 'rgba(239, 68, 68, 1)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgba(239, 68, 68, 1)',
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
                    max: Math.max(...{!! json_encode($monthlySessionsData['data']) !!}) + 2,
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

    // Subjects Chart (Horizontal Bar)
    const subjectsCtx = document.getElementById('subjectsChart').getContext('2d');
    new Chart(subjectsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($subjectsData['labels']) !!},
            datasets: [{
                label: 'Bookings',
                data: {!! json_encode($subjectsData['data']) !!},
                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                borderColor: 'rgba(239, 68, 68, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    max: Math.max(...{!! json_encode($subjectsData['data']) !!}) + 2,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection
