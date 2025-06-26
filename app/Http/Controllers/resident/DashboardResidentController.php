<?php

namespace App\Http\Controllers\resident;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\FeedbackMenu;
use App\Models\PointLog;
use App\Models\Report;

class DashboardResidentController extends Controller
{
    public function index()
    {
        $role = 'resident';
        $user = Auth::guard('resident')->user();


    // Ambil 5 feedback terbaru milik resident yg sedang login
    $latestFeedback = FeedbackMenu::with('meal')
    ->where('resident_id', $user->id)
    ->latest('date')
    ->take(3)
    ->get();

        // Ambil total point terakhir untuk student terkait
        $latestPoint = PointLog::where('student_id', $user->id)
        ->latest('date')
        ->value('new_point') ?? 0;

        $latestPointLogs = $user->student
        ? $user->student->pointLogs()->latest('date')->take(5)->get()
        : collect(); // fallback kalo student null

        $reportsWithTask = Report::where('resident_id', $user->id)
    ->whereHas('task')
    ->with('task')
    ->get();

$reportsWithoutTaskCount = Report::where('resident_id', $user->id)
    ->doesntHave('task')
    ->count();

$reportStatusCount = $reportsWithTask
    ->groupBy(fn ($report) => $report->task->status)
    ->map(fn ($group) => $group->count())
    ->toBase(); // agar bisa ditambah manual

// Tambahkan status Pending manual
$reportStatusCount['Pending'] = $reportsWithoutTaskCount;


// Ambil 3 laporan terbaru milik resident login
$latestReports = Report::with('task')
    ->where('resident_id', $user->id)
    ->latest('submitted_at')
    ->take(3)
    ->get();


    return view('content.resident.dashboard-resident', compact(
      'user',
      'role',
      'latestFeedback',
      'latestPoint',
      'latestPointLogs',
      'latestReports',
      'reportStatusCount' // tambahkan ini
  ));
    }
}
