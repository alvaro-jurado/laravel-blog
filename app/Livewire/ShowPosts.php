<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class ShowPosts extends Component
{
    public $newComment;
    public $showComment = false;

    public function render()
    {
        $posts = Post::with('user', 'comments.user')->latest()->get();

        return view('livewire.show-posts', [
            'posts' => $posts
        ]);
    }

    public function addComment($postId)
    {
        $this->validate([
            'newComment' => 'required'
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $postId,
            'content' => $this->newComment
        ]);

        $this->newComment = ''; 

        $this->render();
    }

    public function toggleShowComment()
    {
        $this->showComment = !$this->showComment;
    }

    public function toggleLike($postId)
{
    $post = Post::find($postId);
    
    if ($post->likes()->where('user_id', Auth::id())->exists()) {
        $post->likes()->detach(Auth::id());
    } else {
        $post->likes()->attach(Auth::id());
    }

    $this->render();
}

public function toggleCommentLike($commentId)
{
    $comment = Comment::find($commentId);
    
    if ($comment->likes()->where('user_id', Auth::id())->exists()) {
        $comment->likes()->detach(Auth::id());
    } else {
        $comment->likes()->attach(Auth::id());
    }

    $this->render();
}
}
