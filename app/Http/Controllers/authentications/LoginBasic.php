<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class LoginBasic extends Controller
{
    // Tampilkan halaman login
    public function index()
    {
        return view('content.authentications.auth-login-basic');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'role'     => 'required|in:admin,technician'
        ]);

        if ($request->role === 'admin') {
            $admin = \App\Models\Admin::where('email', $request->email)->first();
            if ($admin && $request->password === $admin->password) {
                Auth::guard('admin')->login($admin);
                $request->session()->put('user_role', 'admin');
                return redirect()->route('admin.dashboard');
            }

        } else {
            $technician = \App\Models\Technician::where('email', $request->email)->first();
            if ($technician && $request->password === $technician->password) {
                Auth::guard('technician')->login($technician);
                $request->session()->put('user_role', 'technician');
                return redirect()->route('admin.dashboard');
            }

        }

        return back()->with('error', 'Email or password is incorrect.');
    }


    // Logout admin
    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth-login-basic');
    }
}
