<?php

namespace App\Http\Controllers\pages;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ManageAdminController extends Controller
{
    // Tampilkan daftar admin dengan filter search
    public function index(Request $request)
    {
        $search = $request->input('search');

        $admins = Admin::when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
            })
            ->orderBy('name')
            ->paginate(10);

        return view('content.pages.manage-admins', compact('admins', 'search'));
    }

    // Simpan admin baru
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string',
            'email' => 'required|email|unique:admins,email',
        ]);

        $firstName = Str::of($request->name)->explode(' ')[0];
        $plainPassword = 'admin' . ucfirst($firstName) . '123';

        Admin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($plainPassword),
        ]);

        return back()->with('success', 'Admin created successfully. Password: <strong>' . $plainPassword . '</strong>');
    }

    // Update admin
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name'  => 'required|string',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
        ]);

        $admin->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Admin updated.');
    }

    // Hapus admin
    public function destroy(Admin $admin)
    {
        $admin->delete();

        return back()->with('success', 'Admin deleted.');
    }
}
