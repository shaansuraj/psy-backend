<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AppUser;


class AppUsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-app-user', ['only' => ['index', 'show', 'search']]);
        $this->middleware('permission:create-app-user', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-app-user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-app-user', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the users
     *
     * @param  \App\Models\AppUser  $model
     * @return \Illuminate\View\View
     */
    public function index(AppUser $model)
    {
        return view('app_users.index', ['users' => $model->orderBy('id', 'DESC')->paginate(20)]);
    }

    public function search()
    {
        $results = AppUser::where(function ($query) {
            $query->where('name', 'like', '%' . $_GET['query'] . '%')
                ->orWhere('email', 'like', '%' . $_GET['query'] . '%')
                ->orWhere('number', 'like', '%' . $_GET['query'] . '%');
        })->orderBy('created_at', 'desc')->paginate(20);

        return view('app_users.index', ['users' => $results]);
    }

    public function create()
    {
        return view('app_users.create');
    }

    public function show($id)
    {
        $user = AppUser::find($id);

        return view('app_users.view', ['user' => $user]);
    }

    public function store(Request $request)
    {
        $user = AppUser::create($request->all());
        $user->assignRole($request->roles);

        if ($user) {
            return back()->withStatus(__('User created successfully'));
        } else {
            return back()->withErrors(__('Something went wrong'));
        }
    }

    public function update(Request $request)
    {
        $validatedUser = Validator::make($request->all(), [
            "email" => "required|email|unique:app_users,email",
            "name" => "required",
            "user_name" => "required|max:30|alpha_dash|unique:app_users,user_name",
        ]);

        if ($validatedUser->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedUser->errors());
        }

        $user = AppUser::where('id', $request->id);
        $save = $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'user_name' => $request->user_name,
            'about' => $request->about,
            'location' => $request->location,
            'number' => $request->number,
        ]);

        if ($save) {
            return back()->withStatus(__('User updated successfully'));
        } else {
            return back()->withErrors(__('Something went wrong'));
        }
    }

    public function edit($id)
    {
        $user = AppUser::find($id);

        return view('app_users.edit', ['user' => $user]);
    }

    public function destroy($id)
    {
        $user = AppUser::find($id);
        if ($user) {
            $user->delete();
            return back()->withStatus(__('App User Deleted Successfully'));
        } else {
            return back()->withErrors(__('Something went wrong'));
        }
    }
}
