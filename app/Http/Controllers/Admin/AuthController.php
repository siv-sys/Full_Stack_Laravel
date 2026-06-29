<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user with the provided credentials
        // The 'remember' parameter determines if the user should be remembered (kept logged in) across sessions
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Check if the authenticated user is not an admin
            if (!Auth::user()->is_admin) {
                // If the user is not an admin, log them out immediately
                Auth::logout();
                // Return back with an error message indicating admin access is required
                return back()->withErrors(['email' => 'Access denied. Admin only.']);
            }

            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
