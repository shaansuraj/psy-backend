<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view-reports', ['only' => ['index']]);
        $this->middleware('permission:delete-reports', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('reports.index', ['reports' => Report::orderBy('id', 'DESC')->with('user', 'byUser')->paginate(20)]);
    }

    public function destroy($id)
    {
        $report = Report::find($id);
        if ($report) {
            $report->delete();
            return back()->withStatus(__('Report Deleted Successfully'));
        } else {
            return back()->withErrors(__('Something went wrong'));
        }
    }
}
