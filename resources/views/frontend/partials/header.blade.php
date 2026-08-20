<!-- Header -->

<!-- Announcement bar -->
<div class="topbar">
    <div class="container topbar__inner">
        <p class="topbar__promo">
            Free delivery on wholesale orders over $500 &middot; No minimum pickup
        </p>
        <ul class="topbar__meta">
            <li><svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 4h4l2 5-2.5 1.5a12 12 0 0 0 6 6L15 14l5 2v4a16 16 0 0 1-16-16Z" /></svg> (478) 444-5385
            </li>
            <li><svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z" />
                    <circle cx="12" cy="10" r="2.5" /></svg> Garner, NC</li>
            <li>Mon&ndash;Sat &middot; 8am&ndash;7pm</li>
        </ul>
    </div>
</div>

<header class="header">
    <div class="container header__inner">
        <a class="brand" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Carolina Prime Distributors Logo">
        </a>

        <form class="search" role="search" onsubmit="return false">
            <svg class="i search__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="11" cy="11" r="6.5" />
                <path d="m16 16 4.5 4.5" /></svg>
            <label class="sr-only" for="q">Search products</label>
            <input id="q" type="search" placeholder="Search 15,000+ items, brands or SKUs" />
            <button type="submit" class="search__btn">SEARCH</button>
        </form>

        <div class="header__actions">
            @if(auth()->guard('customer')->check() && auth()->guard('customer')->user()->user_type === 'CUSTOMER')
            @php
                $customer = auth()->guard('customer')->user();
                $cartCount = \App\Models\Cart::where('user_id', $customer->id)->count();
            @endphp
            <!-- Cart Button -->
            <a class="btn btn--cart btn--sm" href="{{ route('cart.index') }}" aria-label="Cart">
                <svg class="i" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="21" r="1" />
                    <circle cx="20" cy="21" r="1" />
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                </svg>
                <span class="btn__text">Cart (<span id="cart-count">{{ $cartCount }}</span>)</span>
            </a>

            <!-- My Orders Button -->
            <a class="btn btn--login btn--sm" href="{{ route('customer.orders.index') }}"
                style="border: 1px solid #cbd5e1; text-decoration: none;">
                <svg class="i" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                    <line x1="3" x2="21" y1="6" y2="6" />
                    <path d="M16 10a4 4 0 0 1-8 0" />
                </svg>
                <span class="btn__text">My Orders</span>
            </a>

            <!-- Customer Profile Dropdown -->
            <div class="header-profile-dropdown" id="profileDropdownContainer" style="position: relative;">
                <button type="button" id="profileDropdownBtn" class="customer-profile-btn"
                    style="display: inline-flex; align-items: center; gap: 8px; padding: 4px 10px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; cursor: pointer; transition: all 0.2s ease;">
                    <div style="width: 30px; height: 30px; border-radius: 50%; background: #144523; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; flex-shrink: 0; border: 1.5px solid #d99b26; box-shadow: 0 1px 2px rgba(0,0,0,0.08);">
                        {{ strtoupper(substr($customer->name ?? 'C', 0, 1)) }}
                    </div>
                    <div style="display: flex; flex-direction: column; text-align: left; line-height: 1.2;">
                        <span style="font-size: 0.82rem; font-weight: 700; color: #0b2212; max-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $customer->name }}">
                            {{ $customer->name }}
                        </span>
                        <span style="font-size: 0.68rem; color: #64748b; font-weight: 600; max-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $customer->business_name ?: ($customer->customerGroup->name ?? 'Wholesale Member') }}">
                            {{ $customer->business_name ?: ($customer->customerGroup->name ?? 'Wholesale Member') }}
                        </span>
                    </div>
                    <svg class="dropdown-arrow" viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="#64748b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.2s ease; margin-left: 2px;">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdownMenu" class="profile-dropdown-menu"
                    style="display: none; position: absolute; right: 0; top: calc(100% + 6px); width: 220px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05); z-index: 1000; overflow: hidden;">
                    <!-- User details header -->
                    <div style="padding: 12px 14px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <p style="margin: 0; font-size: 13px; font-weight: 700; color: #0f172a; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $customer->name }}
                        </p>
                        <p style="margin: 2px 0 0 0; font-size: 11px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $customer->email }}
                        </p>
                        @if(!empty($customer->business_name))
                            <span style="display: inline-block; margin-top: 5px; padding: 2px 6px; font-size: 10px; font-weight: 700; color: #144523; background: #e8f0ea; border-radius: 4px; border: 1px solid #d8e4dc;">
                                {{ $customer->business_name }}
                            </span>
                        @endif
                    </div>

                    <!-- Links -->
                    <div style="padding: 4px 0;">
                        <a href="{{ route('customer.orders.index') }}"
                            style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; font-size: 13px; font-weight: 600; color: #334155; text-decoration: none; transition: background 0.15s;"
                            onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                            <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                                <line x1="3" x2="21" y1="6" y2="6"/>
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                            <span>My Orders</span>
                        </a>

                        <a href="{{ route('cart.index') }}"
                            style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; font-size: 13px; font-weight: 600; color: #334155; text-decoration: none; transition: background 0.15s;"
                            onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                            <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"/>
                                <circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                            </svg>
                            <span>Shopping Cart</span>
                        </a>
                    </div>

                    <!-- Logout button inside dropdown -->
                    <div style="border-top: 1px solid #e2e8f0; padding: 4px 0;">
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit"
                                style="width: 100%; display: flex; align-items: center; gap: 8px; padding: 8px 14px; font-size: 13px; font-weight: 600; color: #dc2626; background: none; border: none; text-align: left; cursor: pointer; transition: background 0.15s;"
                                onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
                                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                <span>Log Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @else
            <!-- Not Logged In: Show Login -->
            <a class="btn btn--login btn--sm" href="{{ route('login') }}" aria-label="Log in">
                <svg class="i" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <span class="btn__text">Log in</span>
            </a>
            @endif

        </div>

        <button class="navtoggle" type="button" aria-expanded="false" aria-controls="navbar">
            <span></span><span></span><span></span>
            <span class="sr-only">Toggle navigation</span>
        </button>
    </div>

    <nav class="navbar" id="navbar" aria-label="Categories">
        <div class="container navbar__inner">
            <!-- ALL CATEGORIES -> Takes to Home Page with All Products -->
            <a class="navbar__all" href="{{ route('home') }}">
                <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 6h16M4 12h16M4 18h16" /></svg>
                <span>ALL CATEGORIES</span>
            </a>
            <ul class="navbar__list">
                @forelse($navCategories as $cat)
                <li><a href="{{ route('shop.category', $cat->id) }}">{{ $cat->name }}</a></li>
                @empty
                <li><a href="{{ route('home') }}">No categories yet</a></li>
                @endforelse
            </ul>
            <a class="navbar__deals" href="{{ route('home') }}#deals">
                Weekly Deals
                <svg class="i i--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m6 9 6 6 6-6" /></svg>
            </a>
        </div>
    </nav>
</header>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const profileBtn = document.getElementById('profileDropdownBtn');
    const profileMenu = document.getElementById('profileDropdownMenu');
    
    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isVisible = profileMenu.style.display === 'block';
            profileMenu.style.display = isVisible ? 'none' : 'block';
            const arrow = profileBtn.querySelector('.dropdown-arrow');
            if (arrow) {
                arrow.style.transform = isVisible ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        });

        document.addEventListener('click', function (e) {
            if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.style.display = 'none';
                const arrow = profileBtn.querySelector('.dropdown-arrow');
                if (arrow) {
                    arrow.style.transform = 'rotate(0deg)';
                }
            }
        });
    }
});
</script>