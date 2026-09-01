<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password | Carolina Prime Wholesale</title>
    
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="portal-body">

    <div class="portal-layout">
        <!-- Left Showcase Pane -->
        <div class="portal-brand-pane">
            <div class="brand-header">
                <img src="{{ asset('images/logo.png') }}" alt="Carolina Prime" class="brand-logo-img"
                    onerror="this.style.display='none';" />
                <div class="brand-title-wrap">
                    <h2>Carolina Prime</h2>
                    <p>Wholesale Distribution Network</p>
                </div>
            </div>

            <div class="brand-hero-content">
                <div class="brand-pill">
                    <span>🛡️ Security Verification</span>
                </div>
                <h1>Choose Your <span>New Password</span></h1>
                <p>
                    Set a strong, unique password to secure your wholesale ordering account and protect trade purchasing terms.
                </p>

                <ul class="perks-list">
                    <li>
                        <span class="perks-icon">✓</span>
                        <span>Minimum 8 characters with letters & numbers recommended</span>
                    </li>
                    <li>
                        <span class="perks-icon">✓</span>
                        <span>Immediate authentication upon password update</span>
                    </li>
                    <li>
                        <span class="perks-icon">✓</span>
                        <span>One-time reset token is safely invalidated upon update</span>
                    </li>
                </ul>
            </div>

            <div class="brand-footer">
                <span>Direct Support: <strong>(478) 444-5385</strong></span>
                <a href="{{ route('home') }}">&larr; Back to Public Storefront</a>
            </div>
        </div>

        <!-- Right Form Pane -->
        <div class="portal-form-pane">
            <div class="form-container">
                <div class="form-header">
                    <h2>Set New Password</h2>
                    <p>Enter your new password below for your wholesale account.</p>
                </div>

                <!-- Flash Alerts -->
                @if(session('status'))
                <div class="alert-banner alert-banner--success">
                    <span style="font-weight: bold; font-size: 16px;">✓</span>
                    <span>{{ session('status') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div class="alert-banner alert-banner--error">
                    <span style="font-weight: bold; font-size: 16px;">⚠</span>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="alert-banner alert-banner--error">
                    <span style="font-weight: bold; font-size: 16px;">⚠</span>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <!-- Hidden Token -->
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Account Email -->
                    <div class="input-group">
                        <label for="email">Wholesale Account Email</label>
                        <input type="email" name="email" id="email" class="input-control"
                            value="{{ old('email', $email) }}" required readonly />
                    </div>

                    <!-- New Password -->
                    <div class="input-group">
                        <label for="password">New Password</label>
                        <input type="password" name="password" id="password" class="input-control"
                            required autofocus placeholder="At least 8 characters" />
                    </div>

                    <!-- Confirm New Password -->
                    <div class="input-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="input-control" required placeholder="Repeat your new password" />
                    </div>

                    <button type="submit" class="btn-portal-submit">
                        Reset Password & Sign In &rarr;
                    </button>
                </form>

                <div class="back-box">
                    <a href="{{ route('login') }}">
                        &larr; Back to Wholesale Sign In
                    </a>
                </div>

                <div class="storefront-return">
                    Return to <a href="{{ route('home') }}">Carolina Prime Storefront</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
