<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginLog;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('web')->check()) {
            if (in_array(Auth::guard('web')->user()->user_type, ['ADMIN', 'STAFF'])) {
                return redirect()->route('admin.dashboard');
            }
            Auth::guard('web')->logout();
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('web')->user();

            if (!in_array($user->user_type, ['ADMIN', 'STAFF'])) {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => 'Access denied. Only Admins and Staff can log in here.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            LoginLog::create([
                'user_id'    => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'action'     => 'login',
            ]);

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            LoginLog::create([
                'user_id'    => Auth::guard('web')->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'action'     => 'logout',
            ]);
        }

        Auth::guard('web')->logout();
        $request->session()->regenerate();

        return redirect()->route('admin.login');
    }
}