<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class HelperController extends Controller
{
    public function sendresponse($type, $message, $data)
    {
        if ($type == 'true') {
            return response()->json(
                [
                    "type" => "true",
                    "message" => $message,
                    "data" => $data
                ],
                200
            );
        } elseif ($type == 'false') {
            return response()->json(
                [
                    "type" => "false",
                    "message" => $message,
                    "data" => $data
                ],
                200
            );
        }
    }

    public function createNotifcation($user_id, $type, $user_name, $message)
    {
        $data = ['user' => $user_name, 'msg' => $message];

        Notification::create([
            'user_id' => $user_id,
            'type' => $type,
            'data' => json_encode($data),
            'read' => 0,
        ]);

        return;
    }
}
