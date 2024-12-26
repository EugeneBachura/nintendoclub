<?php

namespace App\Livewire\Comments;

use App\Models\PostComment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Moderation extends Component
{
    public $comments = [];
    public $filterStatus = 'all';

    public function mount()
    {
        $this->loadComments();
    }

    public function loadComments()
    {
        $query = PostComment::with(['user', 'post']);

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        $this->comments = $query->orderBy('created_at', 'desc')->get();
    }

    public function updatedFilterStatus($value)
    {
        $this->loadComments();
    }

    public function setStatus($commentId, $status)
    {
        $comment = PostComment::findOrFail($commentId);
        $comment->update([
            'status' => $status,
            'moderated_by' => Auth::id(),
        ]);

        $this->dispatch('notify', 'success', __('messages.comment_status_updated', ['status' => $status]));
        $this->loadComments();
    }

    public function render()
    {
        return view('livewire.comments.moderation')->layout('layouts.app');
    }
}
