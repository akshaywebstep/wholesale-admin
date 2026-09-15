<!-- Header -->

<!-- Announcement bar -->
<div class="topbar">
    <div class="container topbar__inner">
        <p class="topbar__promo">
            Free delivery on wholesale orders over $500 &middot; No minimum pickup
        </p>
        <ul class="topbar__meta">
            <li><a href="tel:2525074563" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;"><svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 4h4l2 5-2.5 1.5a12 12 0 0 0 6 6L15 14l5 2v4a16 16 0 0 1-16-16Z" /></svg> (252) 507-4563</a>
            </li>
            <li><a href="https://wa.me/12522033927" target="_blank" style="color: #22c55e; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; font-weight: 700;">
                    <span>💬</span> WhatsApp: +1 (252) 203-3927</a>
            </li>
            <li><svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z" />
                    <circle cx="12" cy="10" r="2.5" /></svg> 1620 East 10th St, Roanoke Rapids, NC</li>
            <li>Mon&ndash;Sat &middot; 8am&ndash;7pm</li>
        </ul>
    </div>
</div>

<header class="header">
    <div class="container header__inner">
        <a class="brand" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Carolina Prime Distributors Logo">
        </a>

        <form class="search" role="search" action="{{ route('shop.search') }}" method="GET" style="position: relative;">
            <svg class="i search__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="11" cy="11" r="6.5" />
                <path d="m16 16 4.5 4.5" /></svg>
            <label class="sr-only" for="q">Search products</label>
            <input id="q" name="q" type="search" value="{{ request('q') }}" placeholder="Search 15,000+ items, brands or SKUs" autocomplete="off" />
            <button type="submit" class="search__btn">SEARCH</button>
            <div id="search-dropdown" class="search-dropdown" style="display: none;"></div>
        </form>

        <div class="header__actions">
            @php
                $headerCartCount = (auth('customer')->check() && auth('customer')->user()->user_type === 'CUSTOMER')
                    ? \App\Models\Cart::where('user_id', auth('customer')->id())->count()
                    : 0;
            @endphp

            @if(auth()->guard('customer')->check() && auth()->guard('customer')->user()->user_type === 'CUSTOMER')
            @php
                $customer = auth()->guard('customer')->user();
            @endphp
            <!-- Bulk Orders Button -->
            <a class="header-action-btn" href="{{ route('shop.quick-order.index') }}">
                <span class="action-btn-icon" style="color: #d97706; font-size: 14px;">⚡</span>
                <span class="btn__text">Bulk Orders</span>
            </a>

            <!-- My Orders Button -->
            <a class="header-action-btn" href="{{ route('customer.orders.index') }}">
                <svg class="action-btn-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                    <line x1="3" x2="21" y1="6" y2="6" />
                    <path d="M16 10a4 4 0 0 1-8 0" />
                </svg>
                <span class="btn__text">My Orders</span>
            </a>
            @endif

            <!-- Star Importers Style Cart Widget -->
            <a href="{{ route('cart.index') }}" class="header-cart-widget" aria-label="Cart" title="View Cart">
                <div class="header-cart-icon-wrap">
                    <svg viewBox="0 0 24 24" width="23" height="23" fill="none" stroke="#1e293b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                    <span class="header-cart-badge" id="cart-count">{{ $headerCartCount }}</span>
                </div>
                <div class="header-cart-text">
                    <span class="header-cart-sub">Cart</span>
                    <span class="header-cart-title">Checkout</span>
                </div>
            </a>

            @if(auth()->guard('customer')->check() && auth()->guard('customer')->user()->user_type === 'CUSTOMER')
            <!-- Customer Profile Dropdown (Star Importers Style) -->
            <div class="header-profile-dropdown" id="profileDropdownContainer" style="position: relative; z-index: 1060;">
                <button type="button" id="profileDropdownBtn" class="star-account-btn" aria-expanded="false" aria-label="Account menu">
                    <div class="star-account-avatar">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="7.5" r="4" />
                            <path d="M5.5 20.5a6.5 6.5 0 0 1 13 0" />
                        </svg>
                    </div>
                    <div class="star-account-text">
                        <span class="star-account-sub" title="{{ $customer->name }}">{{ $customer->name }}</span>
                        <span class="star-account-title">My Account</span>
                    </div>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdownMenu" class="profile-dropdown-menu"
                    style="display: none; position: absolute; right: 0; top: calc(100% + 8px); width: 230px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 16px 36px -4px rgba(11, 34, 18, 0.18), 0 4px 12px rgba(0, 0, 0, 0.06); z-index: 9999; overflow: hidden;">
                    <!-- User details header -->
                    <div style="padding: 12px 16px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <p style="margin: 0; font-size: 13px; font-weight: 700; color: #0f172a; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $customer->name }}
                        </p>
                        <p style="margin: 2px 0 0 0; font-size: 11px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $customer->email }}
                        </p>
                        @if(!empty($customer->business_name))
                            <span style="display: inline-block; margin-top: 6px; padding: 2px 8px; font-size: 10px; font-weight: 700; color: #144523; background: #e8f0ea; border-radius: 4px; border: 1px solid #d8e4dc;">
                                {{ $customer->business_name }}
                            </span>
                        @endif
                    </div>

                    <!-- Links -->
                    <div style="padding: 6px 0;">
                        <a href="{{ route('customer.orders.index') }}"
                            style="display: flex; align-items: center; gap: 10px; padding: 9px 16px; font-size: 13px; font-weight: 600; color: #334155; text-decoration: none; transition: background 0.15s;"
                            onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                                <line x1="3" x2="21" y1="6" y2="6"/>
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                            <span>My Orders</span>
                        </a>

                        <a href="{{ route('cart.index') }}"
                            style="display: flex; align-items: center; gap: 10px; padding: 9px 16px; font-size: 13px; font-weight: 600; color: #334155; text-decoration: none; transition: background 0.15s;"
                            onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"/>
                                <circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                            </svg>
                            <span>Shopping Cart</span>
                        </a>
                    </div>

                    <!-- Logout button inside dropdown -->
                    <div style="border-top: 1px solid #e2e8f0; padding: 6px 0;">
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit"
                                style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 9px 16px; font-size: 13px; font-weight: 600; color: #dc2626; background: none; border: none; text-align: left; cursor: pointer; transition: background 0.15s;"
                                onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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
            <!-- Not Logged In: Star Importers Style Guest Account -->
            <div class="header-profile-dropdown" id="guestDropdownContainer" style="position: relative;">
                <a href="{{ route('login') }}" class="star-account-btn" aria-label="Log in / My Account" style="text-decoration: none;">
                    <div class="star-account-avatar">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="7.5" r="4" />
                            <path d="M5.5 20.5a6.5 6.5 0 0 1 13 0" />
                        </svg>
                    </div>
                    <div class="star-account-text">
                        <span class="star-account-sub">Guest</span>
                        <span class="star-account-title">My Account</span>
                    </div>
                </a>
            </div>
            @endif

        </div>

        <button class="navtoggle" type="button" aria-expanded="false" aria-controls="navbar">
            <span></span><span></span><span></span>
            <span class="sr-only">Toggle navigation</span>
        </button>
    </div>
