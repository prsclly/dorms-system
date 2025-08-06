<?php

namespace App\Http\Controllers\point_tracker;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Carbon\Carbon;

class edit_student_point extends Controller
{
    public function edit($id)
    {
        $student = Student::with('pointLogs')->findOrFail($id);

        // Tambahkan atribut can_edit ke masing-masing pointLog
        foreach ($student->pointLogs as $log) {
            $log->can_edit = Carbon::parse($log->created_at)->diffInMinutes(Carbon::now()) <= 60;
        }

        return view('content.point_tracker.edit_student_point', compact('student'));
    }
}
