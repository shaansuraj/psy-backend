<?php

namespace App\Http\Controllers\ApiControllers;

use File;
use App\Models\AppUser;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiControllers\HelperController as HelperController;

class PostController extends HelperController
{
    public function getAuthPosts($id, $type)
    {
        $posts = Post::fetch();

        if ($type === 'trending') {
            $posts = $posts->orderByDesc('likes_count')->paginate(10);
        } else {
            $posts = $posts->whereIn('user_id', AppUser::find($id)->followings()->pluck('id'))->orderBy('created_at', 'desc')->paginate(10);
        }

        $posts->each(function ($post) use ($id) {
            $post->liked_by_user = $post->likes()->where('app_user_id', $id)->exists();
        });

        return $posts;
    }

    public function guest_posts()
    {
        $posts = Post::fetch()
            ->orderByDesc('likes_count')
            ->take(10)
            ->paginate(10);

        return $this->sendresponse('true', 'posts found', $posts);
    }

    public function trending()
    {
        $posts = $this->getAuthPosts(Auth::user()->id, 'trending');

        return $this->sendresponse('true', 'posts found', $posts);
    }

    public function posts()
    {
        $posts = $this->getAuthPosts(Auth::user()->id, 'null');

        return $this->sendresponse('true', 'posts found', $posts);
    }

    public function post(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "uuid" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $post = Post::where('uuid', $request->uuid)
            ->fetch()
            ->get();

        return $this->sendresponse('true', 'found', $post);
    }

    public function edit(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "uuid" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $post = Post::where('uuid', $request->uuid)
            ->fetch()
            ->get();

        if ($post->user_id != Auth::user()->id) {
            return $this->sendresponse('false', 'unauthorised', 'null');
        }

        return $this->sendresponse('true', 'found', $post);
    }

    public function update(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "content" => "required",
            "post_id" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $post = Post::where('id', $request->post_id)->first();


        if (Auth::user()->id != $post->user_id) {
            return $this->sendresponse('false', 'unauthorised', 'null');
        }

        if ($request->has('oldimages')) {
            // Existing images associated with the post
            $existingImages = $post->images->pluck('name')->toArray();

            // Identify removed images
            $removedImages = array_diff($existingImages, $request->oldimages);

            foreach ($removedImages as $imager) {
                // Delete Image model and remove from storage
                Image::where('name', $imager)->first()->delete();

                File::delete(public_path('server/posts/images/' . $imager));
            }
        }

        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {
                $destinationPath = public_path('server/posts/images');
                $filename = time() . rand(1, 99) . '.' . $image->getClientOriginalName();
                $image->move($destinationPath, $filename);

                Image::create([
                    'name' => $filename,
                    'post_id' => $post->id,
                ]);
            }
        }

        $save = $post->update([
            'content' => $request->content,
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'verified' => 1,
        ]);

        if ($save) {
            return $this->sendresponse('true', 'post updated successfully', $post);
        } else {
            return $this->sendresponse('false', 'something went wrong', null);
        }
    }

    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "content" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $user = AppUser::where('id', Auth::user()->id)->first();

        if ($user->provider == 'web' && $user->email_verified_at == null) {
            return $this->sendresponse('false', 'email not verified', null);
        }


        $post = Post::create([
            'uuid' => Str::uuid(),
            'user_id' => $user->id,
            'content' => $request->content,
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'verified' => 0,
        ]);

        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {
                $destinationPath = public_path('server/posts/images');
                $filename = time() . rand(1, 99) . '.' . $image->getClientOriginalName();
                $image->move($destinationPath, $filename);

                Image::create([
                    'name' => $filename,
                    'post_id' => $post->id,
                ]);
            }
        }


        if ($post) {
            return $this->sendresponse('true', 'post created successfully', $post);
        } else {
            return $this->sendresponse('false', 'something went wrong', null);
        }
    }

    public function destroy(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "post_id" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $post = Post::where('id', $request->post_id)->first();
        if (Auth::user()->id === $post->user_id) {
            $delete = $post->delete();
            if ($delete) {
                return $this->sendresponse('true', 'post deleted successfully', null);
            } else {
                return $this->sendresponse('false', 'something went wrong', null);
            }
        } else {
            return $this->sendresponse('false', 'unauthorised', null);
        }
    }
}
