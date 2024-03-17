<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class ManagePosts extends Component
{
    public $posts;
    public $postId;
    public $title;
    public $content;
    public $isEditModalOpen = false;
    public $isConfirmDeleteModalOpen = false;

    protected $rules = [
        'title' => 'required',
        'content' => 'required',
    ];

    public function mount()
    {
        $this->loadPosts();
    }

    public function loadPosts()
    {
        if (Auth::user()->hasRole('admin')) {
            $this->posts = Post::all();
        } else {
            $this->posts = Post::where('user_id', Auth::id())->get();
        }
    }

    public function edit($postId)
    {
        $post = Post::findOrFail($postId);
        $this->postId = $postId;
        $this->title = $post->title;
        $this->content = $post->content;

        $this->isEditModalOpen = true;
    }

    public function update()
    {
        $this->validate();

        $post = Post::findOrFail($this->postId);
        $post->update([
            'title' => $this->title,
            'content' => $this->content,
        ]);

        $this->closeEditModal();
        $this->loadPosts();
    }

    public function confirmPostDeletion($postId)
    {
        $this->postId = $postId;
        $this->isConfirmDeleteModalOpen = true;
    }

    public function delete()
    {
        Post::find($this->postId)->delete();
        $this->isConfirmDeleteModalOpen = false;
        $this->loadPosts();
    }

    public function closeEditModal()
    {
        $this->reset(['title', 'content', 'postId']);
        $this->isEditModalOpen = false;
    }

    public function render()
    {
        return view('livewire.manage-posts');
    }
}
