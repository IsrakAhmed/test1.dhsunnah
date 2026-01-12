<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function adminLogin()
    {
        return view('auth.admin-login');
    }

    public function userLogin()
    {
        return view('auth.user-login');
    }

    public function adminAuthenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            
            if (auth()->user()->role === 'admin' && auth()->user()->status === 'active') {
                return redirect()->intended('admin/dashboard');
            }

            if (auth()->user()->status !== 'active') {
                auth()->logout();
                return back()->withErrors([
                    'email' => 'Your account is deactivated.',
                ])->onlyInput('email');
            }

            auth()->logout();
            return back()->withErrors([
                'email' => 'Access denied. Admins only.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function userAuthenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            if (auth()->user()->role === 'user' && auth()->user()->status === 'active') {
                return redirect()->intended('user/dashboard');
            }

            if (auth()->user()->status !== 'active') {
                 auth()->logout();
                return back()->withErrors([
                    'email' => 'Your account is deactivated.',
                ])->onlyInput('email');
            }
            
            auth()->logout();
            return back()->withErrors([
                'email' => 'Access denied. Users only.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
