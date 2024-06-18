<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiControllers\HelperController as HelperController;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\AppUser;
use App\Models\NestedReply;
use Illuminate\Pagination\LengthAwarePaginator;


class CommentsController extends HelperController
{
    public function getComments(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "post_id" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $comments = Comment::where('post_id', $request->post_id)
            ->with('user:id,user_name,profile')
            ->withCount('nestedReplies')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $this->sendresponse('true', 'comments found', $comments);
    }

    public function getReplies(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "post_id" => "required",
            "comment_id" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        // $replies = NestedReply::where('post_id', $request->post_id)
        //     ->where('comment_id', $request->comment_id)
        //     ->with('user:id,user_name,profile', 'replies')->get();

        $perPage = 10; // Number of items per page
        $page = $request->has('page') ? $request->page : 1; // Current page

        $replies = NestedReply::where('post_id', $request->post_id)
            ->where('comment_id', $request->comment_id)
            ->whereNull('parent_id')
            ->with('user:id,user_name,profile')->get();

        $mergedReplies = collect();

        foreach ($replies as $reply) {
            $mergedReply = $reply->replicate();
            $mergedReply->id = $reply->id;
            $mergedReply->created_at = $reply->created_at;
            $mergedReply->setRelation('replies', null);
            $mergedReplies->push($mergedReply);

            if ($reply->replies->isNotEmpty()) {
                $this->mergeReplies($reply->replies, $mergedReplies);
            }
        }

        $paginator = new LengthAwarePaginator(
            $mergedReplies->forPage($page, $perPage),
            $mergedReplies->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return $this->sendresponse('true', 'replies found', $paginator);
    }

    private function mergeReplies($replies, &$mergedReplies)
    {
        foreach ($replies as $reply) {
            $mergedReply = $reply->replicate();
            $mergedReply->id = $reply->id;
            $mergedReply->user = $reply->getUser($reply->user_id)[0];
            $mergedReply->created_at = $reply->created_at;
            $mergedReply->setRelation('replies', null);
            $mergedReplies->push($mergedReply);

            if ($reply->replies->isNotEmpty()) {
                $this->mergeReplies($reply->replies, $mergedReplies);
            }
        }
    }


    public function addComment(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "post_id" => "required",
            "content" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $comment = new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->post_id = $request->post_id;
        $comment->content = $request->content;
        $save = $comment->save();

        $post = Post::where('id', $request->post_id)->first();
        $user = AppUser::where('id', $request->user_id)->first();

        if ($post->user_id != $request->user_id) {
            $this->createNotifcation($post->user_id, 'comment', $user->user_name, 'commented on your post');
        }

        if ($save) {
            return $this->sendresponse('true', 'comment added successfully', $comment);
        } else {
            return $this->sendresponse('false', 'something went wrong', null);
        }
    }

    public function addReply(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "post_id" => "required",
            "comment_id" => "required",
            "content" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $reply = new NestedReply;
        $reply->user_id = Auth::user()->id;
        $reply->post_id = $request->post_id;
        $reply->comment_id = $request->comment_id;
        $reply->parent_id = $request->parent_id;
        $reply->content = $request->content;
        $save = $reply->save();

        $comment = Comment::where('id', $request->comment_id)->first();
        $user = AppUser::where('id', $request->user_id)->first();

        if ($comment->user_id != $request->user_id) {
            $this->createNotifcation($comment->user_id, 'reply', $user->user_name, 'replied to you');
        }

        if ($save) {
            return $this->sendresponse('true', 'reply added successfully', $reply);
        } else {
            return $this->sendresponse('false', 'something went wrong', null);
        }
    }
}
