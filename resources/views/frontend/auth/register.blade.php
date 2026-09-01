<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Wholesale Trade Account | Carolina Prime Distributors</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>

    <div class="portal-layout">
        <!-- Left Showcase Pane (Original Layout & Content) -->
        <div class="portal-brand-pane">
            <div class="brand-header">
                <img src="{{ asset('images/logo.png') }}" alt="Carolina Prime" class="brand-logo-img" onerror="this.style.display='none';" />
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
                    Join 2,400+ retailers accessing factory-direct case prices, volume tier rebates, and next-day route
                    delivery across the Carolinas.
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

        <!-- Right Registration Form Pane (Polished Form) -->
        <div class="portal-register-form-pane">
            <div class="register-card-container">
                
                <div class="register-form-header">
                    <h2>Apply For Trade Access</h2>
                    <p>Prices and orders are visible to approved business accounts only.</p>
                </div>

                @if($errors->any())
                <div style="background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; color: #991b1b;">
                    <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 13px; margin-bottom: 6px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <span>Please correct the following errors:</span>
                    </div>
                    <ul style="margin: 0; padding-left: 18px; font-size: 12.5px; line-height: 1.5;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('register.store') }}" autocomplete="on" id="wholesaleRegisterForm">
                    @csrf

                    <!-- SECTION 1: Contact & Security -->
                    <div class="form-step-section">
                        <div class="form-step-header">
                            <div class="form-step-number">1</div>
                            <h3>Contact & Login Credentials</h3>
                            <span class="step-subtitle">Primary account details</span>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label" for="name">
                                    <span>Full Name <span class="req">*</span></span>
                                </label>
                                <div class="form-input-wrapper">
                                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}"
                                        required autocomplete="name" placeholder="e.g. John Doe" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="email">
                                    <span>Work Email <span class="req">*</span></span>
                                </label>
                                <div class="form-input-wrapper">
                                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}"
                                        required autocomplete="email" placeholder="buyer@retailstore.com" />
                                </div>
                            </div>
                        </div>

                        <div class="form-grid-2" style="margin-top: 10px;">
                            <div class="form-group">
                                <label class="form-label" for="phone">
                                    <span>Phone / WhatsApp</span>
                                </label>
                                <div class="form-input-wrapper">
                                    <input type="tel" id="phone" name="phone" class="form-input" value="{{ old('phone') }}"
                                        autocomplete="tel" placeholder="e.g. +1 (555) 019-2834" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="password">
                                    <span>Password <span class="req">*</span></span>
                                </label>
                                <div class="form-input-wrapper">
                                    <input type="password" id="password" name="password" class="form-input" required
                                        minlength="6" autocomplete="new-password" placeholder="Minimum 6 characters" />
                                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" aria-label="Toggle Password Visibility">
                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 10px;">
                            <label class="form-label" for="password_confirmation">
                                <span>Confirm Password <span class="req">*</span></span>
                            </label>
                            <div class="form-input-wrapper">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-input" required minlength="6" autocomplete="new-password"
                                    placeholder="Re-type your password" />
                                <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password_confirmation', this)" aria-label="Toggle Password Visibility">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: Business & Tax Identification -->
                    <div class="form-step-section">
                        <div class="form-step-header">
                            <div class="form-step-number">2</div>
                            <h3>Business & Tax Identification</h3>
                            <span class="step-subtitle">Commercial store info</span>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label" for="biz">
                                    <span>Business / Store Name</span>
                                </label>
                                <div class="form-input-wrapper">
                                    <input type="text" id="biz" name="business_name" class="form-input"
                                        value="{{ old('business_name') }}" autocomplete="organization"
                                        placeholder="e.g. Apex Smoke Shop & C-Store" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="tax">
                                    <span>Resale / Tax ID / GSTIN</span>
                                </label>
                                <div class="form-input-wrapper">
                                    <input type="text" id="tax" name="gst_number" class="form-input"
                                        value="{{ old('gst_number') }}" placeholder="State Tax / Resale License #" />
                                </div>
                                <small class="form-hint">Required for tax-exempt wholesale pricing.</small>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: Dispatch & Delivery Address -->
                    <div class="form-step-section">
                        <div class="form-step-header">
                            <div class="form-step-number">3</div>
                            <h3>Registered Delivery Location</h3>
                            <span class="step-subtitle">Drop destination</span>
                        </div>

                        <div class="form-grid-3">
                            <div class="form-group">
                                <label class="form-label" for="country_id">
                                    <span>Country <span class="req">*</span></span>
                                </label>
                                <select id="country_id" name="country_id" class="form-input" required autocomplete="country">
                                    <option value="">Select country</option>
                                    @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="state_id">
                                    <span>State <span class="req">*</span></span>
                                </label>
                                <select id="state_id" name="state_id" class="form-input" required disabled>
                                    <option value="">Select country first</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="city_id">
                                    <span>City <span class="req">*</span></span>
                                </label>
                                <select id="city_id" name="city_id" class="form-input" required disabled>
                                    <option value="">Select state first</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 10px;">
                            <label class="form-label" for="address">
                                <span>Delivery Street Address & Loading Dock <span class="req">*</span></span>
                            </label>
                            <textarea id="address" name="address" class="form-input" rows="2" required
                                placeholder="Commercial store street address, suite/building number, or receiving dock details">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-register-submit" id="btnSubmitApplication">
                        <span>🚀 Submit Trade Application & Unlock Pricing &rarr;</span>
                    </button>

                    <!-- Trust Highlights -->
                    <div class="trust-badges-bar">
                        <div class="trust-badge-pill">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <span>256-Bit SSL Encrypted</span>
                        </div>
                        <div class="trust-badge-pill">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <span>No Onboarding Fees</span>
                        </div>
                        <div class="trust-badge-pill">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                            <span>Verified Trade Network</span>
                        </div>
                    </div>

                </form>

                <!-- Existing Customer Login Prompt -->
                <div class="register-login-prompt">
                    <p>Already an approved wholesale customer?</p>
                    <a href="{{ route('login') }}" class="btn-to-login-portal">
                        Sign In to Wholesale Portal &rarr;
                    </a>
                </div>

                <div class="register-storefront-back">
                    Return to <a href="{{ route('home') }}">Carolina Prime Wholesale Storefront</a>
                </div>

            </div>
        </div>
    </div>

    <!-- Scripts for Password Eye Toggle & Cascading Dropdowns with Pre-fill Support -->
    <script>
        function togglePasswordVisibility(fieldId, btn) {
            const input = document.getElementById(fieldId);
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = `<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`;
            } else {
                input.type = 'password';
                btn.innerHTML = `<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const countrySelect = document.getElementById('country_id');
            const stateSelect = document.getElementById('state_id');
            const citySelect = document.getElementById('city_id');

            const oldCountryId = "{{ old('country_id') }}";
            const oldStateId = "{{ old('state_id') }}";
            const oldCityId = "{{ old('city_id') }}";

            function loadStates(countryId, selectedStateId = null) {
                if (!countryId) {
                    stateSelect.innerHTML = '<option value="">Select country first</option>';
                    stateSelect.disabled = true;
                    citySelect.innerHTML = '<option value="">Select state first</option>';
                    citySelect.disabled = true;
                    return;
                }

                stateSelect.innerHTML = '<option value="">Loading states...</option>';
                stateSelect.disabled = true;
                citySelect.innerHTML = '<option value="">Select state first</option>';
                citySelect.disabled = true;

                fetch('/get-states/' + countryId)
                    .then(res => res.json())
                    .then(states => {
                        stateSelect.innerHTML = '<option value="">Select state</option>';
                        states.forEach(state => {
                            const opt = document.createElement('option');
                            opt.value = state.id;
                            opt.textContent = state.name;
                            if (selectedStateId && selectedStateId == state.id) {
                                opt.selected = true;
                            }
                            stateSelect.appendChild(opt);
                        });
                        stateSelect.disabled = false;

                        if (selectedStateId) {
                            loadCities(selectedStateId, oldCityId);
                        }
                    })
                    .catch(err => {
                        stateSelect.innerHTML = '<option value="">Error loading states</option>';
                    });
            }

            function loadCities(stateId, selectedCityId = null) {
                if (!stateId) {
                    citySelect.innerHTML = '<option value="">Select state first</option>';
                    citySelect.disabled = true;
                    return;
                }

                citySelect.innerHTML = '<option value="">Loading cities...</option>';
                citySelect.disabled = true;

                fetch('/get-cities/' + stateId)
                    .then(res => res.json())
                    .then(cities => {
                        citySelect.innerHTML = '<option value="">Select city</option>';
                        cities.forEach(city => {
                            const opt = document.createElement('option');
                            opt.value = city.id;
                            opt.textContent = city.name;
                            if (selectedCityId && selectedCityId == city.id) {
                                opt.selected = true;
                            }
                            citySelect.appendChild(opt);
                        });
                        citySelect.disabled = false;
                    })
                    .catch(err => {
                        citySelect.innerHTML = '<option value="">Error loading cities</option>';
                    });
            }

            countrySelect.addEventListener('change', function() {
                loadStates(this.value);
            });

            stateSelect.addEventListener('change', function() {
                loadCities(this.value);
            });

            // If returning from validation error with old values
            if (oldCountryId) {
                loadStates(oldCountryId, oldStateId);
            }
        });
    </script>
</body>

</html>