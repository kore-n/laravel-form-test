<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Auth::user()->posts()->get();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            abort(403, 'ログインしていません'); // 🔥 未認証ならエラーメッセージを返す
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
            'published_at' => 'nullable|date',
        ]);

        Auth::user()->posts()->create($validated);

        return redirect()->route('posts.index')->with('success', '投稿が作成されました！');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }
    
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
            'published_at' => 'nullable|date',
        ]);
    
        $post->update($validated);
    
        return redirect()->route('posts.index')->with('success', '投稿が更新されました！');
    }

    public function destroy(Post $post)
    {
        $post->delete();
    
        return redirect()->route('posts.index')->with('success', '投稿が削除されました！');
    }
}
