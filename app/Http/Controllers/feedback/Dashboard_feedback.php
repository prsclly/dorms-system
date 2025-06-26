<?php

namespace App\Http\Controllers\feedback;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeedbackMenu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard_feedback extends Controller
{
    public function index()
    {
        $totalLog = FeedbackMenu::count();
        $thisMonthLog = FeedbackMenu::whereMonth('created_at', Carbon::now()->month)->count();
        $todayLog = FeedbackMenu::whereDate('created_at', Carbon::today())->count();
        $thisWeekLog = FeedbackMenu::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();

        $chartPic = FeedbackMenu::with('meal.pic')
            ->whereHas('meal.pic', function ($query) {
                $query->where('status', 'active');
            })
            ->get()
            ->groupBy(function ($feedback) {
                return $feedback->meal->pic->name ?? 'Unknown';
            })
            ->map(function ($group) {
                return count($group);
            });

        // Distribusi kategori total (semua waktu)
        $categoryDistributionRaw = FeedbackMenu::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $categoryDistribution = $categoryDistributionRaw->map(function ($count) use ($totalLog) {
            return round(($count / max($totalLog, 1)) * 100, 1);
        });

        $categoryCount = $categoryDistributionRaw;

        // Waktu awal per periode
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfSemester = Carbon::now()->month >= 7
            ? Carbon::create(Carbon::now()->year, 7, 1)
            : Carbon::create(Carbon::now()->year, 1, 1);

        // Total log per periode
        $totalLogWeek = FeedbackMenu::where('created_at', '>=', $startOfWeek)->count();
        $totalLogMonth = FeedbackMenu::where('created_at', '>=', $startOfMonth)->count();
        $totalLogSemester = FeedbackMenu::where('created_at', '>=', $startOfSemester)->count();

        // Chart PIC per periode
        $chartPicWeek = FeedbackMenu::with('meal.pic')
            ->where('created_at', '>=', $startOfWeek)
            ->get()
            ->groupBy(fn($f) => $f->meal->pic->name ?? 'Unknown')
            ->map(fn($g) => count($g));

        $chartPicMonth = FeedbackMenu::with('meal.pic')
            ->where('created_at', '>=', $startOfMonth)
            ->get()
            ->groupBy(fn($f) => $f->meal->pic->name ?? 'Unknown')
            ->map(fn($g) => count($g));

        $chartPicSemester = FeedbackMenu::with('meal.pic')
            ->where('created_at', '>=', $startOfSemester)
            ->get()
            ->groupBy(fn($f) => $f->meal->pic->name ?? 'Unknown')
            ->map(fn($g) => count($g));

        // Raw count kategori per periode
        $categoryDistributionWeekRaw = FeedbackMenu::where('created_at', '>=', $startOfWeek)
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $categoryDistributionMonthRaw = FeedbackMenu::where('created_at', '>=', $startOfMonth)
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $categoryDistributionSemesterRaw = FeedbackMenu::where('created_at', '>=', $startOfSemester)
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        // Hitung persentase berdasarkan total log per periode
        $categoryDistributionWeek = $categoryDistributionWeekRaw->map(fn($count) => round(($count / max($totalLogWeek, 1)) * 100, 1));
        $categoryDistributionMonth = $categoryDistributionMonthRaw->map(fn($count) => round(($count / max($totalLogMonth, 1)) * 100, 1));
        $categoryDistributionSemester = $categoryDistributionSemesterRaw->map(fn($count) => round(($count / max($totalLogSemester, 1)) * 100, 1));

        // Trendline per bulan (Januari - Desember tahun ini)
        $trendMonthly = FeedbackMenu::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->mapWithKeys(function ($item) {
            $monthName = Carbon::create()->month($item->month)->format('F');
            return [$monthName => $item->total];
        });

       // Ambil semua tahun unik dari data feedback
        $availableYears = FeedbackMenu::selectRaw('YEAR(created_at) as year')
        ->distinct()
        ->orderBy('year', 'asc')
        ->pluck('year');

        // Tahun yang dipilih (default: tahun sekarang)
        $selectedYear = request()->get('year', Carbon::now()->year);

        // Data trend bulanan berdasarkan tahun yang dipilih
        $trendMonthly = FeedbackMenu::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->whereYear('created_at', $selectedYear)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->mapWithKeys(function ($item) {
            $monthName = Carbon::create()->month($item->month)->format('F');
            return [$monthName => $item->total];
        });



        return view('content.feedback.dashboard_feedback', compact(
            'totalLog',
            'thisMonthLog',
            'todayLog',
            'thisWeekLog',
            'chartPic',
            'categoryDistribution',
            'categoryCount',
            'chartPicWeek',
            'chartPicMonth',
            'chartPicSemester',
            'categoryDistributionWeek',
            'categoryDistributionMonth',
            'categoryDistributionSemester',
            'categoryDistributionWeekRaw',
            'categoryDistributionMonthRaw',
            'categoryDistributionSemesterRaw',
            'trendMonthly',
            'availableYears',
             'selectedYear'

        ));
    }
}
