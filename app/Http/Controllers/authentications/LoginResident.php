<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Resident;

class LoginResident extends Controller
{
    // Tampilkan halaman login resident
    public function index()
    {
        return view('content.authentications.auth-login-resident');
    }

    // Proses login resident
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('resident')->attempt($credentials)) {
            $request->session()->put('user_role', 'resident');
            return redirect()->route('resident.dashboard');
        }

        return back()->with('error', 'Email or password is incorrect.');
    }


    // Logout resident
    public function logout(Request $request)
    {
        Auth::guard('resident')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('resident.login');
    }
}
