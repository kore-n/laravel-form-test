<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // 🔥 各ユーザーの投稿数も取得
        $users = User::withCount('posts')->get();

        return view('users.index', compact('users'));
    }
}