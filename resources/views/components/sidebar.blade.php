
@php
    $user = Auth::user();
    $role = $user->isAdmin() ? 'admin' : ($user->isTutor() ? 'tutor' : 'tutee');
    $avatar = $user->avatar ?? null;
@endphp
<div class="d-flex flex-column align-items-center py-2 mb-2">
    <div class="mb-2 position-relative">
        @if($avatar)
            <img src="{{ asset('storage/avatars/' . $avatar) }}" alt="Avatar" class="sidebar-avatar shadow-sm border border-2 border-success">
        @else
            <span class="sidebar-avatar d-flex align-items-center justify-content-center bg-light border border-2 border-success">
                <i class="bi bi-person-circle text-secondary" style="font-size:2rem;"></i>
            </span>
        @endif
    </div>
    <div class="sidebar-user-name text-center w-100" style="font-size:1.12rem;">{{ $user->name }}</div>
    <div class="sidebar-user-role mb-2 text-center w-100 small">
        @if($role === 'admin')
            System Administrator
        @else
            {{ ucfirst($role) }}
        @endif
    </div>
    <hr class="w-75 my-2" style="border-top:1.5px solid #e2e8f0; opacity:0.7;">
</div>
@php
    $unreadCount = Auth::user()->unreadNotifications()->count();
@endphp
<ul class="nav flex-column mb-auto w-100 px-2 gap-1">
    @if($role === 'admin')
        <li><a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> <span class="sidebar-link-text">Dashboard</span></a></li>
        <li><a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"><i class="bi bi-people"></i> <span class="sidebar-link-text">Users</span></a></li>
        <li><a href="{{ route('admin.allowed-student-ids.index') }}" class="sidebar-link {{ request()->routeIs('admin.allowed-student-ids*') ? 'active' : '' }}"><i class="bi bi-list-check"></i> <span class="sidebar-link-text">Allowed Student IDs</span></a></li>
        <li><a href="{{ route('admin.calendar') }}" class="sidebar-link {{ request()->routeIs('admin.calendar') ? 'active' : '' }}"><i class="bi bi-calendar3"></i> <span class="sidebar-link-text">Calendar</span></a></li>
        <li>
            <a href="{{ route('notifications.index') }}" class="sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i>
                <span class="sidebar-link-text">Notifications</span>
                @if($unreadCount > 0)
                    <span class="badge bg-danger ms-auto" style="font-size:0.8em;">{{ $unreadCount }}</span>
                @endif
            </a>
        </li>
        <li><a href="{{ route('admin.analytics') }}" class="sidebar-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}"><i class="bi bi-graph-up"></i> <span class="sidebar-link-text">Analytics</span></a></li>
        <li class="mt-2">
            <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> <span class="sidebar-link-text">Settings</span>
            </a>
        </li>
    @elseif($role === 'tutor')
        <li><a href="{{ route('tutor.dashboard') }}" class="sidebar-link {{ request()->routeIs('tutor.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> <span class="sidebar-link-text">Dashboard</span></a></li>
        <li><a href="{{ route('tutor.profile.show') }}" class="sidebar-link {{ request()->routeIs('tutor.profile.*') ? 'active' : '' }}"><i class="bi bi-person-badge"></i> <span class="sidebar-link-text">My Profile</span></a></li>
        <li><a href="{{ route('tutor.subjects') }}" class="sidebar-link {{ request()->routeIs('tutor.subjects*') ? 'active' : '' }}"><i class="bi bi-book"></i> <span class="sidebar-link-text">My Subjects</span></a></li>
        <li><a href="{{ route('tutor.bookings') }}" class="sidebar-link {{ request()->routeIs('tutor.bookings*') ? 'active' : '' }}"><i class="bi bi-calendar-check"></i> <span class="sidebar-link-text">Booking Requests</span></a></li>
        <li><a href="{{ route('tutor.calendar') }}" class="sidebar-link {{ request()->routeIs('tutor.calendar') ? 'active' : '' }}"><i class="bi bi-calendar3"></i> <span class="sidebar-link-text">Calendar</span></a></li>
        <li>
            <a href="{{ route('notifications.index') }}" class="sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i>
                <span class="sidebar-link-text">Notifications</span>
                @if($unreadCount > 0)
                    <span class="badge bg-danger ms-auto" style="font-size:0.8em;">{{ $unreadCount }}</span>
                @endif
            </a>
        </li>
    @else
        <li><a href="{{ route('student.dashboard') }}" class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> <span class="sidebar-link-text">Dashboard</span></a></li>
        <li><a href="{{ route('student.bookings') }}" class="sidebar-link {{ request()->routeIs('student.bookings') ? 'active' : '' }}"><i class="bi bi-journal-bookmark"></i> <span class="sidebar-link-text">My Bookings</span></a></li>
        <li><a href="{{ route('student.request_session') }}" class="sidebar-link {{ request()->routeIs('student.request_session') ? 'active' : '' }}"><i class="bi bi-calendar-plus"></i> <span class="sidebar-link-text">Request Session</span></a></li>
        <li><a href="{{ route('student.tutors.browse') }}" class="sidebar-link {{ request()->routeIs('student.tutors.browse') ? 'active' : '' }}"><i class="bi bi-search"></i> <span class="sidebar-link-text">Tutors</span></a></li>
        <li><a href="{{ route('student.calendar') }}" class="sidebar-link {{ request()->routeIs('student.calendar') ? 'active' : '' }}"><i class="bi bi-calendar3"></i> <span class="sidebar-link-text">Calendar</span></a></li>
        <li><a href="{{ route('student.feedback') }}" class="sidebar-link {{ request()->routeIs('student.feedback*') ? 'active' : '' }}"><i class="bi bi-chat-left-text"></i> <span class="sidebar-link-text">Feedback</span></a></li>
        <li>
            <a href="{{ route('notifications.index') }}" class="sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i>
                <span class="sidebar-link-text">Notifications</span>
                @if($unreadCount > 0)
                    <span class="badge bg-danger ms-auto" style="font-size:0.8em;">{{ $unreadCount }}</span>
                @endif
            </a>
        </li>
    @endif
    <li class="my-2"><hr class="my-2 w-75 mx-auto" style="border-top:1.5px solid #e2e8f0; opacity:0.7;"></li>
    <li class="mt-2">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link text-danger bg-transparent border-0 w-100 text-start"><i class="bi bi-box-arrow-right"></i> <span class="sidebar-link-text">Logout</span></button>
        </form>
    </li>
</ul>
