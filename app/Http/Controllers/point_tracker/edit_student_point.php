<?php

namespace App\Http\Controllers\point_tracker;

use App\Http\Controllers\Controller;
use App\Models\Student;

class edit_student_point extends Controller
{
    public function edit($id) // ← ganti dari index ke edit
    {
        $student = Student::with('pointLogs')->findOrFail($id);
        return view('content.point_tracker.edit_student_point', compact('student'));

    }
}