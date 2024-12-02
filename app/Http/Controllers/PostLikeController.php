<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Handles toggling likes for posts.
 */
class PostLikeController extends Controller
{
    /**
     * Toggle like for a specific post.
     *
     * @param int $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleLike($postId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

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

        $updatedLikesCount = $post->likes()->count();
        return response()->json(['likesCount' => $updatedLikesCount]);
    }
}
