<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Carolina Prime Wholesale</title>
    
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
                    <span>🔐 Secure Account Recovery</span>
                </div>
                <h1>Reset Your <span>Wholesale Access</span></h1>
                <p>
                    Enter your authorized wholesale business email address. We will send you a secure link to safely set a new password for your trade portal account.
                </p>

                <ul class="perks-list">
                    <li>
                        <span class="perks-icon">✓</span>
                        <span>Encrypted one-time secure password reset links</span>
                    </li>
                    <li>
                        <span class="perks-icon">✓</span>
                        <span>Instant delivery to your registered business email</span>
                    </li>
                    <li>
                        <span class="perks-icon">✓</span>
                        <span>Full protection for your wholesale pricing tiers & credit</span>
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
                    <h2>Forgot Password</h2>
                    <p>Enter your business email address and we'll send you a password reset link.</p>
                </div>

                <!-- Flash Alerts -->
                @if(session('success'))
                <div class="alert-banner alert-banner--success">
                    <span style="font-weight: bold; font-size: 16px;">✓</span>
                    <span>{{ session('success') }}</span>
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

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="input-group">
                        <label for="email">Work / Business Email</label>
                        <input type="email" name="email" id="email" class="input-control"
                            value="{{ old('email') }}" required autofocus placeholder="buyer@retailstore.com" />
                    </div>

                    <button type="submit" class="btn-portal-submit">
                        Send Password Reset Link &rarr;
                    </button>
                </form>

                <div class="back-box">
                    <a href="{{ route('login') }}">
                        &larr; Remember your password? Sign In
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
