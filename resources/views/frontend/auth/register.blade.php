<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Wholesale Trade Account | Carolina Prime Distributors</title>
    
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
            grid-template-columns: 0.95fr 1.05fr;
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
            padding: 50px 44px;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        .brand-header {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .brand-logo-img {
            height: 44px;
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
            margin: 30px 0;
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
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 16px;
        }
        .brand-hero-content h1 {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 38px;
            font-weight: 800;
            line-height: 1.1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 14px;
            color: #ffffff;
        }
        .brand-hero-content h1 span {
            color: #f59e0b;
        }
        .brand-hero-content p {
            font-size: 14px;
            line-height: 1.6;
            color: #cbd5e1;
        }
        
        /* 3-Step Verification Timeline */
        .timeline-steps {
            margin-top: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .timeline-step {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }
        .step-badge {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #2563eb;
            color: #ffffff;
            font-size: 12px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
        }
        .step-content h4 {
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .step-content p {
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.4;
            margin: 0;
        }
        
        .brand-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 20px;
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
            padding: 48px 48px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            overflow-y: auto;
            max-height: 100vh;
        }
        .form-container {
            width: 100%;
            max-width: 520px;
        }
        .form-header {
            margin-bottom: 24px;
        }
        .form-header h2 {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 32px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .form-header p {
            font-size: 13px;
            color: #64748b;
        }
        
        .alert-banner {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
        }
        .alert-banner--error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }
        
        .form-section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 20px 0 14px;
            border-bottom: 1.5px solid #f1f5f9;
            padding-bottom: 6px;
        }
        .form-section-num {
            background: #eff6ff;
            color: #2563eb;
            font-size: 11px;
            font-weight: 800;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-section-title h3 {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            color: #0f172a;
            letter-spacing: 0.05em;
            margin: 0;
        }
        
        .input-group {
            margin-bottom: 14px;
        }
        .input-group label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 5px;
        }
        .input-control {
            width: 100%;
            padding: 10px 14px;
            font-size: 13px;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            color: #0f172a;
            transition: all 0.2s ease;
            outline: none;
            background: #ffffff;
        }
        .input-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
        }
        @media(max-width: 600px) {
            .grid-2, .grid-3 {
                grid-template-columns: 1fr;
            }
        }
        
        .btn-portal-submit {
            width: 100%;
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 14px;
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
        }
        .btn-portal-submit:hover {
            background: #1d4ed8;
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
        }
        
        .login-prompt-box {
            margin-top: 20px;
            background: #f8fafc;
            border: 1.5px dashed #cbd5e1;
            border-radius: 10px;
            padding: 14px;
            text-align: center;
        }
        .login-prompt-box p {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 8px;
        }
        .btn-to-login {
            display: inline-block;
            width: 100%;
            background: #ffffff;
            color: #0f172a;
            border: 1.5px solid #0f172a;
            padding: 9px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 6px;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }
        .btn-to-login:hover {
            background: #0f172a;
            color: #ffffff;
        }
        .storefront-return {
            text-align: center;
            margin-top: 18px;
            font-size: 12px;
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
                    <span>⚡ B2B Trade Onboarding</span>
                </div>
                <h1>Open Your Wholesale <span>Trade Account</span></h1>
                <p>
                    Join 2,400+ retailers accessing factory-direct case prices, volume tier rebates, and next-day route delivery across the Carolinas.
                </p>

                <!-- 3-Step Process -->
                <div class="timeline-steps">
                    <div class="timeline-step">
                        <div class="step-badge">1</div>
                        <div class="step-content">
                            <h4>Submit Store Details</h4>
                            <p>Provide your business name, delivery address & resale tax certificate.</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="step-badge">2</div>
                        <div class="step-content">
                            <h4>Fast 24-Hr Verification</h4>
                            <p>Our operations team verifies your credentials in under 2 to 4 business hours.</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="step-badge">3</div>
                        <div class="step-content">
                            <h4>Unlock Locked Pricing & Order</h4>
                            <p>Sign in to view live inventory, place truck delivery orders, or apply for Net 30.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="brand-footer">
                <span>Operations Desk: <strong>(478) 444-5385</strong></span>
                <a href="{{ route('home') }}">&larr; Return to Storefront</a>
            </div>
        </div>

        <!-- Right Registration Form Pane -->
        <div class="portal-form-pane">
            <div class="form-container">
                <div class="form-header">
                    <h2>Apply For Trade Access</h2>
                    <p>Prices are visible to approved business accounts only.</p>
                </div>

                @if($errors->any())
                <div class="alert-banner alert-banner--error">
                    <ul style="margin: 0 0 0 16px; padding: 0;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('register.store') }}">
                    @csrf

                    <!-- Section 1: Contact & Credentials -->
                    <div class="form-section-title">
                        <div class="form-section-num">1</div>
                        <h3>Contact & Login Credentials</h3>
                    </div>

                    <div class="grid-2">
                        <div class="input-group">
                            <label for="name">Full Name <span style="color:#dc2626;">*</span></label>
                            <input type="text" id="name" name="name" class="input-control"
                                value="{{ old('name') }}" required placeholder="e.g. John Doe" />
                        </div>

                        <div class="input-group">
                            <label for="email">Work Email <span style="color:#dc2626;">*</span></label>
                            <input type="email" id="email" name="email" class="input-control"
                                value="{{ old('email') }}" required placeholder="buyer@retailstore.com" />
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="input-group">
                            <label for="phone">Phone / WhatsApp</label>
                            <input type="text" id="phone" name="phone" class="input-control"
                                value="{{ old('phone') }}" placeholder="e.g. +1 (555) 019-2834" />
                        </div>

                        <div class="input-group">
                            <label for="password">Password <span style="color:#dc2626;">*</span></label>
                            <input type="password" id="password" name="password" class="input-control"
                                required placeholder="Minimum 6 characters" />
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="password_confirmation">Confirm Password <span style="color:#dc2626;">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="input-control"
                            required placeholder="Re-type your password" />
                    </div>

                    <!-- Section 2: Store & Tax Credentials -->
                    <div class="form-section-title">
                        <div class="form-section-num">2</div>
                        <h3>Business & Tax Identification</h3>
                    </div>

                    <div class="grid-2">
                        <div class="input-group">
                            <label for="biz">Business / Store Name</label>
                            <input type="text" id="biz" name="business_name" class="input-control"
                                value="{{ old('business_name') }}" placeholder="e.g. Apex Smoke & C-Store" />
                        </div>

                        <div class="input-group">
                            <label for="tax">Resale / GSTIN / Tax ID</label>
                            <input type="text" id="tax" name="gst_number" class="input-control"
                                value="{{ old('gst_number') }}" placeholder="State Tax / Resale License" />
                        </div>
                    </div>

                    <!-- Section 3: Delivery Location -->
                    <div class="form-section-title">
                        <div class="form-section-num">3</div>
                        <h3>Registered Delivery Location</h3>
                    </div>

                    <div class="grid-3">
                        <div class="input-group">
                            <label for="country_id">Country <span style="color:#dc2626;">*</span></label>
                            <select id="country_id" name="country_id" class="input-control" required>
                                <option value="">Select country</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="state_id">State <span style="color:#dc2626;">*</span></label>
                            <select id="state_id" name="state_id" class="input-control" required>
                                <option value="">Select state</option>
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="city_id">City <span style="color:#dc2626;">*</span></label>
                            <select id="city_id" name="city_id" class="input-control" required>
                                <option value="">Select city</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="address">Delivery Street Address & Dock <span style="color:#dc2626;">*</span></label>
                        <textarea id="address" name="address" class="input-control" rows="2" required placeholder="Physical store or delivery dock address">{{ old('address') }}</textarea>
                    </div>

                    <button type="submit" class="btn-portal-submit">
                        🚀 Submit Trade Application & Unlock Pricing &rarr;
                    </button>
                </form>

                <!-- Existing Member Login Prompt -->
                <div class="login-prompt-box">
                    <p>Already an approved wholesale customer?</p>
                    <a href="{{ route('login') }}" class="btn-to-login">
                        Sign In to Wholesale Portal &rarr;
                    </a>
                </div>

                <div class="storefront-return">
                    Return to <a href="{{ route('home') }}">Carolina Prime Storefront</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts for dynamic State/City Cascading Dropdowns -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const countrySelect = document.getElementById('country_id');
        const stateSelect = document.getElementById('state_id');
        const citySelect = document.getElementById('city_id');

        countrySelect.addEventListener('change', function () {
            stateSelect.innerHTML = '<option value="">Select state</option>';
            citySelect.innerHTML = '<option value="">Select city</option>';
            if (!this.value) return;

            fetch('/get-states/' + this.value)
                .then(res => res.json())
                .then(states => {
                    states.forEach(state => {
                        const opt = document.createElement('option');
                        opt.value = state.id;
                        opt.textContent = state.name;
                        stateSelect.appendChild(opt);
                    });
                });
        });

        stateSelect.addEventListener('change', function () {
            citySelect.innerHTML = '<option value="">Select city</option>';
            if (!this.value) return;

            fetch('/get-cities/' + this.value)
                .then(res => res.json())
                .then(cities => {
                    cities.forEach(city => {
                        const opt = document.createElement('option');
                        opt.value = city.id;
                        opt.textContent = city.name;
                        citySelect.appendChild(opt);
                    });
                });
        });
    });
    </script>
</body>
</html>