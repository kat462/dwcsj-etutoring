@php
    $user = Auth::user();
    $role = 'student';
    if ($user->isAdmin()) $role = 'admin';
    elseif ($user->isTutor()) $role = 'tutor';
@endphp

<aside class="sidebar-inner px-3">
    <div class="px-3 mb-3">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/DWCSJ_Seal.png') }}" alt="logo" style="width:44px;height:44px;object-fit:contain;margin-right:0.75rem;border-radius:8px;">
            <div>
                <div class="fw-bold">{{ $user->name }}</div>
                <div class="text-muted small">{{ ucfirst($role) }}</div>
            </div>
        </div>
    </div>

    <nav aria-label="sidebar-navigation">
        @if($role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Users
            </a>
            <a href="{{ route('admin.allowed-student-ids.index') }}" class="sidebar-link {{ request()->routeIs('admin.allowed-student-ids*') ? 'active' : '' }}">
                <i class="bi bi-list-check"></i> Allowed Student IDs
            </a>
            <a href="{{ route('admin.calendar') }}" class="sidebar-link {{ request()->routeIs('admin.calendar') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Calendar
            </a>
            <a href="{{ url('/admin/analytics') }}" class="sidebar-link">
                <i class="bi bi-graph-up"></i> Analytics
            </a>
        @elseif($role === 'tutor')
            <a href="{{ route('tutor.dashboard') }}" class="sidebar-link {{ request()->routeIs('tutor.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('tutor.profile.show') }}" class="sidebar-link {{ request()->routeIs('tutor.profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> My Profile
            </a>
            <a href="{{ route('tutor.subjects') }}" class="sidebar-link {{ request()->routeIs('tutor.subjects*') ? 'active' : '' }}">
                <i class="bi bi-book"></i> My Subjects
            </a>
            <a href="{{ route('tutor.bookings') }}" class="sidebar-link {{ request()->routeIs('tutor.bookings*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Booking Requests
            </a>
            <a href="{{ route('tutor.calendar') }}" class="sidebar-link {{ request()->routeIs('tutor.calendar') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Calendar
            </a>
        @else
            <a href="{{ route('student.dashboard') }}" class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('student.bookings') }}" class="sidebar-link {{ request()->routeIs('student.bookings*') ? 'active' : '' }}">
                <i class="bi bi-calendar-plus"></i> Request Session
            </a>
            <a href="{{ url('/student/calendar') }}" class="sidebar-link {{ request()->routeIs('student.tutor.calendar') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Calendar
            </a>
            <a href="{{ url('/student/feedback') }}" class="sidebar-link">
                <i class="bi bi-chat-left-text"></i> Feedback
            </a>
        @endif
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
