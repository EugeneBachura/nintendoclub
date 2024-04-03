<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostLikeController extends Controller
{
    public function toggleLike($postId)
    {
        $user = Auth::user();
        $post = Post::findOrFail($postId);
        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
        } else {
            PostLike::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
        }

        // Возвращаем обновленное количество лайков
        $updatedLikesCount = $post->likes()->count();
        return response()->json(['likesCount' => $updatedLikesCount]);
    }
}
