<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;
    protected $table = 'post_comments';
    protected $fillable = ['post_id', 'user_id', 'parent_id', 'content'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь с родительским комментарием
    public function parent()
    {
        return $this->belongsTo(PostComment::class, 'parent_id');
    }

    // Связь с дочерними комментариями
    public function replies()
    {
        return $this->hasMany(PostComment::class, 'parent_id');
    }

    // Связь с лайками комментария
    public function likes()
    {
        return $this->hasMany(PostCommentLike::class, 'comment_id');
    }

    public function design()
    {
        return User::where('id', $this->user_id)->first()->design()->first() ?? null;
    }
}
