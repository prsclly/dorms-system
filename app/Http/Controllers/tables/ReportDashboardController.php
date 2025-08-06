<?php

namespace App\Http\Controllers\tables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\FeedbackReport;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Total Data
        $totalReports = Report::count();
        
        //Latest Feedback
        $latestFeedbacks = FeedbackReport::latest('submitted_at')
            ->with(['resident', 'report'])
            ->take(10)
            ->get();

        // Pending Reports
        $pendingReports = Report::whereDoesntHave('task')
            ->orWhereHas('task', function ($q) {
                $q->where('status', 'Pending');
            })
            ->count();

        // Today Log (jumlah report yang dikirim hari ini)
        $todayLog = Report::whereDate('submitted_at', Carbon::today())->count();

        // In Progress
        $inProgressReports = Report::whereHas('task', fn($q) => $q->where('status', 'In Progress'))->count();

        // Trendline per bulan (Januari - Desember tahun ini)
        $trendMonthly = Report::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->mapWithKeys(function ($item) {
            $monthName = Carbon::create()->month($item->month)->format('F');
            return [$monthName => $item->total];
        });

        $availableYears = Report::selectRaw('YEAR(created_at) as year')
        ->distinct()
        ->orderBy('year', 'asc')
        ->pluck('year');

        // Tahun yang dipilih (default: tahun sekarang)
        $selectedYear = request()->get('year', Carbon::now()->year);

        // Data trend bulanan berdasarkan tahun yang dipilih
        $trendMonthly = Report::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->whereYear('created_at', $selectedYear)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->mapWithKeys(function ($item) {
            $monthName = Carbon::create()->month($item->month)->format('F');
            return [$monthName => $item->total];
        });
        
        // Distribusi kategori total (semua waktu)
        $categoryDistributionRaw = Report::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $categoryDistribution = $categoryDistributionRaw->map(function ($count) use ($totalReports) {
            return round(($count / max($totalReports, 1)) * 100, 1);
        });

        $categoryCount = $categoryDistributionRaw;

        // Batas waktu periode
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfSemester = Carbon::now()->month >= 7
            ? Carbon::create(Carbon::now()->year, 7, 1)
            : Carbon::create(Carbon::now()->year, 1, 1);

        // Total log per periode
        $totalLogWeek = Report::where('created_at', '>=', $startOfWeek)->count();
        $totalLogMonth = Report::where('created_at', '>=', $startOfMonth)->count();
        $totalLogSemester = Report::where('created_at', '>=', $startOfSemester)->count();

        // Raw count kategori per periode
        $categoryDistributionWeekRaw = Report::where('created_at', '>=', $startOfWeek)
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $categoryDistributionMonthRaw = Report::where('created_at', '>=', $startOfMonth)
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $categoryDistributionSemesterRaw = Report::where('created_at', '>=', $startOfSemester)
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        // Persentase kategori
        $categoryDistributionWeek = $categoryDistributionWeekRaw->map(fn($count) => round(($count / max($totalLogWeek, 1)) * 100, 1));
        $categoryDistributionMonth = $categoryDistributionMonthRaw->map(fn($count) => round(($count / max($totalLogMonth, 1)) * 100, 1));
        $categoryDistributionSemester = $categoryDistributionSemesterRaw->map(fn($count) => round(($count / max($totalLogSemester, 1)) * 100, 1));

        return view('content.tables.report-dashboard', compact(
            'totalReports',
            'latestFeedbacks',
            'pendingReports',
            'todayLog',
            'inProgressReports',
            'trendMonthly',
            'categoryDistributionWeekRaw',
            'categoryDistributionMonthRaw',
            'categoryDistributionSemesterRaw',
            'categoryDistributionWeek',
            'categoryDistributionMonth',
            'categoryDistributionSemester',
            'availableYears',
            'selectedYear'
        ));
    }
}
