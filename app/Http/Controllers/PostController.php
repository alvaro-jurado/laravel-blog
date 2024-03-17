<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        Post::create([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'user_id' => auth()->id(),
        ]);

        return redirect('/dashboard')->with('success', 'Post creado correctamente.');
    }

    public function create()
    {
        return view('posts.create');
    }

    public function manage()
    {
        return view('posts.manage');
    }
}
