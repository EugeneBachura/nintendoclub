<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Manages notifications for users.
 */
class NotificationController extends Controller
{
    /**
     * Display a list of notifications for the user.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $notificationCount = $user->unreadNotifications->count();
        $notifications = Cache::remember("user_{$user->id}_notifications", 60, function () use ($user) {
            return $user->notifications()->latest()->paginate(10);
        });

        return view('notifications', compact('notifications'));
    }

    /**
     * Mark a specific notification as read.
     *
     * @param Request $request
     * @param string $notificationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request, $notificationId)
    {
        $notification = $request->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();
        return response()->json(['message' => 'Notification marked as read.']);
    }

    /**
     * Mark all notifications as read for the user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'All notifications marked as read.']);
    }

    /**
     * Show form to create a new notification.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $users = User::pluck('name', 'id');
        return view('notifications.create', compact('users'));
    }

    /**
     * Send a notification to a specific user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'url' => 'required|url',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->notifyWithLimit(new UserNotification($request->message, $request->url));

        return back()->with('success', 'Уведомление успешно отправлено!');
    }
}
