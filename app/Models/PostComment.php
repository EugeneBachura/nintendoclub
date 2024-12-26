<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;
    protected $table = 'post_comments';
    protected $fillable = ['post_id', 'user_id', 'parent_id', 'content', 'status', 'moderated_by'];

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_SPAM = 'spam';

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(PostComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(PostComment::class, 'parent_id');
    }

    public function likes()
    {
        return $this->hasMany(PostCommentLike::class, 'comment_id');
    }

    public function design()
    {
        return $this->user()?->design();
    }

    public function getDesignAttribute()
    {
        return $this->user?->design()->first();
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
            self::STATUS_SPAM,
        ];
    }
}
