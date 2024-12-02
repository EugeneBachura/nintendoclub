<?php

namespace App\Http\Controllers;

use App\Models\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostCommentLikeController extends Controller
{
    /**
     * Like or unlike a comment.
     *
     * @param Request $request
     * @param int $commentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Request $request, $commentId)
    {
        try {
            $comment = PostComment::findOrFail($commentId);
            $user = Auth::user();

            $like = $comment->likes()->where('user_id', $user->id)->first();

            if ($like) {
                $like->delete();
            } else {
                $existingLike = $comment->likes()->where('user_id', $user->id)->first();
                if (!$existingLike) {
                    $comment->likes()->create(['user_id' => $user->id]);
                }
            }

            return response()->json(['likesCount' => $comment->likes()->count()]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}
