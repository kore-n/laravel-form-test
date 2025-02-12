<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    public function index()
    {
        $posts = Auth::user()->posts()->get()->map(function ($post) {
            $post->title = strtoupper($post->title); // 🔥 直接オブジェクトのプロパティを変更
            return $post;
        });
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(StorePostRequest $request)
    {
        Auth::user()->posts()->create($request->validated());

        return redirect()->route('posts.index')->with('success', '投稿が作成されました！');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }
    
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->validated());

        return redirect()->route('posts.index')->with('success', '投稿が更新されました！');
    }

    public function destroy(Post $post)
    {
        $post->delete();
    
        return redirect()->route('posts.index')->with('success', '投稿が削除されました！');
    }
}
