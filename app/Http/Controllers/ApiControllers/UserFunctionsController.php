<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Http\Request;
use App\Models\AppUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiControllers\HelperController as HelperController;


class UserFunctionsController extends HelperController
{
    public function search(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "search" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $auth_user_id = Auth::user()->id;
        $users = AppUser::where(function ($query) use ($request) {
            $query->where('user_name', 'like', '%' . $request->search . '%')
                ->orWhere('name', 'like', '%' . $request->search . '%');
        })
            ->where('id', '!=', $auth_user_id)
            ->get()->take(20);


        if ($users->count() != 0) {
            return $this->sendresponse('true', 'results found', $users);
        } elseif ($users->count() == 0) {
            return $this->sendresponse('true', 'no results found', null);
        } else {
            return $this->sendresponse('false', 'something went wrong', null);
        }
    }

    public function suggestions()
    {
        $user_id = Auth::user()->id;

        $users = AppUser::whereDoesntHave('followers',  function ($query) use ($user_id) {
            $query->where('follower_id', $user_id);
        })->where('id', '!=', $user_id)->withCount('followers')->orderByDesc('followers_count')->take(6)->get();


        if ($users) {
            return $this->sendresponse('true', 'users found', $users);
        } else {
            return $this->sendresponse('false', 'something went wrong', null);
        }
    }

    public function follow(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "user_follow_id" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $authUser = AppUser::where('id', Auth::user()->id)->first();
        $user_follow = AppUser::where('id', $request->user_follow_id)->first();

        $this->createNotifcation($user_follow->id, 'follow', $authUser->user_name, 'started following you');

        $authUser->followings()->attach($user_follow);

        return $this->sendresponse('true', 'followed', 'null');
    }

    public function unfollow(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "user_follow_id" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $authUser = AppUser::where('id', Auth::user()->id)->first();
        $user_follow = AppUser::where('id', $request->user_follow_id)->first();

        $authUser->followings()->detach($user_follow);

        return $this->sendresponse('true', 'unfollowed', 'null');
    }

    public function followings(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "user_id" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $user = AppUser::where('id', $request->user_id)->first();

        $data = $user->followings()->paginate(10);

        return $this->sendresponse('true', 'found', $data);
    }

    public function followers(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "user_id" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $user = AppUser::where('id', $request->user_id)->first();

        $data = $user->followers()->paginate(10);

        return $this->sendresponse('true', 'found', $data);
    }
}
