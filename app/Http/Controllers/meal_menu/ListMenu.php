<?php

namespace App\Http\Controllers\meal_menu;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Pic; // Tambahkan model Pic
use Illuminate\Http\Request;
use Carbon\Carbon;

class ListMenu extends Controller
{
    public function index(Request $request)
    {
        // ── 1.  Get the week’s first day (default = today’s Monday) ─────────────────
    $weekStart = $request->input('week')
    ? Carbon::parse($request->input('week'))->startOfWeek(Carbon::MONDAY)
    : now()->startOfWeek(Carbon::MONDAY);

$weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

// ── 2.  Pull ALL meals in that 7-day range, eager-load PIC ──────────────────
$meals = Meal::with('pic')
    ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
    ->orderBy('date')
    ->orderByRaw("FIELD(meal_type,'Breakfast','Lunch','Dinner')")
    ->get();

// ── 3.  Group collection by day so Blade can iterate easily ─────────────────
$mealsByDate = $meals->groupBy('date');   //  '2025-05-26' => [ ... ]

$pics = Pic::all();                       // for dropdown in modal

return view('content.meal_menu.menu_list', [
    'mealsByDate' => $mealsByDate,
    'weekStart'   => $weekStart,
    'weekEnd'     => $weekEnd,
    'pics'        => $pics,
]);
    }
}