</header>

<nav class="navbar" id="navbar" aria-label="Categories">
    <div class="container navbar__inner">
        <!-- More Menu (Star Importers Style) -->
        <a class="navbar__more-btn" href="{{ route('home') }}">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
            <span>More Menu</span>
        </a>

        <!-- Categories List (Multi-Row Wrapping Star Importers Style) -->
        <ul class="navbar__list">
            @forelse($navCategories as $cat)
            <li class="navbar__item">
                <a href="{{ route('shop.category', $cat->id) }}" class="navbar__link">
                    <span>{{ $cat->name }}</span>
                    @if($cat->children && $cat->children->isNotEmpty())
                    <svg viewBox="0 0 24 24" width="9" height="9" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="navbar__caret">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                    @endif
                </a>
                @if($cat->children && $cat->children->isNotEmpty())
                <div class="navbar__submenu">
                    <div class="navbar__submenu-header">
                        {{ $cat->name }}
                    </div>
                    @foreach($cat->children as $child)
                    <a href="{{ route('shop.category', $child->id) }}" class="navbar__submenu-link">
                        {{ $child->name }}
                    </a>
                    @endforeach
                </div>
                @endif
            </li>
            @empty
            <li><a href="{{ route('home') }}" style="font-size: 13px; color: #64748b;">No categories yet</a></li>
            @endforelse
        </ul>

       
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const profileBtn = document.getElementById('profileDropdownBtn');
    const profileMenu = document.getElementById('profileDropdownMenu');
    
    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isVisible = profileMenu.style.display === 'block';
            profileMenu.style.display = isVisible ? 'none' : 'block';
            profileBtn.setAttribute('aria-expanded', isVisible ? 'false' : 'true');
        });

        document.addEventListener('click', function (e) {
            if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.style.display = 'none';
                profileBtn.setAttribute('aria-expanded', 'false');
            }
        });

        window.addEventListener('scroll', function () {
            if (profileMenu.style.display === 'block') {
                profileMenu.style.display = 'none';
                profileBtn.setAttribute('aria-expanded', 'false');
            }
        }, { passive: true });
    }
});
</script>