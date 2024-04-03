<?php

namespace App\Http\Controllers;

use App\Models\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostCommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|integer|exists:post_comments,id',
            'post_id' => 'required|integer|exists:posts,id',
        ]);

        $comment = new PostComment();
        $comment->content = $request->content;
        $comment->user_id = Auth::id();
        $comment->post_id = $request->post_id;
        $comment->parent_id = $request->parent_id; // Установите parent_id для ответов
        $comment->save();

        return back()->with('success', 'Комментарий отправлен и будет опубликован после проверки модератора');
    }
}
