<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login - Carolina Prime Distributors</title>
    
    {{-- Main CSS file link --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="auth-page">

    <div class="auth-card">
        <div class="auth-header">
            <h1 class="display">Wholesale Portal</h1>
            <p>Log in to view live wholesale pricing & place orders</p>
        </div>

        @if ($errors->any())
            <div class="auth-alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="auth-form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="auth-form-control" value="{{ old('email') }}" required autofocus placeholder="name@company.com">
            </div>

            <div class="auth-form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="auth-form-control" required placeholder="••••••••">
            </div>

            <div class="auth-actions">
                <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <a href="#">Forgot password?</a>
            </div>

            <button type="submit" class="auth-btn-submit">Sign In</button>
        </form>

        <div class="auth-footer">
            Back to <a href="{{ route('home') }}">Storefront</a>
        </div>
    </div>

</body>
</html>