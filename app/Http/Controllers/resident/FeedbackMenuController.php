<?php

namespace App\Http\Controllers\resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FeedbackMenu;

class FeedbackMenuController extends Controller
{
    public function index(Request $request)
    {
        $residentId = Auth::guard('resident')->id();

        $query = FeedbackMenu::with(['meal.pic'])
            ->where('resident_id', $residentId);

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Filter by meal time
        if ($request->filled('meal_time')) {
            $query->whereHas('meal', function ($q) use ($request) {
                $q->where('meal_type', $request->meal_time);
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $feedbacks = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('content.resident.feedback_menu', compact('feedbacks'));
    }
}
