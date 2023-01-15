<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function authenticate(Request $req)
    {
        $form = $req->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        $authenticated = Auth::attempt($form);
        if ($authenticated) {
            $req->session()->regenerate();
            return redirect()->intended('/');
        }
        return back()->withErrors(['password' => 'Email / password salah!']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
