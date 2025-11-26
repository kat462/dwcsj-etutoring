<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    public function markRead($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        if ($notification->notifiable_id === Auth::id()) {
            $notification->markAsRead();
        }
        return back();
    }

    public function markAllRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        return back();
    }

    public function destroy($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        if ($notification->notifiable_id === Auth::id()) {
            $notification->delete();
        }
        return back();
    }
}
