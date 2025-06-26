<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Resident;
use App\Models\Report;
use App\Models\Task;
use App\Models\Meal;
use Carbon\Carbon;
use App\Models\PointLog;
use App\Models\FeedbackMenu;




class Analytics extends Controller
{
    public function index()
    {
        $role = session('user_role', 'guest');

        if ($role === 'admin') {
            $user = Auth::guard('admin')->user();
        } elseif ($role === 'technician') {
            $user = Auth::guard('technician')->user();
        } else {
            $user = null;
        }

        // Ambil jumlah total residents
        $activeResidentCount = Resident::count();

        $pendingReportsCount = Report::whereDoesntHave('task')->count();

        $todayMeals = Meal::where('date', Carbon::today())->get()->groupBy('meal_type');

        $latestPointLogs = PointLog::with('student')
    ->orderByDesc('created_at')
    ->take(5)
    ->get();

            // Ambil 5 feedback catering terbaru
    $latestCateringFeedbacks = FeedbackMenu::latest()
    ->take(5)
    ->get();


$latestDormReports = Report::latest()
->take(5)
->get();



        return view('content.dashboard.dashboards-analytics', compact(
          'user',
          'role',
          'activeResidentCount',
          'pendingReportsCount',
          'todayMeals',
          'latestPointLogs',
          'latestCateringFeedbacks',
          'latestDormReports'
      ));

    }
}
