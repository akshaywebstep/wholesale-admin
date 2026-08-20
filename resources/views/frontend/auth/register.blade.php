@extends('frontend.layouts.app')

@section('title', 'Create Trade Account | Carolina Prime Distributors')

@section('content')
<section class="section" id="top">
    <div class="container container--narrow">
        <header class="section__head section__head--center">
            <div>
                <p class="eyebrow">Get Wholesale Pricing</p>
                <h2 class="heading">Create Your Trade Account</h2>
                <p class="section__sub">Prices are visible to approved trade accounts only. Verification typically takes under 24 hours.</p>
            </div>
        </header>

        @if($errors->any())
        <div class="alert alert--error">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form class="form register-form" method="POST" action="{{ route('register.store') }}">
            @csrf

            <p class="form__section-label">Contact Details</p>

            <div class="form__row">
                <div>
                    <label class="sr-only" for="name">Full Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Full name" required />
                </div>
                <div>
                    <label class="sr-only" for="email">Work email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Work email address" required />
                </div>
            </div>

            <div class="form__row">
                <div>
                    <label class="sr-only" for="phone">Phone</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}" placeholder="Phone number" />
                </div>
                <div></div>
            </div>

            <div class="form__row">
                <div>
                    <label class="sr-only" for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="Create password" required />
                </div>
                <div>
                    <label class="sr-only" for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm password" required />
                </div>
            </div>

            <p class="form__section-label">Business Details</p>

            <div class="form__row">
                <div>
                    <label class="sr-only" for="biz">Business name</label>
                    <input id="biz" name="business_name" type="text" value="{{ old('business_name') }}" placeholder="Business / store name" />
                </div>
                <div>
                    <label class="sr-only" for="tax">Resale / tax ID</label>
                    <input id="tax" name="gst_number" type="text" value="{{ old('gst_number') }}" placeholder="Resale / tax ID (optional)" />
                </div>
            </div>

            <p class="form__section-label">Delivery Location</p>

            <div class="form__row">
                <div>
                    <label class="sr-only" for="country_id">Country</label>
                    <select id="country_id" name="country_id" required>
                        <option value="">Select country</option>
                        @foreach($countries as $country)
                        <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="sr-only" for="state_id">State</label>
                    <select id="state_id" name="state_id" required>
                        <option value="">Select state</option>
                    </select>
                </div>
            </div>

            <div class="form__row">
                <div>
                    <label class="sr-only" for="city_id">City</label>
                    <select id="city_id" name="city_id" required>
                        <option value="">Select city</option>
                    </select>
                </div>
                <div></div>
            </div>

            <label class="sr-only" for="address">Address</label>
            <textarea id="address" name="address" rows="3" placeholder="Street address, building, suite" required>{{ old('address') }}</textarea>

            <button class="btn btn--dark btn--block" type="submit">
                Request Trade Access <span aria-hidden="true">&rarr;</span>
            </button>

            <p class="form__login-link">
                Already have an account? <a href="{{ route('login') }}">Log in</a>
            </p>
        </form>
    </div>
</section>
@endsection

@push('scripts')
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
@endpush