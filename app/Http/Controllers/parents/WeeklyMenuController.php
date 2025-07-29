<?php

namespace App\Http\Controllers\parents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Meal;

class WeeklyMenuController extends Controller
{
    public function index(Request $request)
    {
        $weekParam = $request->get('week');
        $weekStart = $weekParam ? Carbon::parse($weekParam)->startOfWeek() : now()->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();

        $meals = Meal::with('pic')
            ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->orderBy('date')
            ->orderByRaw("FIELD(meal_type, 'Breakfast', 'Lunch', 'Dinner')")
            ->get()
            ->groupBy('date');

        return view('content.parents.weekly-menu', [
            'mealsByDate' => $meals,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
        ]);
    }
}
