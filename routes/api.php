<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiControllers\AuthController;
use App\Http\Controllers\ApiControllers\UserController;
use App\Http\Controllers\ApiControllers\PostController;
use App\Http\Controllers\ApiControllers\UserFunctionsController;
use App\Http\Controllers\ApiControllers\PostFunctionsController;
use App\Http\Controllers\ApiControllers\ReportController;
use App\Http\Controllers\ApiControllers\CommentsController;
use App\Http\Controllers\ApiControllers\ChatController;
use App\Http\Controllers\ApiControllers\NotificationController;
use App\Http\Controllers\ApiControllers\PusherController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Auth Routes
Route::post("/login", [AuthController::class, "login"]);
Route::post("/logout", [AuthController::class, "logout"]);
Route::post("/email-register", [AuthController::class, "send_email_otp"]);
Route::post("/register", [AuthController::class, "register"]);
Route::post("/social-login", [AuthController::class, "socialLogin"]);
Route::post("/social-register", [AuthController::class, "socialRegister"]);
Route::post("/forgot-password", [AuthController::class, "forgetPassword"]);
Route::post("/reset-password", [AuthController::class, "resetPassword"]);
Route::post("/verify-email", [AuthController::class, "verifyEmail"]);

//Post Share
Route::post("/share", [PostFunctionsController::class, "share"]);

//Guest Post
Route::post("/guest-posts", [PostController::class, "guest_posts"]);

//Post
Route::post("/post", [PostController::class, "post"]);

//Protected Routes
Route::group(["middleware" => ["auth:sanctum"]], function () {

    Route::post('/pusher/auth', [PusherController::class, 'pusherAuth']);

    //Posts Routes
    Route::post("/trending-posts", [PostController::class, "trending"]);
    Route::post("/posts", [PostController::class, "posts"]);
    Route::post("/add-post", [PostController::class, "store"]);
    Route::post("/edit-post", [PostController::class, "edit"]);
    Route::post("/update-post", [PostController::class, "update"]);
    Route::post("/delete-post", [PostController::class, "destroy"]);
    Route::post("/user", [AuthController::class, "user"]);

    //Like & UnLike
    Route::post("/post-like", [PostFunctionsController::class, "like"]);
    Route::post("/post-unlike", [PostFunctionsController::class, "unlike"]);

    //User Routes
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::post("/my-profile", [UserController::class, "profile"]);
    Route::post("/update-profile", [UserController::class, "updateProfile"]);
    Route::post("/upload-profile", [UserController::class, "uploadProfile"]);
    Route::post("/upload-banner", [UserController::class, "uploadBanner"]);
    Route::post("/user-posts", [UserController::class, "userPosts"]);

    //User profile
    Route::post("/user-profile", [UserController::class, "userProfile"]);
    Route::post("/search", [UserFunctionsController::class, "search"]);
    Route::post("/follow-suggestions", [UserFunctionsController::class, "suggestions"]);

    //Follow & Unfollow
    Route::post("/user-follow", [UserFunctionsController::class, "follow"]);
    Route::post("/user-unfollow", [UserFunctionsController::class, "unfollow"]);

    //Followers & Followings
    Route::post("/followers", [UserFunctionsController::class, "followers"]);
    Route::post("/followings", [UserFunctionsController::class, "followings"]);

    //Add Comments & Replies
    Route::post("/add-comment", [CommentsController::class, "addComment"]);
    Route::post("/add-reply", [CommentsController::class, "addReply"]);

    //Fetch Comments & Replies
    Route::post("/fetch-comments", [CommentsController::class, "getComments"]);
    Route::post("/fetch-replies", [CommentsController::class, "getReplies"]);

    //Report
    Route::post("/report", [ReportController::class, "save"]);

    //Notifications
    Route::post("/notifications", [NotificationController::class, "notifications"]);
    Route::post("/notifications-count", [NotificationController::class, "notificationsCount"]);
    Route::post("/notifications-update", [NotificationController::class, "notificationsUpdate"]);

    //Chats
    Route::post('/send-message', [ChatController::class, "sendMessage"]);
    Route::post('/get-messages', [ChatController::class, "getMessages"]);
    Route::post('/get-chats', [ChatController::class, "getChats"]);
});
