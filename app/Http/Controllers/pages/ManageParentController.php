<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parents;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ManageParentController extends Controller
{
    // Tampilkan daftar parent + data student untuk form
    public function index(Request $request)
    {
        $search = $request->input('search');

        $parents = Parents::with('students')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
            })
            ->orderBy('name')
            ->paginate(10);

        // Anak-anak yang BELUM punya parent (untuk form ADD)
        $students = Student::whereDoesntHave('parents')->orderBy('name')->get();

        // Semua anak (untuk form EDIT)
        $allStudents = Student::orderBy('name')->get();

        return view('content.pages.manage-parents', compact('parents', 'students', 'allStudents', 'search'));
    }

    // Tambah parent baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:parents,email',
            'student_ids' => 'required|array|min:1',
        ]);

        $name = strtolower(str_replace(' ', '', $request->name));
        $plainPassword = 'parents' . $name;

        $parent = Parents::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($plainPassword),
        ]);

        $parent->students()->attach($request->student_ids);

        // Kirim email
        Mail::send('emails.parents-password', [
            'parent' => $parent,
            'password' => $plainPassword
        ], function ($message) use ($parent) {
            $message->to($parent->email)
                    ->subject('Your Parent Account - Dorm System');
        });

        return back()->with('success', 'Parent created and linked. Login credentials sent via email.');
    }

    // Update data parent
    public function update(Request $request, Parents $parent)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:parents,email,' . $parent->id,
            'student_ids' => 'required|array|min:1',
        ]);

        $parent->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $parent->students()->sync($request->student_ids);

        return back()->with('success', 'Parent updated successfully.');
    }

    // Hapus parent
    public function destroy(Parents $parent)
    {
        $parent->students()->detach();
        $parent->delete();

        return back()->with('success', 'Parent deleted successfully.');
    }
}
