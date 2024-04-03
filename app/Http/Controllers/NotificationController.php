<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $notificationCount = $user->unreadNotifications->count();
        $notifications = Cache::remember("user_{$user->id}_notifications", 60, function () use ($user) {
            // Получаем уведомления через отношение, предоставляемое трейтом Notifiable
            return $user->notifications()->latest()->paginate(10);
        });

        return view('notifications', compact('notifications'));
    }

    public function markAsRead(Request $request, $notificationId)
    {
        $notification = $request->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();
        return response()->json(['message' => 'Notification marked as read.']);
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'All notifications marked as read.']);
    }

    public function create()
    {
        // Возвращаем представление с формой отправки уведомлений
        $users = User::pluck('name', 'id'); // Получаем пользователей для выпадающего списка
        return view('notifications.create', compact('users'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'url' => 'required|url',
        ]);

        $user = User::findOrFail($request->user_id);
        //$user->notify(new UserNotification($request->message, $request->url));
        $user->notifyWithLimit(new UserNotification($request->message, $request->url));

        return back()->with('success', 'Уведомление успешно отправлено!');
    }
}
