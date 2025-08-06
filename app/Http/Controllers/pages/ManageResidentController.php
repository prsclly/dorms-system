<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ManageResidentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $residents = Resident::with('student')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('content.pages.manage-residents', compact('residents', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:residents,email',
            'phone_number' => 'required|string',
            'room_number' => 'required|string',
            'nim' => 'required|string|unique:students,nim',
        ]);

        $formattedName = strtolower(str_replace(' ', '', $request->name));
        $customPassword = 'resident' . $formattedName;

        $resident = Resident::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'room_number' => $request->room_number,
            'password' => Hash::make($customPassword),
        ]);

        Student::create([
            'resident_id' => $resident->id,
            'nim' => $request->nim,
            'name' => $resident->name,
        ]);

        // Kirim email berisi password ke resident
        Mail::send('emails.resident-password', [
            'resident' => $resident,
            'password' => $customPassword
        ], function ($message) use ($resident) {
            $message->to($resident->email)
                    ->subject('Your Account Credentials - Dorm System');
        });

        return redirect()->back()->with('success', 'Resident added successfully and password sent by email.');
        }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:residents,email,' . $id,
            'phone_number' => 'required|string',
            'room_number' => 'required|string',
            'nim' => 'required|string|unique:students,nim,' . $id . ',resident_id',
        ]);

        $resident = Resident::findOrFail($id);
        $resident->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'room_number' => $request->room_number,
        ]);

        // Update NIM di tabel students
        $resident->student->update([
            'nim' => $request->nim,
        ]);

        return redirect()->back()->with('success', 'Resident updated successfully.');
    }

    public function destroy(Resident $resident)
    {
        $resident->delete();
        return redirect()->back()->with('success', 'Resident account deleted.');
    }
}
