<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wholesale Trade Sign In | Carolina Prime Distributors</title>
    
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
                    <span>⚡ B2B Verified Trade Portal</span>
                </div>
                <h1>Direct Wholesale Supply <span>Before You Open</span></h1>
                <p>
                    Access 15,000+ top-selling wholesale SKUs across vape, hookah, beverages, confectionery, and general c-store merchandise.
                </p>

                <ul class="perks-list">
                    <li>
                        <span class="perks-icon">✓</span>
                        <span>Locked tier wholesale pricing & case rate discounts</span>
                    </li>
                    <li>
                        <span class="perks-icon">✓</span>
                        <span>Free next-day route delivery across the Carolinas</span>
                    </li>
                    <li>
                        <span class="perks-icon">✓</span>
                        <span>Zero minimum order pickup at Roanoke Rapids, NC Master Hub</span>
                    </li>
                    <li>
                        <span class="perks-icon">✓</span>
                        <span>Instant invoice generation & Net 30 credit applications</span>
                    </li>
                </ul>
            </div>

            <div class="brand-footer">
                <span>Direct Support: <strong>(252) 507-4563</strong></span>
                <a href="{{ route('home') }}">&larr; Back to Public Storefront</a>
            </div>
        </div>

        <!-- Right Login Form Pane -->
        <div class="portal-form-pane">
            <div class="form-container">
                <div class="form-header">
                    <h2>Trade Sign In</h2>
                    <p>Enter your authorized wholesale business credentials.</p>
                </div>

                <!-- Flash Alerts -->
                @if(session('success'))
                <div class="alert-banner alert-banner--success">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div class="alert-banner alert-banner--error">
                    <span>⚠</span>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="alert-banner alert-banner--error">
                    <span>⚠</span>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <div class="input-group">
                        <label for="email">Work / Business Email</label>
                        <input type="email" name="email" id="email" class="input-control"
                            value="{{ old('email') }}" required autofocus placeholder="buyer@retailstore.com" />
                    </div>

                    <div class="input-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <label for="password" style="margin-bottom: 0;">Password</label>
                            <a href="{{ route('password.request') }}" style="font-size: 12px; color: #2563eb; text-decoration: none; font-weight: 600;">Forgot Password?</a>
                        </div>
                        <input type="password" name="password" id="password" class="input-control"
                            required placeholder="••••••••" />
                    </div>

                    <div class="form-row-between">
                        <label>
                            <input type="checkbox" name="remember"> Keep me signed in
                        </label>
                        <a href="{{ route('register') }}">Need access?</a>
                    </div>

                    <button type="submit" class="btn-portal-submit">
                        Sign In to Wholesale Portal &rarr;
                    </button>
                </form>

                <!-- New Customer Registration Prompt -->
                <div class="register-box">
                    <h4>New Retailer or Wholesale Buyer?</h4>
                    <p>Submit your trade application in under 2 minutes to unlock locked bulk pricing.</p>
                    <a href="{{ route('register') }}" class="btn-portal-register">
                        ✨ Create Trade / Wholesale Account &rarr;
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