<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Resident;
use App\Models\Report;
use App\Models\Task;
use App\Models\FeedbackReport;

class TechnicianDashboardController extends Controller
{
    public function index()
    {
        $technician = Auth::guard('technician')->user();

        $totalTask = Task::where('technician_id', $technician->id)->count();

        $assignedTask = Task::where('technician_id', $technician->id)
            ->where('status', 'Assigned')
            ->count();

        $assignedToday = Task::where('technician_id', $technician->id)
            ->where('status', 'Assigned')
            ->whereDate('created_at', today())
            ->count();

        $inProgressTask = Task::where('technician_id', $technician->id)
            ->where('status', 'In Progress')
            ->count();

        // Ambil feedback yang terkait dengan report yang ditangani teknisi ini
        $reportIds = Task::where('technician_id', $technician->id)->pluck('report_id');

        $latestFeedbacks = FeedbackReport::with('report')
            ->whereIn('report_id', $reportIds)
            ->latest('submitted_at')
            ->take(10)
            ->get();
        
        $latestTasks = Task::with('report')
            ->where('technician_id', $technician->id)
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('content.technician.dashboard', [
            'user' => $technician,
            'role' => 'technician',
            'totalTask' => $totalTask,
            'assignedTask' => $assignedTask,
            'assignedToday' => $assignedToday,
            'inProgressTask' => $inProgressTask,
            'latestFeedbacks' => $latestFeedbacks,
            'latestTasks'=> $latestTasks
        ]);
    }

}
