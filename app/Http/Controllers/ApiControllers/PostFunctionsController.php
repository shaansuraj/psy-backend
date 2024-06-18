<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\AppUser;
use App\Models\PostShare;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiControllers\HelperController as HelperController;

class PostFunctionsController extends HelperController
{

    public function like(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "post_id" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $user = AppUser::where('id', Auth::user()->id)->first();
        $post = Post::where('id', $request->post_id)->first();

        if ($post->user_id != $request->user_id) {
            $this->createNotifcation($post->user_id, 'like', $user->user_name, 'liked your post');
        }

        $user->likes()->attach($post);

        return $this->sendresponse('true', 'liked', 'null');
    }


    public function unlike(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "post_id" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $user = AppUser::where('id', Auth::user()->id)->first();
        $post = Post::where('id', $request->post_id)->first();

        $user->likes()->detach($post);

        return $this->sendresponse('true', 'unliked', 'null');
    }


    public function share(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "post_id" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $post = Post::where('id', $request->post_id)->first();

        $share = new PostShare;
        $share->post_id = $post->id;
        $save = $share->save();

        if ($save) {
            return $this->sendresponse('true', 'shared', 'null');
        } else {
            return $this->sendresponse('false', 'something went wrong', 'null');
        }
    }
}
