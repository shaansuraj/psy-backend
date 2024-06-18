<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiControllers\HelperController as HelperController;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends HelperController
{
    public function notifications()
    {
        $data = Notification::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        return $this->sendresponse('true', 'notifications', $data);
    }

    public function notificationsCount()
    {
        $data = Notification::where('user_id', Auth::user()->id)->where('read', 0)->get();

        return $this->sendresponse('true', 'notifications', $data->count());
    }

    public function notificationsUpdate(Request $request)
    {
        $data = Notification::where('user_id', Auth::user()->id)->where('read', 0)->update([
            'read' => 1,
        ]);

        return $this->sendresponse('true', 'notifications', null);
    }
}
