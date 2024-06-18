<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes(['register' => false]);

Route::get('/dashboard', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    //User
    Route::get('user/search', 'App\Http\Controllers\AdminControllers\UsersController@search')->name('user.search');
    Route::resource('user', 'App\Http\Controllers\AdminControllers\UsersController', ['except' => ['show']]);

    //App User
    Route::get('appuser/search', 'App\Http\Controllers\AdminControllers\AppUsersController@search')->name('appuser.search');
    Route::resource('appuser', 'App\Http\Controllers\AdminControllers\AppUsersController');

    //User Roles
    Route::resource('role', 'App\Http\Controllers\AdminControllers\RoleController', ['except' => ['show']]);

    //Posts
    Route::get('post/search', 'App\Http\Controllers\AdminControllers\PostController@search')->name('post.search');
    Route::resource('post', 'App\Http\Controllers\AdminControllers\PostController', ['except' => ['create']]);

    //Reports
    Route::get('reports', 'App\Http\Controllers\AdminControllers\ReportController@index')->name('reports');
    Route::get('report-destroy/{id}', 'App\Http\Controllers\AdminControllers\ReportController@destroy')->name('report.destroy');

    //Post Verifications
    Route::get('verfiy-post/search', 'App\Http\Controllers\AdminControllers\PostVerifyController@search')->name('verifypost.search');
    Route::get('verfiy-post', 'App\Http\Controllers\AdminControllers\PostVerifyController@index')->name('post.verify');

    //User Profile
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\AdminControllers\ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\AdminControllers\ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\AdminControllers\ProfileController@password']);
});
