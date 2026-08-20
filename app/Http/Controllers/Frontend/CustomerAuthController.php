<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('home');
        }

        return view('frontend.auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
        $user = Auth::guard('customer')->user();

        if ($user->user_type !== 'CUSTOMER') {
            Auth::guard('customer')->logout();
            return back()->withErrors([
                'email' => 'Please login with a Customer account.',
            ])->onlyInput('email');
        }

        // Status check karo
        if (strtoupper($user->status) !== 'ACTIVE') {
            Auth::guard('customer')->logout();

            $message = match (strtoupper($user->status)) {
                'PENDING'  => 'Your account is pending approval. We will notify you once it is approved.',
                'REJECTED' => 'Your account request has been rejected. Please contact support for more details.',
                'INACTIVE' => 'Your account has been deactivated. Please contact support.',
                default    => 'Your account is not active. Please contact support.',
            };

            return back()->withErrors([
                'email' => $message,
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Logged in successfully!');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
