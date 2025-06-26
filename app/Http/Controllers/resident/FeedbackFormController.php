<?php

namespace App\Http\Controllers\resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeedbackMenu;
use Illuminate\Support\Facades\Auth;

class FeedbackFormController extends Controller
{
    // Tampilkan halaman feedback form
    public function index()
    {
        return view('content.resident.feedback_form');
    }

    // Tangani submit feedback form
    public function store(Request $request) {

      // Validasi input
      $validated = $request->validate([
          'date' => 'required|date',
          'meal_time' => 'required|string',
          'category' => 'required|in:Hygiene,Food Quality,Taste,Others',
          'description' => 'required|string',
      ]);

      // Cari meal_id berdasarkan date dan meal_time
      $meal = \App\Models\Meal::where('date', $validated['date'])
          ->where('meal_type', $validated['meal_time'])
          ->first();

      if (!$meal) {
          return redirect()->back()->withErrors(['meal_time' => 'Meal not found for the selected date and meal time.']);
      }

      // Cek apakah feedback dengan kategori yang sama sudah dikirim oleh resident yang sama untuk meal yang sama
      $existingFeedback = \App\Models\FeedbackMenu::where('resident_id', Auth::guard('resident')->id())
          ->where('meal_id', $meal->id)
          ->where('category', $validated['category'])
          ->first();

      if ($existingFeedback) {
          return redirect()->back()->withErrors([
              'duplicate' => 'You have already submitted feedback for this meal and category.'
          ]);
      }

      // Simpan feedback ke database
      \App\Models\FeedbackMenu::create([
          'date' => $validated['date'],
          'category' => $validated['category'],
          'message' => $validated['description'],
          'resident_id' => Auth::guard('resident')->id(),
          'meal_id' => $meal->id,
      ]);

      return redirect()->route('catering-feedback-form')
          ->with('success', 'Your feedback has been submitted.');
  }
  }
