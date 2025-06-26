<?php

namespace App\Http\Controllers\point_tracker;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request; // Tambahkan ini

class student_point extends Controller
{
  public function index(Request $request)
  {
    $query = Student::query();

    // Cek apakah ada input pencarian
    if ($request->has('search')) {
      $search = $request->input('search');
      $query->where('name', 'like', "%{$search}%")
        ->orWhere('nim', 'like', "%{$search}%");

    }

    // Ambil hasil query dengan urutan terbaru
    $students = $query->orderBy('updated_at', 'desc')->get();

    return view('content.point_tracker.student_point', compact('students'));
  }
}