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
            <a class="btn btn--cart btn--sm" href="{{ route('cart.index') }}" aria-label="Cart">
                <svg class="i" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="21" r="1" />
                    <circle cx="20" cy="21" r="1" />
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                </svg>
                <span class="btn__text">Cart (<span
                        id="cart-count">{{ auth('customer')->check() ? \App\Models\Cart::where('user_id', auth('customer')->id())->count() : 0 }}</span>)</span>
            </a>
            <!-- My Orders Button -->
            <a class="btn btn--login btn--sm" href="{{ route('customer.orders.index') }}"
                style="border: 1px solid #cbd5e1; text-decoration: none; margin-right: 6px;">
                <svg class="i" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                    <line x1="3" x2="21" y1="6" y2="6" />
                    <path d="M16 10a4 4 0 0 1-8 0" />
                </svg>
                <span class="btn__text">My Orders</span>
            </a>
            <!-- Customer Logged In: Show Logout -->
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn--login btn--sm"
                    style="background: none; border: 1px solid #cbd5e1; cursor: pointer;">
                    <svg class="i" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span class="btn__text">Logout</span>
                </button>
            </form>
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