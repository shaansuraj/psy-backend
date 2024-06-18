<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-post', ['only' => ['index']]);
        $this->middleware('permission:edit-post', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-post', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the users
     *
     * @param  \App\Models\Post  $model
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $posts = Post::withCount('likes')
            ->withCount('comments')
            ->withCount('shares')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('posts.index', ['posts' => $posts]);
    }

    public function search()
    {
        $results = Post::where(function ($query) {
            $query->where('content', 'like', '%' . $_GET['query'] . '%');
        })->withCount('likes')
            ->withCount('comments')
            ->orderBy('created_at', 'desc')->paginate(20);

        return view('posts.index', ['posts' => $results]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $post = Post::where('id', $id)
            ->with(['images' => function ($query) {
                $query->select('name', 'post_id');
            }])->first();

        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $post = Post::find($request->id);

        if ($request->verified == 1) {

            $data = ['user' => 'null', 'msg' => 'Post Verified!'];

            Notification::create([
                'user_id' => $post->user_id,
                'type' => 'post_verified',
                'data' => json_encode($data),
                'read' => 0,
            ]);
        }

        $post->update($request->all());

        if ($post) {
            return back()->withStatus(__('Post Updated Successfully'));
        } else {
            return back()->withErrors(__('Something went wrong'));
        }
    }

    public function show($id)
    {
        $post = Post::where('id', $id)
            ->with(['images' => function ($query) {
                $query->select('name', 'post_id');
            }])
            ->withCount('likes')
            ->withCount('comments')
            ->withCount('shares')->first();

        return view('posts.view', ['post' => $post]);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $post = Post::find($id);
        if ($post) {
            $post->delete();
            return back()->withStatus(__('Post Deleted Successfully'));
        } else {
            return back()->withErrors(__('Something went wrong'));
        }
    }
}
