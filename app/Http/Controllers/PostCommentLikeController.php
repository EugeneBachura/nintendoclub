<?php

namespace App\Http\Controllers;

use App\Models\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostCommentLikeController extends Controller
{
    public function like(Request $request, $commentId)
    {
        try {
            $comment = PostComment::findOrFail($commentId);
            $user = Auth::user();

            $like = $comment->likes()->where('user_id', $user->id)->first();

            if ($like) {
                // Если лайк уже существует, удаляем его
                $like->delete();
            } else {
                // Проверяем, не был ли лайк уже создан в другом запросе
                $existingLike = $comment->likes()->where('user_id', $user->id)->first();
                if (!$existingLike) {
                    // Если лайка нет, создаем его
                    $comment->likes()->create(['user_id' => $user->id]);
                }
            }

            // Возвращаем обновленное количество лайков
            return response()->json(['likesCount' => $comment->likes()->count()]);
        } catch (\Exception $e) {
            // Логируем исключение
            Log::error($e->getMessage());
            // Возвращаем сообщение об ошибке
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}
