<?php

namespace App\Http\Controllers\resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\Resident;

class ResidentProfileController extends Controller
{
    public function index()
    {
        $resident = Auth::guard('resident')->user();
        $student = Student::where('resident_id', $resident->id)->first();

        return view('content.resident.profile', compact('resident', 'student'));
    }

    public function update(Request $request)
    {
        $resident = Auth::guard('resident')->user();

        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:residents,email,' . $resident->id,
            'phone_number' => 'nullable|string|max:20',
            'room_number'  => 'required|string|max:50',
            'nim'          => 'required|string|unique:students,nim,' . $resident->student->id,
            'password'     => 'nullable|string|min:6',
        ]);

        $resident->name = $request->name;
        $resident->email = $request->email;
        $resident->phone_number = $request->phone_number;
        $resident->room_number = $request->room_number;

        if ($request->filled('password')) {
            $resident->password = Hash::make($request->password);
        }

        $resident->save();

        // Update student NIM
        $student = Student::where('resident_id', $resident->id)->first();
        if ($student) {
            $student->nim = $request->nim;
            $student->save();
        }

        return back()->with('success', 'Profile updated successfully.');
    }
}
