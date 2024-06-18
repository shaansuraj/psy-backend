<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-user', ['only' => ['index', 'search']]);
        $this->middleware('permission:create-user', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        return view('users.index', ['users' => $model->orderBy('id', 'DESC')->paginate(20)]);
    }

    public function search()
    {
        $results = User::where(function ($query) {
            $query->where('name', 'like', '%' . $_GET['query'] . '%')
                ->orWhere('email', 'like', '%' . $_GET['query'] . '%');
        })->orderBy('created_at', 'desc')->paginate(20);

        return view('users.index', ['users' => $results]);
    }

    public function create()
    {
        $roles = Role::pluck('name')->all();
        return view('users.create', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        $user = User::create($request->all());
        $user->assignRole($request->roles);

        if ($user) {
            return back()->withStatus(__('User created successfully'));
        } else {
            return back()->withErrors(__('Something went wrong'));
        }
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name')->all();
        return view('users.edit', ['user' => $user, 'roles' => $roles, 'userRoles' => $user->roles->pluck('name')->all()]);
    }

    public function update(UserRequest $request)
    {
        $user = User::find($request->id);
        if ($request->filled('password')) {
            $request->merge(['password' => bcrypt($request->password)]);
        }
        unset($request['id']);
        if (!$request->password) {
            unset($request['password']);
        }
        unset($request['password_confirmation']);

        $user->update($request->all());
        $user->syncRoles($request->roles);

        if ($user) {
            return back()->withStatus(__('User Updated Successfully'));
        } else {
            return back()->withErrors(__('Something went wrong'));
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return back()->withStatus(__('User Deleted Successfully'));
        } else {
            return back()->withErrors(__('Something went wrong'));
        }
    }
}
