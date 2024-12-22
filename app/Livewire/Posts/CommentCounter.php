<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

/**
 * Handles dynamic comment counter for posts.
 */
class CommentCounter extends Component
{
    public $postId;
    public $commentsCount;

    public function mount($postId)
    {
        $this->postId = $postId;
        $this->updateCommentsCount();
    }

    public function updateCommentsCount()
    {
        $post = Post::findOrFail($this->postId);
        $this->commentsCount = $post->comments()
            ->where('status', 'approved')
            ->whereNull('parent_id')
            ->count();
    }

    public function render()
    {
        return view('livewire.posts.comment-counter');
    }
}
