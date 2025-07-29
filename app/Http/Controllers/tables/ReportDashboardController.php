<?php

namespace App\Http\Controllers\tables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\FeedbackReport;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class ReportDashboardController extends Controller
{
    public function index()
    {
        // Total Reports
        $totalReports = Report::count();

        // Total Feedbacks
        $totalFeedbacks = FeedbackReport::count();

        // Today Reports with Pending Tasks
        $todayPendingReports = Report::whereDate('created_at', today())
            ->whereHas('task', function ($query) {
                $query->where('status', 'Pending');
            })
            ->count();

        // In Progress Reports
        $inProgressReports = Report::whereHas('task', function ($query) {
            $query->where('status', 'In Progress');
        })->count();

        // Category Distribution
        $categoryDistribution = Report::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();

        // Monthly Trendline
        $monthlyReports = Report::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Year Filter (optional enhancement)
        $selectedYear = request('year', now()->year);

        $monthlyReports = Report::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as total')
            )
            ->whereYear('created_at', $selectedYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $availableYears = Report::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('content.tables.report-dashboard', compact(
            'totalReports',
            'totalFeedbacks',
            'todayPendingReports',
            'inProgressReports',
            'categoryDistribution',
            'monthlyReports',
            'selectedYear',
            'availableYears'
        ));
    }
}
