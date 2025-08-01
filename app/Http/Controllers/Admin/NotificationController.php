<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', auth()->id() ?? 1)
            ->with(['project'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function getUnread()
    {
        $notifications = Notification::where('user_id', auth()->id() ?? 1)
            ->where('is_read', false)
            ->with(['project'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $unreadCount = Notification::where('user_id', auth()->id() ?? 1)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead(Notification $notification)
    {
        // Ensure the notification belongs to the current user
        if ($notification->user_id !== (auth()->id() ?? 1)) {
            abort(403);
        }

        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        // Redirect to the action URL if provided
        if ($notification->action_url) {
            return redirect($notification->action_url);
        }

        return back();
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id() ?? 1)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(Notification $notification)
    {
        // Ensure the notification belongs to the current user
        if ($notification->user_id !== (auth()->id() ?? 1)) {
            abort(403);
        }

        $notification->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification deleted.');
    }
}
