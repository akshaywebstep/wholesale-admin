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
    
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #0b1329;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .portal-layout {
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            width: 100%;
            min-height: 100vh;
            background: #ffffff;
        }
        @media (max-width: 960px) {
            .portal-layout {
                grid-template-columns: 1fr;
            }
            .portal-brand-pane {
                display: none !important;
            }
        }
        /* Left Brand Showcase Pane */
        .portal-brand-pane {
            background: linear-gradient(145deg, #091224 0%, #11203d 50%, #060c18 100%);
            padding: 60px 48px;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .portal-brand-pane::after {
            content: '';
            position: absolute;
            bottom: -50px;
            right: -50px;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, rgba(0,0,0,0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .brand-header {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .brand-logo-img {
            height: 48px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.3));
        }
        .brand-title-wrap h2 {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #ffffff;
            text-transform: uppercase;
            line-height: 1;
        }
        .brand-title-wrap p {
            font-size: 11px;
            color: #94a3b8;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-top: 3px;
        }
        .brand-hero-content {
            margin: 40px 0;
        }
        .brand-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(37, 99, 235, 0.2);
            border: 1px solid rgba(59, 130, 246, 0.4);
            color: #60a5fa;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 5px 12px;
            border-radius: 20px;
            margin-bottom: 20px;
        }
        .brand-hero-content h1 {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 42px;
            font-weight: 800;
            line-height: 1.1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            color: #ffffff;
        }
        .brand-hero-content h1 span {
            color: #f59e0b;
        }
        .brand-hero-content p {
            font-size: 15px;
            line-height: 1.6;
            color: #cbd5e1;
            max-width: 480px;
        }
        .perks-list {
            list-style: none;
            margin-top: 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .perks-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: #e2e8f0;
        }
        .perks-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(34, 197, 94, 0.2);
            color: #4ade80;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            flex-shrink: 0;
        }
        .brand-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 12px;
            color: #94a3b8;
        }
        .brand-footer a {
            color: #60a5fa;
            text-decoration: none;
            font-weight: 600;
        }
        
        /* Right Form Pane */
        .portal-form-pane {
            background: #ffffff;
            padding: 60px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            width: 100%;
            max-width: 420px;
        }
        .form-header {
            margin-bottom: 28px;
        }
        .form-header h2 {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 32px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .form-header p {
            font-size: 14px;
            color: #64748b;
        }
        .alert-banner {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-banner--error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }
        .alert-banner--success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
        }
        .input-group {
            margin-bottom: 20px;
        }
        .input-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 6px;
        }
        .input-control {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            color: #0f172a;
            transition: all 0.2s ease;
            outline: none;
        }
        .input-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }
        .form-row-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 24px;
        }
        .form-row-between label {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #475569;
            cursor: pointer;
            user-select: none;
        }
        .form-row-between a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }
        .btn-portal-submit {
            width: 100%;
            background: #0f172a;
            color: #ffffff;
            border: none;
            padding: 14px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-portal-submit:hover {
            background: #1e293b;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.25);
        }
        .btn-portal-submit:active {
            transform: scale(0.99);
        }
        
        .register-box {
            margin-top: 28px;
            background: #f8fafc;
            border: 1.5px dashed #cbd5e1;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }
        .register-box h4 {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .register-box p {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 14px;
        }
        .btn-portal-register {
            display: inline-block;
            width: 100%;
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 12px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 8px;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }
        .btn-portal-register:hover {
            background: #1d4ed8;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }
        .storefront-return {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: #64748b;
        }
        .storefront-return a {
            color: #0f172a;
            font-weight: 600;
            text-decoration: none;
        }
        .storefront-return a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

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
                        <span>Zero minimum order pickup at Garner, NC Master Hub</span>
                    </li>
                    <li>
                        <span class="perks-icon">✓</span>
                        <span>Instant invoice generation & Net 30 credit applications</span>
                    </li>
                </ul>
            </div>

            <div class="brand-footer">
                <span>Direct Support: <strong>(478) 444-5385</strong></span>
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
                        <label for="password">Password</label>
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