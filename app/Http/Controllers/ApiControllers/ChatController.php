<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\ApiControllers\HelperController as HelperController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Message;
use App\Models\AppUser;
use App\Events\PrivateChat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;


class ChatController extends HelperController
{
    public function sendMessage(Request $request)
    {

        $validatedData = Validator::make($request->all(), [
            "receiver_id" => "required",
            "content" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $message = new Message();
        $message->sender_id = Auth::user()->id;
        $message->receiver_id = $request->receiver_id;
        $message->content = $request->content;
        $message->save();

        event(new PrivateChat(['message' => $message, 'day' => Carbon::now()->format('l - d/m')]));

        $message->sent = 1;
        $message->received = 0;


        return $this->sendresponse('true', 'sent', ['message' => $message, 'day' => Carbon::now()->format('l - d/m')]);
    }

    public function getMessages(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            "receiver_id" => "required",
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $user_id = Auth::user()->id;
        $receiver_user = AppUser::where('id', $request->receiver_id)->selectbasic()->withCount('followers')->first();
        $receiver_id = $request->receiver_id;

        $messages = Message::where(function ($query) use ($user_id, $receiver_id) {
            $query->where('sender_id', $user_id)
                ->where('receiver_id', $receiver_id);
        })->orWhere(function ($query) use ($user_id, $receiver_id) {
            $query->where('sender_id', $receiver_id)
                ->where('receiver_id', $user_id);
        })->orderBy('created_at', 'asc')->get();

        // Group messages by day names and reverse the messages within each group
        $groupedMessages = $messages->groupBy(function ($message) {
            return $message->created_at->format('l - d/m'); // 'l' format returns the full day name
        })->map(function ($messagesByDay) use ($user_id) {
            return $messagesByDay->map(function ($message) use ($user_id) {
                $message->sent = $message->sender_id == $user_id ? 1 : 0;
                $message->received = $message->receiver_id == $user_id ? 1 : 0;
                return $message;
            });
        });

        // Reverse the order of day groups
        $groupedMessages = $groupedMessages->reverse();

        return $this->sendresponse('true', 'found', ['messages' => $groupedMessages, 'receiver' => $receiver_user]);
    }


    public function getChats(Request $request)
    {
        $perPage = 15;
        $page = $request->page || 1;
        $offset = ($page - 1) * $perPage;

        $user_id = Auth::user()->id;

        $chats = Message::select('sender_id', 'receiver_id')
            ->where('sender_id', $user_id)
            ->orWhere('receiver_id', $user_id)
            ->groupBy('sender_id', 'receiver_id')
            ->get();

        $chatParticipants = collect([]);

        foreach ($chats as $chat) {
            if ($chat->sender_id != $user_id) {
                $chatParticipants->push($chat->sender_id);
            }
            if ($chat->receiver_id != $user_id) {
                $chatParticipants->push($chat->receiver_id);
            }
        }

        $uniqueChatParticipants = $chatParticipants->unique();

        $chatsInfo = [];

        foreach ($uniqueChatParticipants->slice($offset, $perPage) as $participantId) {
            $participant = AppUser::where('id', $participantId)->selectbasic()->first();
            if ($participant) {
                $lastMessage = Message::where(function ($query) use ($user_id, $participantId) {
                    $query->where('sender_id', $user_id)
                        ->where('receiver_id', $participantId);
                })->orWhere(function ($query) use ($user_id, $participantId) {
                    $query->where('sender_id', $participantId)
                        ->where('receiver_id', $user_id);
                })->orderBy('created_at', 'desc')->first();

                $chatsInfo[] = [
                    'participant' => $participant,
                    'last_message' => $lastMessage,
                ];
            }
        }

        $totalChats = count($uniqueChatParticipants);
        $totalPages = ceil($totalChats / $perPage);

        return $this->sendresponse('true', 'found', [
            'data' => $chatsInfo,
            'pagination' => [
                'total' => $totalChats,
                'per_page' => $perPage,
                'current_page' => $page,
                'total_pages' => $totalPages,
            ],
        ]);
    }
}
