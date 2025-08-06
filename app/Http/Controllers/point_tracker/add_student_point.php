<?php

namespace App\Http\Controllers\point_tracker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class add_student_point extends Controller
{
    public function index()
    {
        return view('content.point_tracker.add_student_point');
    }
}
