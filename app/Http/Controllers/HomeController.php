<?php

namespace App\Http\Controllers;

use App\Models\AppUser;
use App\Models\Post;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the latest year from the database
        $latestYear = AppUser::latest('created_at')->value(DB::raw('YEAR(created_at)'));

        // Get user data by month
        $usersByMonth = AppUser::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total_users')
        )
            ->whereYear('created_at', $latestYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Get user data by week
        $usersByWeek = AppUser::select(
            DB::raw('WEEK(created_at) as week'),
            DB::raw('COUNT(*) as total_users')
        )
            ->whereYear('created_at', $latestYear)
            ->groupBy(DB::raw('WEEK(created_at)'))
            ->get();

        // Get user data by day
        $usersByDay = AppUser::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_users')
        )
            ->whereYear('created_at', $latestYear)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        // Initialize labels and data arrays for month, week, and day
        $labelsMonth = [];
        $dataMonth = [];
        $labelsWeek = [];
        $dataWeek = [];
        $labelsDay = [];
        $dataDay = [];

        // Fill data arrays with default values
        for ($i = 1; $i <= 12; $i++) {
            $labelsMonth[] = date('F', mktime(0, 0, 0, $i, 1));
            $dataMonth[] = 0;
        }

        for ($i = 1; $i <= 52; $i++) {
            $labelsWeek[] = 'Week ' . $i;
            $dataWeek[] = 0;
        }

        $currentYear = date('Y');
        $currentMonth = date('m');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $labelsDay[] = date('F d', mktime(0, 0, 0, $currentMonth, $i, $currentYear));
            $dataDay[] = 0;
        }

        // Fill data arrays with values from the database for month, week, and day
        foreach ($usersByMonth as $user) {
            $dataMonth[$user->month - 1] = $user->total_users;
        }

        foreach ($usersByWeek as $user) {
            $dataWeek[$user->week - 1] = $user->total_users;
        }

        foreach ($usersByDay as $user) {
            $date = date('Y-m-d', strtotime($user->date));
            $dayIndex = date('j', strtotime($date)) - 1;
            $dataDay[$dayIndex] = $user->total_users;
        }

        // Prepare data for the chart
        $chartDataMonth = [
            'labels' => $labelsMonth,
            'data' => $dataMonth,
        ];

        $chartDataWeek = [
            'labels' => $labelsWeek,
            'data' => $dataWeek,
        ];

        $chartDataDay = [
            'labels' => $labelsDay,
            'data' => $dataDay,
        ];


        // Get the latest year from the database
        $latestYearPost = Post::latest('created_at')->value(DB::raw('YEAR(created_at)'));

        // Get user data by month
        $postsByMonth = Post::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total_posts')
        )
            ->whereYear('created_at', $latestYearPost)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Get user data by week
        $postsByWeek = Post::select(
            DB::raw('WEEK(created_at) as week'),
            DB::raw('COUNT(*) as total_posts')
        )
            ->whereYear('created_at', $latestYearPost)
            ->groupBy(DB::raw('WEEK(created_at)'))
            ->get();

        // Get user data by day
        $postsByDay = Post::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_posts')
        )
            ->whereYear('created_at', $latestYearPost)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        // Initialize labels and data arrays for month, week, and day
        $labelsPostMonth = [];
        $dataPostMonth = [];
        $labelsPostWeek = [];
        $dataPostWeek = [];
        $labelsPostDay = [];
        $dataPostDay = [];

        // Fill data arrays with default values
        for ($i = 1; $i <= 12; $i++) {
            $labelsPostMonth[] = date('F', mktime(0, 0, 0, $i, 1));
            $dataPostMonth[] = 0;
        }

        for ($i = 1; $i <= 52; $i++) {
            $labelsPostWeek[] = 'Week ' . $i;
            $dataPostWeek[] = 0;
        }

        $currentPostYear = date('Y');
        $currentPostMonth = date('m');
        $daysInPostMonth = cal_days_in_month(CAL_GREGORIAN, $currentPostMonth, $currentPostYear);

        for ($i = 1; $i <= $daysInPostMonth; $i++) {
            $labelsPostDay[] = date('F d', mktime(0, 0, 0, $currentPostMonth, $i, $currentPostYear));
            $dataPostDay[] = 0;
        }

        // Fill data arrays with values from the database for month, week, and day
        foreach ($postsByMonth as $post) {
            $dataPostMonth[$post->month - 1] = $post->total_posts;
        }

        foreach ($postsByWeek as $post) {
            $dataPostWeek[$post->week - 1] = $post->total_posts;
        }

        foreach ($postsByDay as $post) {
            $date = date('Y-m-d', strtotime($post->date));
            $dayIndex = date('j', strtotime($date)) - 1;
            $dataPostDay[$dayIndex] = $post->total_posts;
        }

        // Prepare data for the chart
        $chartPostDataMonth = [
            'labels' => $labelsPostMonth,
            'data' => $dataPostMonth,
        ];

        $chartPostDataWeek = [
            'labels' => $labelsPostWeek,
            'data' => $dataPostWeek,
        ];

        $chartPostDataDay = [
            'labels' => $labelsPostDay,
            'data' => $dataPostDay,
        ];

        $postCounts = Post::all()->count();
        $userCounts = AppUser::all()->count();

        return view('dashboard')->with([
            'userDataMonth' => $chartDataMonth,
            'userDataWeek' => $chartDataWeek,
            'userDataDay' => $chartDataDay,
            'postCounts' => $postCounts,
            'userCounts' => $userCounts,
            'chartPostDataMonth' => $chartPostDataMonth,
            'chartPostDataWeek' => $chartPostDataWeek,
            'chartPostDataDay' => $chartPostDataDay,
        ]);
    }
}
