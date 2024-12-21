<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Handles dynamic like counter for posts.
 */
class LikeCounter extends Component
{
    public $postId;
    public $likesCount;
    public $isLiked;

    public function mount($postId)
    {
        $this->postId = $postId;
        $this->updateLikes();
    }

    public function updateLikes()
    {
        $post = Post::findOrFail($this->postId);
        $this->likesCount = $post->likes()->count();
        $this->isLiked = auth()->check() && $post->likes()->where('user_id', auth()->id())->exists();
    }

    public function toggleLike()
    {
        if (!Auth::check()) {
            return;
        }

        $post = Post::findOrFail($this->postId);

        if ($this->isLiked) {
            $post->likes()->where('user_id', Auth::id())->delete();
            $this->isLiked = false;
        } else {
            PostLike::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
            ]);
            $this->isLiked = true;
        }

        $this->likesCount = $post->likes()->count();
        $this->dispatch('like-updated', ['postId' => $this->postId]);
    }

    public function render()
    {
        return view('livewire.posts.like-counter');
    }
}
