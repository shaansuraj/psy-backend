<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiControllers\HelperController as HelperController;

class ReportController extends HelperController
{
    public function save(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'by_user_id' => 'required',
            'user_id' => 'required',
            'reported_item_id' => 'required',
            'reported_item_type' => 'required',
            'reason' => 'required',
        ]);

        if ($validatedData->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedData->errors());
        }

        $report = Report::create($request->all());

        return $this->sendresponse('true', 'report submitted successfully', $report);
    }

    // public function index()
    // {
    //     $reports = Report::with(['byUser:id,name,user_name', 'user:id,name,user_name'])
    //         ->latest()
    //         ->get();

    //     return response()->json(['reports' => $reports], 200);
    // }
}
