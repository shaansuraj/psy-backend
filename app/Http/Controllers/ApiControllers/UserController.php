<?php

namespace App\Http\Controllers\ApiControllers;

use App\Models\AppUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiControllers\HelperController as HelperController;
use App\Models\Post;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends HelperController
{
    public function profile()
    {
        $user = AppUser::where('id', Auth::user()->id)->first();
        $followerCounts = $user->followers()->count();
        $followingCounts = $user->followings()->count();

        if ($user) {
            return $this->sendresponse('true', 'profile found', ['userProfile' => $user, 'followerCounts' => $followerCounts, 'followingCounts' => $followingCounts]);
        } else {
            return $this->sendresponse('false', 'profile not found', null);
        }
    }

    public function updateProfile(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "user_name" => "unique:app_users,user_name",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $user = AppUser::where('id', Auth::user()->id)->first();

        $save = $user->update($request->all());

        if ($save) {
            return $this->sendresponse('true', 'profile updated', $user);
        } else {
            return $this->sendresponse('false', 'something went wrong', null);
        }
    }


    public function userProfile(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "user_name" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $user = AppUser::where('user_name', $request->user_name)->first();
        $followerCounts = $user->followers()->count();
        $followingCounts = $user->followings()->count();
        $authUser = AppUser::where('id', Auth::user()->id)->first();
        $follows = $authUser->follows($user->id);

        if ($user) {
            return $this->sendresponse('true', 'profile found', ['userProfile' => $user, 'follows' => $follows, 'followerCounts' => $followerCounts, 'followingCounts' => $followingCounts]);
        } else {
            return $this->sendresponse('false', 'something went wrong', null);
        }
    }

    public function userPosts(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "user_id" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $posts = Post::where('user_id', $request->user_id)
            ->fetch()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $posts->each(function ($post) use ($request) {
            $post->liked_by_user = $post->likes()->where('app_user_id', $request->user_id)->exists();
        });

        if ($posts) {
            return $this->sendresponse('true', 'posts found', $posts);
        } else {
            return $this->sendresponse('false', 'something went wrong', null);
        }
    }

    public function uploadProfile(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "profile" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $user = AppUser::where('id', Auth::user()->id)->first();

        if ($user->profile != 'default.png') {
            File::delete(public_path('server/profile/') . $user->profile);
        }

        if ($request->has('profile')) {

            $image = $request->profile;
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = Str::random(12) . '.' . 'png';
            File::put(public_path('server/profile/') . $imageName, base64_decode($image));

            $user->profile = $imageName;
        }

        $save = $user->save();

        if ($save) {
            return $this->sendresponse('true', 'profile updated', $user);
        } else {
            return $this->sendresponse('false', 'something went wrong', null);
        }
    }

    public function uploadBanner(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "banner" => "required|image|mimes:jpg,png,jpeg,gif|max:2048",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $user = AppUser::where('id', Auth::user()->id)->first();

        if ($user->banner != 'default.png') {
            File::delete(public_path('server/banner/') . $user->banner);
        }

        if ($request->hasFile('banner')) {
            $image = $request->file('banner');
            $destinationPath = public_path('server/banner');
            $filename = time() . '.' . $image->getClientOriginalName();
            $image->move($destinationPath, $filename);

            $user->banner = $filename;
        }

        $save = $user->save();

        if ($save) {
            return $this->sendresponse('true', 'banner updated', $user);
        } else {
            return $this->sendresponse('false', 'something went wrong', null);
        }
    }
}
