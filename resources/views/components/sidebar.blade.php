@php
    $user = Auth::user();
    $role = 'student';
    if ($user->isAdmin()) $role = 'admin';
    elseif ($user->isTutor()) $role = 'tutor';
@endphp

<aside class="sidebar-inner px-3">
    <div class="px-3 mb-3">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/DWCSJ_Seal.png') }}" alt="logo" class="sidebar-avatar me-3">
            <div class="flex-fill">
                <div class="sidebar-user-name">{{ $user->name }}</div>
                <div class="sidebar-user-role">{{ ucfirst($role) }}</div>
            </div>
        </div>
    </div>

    <nav aria-label="sidebar-navigation">
        @if($role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" aria-current="{{ request()->routeIs('admin.dashboard') ? 'page' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span class="sidebar-link-text">Dashboard</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span class="sidebar-link-text">Users</span>
            </a>
            <a href="{{ route('admin.allowed-student-ids.index') }}" class="sidebar-link {{ request()->routeIs('admin.allowed-student-ids*') ? 'active' : '' }}">
                <i class="bi bi-list-check"></i>
                <span class="sidebar-link-text">Allowed Student IDs</span>
            </a>
            <a href="{{ route('admin.calendar') }}" class="sidebar-link {{ request()->routeIs('admin.calendar') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i>
                <span class="sidebar-link-text">Calendar</span>
            </a>
            <a href="{{ url('/admin/analytics') }}" class="sidebar-link">
                <i class="bi bi-graph-up"></i>
                <span class="sidebar-link-text">Analytics</span>
            </a>
        @elseif($role === 'tutor')
            <a href="{{ route('tutor.dashboard') }}" class="sidebar-link {{ request()->routeIs('tutor.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span class="sidebar-link-text">Dashboard</span>
            </a>
            <a href="{{ route('tutor.profile.show') }}" class="sidebar-link {{ request()->routeIs('tutor.profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i>
                <span class="sidebar-link-text">My Profile</span>
            </a>
            <a href="{{ route('tutor.subjects') }}" class="sidebar-link {{ request()->routeIs('tutor.subjects*') ? 'active' : '' }}">
                <i class="bi bi-book"></i>
                <span class="sidebar-link-text">My Subjects</span>
            </a>
            <a href="{{ route('tutor.bookings') }}" class="sidebar-link {{ request()->routeIs('tutor.bookings*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i>
                <span class="sidebar-link-text">Booking Requests</span>
            </a>
            <a href="{{ route('tutor.calendar') }}" class="sidebar-link {{ request()->routeIs('tutor.calendar') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i>
                <span class="sidebar-link-text">Calendar</span>
            </a>
        @else
            <a href="{{ route('student.dashboard') }}" class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span class="sidebar-link-text">Dashboard</span>
            </a>
            <a href="{{ route('student.bookings') }}" class="sidebar-link {{ request()->routeIs('student.bookings*') ? 'active' : '' }}">
                <i class="bi bi-calendar-plus"></i>
                <span class="sidebar-link-text">Request Session</span>
            </a>
            <!-- Use bookings index as canonical student calendar view to avoid broken legacy links -->
            <a href="{{ route('student.bookings') }}" class="sidebar-link {{ request()->routeIs('student.bookings*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i>
                <span class="sidebar-link-text">Calendar</span>
            </a>
            <!-- Feedback is per-booking; link to bookings list to see feedback entry points -->
            <a href="{{ route('student.bookings') }}" class="sidebar-link">
                <i class="bi bi-chat-left-text"></i>
                <span class="sidebar-link-text">Feedback</span>
            </a>
        @endif
    </nav>

    <nav class="mt-3" aria-label="account-navigation">
        <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i>
            <span class="sidebar-link-text">Settings</span>
        </a>
    </nav>

    <div class="mt-4 px-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>
</aside>
