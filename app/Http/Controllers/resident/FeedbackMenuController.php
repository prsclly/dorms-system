<?php

namespace App\Http\Controllers\resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FeedbackMenu;
use App\Models\Meal;

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

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'meal_time' => 'required|in:Breakfast,Lunch,Dinner',
            'category' => 'required|in:Taste,Hygiene,Food Quality,Others',
            'message' => 'required|string|min:25|max:150',
        ]);

        $residentId = Auth::guard('resident')->id();

        $meal = Meal::where('meal_type', $request->meal_time)
                    ->whereDate('date', $request->date)
                    ->first();

        FeedbackMenu::create([
            'resident_id' => $residentId,
            'meal_id' => $meal?->id,
            'date' => $request->date,
            'meal_time' => $request->meal_time,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Feedback submitted successfully!');
    }

    public function edit($id)
{
    $feedback = FeedbackMenu::where('id', $id)
        ->where('resident_id', Auth::guard('resident')->id())
        ->firstOrFail();

    // Batasi hanya feedback yang dibuat ≤ 1 jam yang bisa diedit
    if (now()->diffInMinutes($feedback->created_at) > 60) {
        return redirect()->back()->with('error', 'Editing time has expired.');
    }

    return view('content.resident.feedback_edit', compact('feedback'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'category' => 'required|in:Taste,Hygiene,Food Quality,Others',
        'message' => 'required|string|min:25|max:150',
    ]);

    $feedback = FeedbackMenu::where('id', $id)
        ->where('resident_id', Auth::guard('resident')->id())
        ->firstOrFail();

    if (now()->diffInMinutes($feedback->created_at) > 60) {
        return redirect()->back()->with('error', 'Editing time has expired.');
    }

    $feedback->update([
      'category' => $request->category,
      'message' => $request->message,
      'created_at' => now(), // reset waktu ke waktu edit
      'is_edited' => true,
  ]);


    return redirect()->route('catering-feedback-history')->with('success', 'Feedback updated successfully!');
}

public function destroy($id)
{
    $feedback = FeedbackMenu::where('id', $id)
        ->where('resident_id', Auth::guard('resident')->id())
        ->firstOrFail();

    if (now()->diffInMinutes($feedback->created_at) > 60) {
        return redirect()->back()->with('error', 'Deletion time has expired.');
    }

    $feedback->delete();

    return redirect()->route('catering-feedback-history')->with('success', 'Feedback deleted successfully!');
}

}
