@extends('layouts.app')
@section('content')
<div class="page-header mb-4 shadow-sm">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-bell text-primary me-3" style="font-size: 2.2rem;"></i>
        <h1 class="fw-bold mb-0">Notifications</h1>
    </div>
</div>
<div class="card card-modern mb-4 shadow-sm border-0">
    <div class="card-body">
        <form method="POST" action="{{ route('notifications.markAllRead') }}" class="mb-3 d-flex justify-content-end">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm">Mark all as read</button>
        </form>
        @if($notifications->count())
        <ul class="list-group list-group-flush">
            @foreach($notifications as $notification)
            <li class="list-group-item d-flex align-items-start {{ $notification->read_at ? '' : 'bg-light' }}">
                <div class="me-3">
                    <i class="bi bi-{{ $notification->read_at ? 'bell' : 'bell-fill' }} text-{{ $notification->read_at ? 'secondary' : 'primary' }}" style="font-size:1.3rem;"></i>
                </div>
                <div class="flex-fill">
                    <div class="fw-semibold">{{ $notification->data['message'] ?? 'Notification' }}</div>
                    @if(isset($notification->data['subject']))
                        <div class="small text-muted">Subject: {{ $notification->data['subject'] }}</div>
                    @endif
                    @if(isset($notification->data['scheduled_at']))
                        <div class="small text-muted">Date: {{ \Carbon\Carbon::parse($notification->data['scheduled_at'])->format('M d, Y h:i A') }}</div>
                    @endif
                    @if(isset($notification->data['notes']) && $notification->data['notes'])
                        <div class="small text-muted">Notes: {{ $notification->data['notes'] }}</div>
                    @endif
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
                @if(!$notification->read_at)
                <form method="POST" action="{{ route('notifications.markRead', $notification->id) }}" class="ms-2 d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-link text-success" title="Mark as read"><i class="bi bi-check2-circle"></i></button>
                </form>
                @endif
                <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" class="ms-2 d-inline" onsubmit="return confirm('Delete this notification?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-link text-danger" title="Delete"><i class="bi bi-trash"></i></button>
                </form>
            </li>
            @endforeach
        </ul>
        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
        @else
        <div class="text-center text-muted py-5">
            <i class="bi bi-bell" style="font-size:2rem;"></i><br>
            <span class="fw-semibold">No notifications yet.</span>
        </div>
        @endif
    </div>
</div>
@endsection
