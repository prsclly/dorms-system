<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Parents;

class ParentAuthController extends Controller
{
    public function index()
    {
        return view('content.authentications.auth-login-parent');
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('parent')->attempt($credentials)) {
            return redirect()->route('parent.dashboard');
        }


        return back()->with('error', 'Email or password is incorrect.');

    }

    public function logout(Request $request)
    {
        auth('parent')->logout(); // logout guard parent

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('parent.login');
    }
}
