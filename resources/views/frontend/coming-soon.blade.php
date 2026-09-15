<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon | Carolina Prime Distributors</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --primary: #144523;
            --primary-dark: #0b2212;
            --primary-light: #1e6333;
            --accent: #22c55e;
            --text-main: #0f172a;
            --text-muted: #475569;
            --border-light: #e2e8f0;
            --card-bg: rgba(255, 255, 255, 0.95);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: radial-gradient(circle at 10% 20%, #f0fdf4 0%, #e2f2e6 40%, #ffffff 90%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: var(--text-main);
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Background Glow */
        .bg-glow {
            position: absolute;
            top: -100px;
            right: -100px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(20, 69, 35, 0.12) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .bg-glow-bottom {
            position: absolute;
            bottom: -150px;
            left: -150px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        header {
            padding: 24px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        .brand-logo-container {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }

        .brand-logo-img {
            height: 52px;
            width: auto;
            object-fit: contain;
        }

        .brand-text h1 {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--primary);
            line-height: 1;
        }

        .brand-text p {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-top: 3px;
        }

        /* Main Hero Section */
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px 60px;
            position: relative;
            z-index: 10;
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 20px 40px -15px rgba(11, 34, 18, 0.12), 0 0 0 1px rgba(20, 69, 35, 0.05);
            border-radius: 28px;
            max-width: 600px;
            width: 100%;
            padding: 56px 48px;
            text-align: center;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 6px 18px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.6); }
            70% { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        .headline {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 58px;
            font-weight: 800;
            line-height: 1.05;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--primary);
            margin-bottom: 28px;
        }

        /* Buttons Section */
        .cta-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: var(--primary);
            color: #ffffff;
            font-size: 16px;
            font-weight: 700;
            padding: 15px 36px;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 20px -5px rgba(20, 69, 35, 0.4);
            transition: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1);
            font-family: inherit;
        }

        .btn-primary:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 14px 24px -5px rgba(20, 69, 35, 0.5);
        }

        footer {
            padding: 24px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
            position: relative;
            z-index: 10;
        }

        footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        /* =========================================
           LOGIN POPUP MODAL STYLES
        ========================================= */
        .login-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(11, 34, 18, 0.65);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }

        .login-modal-overlay.is-active {
            opacity: 1;
            visibility: visible;
        }

        .login-modal-box {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(11, 34, 18, 0.25), 0 0 0 1px rgba(20, 69, 35, 0.08);
            max-width: 460px;
            width: 100%;
            padding: 36px 32px;
            position: relative;
            transform: scale(0.92) translateY(12px);
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .login-modal-overlay.is-active .login-modal-box {
            transform: scale(1) translateY(0);
        }

        .login-modal-close {
            position: absolute;
            top: 18px;
            right: 18px;
            width: 36px;
            height: 36px;
            background: #f1f5f9;
            border: none;
            border-radius: 50%;
            font-size: 22px;
            line-height: 1;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .login-modal-close:hover {
            background: #e2e8f0;
            color: #0f172a;
            transform: scale(1.05);
        }

        .login-modal-header {
            text-align: left;
            margin-bottom: 22px;
        }

        .modal-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.6px;
            margin-bottom: 12px;
        }

        .login-modal-header h3 {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 30px;
            font-weight: 800;
            color: var(--primary-dark);
            text-transform: uppercase;
            letter-spacing: -0.3px;
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .login-modal-header p {
            font-size: 13.5px;
            color: var(--text-muted);
            line-height: 1.45;
        }

        .modal-alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 11px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            line-height: 1.4;
            text-align: left;
        }

        .modal-alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 11px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            text-align: left;
        }

        .modal-input-group {
            text-align: left;
            margin-bottom: 16px;
        }

        .modal-input-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .modal-label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .modal-forgot-link {
            font-size: 12px;
            font-weight: 600;
            color: #2563eb;
            text-decoration: none;
        }

        .modal-forgot-link:hover {
            text-decoration: underline;
        }

        .modal-input {
            width: 100%;
            padding: 12px 14px;
            background: #f8fafc;
            border: 1.5px solid var(--border-light);
            border-radius: 12px;
            font-size: 14px;
            color: var(--text-main);
            outline: none;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .modal-input:focus {
            background: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(20, 69, 35, 0.12);
        }

        .modal-remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            text-align: left;
        }

        .remember-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-muted);
            cursor: pointer;
        }

        .remember-label input {
            accent-color: var(--primary);
            width: 16px;
            height: 16px;
        }

        .modal-btn-submit {
            width: 100%;
            background: var(--primary);
            color: #ffffff;
            border: none;
            padding: 14px 24px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 8px 16px -4px rgba(20, 69, 35, 0.35);
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .modal-btn-submit:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
            box-shadow: 0 12px 20px -4px rgba(20, 69, 35, 0.45);
        }

        .modal-btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .modal-footer-register {
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid #f1f5f9;
            font-size: 13px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .modal-register-link {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        .modal-register-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 640px) {
            .card {
                padding: 40px 24px;
            }
            .headline {
                font-size: 42px;
            }
            .btn-primary {
                width: 100%;
            }
            .login-modal-box {
                padding: 30px 22px;
            }
        }
    </style>
</head>
<body>

    <div class="bg-glow"></div>
    <div class="bg-glow-bottom"></div>

    <header>
        <a href="{{ url('/') }}" class="brand-logo-container">
            <img src="{{ asset('images/logo.png') }}" alt="Carolina Prime Distributors" class="brand-logo-img"
                 onerror="this.style.display='none';" />
            <div class="brand-text">
                <h1>Carolina Prime</h1>
                <p>Wholesale Distribution</p>
            </div>
        </a>
    </header>

    <main>
        <div class="card">
            <div class="status-badge">
                <span class="status-dot"></span>
                <span>Coming Soon</span>
            </div>

            <h2 class="headline">
                Coming Soon
            </h2>

            <div class="cta-group">
                <button type="button" class="btn-primary" onclick="openLoginModal()">
                    <span>Sign In</span>
                    <span>&rarr;</span>
                </button>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Carolina Prime Distributors Inc. All Rights Reserved. &bull; <a href="tel:2525074563">Direct Phone: (252) 507-4563</a> &bull; 1620 East 10th St, Roanoke Rapids, NC</p>
    </footer>

    <!-- =========================================
         LOGIN MODAL OVERLAY & POPUP
    ========================================= -->
    <div id="loginModal" class="login-modal-overlay" onclick="handleBackdropClick(event)">
        <div class="login-modal-box">
            <!-- Close Button -->
            <button type="button" class="login-modal-close" onclick="closeLoginModal()">&times;</button>

            <div class="login-modal-header">
                <div class="modal-badge">
                    <span>🔐</span>
                    <span>ACCOUNT SIGN IN</span>
                </div>
                <h3>Sign In</h3>
                <p>Enter your authorized wholesale credentials to access your account.</p>
            </div>

            <!-- Dynamic Error Alert Box -->
            <div id="modalAlertError" class="modal-alert-error" style="display: {{ $errors->any() ? 'flex' : 'none' }};">
                <span>⚠️</span>
                <span id="modalErrorText">{{ $errors->first() }}</span>
            </div>

            @if(session('success'))
            <div class="modal-alert-success">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <form id="loginModalForm" method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="modal-input-group">
                    <label for="modalEmail">Work / Business Email</label>
                    <input type="email" name="email" id="modalEmail" class="modal-input"
                        value="{{ old('email') }}" required autofocus placeholder="buyer@retailstore.com" />
                </div>

                <div class="modal-input-group">
                    {{-- <div class="modal-label-row">
                        <label for="modalPassword">Password</label>
                        <a href="{{ route('password.request') }}" class="modal-forgot-link">Forgot Password?</a>
                    </div> --}}
                    <input type="password" name="password" id="modalPassword" class="modal-input"
                        required placeholder="••••••••" />
                </div>

                <div class="modal-remember-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" value="1">
                        <span>Keep me signed in</span>
                    </label>
                </div>

                <button type="submit" id="modalSubmitBtn" class="modal-btn-submit">
                    <span>Sign In to Wholesale Portal</span>
                    <span>&rarr;</span>
                </button>
            </form>

            <div class="modal-footer-register">
                <span>New retailer or wholesale buyer?</span>
                {{-- <a href="{{ route('register') }}" class="modal-register-link">Apply for Account &rarr;</a> --}}
            </div>
        </div>
    </div>

    <script>
        function openLoginModal() {
            const modal = document.getElementById('loginModal');
            modal.classList.add('is-active');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                const emailInput = document.getElementById('modalEmail');
                if (emailInput) emailInput.focus();
            }, 100);
        }

        function closeLoginModal() {
            const modal = document.getElementById('loginModal');
            modal.classList.remove('is-active');
            document.body.style.overflow = '';
        }

        function handleBackdropClick(e) {
            if (e.target.id === 'loginModal') {
                closeLoginModal();
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLoginModal();
            }
        });

        // AJAX Form Submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginModalForm');
            if (!form) return;

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const submitBtn = document.getElementById('modalSubmitBtn');
                const alertBox = document.getElementById('modalAlertError');
                const errorText = document.getElementById('modalErrorText');

                alertBox.style.display = 'none';
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span>Verifying credentials...</span>';

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        submitBtn.innerHTML = '<span>✓ Logged in! Opening portal...</span>';
                        window.location.href = data.redirect || "{{ route('home') }}";
                        return;
                    }

                    let msg = data.message || 'The provided credentials do not match our records.';
                    if (data.errors && data.errors.email) {
                        msg = data.errors.email[0];
                    }
                    errorText.textContent = msg;
                    alertBox.style.display = 'flex';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span>Sign In to Wholesale Portal</span> <span>&rarr;</span>';

                } catch (err) {
                    // Fallback to traditional POST submission if network or fetch fails
                    form.submit();
                }
            });

            @if($errors->any() || session('error'))
            openLoginModal();
            @endif
        });
    </script>

    <!-- Age Verification Warning Box Modal -->
    @include('frontend.partials.age-verification-modal')

</body>
</html>
