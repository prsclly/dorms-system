<?php

namespace App\Http\Controllers\resident;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use Illuminate\Http\Request;

class MenuList extends Controller
{
  public function index(Request $request)
  {
      $date = $request->input('date', now()->toDateString());
      $view = $request->input('view', 'daily');

      if ($view === 'weekly') {
          // Ambil dari tanggal yang dipilih ke 6 hari ke depan
          $startDate = \Carbon\Carbon::parse($date)->startOfDay();
          $endDate = $startDate->copy()->addDays(6);

          $meals = Meal::with('pic')
              ->whereBetween('date', [$startDate, $endDate])
              ->orderBy('date')
              ->orderByRaw("FIELD(meal_type,'Breakfast','Lunch','Dinner')")
              ->get();
      } else {
          // daily (default)
          $meals = Meal::with('pic')
              ->whereDate('date', $date)
              ->orderByRaw("FIELD(meal_type,'Breakfast','Lunch','Dinner')")
              ->get();
      }

      return view('content.resident.daily_menu', compact('meals', 'date', 'view'));
  }

}
