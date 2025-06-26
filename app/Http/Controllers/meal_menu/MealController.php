<?php

namespace App\Http\Controllers\meal_menu;   // ⬅ namespace sesuai folder

use App\Http\Controllers\Controller;      // ⬅ perlu di-import
use Illuminate\Http\Request;
use App\Models\Meal;

class MealController extends Controller
{
    public function picForMeal(Request $request)
    {
        $request->validate([
            'date'      => 'required|date',
            'meal_time' => 'required|in:Breakfast,Lunch,Dinner',
        ]);

        $meal = Meal::with('pic')
                    ->whereDate('date', $request->date)
                    ->where('meal_type', $request->meal_time)
                    ->first();

        if (!$meal || !$meal->pic) {
            return response()->json([
                'found'     => false,
                'pic_name'  => null,
                'message'   => 'PIC not found for the selected meal.',
            ], 404);
        }

        return response()->json([
            'found'     => true,
            'pic_name'  => $meal->pic->name,
            'pic_id'    => $meal->pic->id,
        ]);
    }
}