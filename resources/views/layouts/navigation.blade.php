<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-primary" href="{{ route('dashboard') }}">
            <i class="bi bi-mortarboard-fill me-2"></i>e-Tutoring
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <!-- Add more nav links here as needed -->
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                <!-- Notifications Dropdown -->
                <li class="nav-item dropdown me-2">
                    @php $unreadCount = Auth::user()->unreadNotifications()->count(); @endphp
                    <a class="nav-link position-relative" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell" style="font-size:1.3rem;"></i>
                        @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="notifDropdown" style="min-width:340px; max-width:400px;">
                        <li class="dropdown-header bg-light fw-semibold py-2 px-3">Notifications</li>
                        <li>
                            <div id="notif-list" style="max-height:320px; overflow-y:auto;">
                                @foreach(Auth::user()->unreadNotifications->take(8) as $notification)
                                    <div class="dropdown-item d-flex align-items-start bg-light border-bottom">
                                        <i class="bi bi-bell-fill text-primary me-2" style="font-size:1.1rem;"></i>
                                        <div class="flex-fill">
                                            <div class="fw-semibold">{{ $notification->data['message'] ?? 'Notification' }}</div>
                                            @if(isset($notification->data['subject']))
                                                <div class="small text-muted">Subject: {{ $notification->data['subject'] }}</div>
                                            @endif
                                            @if(isset($notification->data['scheduled_at']))
                                                <div class="small text-muted">Date: {{ \Carbon\Carbon::parse($notification->data['scheduled_at'])->format('M d, Y h:i A') }}</div>
                                            @endif
                                            <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                @endforeach
                                @if(Auth::user()->unreadNotifications->count() == 0)
                                    <div class="dropdown-item text-center text-muted py-3">
                                        <i class="bi bi-bell" style="font-size:1.5rem;"></i><br>No new notifications
                                    </div>
                                @endif
                            </div>
                        </li>
                        <li><a class="dropdown-item text-center small" href="{{ route('notifications.index') }}">View all notifications</a></li>
                    </ul>
                </li>
                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-lines-fill me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Log Out</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
