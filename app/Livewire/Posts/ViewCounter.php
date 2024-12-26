<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

/**
 * Handles dynamic view counter for posts.
 */
class ViewCounter extends Component
{
    public $postId;
    public $viewsCount;

    public function mount($postId)
    {
        $this->postId = $postId;
        $this->updateViews();
    }

    public function updateViews()
    {
        $post = Post::findOrFail($this->postId);
        $this->viewsCount = $post->views_count;
    }

    public function render()
    {
        return view('livewire.posts.view-counter');
    }
}
