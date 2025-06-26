<?php

namespace App\Http\Controllers\resident;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use Illuminate\Http\Request;

class MenuList extends Controller
{
  public function index(Request $request)
  {
      // pick the date coming from the <input type="date">.
      // if empty → default to today.
      $date = $request->input('date', now()->toDateString());

      // pull 3 records (breakfast, lunch, dinner) for that date
      // eager-load pic() so we can access $meal->pic->name
      $meals = Meal::with('pic')
          ->whereDate('date', $date)
          ->orderByRaw("FIELD(meal_type,'Breakfast','Lunch','Dinner')")
          ->get();

          return view('content.resident.daily_menu', compact('meals', 'date'));

  }
}