<?php

namespace App\Http\Controllers\resident;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentPointController extends Controller
{
    public function index()
    {
        $resident = Auth::guard('resident')->user(); // guard khusus resident
        $student = $resident->student;

        if (!$student) {
            abort(403, 'Student data not found.');
        }

        $pointlogs = $student->pointlogs()->latest()->get();

        return view('content.resident.StudentPoint', compact('student', 'pointlogs'));
    }
}
