<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\DynamicMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class CustomerPasswordResetController extends Controller
{
    /**
     * Display the form to request a password reset link.
     */
    public function showForgotForm()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('home');
        }

        return view('frontend.auth.forgot-password');
    }

    /**
     * Send a reset link to the given customer's email.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)
            ->where('user_type', 'CUSTOMER')
            ->first();

        // Ensure user is an active customer
        if (!$user) {
            return back()->withErrors([
                'email' => 'We could not find a registered wholesale customer account with that email address.',
            ])->withInput();
        }

        if (strtoupper($user->status) !== 'ACTIVE') {
            $statusMessage = match (strtoupper($user->status)) {
                'PENDING'  => 'Your customer account is currently pending approval.',
                'REJECTED' => 'Your account request was rejected. Please contact support.',
                'INACTIVE' => 'Your account is deactivated. Please contact support.',
                default    => 'Your account is not active. Please contact support.',
            };

            return back()->withErrors(['email' => $statusMessage])->withInput();
        }

        // Generate password reset token via Laravel's password broker
        $token = Password::broker()->createToken($user);

        // Generate customer-specific password reset URL
        $resetUrl = route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);

        // Send email dynamically using email_configs table
        $mailResult = DynamicMailService::sendDynamicEmail(
            module: 'customer',
            action: 'forgot-password',
            toEmail: $user->email,
            variables: [
                '{name}'       => $user->name,
                '{email}'      => $user->email,
                '{reset_link}' => $resetUrl,
                '{app_name}'   => config('app.name', 'Carolina Prime Wholesale'),
            ],
            recipientName: $user->name
        );

        if (!$mailResult['success']) {
            return back()->withErrors([
                'email' => 'Failed to send password reset email: ' . $mailResult['message'] . '. Please verify your SMTP settings in the email_configs table.',
            ])->withInput();
        }

        return back()->with('success', 'A password reset link has been sent to ' . $user->email . '. Please check your inbox and spam folder.');
    }

    /**
     * Display the password reset form for a verified customer token.
     */
    public function showResetForm(Request $request, string $token)
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('home');
        }

        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('password.request')->withErrors([
                'email' => 'Missing email address for password reset.',
            ]);
        }

        $user = User::where('email', $email)
            ->where('user_type', 'CUSTOMER')
            ->first();

        if (!$user || !Password::broker()->tokenExists($user, $token)) {
            return redirect()->route('password.request')->withErrors([
                'email' => 'This password reset link is invalid or has expired. Please request a new one.',
            ]);
        }

        return view('frontend.auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Reset the customer's password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => ['required'],
            'email'                 => ['required', 'email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $request->email)
            ->where('user_type', 'CUSTOMER')
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'We could not find a customer account associated with this email.',
            ])->withInput();
        }

        // Verify token existence and validity
        if (!Password::broker()->tokenExists($user, $request->token)) {
            return back()->withErrors([
                'email' => 'This password reset link is invalid or has expired. Please request a new reset link.',
            ])->withInput();
        }

        // Update password and save
        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        // Invalidate the reset token
        Password::broker()->deleteToken($user);

        return redirect()->route('login')->with('success', 'Your password has been successfully reset! You can now log in with your new password.');
    }
}
