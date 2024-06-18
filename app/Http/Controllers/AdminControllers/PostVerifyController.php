<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostVerifyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:verify-post', ['only' => ['index']]);
    }

    public function index()
    {
        $posts = Post::where('verified', 0)
            ->withCount('likes')
            ->withCount('comments')
            ->withCount('shares')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('verify_post.index', ['posts' => $posts]);
    }

    public function search()
    {
        $results = Post::where('verified', 0)->where(function ($query) {
            $query->where('content', 'like', '%' . $_GET['query'] . '%');
        })->withCount('likes')
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('verify_post.index', ['posts' => $results]);
    }
}
