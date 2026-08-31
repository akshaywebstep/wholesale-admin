@extends('frontend.layouts.app')

@section('title', 'Carolina Prime Distributors | Wholesale Supply for Convenience & Smoke Shops')

@section('content')

<!-- Hero -->
<section class="hero">
    <div class="hero__bg">
        <img src="{{ asset('images/hero-warehouse.jpg') }}" alt="" width="1600" height="900" />
    </div>
    <div class="container hero__inner">
        <div class="hero__copy">
            <p class="pill">Trusted by 2,400+ retailers</p>
            <h1 class="display">
                Wholesale supply<br />
                that arrives <em>before</em><br />
                <em>you open</em>
            </h1>
            <p class="lead">
                Over 15,000 SKUs across hookah, vape, snacks, beverages and general merchandise
                &mdash; with free next-day delivery, real wholesale pricing and no order minimums.
            </p>
            <div class="hero__cta">
                <a class="btn btn--primary" href="#deals">Browse catalog <span aria-hidden="true">&rarr;</span></a>
                <a class="btn btn--ghost" href="#account">Open a trade account</a>
            </div>
            <dl class="stats">
                <div>
                    <dt>15,000+</dt>
                    <dd>SKUs in stock</dd>
                </div>
                <div>
                    <dt>Next day</dt>
                    <dd>Free delivery</dd>
                </div>
                <div>
                    <dt>$0</dt>
                    <dd>Order minimum</dd>
                </div>
            </dl>
        </div>

                                @customer
        @php
            $activeCust = (auth('customer')->user() && auth('customer')->user()->user_type === 'CUSTOMER') ? auth('customer')->user() : auth('web')->user();
        @endphp
        <aside class="card card--form" style="background: #ffffff; border: 1.5px solid #d8e4dc; border-radius: 16px; padding: 28px; box-shadow: 0 15px 35px -5px rgba(11,34,18,0.25);">
            <div style="display: inline-flex; align-items: center; gap: 6px; background: #ecfdf5; color: #059669; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; padding: 4px 10px; border-radius: 20px; margin-bottom: 12px; border: 1px solid #a7f3d0;">
                ● Verified Trade Account
            </div>
            <h2 class="card__title" style="font-family: 'Barlow Condensed', sans-serif; font-size: 26px; font-weight: 800; text-transform: uppercase; color: #0b2212; margin-bottom: 8px;">
                Wholesale Pricing Active
            </h2>
            <p class="card__note" style="color: #546b5a; font-size: 13px; line-height: 1.5; margin-bottom: 18px;">
                Welcome back, <strong>{{ $activeCust->name }}</strong>! Live wholesale bulk discounts and case rates are unlocked.
            </p>
            <a href="#deals" class="btn btn--primary btn--block" style="padding: 13px; font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; text-align: center; text-decoration: none; display: block; margin-bottom: 12px; background: #d99b26; color: #0b2212; border: none; box-shadow: 0 4px 14px rgba(217,155,38,0.35);">
                📦 Browse Full Catalog &rarr;
            </a>
            <div style="text-align: center; font-size: 12px; color: #546b5a;">
                <a href="{{ route('cart.index') }}" style="color: #144523; font-weight: 800; text-decoration: none;">View Active Order / Cart &rarr;</a>
            </div>
        </aside>
        @else
        <aside class="card card--form" style="background: #ffffff; border: 1.5px solid #d8e4dc; border-radius: 16px; padding: 28px; box-shadow: 0 15px 35px -5px rgba(11,34,18,0.25);">
            <div style="display: inline-flex; align-items: center; gap: 6px; background: #fdf6e7; color: #b8801b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; padding: 4px 10px; border-radius: 20px; margin-bottom: 12px; border: 1px solid #fde68a;">
                ⚡ 2-Minute Trade Setup
            </div>
            <h2 class="card__title" style="font-family: 'Barlow Condensed', sans-serif; font-size: 26px; font-weight: 800; text-transform: uppercase; color: #0b2212; margin-bottom: 8px;">
                Get Wholesale Pricing
            </h2>
            <p class="card__note" style="color: #546b5a; font-size: 13px; line-height: 1.5; margin-bottom: 18px;">
                True bulk case prices, tier rebates & truck routes are visible to approved retailers only.
            </p>
            <a href="{{ route('register') }}" class="btn btn--primary btn--block" style="padding: 13px; font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; text-align: center; text-decoration: none; display: block; margin-bottom: 12px; background: #d99b26; color: #0b2212; border: none; box-shadow: 0 4px 14px rgba(217,155,38,0.35);">
                ✨ Create Trade Account &rarr;
            </a>
            <div style="text-align: center; font-size: 12px; color: #546b5a; margin-bottom: 12px;">
                Already verified? <a href="{{ route('login') }}" style="color: #144523; font-weight: 800; text-decoration: none;">Sign In &rarr;</a>
            </div>
            <p class="card__fine" style="font-size: 11px; color: #546b5a; display: flex; align-items: center; gap: 6px; border-top: 1px solid #d8e4dc; padding-top: 10px; margin-top: 8px;">
                <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:14px; height:14px; flex-shrink:0; color: #144523;">
                    <rect x="5" y="10" width="14" height="10" rx="2" />
                    <path d="M8 10V7a4 4 0 0 1 8 0v3" />
                </svg> 
                21+ state business verification required for age-restricted SKUs.
            </p>
        </aside>
        @endcustomer
    </div>
</section>

<!-- Trust strip -->
<section class="trust" aria-label="Why retailers buy from us">
    <div class="container trust__grid">
        <article class="trust__item">
            <span class="trust__icon" aria-hidden="true"><svg class="i" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M2 7h11v9H2zM13 10h4l4 3v3h-8z" />
                    <circle cx="6" cy="18" r="1.6" />
                    <circle cx="17" cy="18" r="1.6" /></svg></span>
            <div>
                <h3>Free delivery</h3>
                <p>Next-day routes across the Carolinas.</p>
            </div>
        </article>
        <article class="trust__item">
            <span class="trust__icon" aria-hidden="true"><svg class="i" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <circle cx="12" cy="12" r="8.5" />
                    <path d="M14.5 9.5A2.5 2.5 0 0 0 10 11c0 2.5 4.5 1.5 4.5 4a2.5 2.5 0 0 1-4.5 1M12 7v10" />
                </svg></span>
            <div>
                <h3>True wholesale</h3>
                <p>Case pricing with no hidden fees.</p>
            </div>
        </article>
        <article class="trust__item">
            <span class="trust__icon" aria-hidden="true"><svg class="i" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M12 3 20 7v10l-8 4-8-4V7z" />
                    <path d="M4 7l8 4 8-4M12 11v10" /></svg></span>
            <div>
                <h3>15,000+ items</h3>
                <p>One purchase order for the whole store.</p>
            </div>
        </article>
        <article class="trust__item">
            <span class="trust__icon" aria-hidden="true"><svg class="i" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M12 3 20 6v6c0 4.4-3.4 7.7-8 9-4.6-1.3-8-4.6-8-9V6z" />
                    <path d="m9 12 2 2 4-4" /></svg></span>
            <div>
                <h3>Licensed &amp; compliant</h3>
                <p>Fully permitted tobacco distributor.</p>
            </div>
        </article>
    </div>
</section>

<!-- Departments (DYNAMIC) -->
<section class="section" id="departments">
    <div class="container">
        <header class="section__head">
            <div>
                <p class="eyebrow">Shop by department</p>
                <h2 class="heading">Everything your shelves need</h2>
                <p class="section__sub">
                    Order across every department in a single invoice &mdash; picked, packed and
                    delivered by our own fleet.
                </p>
            </div>
            <a class="textlink" href="#deals">View full catalog <span aria-hidden="true">&rarr;</span></a>
        </header>

        <div class="cats">
            @forelse($categories as $category)
            <a class="cat @if($loop->first) cat--featured @endif" href="{{ route('shop.category', $category->id) }}">
                <span class="cat__icon" aria-hidden="true">
                    <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 6h16M4 12h16M4 18h16" /></svg>
                </span>
                <span class="cat__body">
                    <span class="cat__name">{{ $category->name }}</span>
                    <span class="cat__count">{{ $category->total_products_count }} items</span>
                </span>
                <span class="cat__go" aria-hidden="true">&rarr;</span>
            </a>
            @empty
            <p class="products__empty">No categories found.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- Featured deals (DYNAMIC) -->
<section class="section section--alt" id="deals">
    <div class="container">
        <header class="section__head">
            <div>
                <p class="eyebrow">This week at the warehouse</p>
                <h2 class="heading">Featured wholesale deals</h2>
                <p class="section__sub">
                    Refreshed every Monday. Log in to your trade account to view live case pricing and
                    stock levels.
                </p>
            </div>
            <a class="textlink" href="#account">See all deals <span aria-hidden="true">&rarr;</span></a>
        </header>

        <div class="filters" role="tablist" aria-label="Filter products">
            <a href="{{ url()->current() }}?category=all#deals"
                class="chip {{ !request('category') || request('category') === 'all' ? 'is-active' : '' }}">All</a>

            @foreach($categories as $category)
            <a href="{{ url()->current() }}?category={{ $category->id }}#deals"
                class="chip {{ request('category') == $category->id ? 'is-active' : '' }}">
                {{ $category->name }}
            </a>
            @endforeach
        </div>

        <div class="products" id="product-grid">
            @forelse($featuredProducts as $product)
            <article class="product" data-cat="{{ $product->category_id }}">
                <div class="product__media">
                    @if($product->is_active && $product->created_at->gt(now()->subDays(7)))
                    <span class="badge badge--dark">New</span>
                    @endif
                    <img src="{{ $product->featured_image_url }}"
                        alt="{{ $product->name }}" width="800" height="800" loading="lazy"
                        onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';" />
                    <button class="product__quick" type="button" data-id="{{ $product->id }}">Quick view</button>
                </div>
                <div class="product__body">
                    <p class="product__pack">{{ $product->category->name ?? '' }}</p>
                    <h3 class="product__name">{{ $product->name }}</h3>
                    <p class="product__meta" style="border: none; padding-bottom: 0; margin-bottom: 6px;">
                        SKU {{ $product->sku }} &middot; In stock
                    </p>

                    <div class="product__price-container"
                        style="border-top: 1px dotted #e5e7eb; border-bottom: none; padding: 10px 0; margin: 8px 0;">
                        @customer
                        @php
                            $activeCust = (auth('customer')->user() && auth('customer')->user()->user_type === 'CUSTOMER') ? auth('customer')->user() : auth('web')->user();
                        @endphp
                        <p class="product__price" style="border: none; margin: 0;">
                            ${{ number_format($product->priceForUser($activeCust), 2) }}
                        </p>
                        @else
                        <a href="{{ route('login') }}" class="product__login-price" style="border: none;">
                            <svg class="lock-icon" viewBox="0 0 24 24" width="16" height="16" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <span>Log In To See Price</span>
                        </a>
                        @endcustomer
                    </div>

                    @customer
                    <a href="{{ route('shop.product', $product->id) }}" class="btn btn--outline btn--block">
                        Add to order
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn--outline btn--block" style="color: #64748b; border-color: #cbd5e1; background: #f8fafc; font-size: 13px; font-weight: 600;">
                        <svg class="lock-icon" viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block; vertical-align:middle; margin-right:4px;">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        Log In To Order
                    </a>
                    @endcustomer
                </div>
            </article>
            @empty
            <p class="products__empty" id="empty-state">No products found.</p>
            @endforelse
        </div>

        <p class="products__empty" id="empty-state-js" hidden>No products in this category yet.</p>
    </div>
</section>

<!-- Store Type Solutions & Department Showcase (DYNAMIC) -->
<section class="section solutions" id="solutions">
    <div class="container">
        <header class="section__head section__head--center">
            <div>
                <p class="eyebrow">Department Inventory Highlights</p>
                <h2 class="heading">Tailored Solutions &amp; Category Showcase</h2>
                <p class="section__sub">
                    Explore top-selling inventory mixes, wholesale pack sizes, and direct distribution support crafted for retail shelves.
                </p>
            </div>
        </header>

        <!-- Dynamic Department Solution Tabs -->
        <div class="solution-tabs" role="tablist" aria-label="Select department">
            @foreach(($solutions ?? collect()) as $index => $sol)
            <button class="solution-tab {{ $index === 0 ? 'is-active' : '' }}" type="button" role="tab"
                aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                aria-controls="panel-{{ $sol['slug'] }}" id="tab-{{ $sol['slug'] }}" data-target="panel-{{ $sol['slug'] }}">
                <span class="solution-tab__icon">
                    <span style="font-size: 16px;">{{ $sol['icon'] }}</span>
                </span>
                <span>{{ $sol['name'] }}</span>
            </button>
            @endforeach
        </div>

        <div class="solution-panels">
            @foreach(($solutions ?? collect()) as $index => $sol)
            <div class="solution-panel {{ $index === 0 ? 'is-active' : '' }}" id="panel-{{ $sol['slug'] }}" role="tabpanel" aria-labelledby="tab-{{ $sol['slug'] }}" {{ $index === 0 ? '' : 'hidden' }}>
                <div class="solution-panel__grid">
                    <!-- Left Info -->
                    <div class="solution-info">
                        <span class="solution-badge">{{ $sol['badge'] }}</span>
                        <h3 class="solution-title">{{ $sol['tagline'] }}</h3>
                        <p class="solution-desc">
                            {{ $sol['desc'] }}
                        </p>
                        <div class="solution-stats">
                            <div class="sol-stat"><strong>{{ $sol['product_count'] }}+</strong><span>Active SKUs</span></div>
                            <div class="sol-stat"><strong>{{ $sol['sub_count'] }}</strong><span>Product Lines</span></div>
                            <div class="sol-stat"><strong>Next-Day</strong><span>Truck Drop-off</span></div>
                        </div>
                        <ul class="solution-perks">
                            @foreach($sol['perks'] as $perk)
                            <li>
                                <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                {{ $perk }}
                            </li>
                            @endforeach
                        </ul>
                    </div>

                                        <!-- Right Featured Product Showcase Card -->
                    @if($sol['product'])
                    <div class="starter-card">
                        <div class="starter-card__tag">Featured SKU</div>
                        <div class="starter-card__media">
                            <img src="{{ $sol['product']->featured_image_url }}" class="starter-card__ambient-bg" alt="" aria-hidden="true" onerror="this.style.display='none';" />
                            <a href="{{ route('shop.product', $sol['product']->id) }}" class="starter-card__img-link">
                                <img src="{{ $sol['product']->featured_image_url }}"
                                    alt="{{ $sol['product']->name }}" class="starter-card__main-img" loading="lazy"
                                    onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';" />
                            </a>
                        </div>
                        <div class="starter-card__content">
                            <p class="starter-card__cat">{{ $sol['name'] }}</p>
                            <a href="{{ route('shop.product', $sol['product']->id) }}" style="text-decoration: none; color: inherit;">
                                <h4 class="starter-card__title" style="margin-bottom: 8px;">{{ $sol['product']->name }}</h4>
                            </a>
                            <p class="starter-card__meta" style="margin-bottom: 14px;">
                                SKU: <strong>{{ $sol['product']->sku }}</strong>
                                @if($sol['product']->formatted_weight)
                                &middot; Weight: <strong>{{ $sol['product']->formatted_weight }}</strong>
                                @endif
                                &middot; <span style="color:#16a34a; font-weight:600;">● In Stock</span>
                            </p>

                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; margin-bottom: 16px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #475569;">
                                    <span>Packaging Unit:</span>
                                    <strong style="color: #0f172a;">{{ $sol['product']->unit->name ?? 'Case / Pack' }}</strong>
                                </div>
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #475569; margin-top: 4px;">
                                    <span>Availability:</span>
                                    <strong style="color: #16a34a;">Immediate Route Dispatch</strong>
                                </div>
                            </div>

                            <div class="starter-card__footer" style="padding-top: 4px;">
                                <a href="{{ route('shop.category', $sol['id']) }}" class="btn btn--primary btn--block" style="text-align: center; text-decoration: none; font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 11px;">
                                    Explore {{ $sol['name'] }} Catalog &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="starter-card" style="display:flex; align-items:center; justify-content:center; padding:30px; text-align:center;">
                        <p style="color:#64748b; font-size:14px;">Contact representative for customized {{ $sol['name'] }} inventory mixes.</p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Brands -->
<section class="brands" aria-label="Brands we distribute">
    <div class="container">
        <p class="brands__title">Authorized distributor for 300+ leading wholesale brands</p>
        <div class="swiper brands-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide"><span class="brand-pill">Al Fakher</span></div>
                <div class="swiper-slide"><span class="brand-pill">Starbuzz</span></div>
                <div class="swiper-slide"><span class="brand-pill">Zippo</span></div>
                <div class="swiper-slide"><span class="brand-pill">RAW Papers</span></div>
                <div class="swiper-slide"><span class="brand-pill">Paldo</span></div>
                <div class="swiper-slide"><span class="brand-pill">Havoline</span></div>
                <div class="swiper-slide"><span class="brand-pill">Pennzoil</span></div>
                <div class="swiper-slide"><span class="brand-pill">Celltekk</span></div>
                <div class="swiper-slide"><span class="brand-pill">Fumari</span></div>
                <div class="swiper-slide"><span class="brand-pill">Supreme</span></div>
                <div class="swiper-slide"><span class="brand-pill">BIC Lighters</span></div>
                <div class="swiper-slide"><span class="brand-pill">Clipper</span></div>
            </div>
        </div>
    </div>
</section>

<!-- Wholesale Benefits -->
<section class="section benefits benefits--dark" id="benefits">
    <div class="container">
        <header class="section__head section__head--center section__head--light">
            <div>
                <p class="eyebrow eyebrow--light">Why Retailers Partner With Us</p>
                <h2 class="heading heading--light">Built for High-Volume Store Owners</h2>
                <p class="section__sub section__sub--light">
                    Everything we do is optimized to keep your inventory fresh, your margins high, and your operational
                    hassle to zero.
                </p>
            </div>
        </header>

        <div class="benefits__grid">
            <article class="benefit-card benefit-card--glass">
                <div class="benefit-card__icon" aria-hidden="true">
                    <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" /></svg>
                </div>
                <h3>Factory-Direct Pricing</h3>
                <p>Direct relationships with top brand manufacturers allow us to provide genuine wholesale case pricing
                    with max retail margins.</p>
            </article>
            <article class="benefit-card benefit-card--glass">
                <div class="benefit-card__icon" aria-hidden="true">
                    <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="3" width="15" height="13" rx="2" />
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                        <circle cx="5.5" cy="18.5" r="2.5" />
                        <circle cx="18.5" cy="18.5" r="2.5" /></svg>
                </div>
                <h3>Dedicated Fleet Delivery</h3>
                <p>Our fleet delivery vans cover routes across North &amp; South Carolina with guaranteed morning
                    drop-offs right to your store doors.</p>
            </article>
            <article class="benefit-card benefit-card--glass">
                <div class="benefit-card__icon" aria-hidden="true">
                    <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                </div>
                <h3>Dedicated Account Rep</h3>
                <p>Get a personal account manager who knows your inventory turnover, hot sellers, and helps you optimize
                    standing weekly orders.</p>
            </article>
            <article class="benefit-card benefit-card--glass">
                <div class="benefit-card__icon" aria-hidden="true">
                    <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        <path d="m9 12 2 2 4-4" /></svg>
                </div>
                <h3>100% Licensed &amp; Compliant</h3>
                <p>Full regulatory compliance across state tobacco, vape, and food safety standards. All invoices state
                    tax compliant.</p>
            </article>
        </div>
    </div>
</section>

<!-- Retailer Reviews (Swiper Slider Carousel) -->
<section class="section reviews reviews--styled" id="reviews">
    <div class="container">
        <header class="section__head section__head--center">
            <div>
                <p class="eyebrow">Store Owner Feedback</p>
                <h2 class="heading">Trusted by 2,400+ Independent Retailers</h2>
                <p class="section__sub">
                    See how convenience store, smoke shop, and grocer owners rely on wholesale Supply for their weekly
                    inventory.
                </p>
            </div>
        </header>

        <div class="reviews-slider-wrapper">
            <div class="swiper reviews-swiper">
                <div class="swiper-wrapper">
                    <!-- Slide 1 -->
                    <div class="swiper-slide">
                        <article class="review-card">
                            <div class="review-card__top">
                                <div class="review-card__stars" aria-label="5 out of 5 stars">★★★★★</div>
                                <span class="review-card__badge">Verified Store</span>
                            </div>
                            <p class="review-card__text">
                                "wholesale Supply changed how we run inventory. Ordering before 6 PM and having trucks
                                unload by 8 AM next morning saves us from ever running out of top-selling vape and
                                shisha brands."
                            </p>
                            <div class="review-card__author">
                                <div class="review-card__avatar">MV</div>
                                <div>
                                    <strong>Marcus Vance</strong>
                                    <span>Owner, Apex Mart &amp; Tobacco (Raleigh, NC)</span>
                                </div>
                            </div>
                        </article>
                    </div>
                    <!-- Slide 2 -->
                    <div class="swiper-slide">
                        <article class="review-card">
                            <div class="review-card__top">
                                <div class="review-card__stars" aria-label="5 out of 5 stars">★★★★★</div>
                                <span class="review-card__badge">Verified Store</span>
                            </div>
                            <p class="review-card__text">
                                "The case pricing on beverages and snacks is unbeatable compared to cash-and-carry
                                places. Their sales rep even double-checks our stock before major holiday weekends!"
                            </p>
                            <div class="review-card__author">
                                <div class="review-card__avatar">HS</div>
                                <div>
                                    <strong>Harpreet Singh</strong>
                                    <span>General Manager, wholesale C-Store Chain</span>
                                </div>
                            </div>
                        </article>
                    </div>
                    <!-- Slide 3 -->
                    <div class="swiper-slide">
                        <article class="review-card">
                            <div class="review-card__top">
                                <div class="review-card__stars" aria-label="5 out of 5 stars">★★★★★</div>
                                <span class="review-card__badge">Verified Store</span>
                            </div>
                            <p class="review-card__text">
                                "Finding a single licensed distributor for glass, lighters, rolling papers, and snacks
                                on one invoice streamlined our accounting. Verification was super quick too."
                            </p>
                            <div class="review-card__author">
                                <div class="review-card__avatar">DM</div>
                                <div>
                                    <strong>David Miller</strong>
                                    <span>Operations Director, Smoke &amp; Express Stores</span>
                                </div>
                            </div>
                        </article>
                    </div>
                    <!-- Slide 4 -->
                    <div class="swiper-slide">
                        <article class="review-card">
                            <div class="review-card__top">
                                <div class="review-card__stars" aria-label="5 out of 5 stars">★★★★★</div>
                                <span class="review-card__badge">Verified Store</span>
                            </div>
                            <p class="review-card__text">
                                "Zero delivery delays for 6 months straight! The team is always responsive when we add
                                last-minute items to our daily truck shipment schedule."
                            </p>
                            <div class="review-card__author">
                                <div class="review-card__avatar">TK</div>
                                <div>
                                    <strong>Tariq Khan</strong>
                                    <span>Owner, Triangle Market &amp; Hookah Lounge</span>
                                </div>
                            </div>
                        </article>
                    </div>
                    <!-- Slide 5 -->
                    <div class="swiper-slide">
                        <article class="review-card">
                            <div class="review-card__top">
                                <div class="review-card__stars" aria-label="5 out of 5 stars">★★★★★</div>
                                <span class="review-card__badge">Verified Store</span>
                            </div>
                            <p class="review-card__text">
                                "Net 30 terms helped us scale our second gas station location smoothly. Great customer
                                support and genuine brand SKUs every single time."
                            </p>
                            <div class="review-card__author">
                                <div class="review-card__avatar">AL</div>
                                <div>
                                    <strong>Arthur Lawton</strong>
                                    <span>Manager, Charlotte Express C-Store</span>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
                <!-- Controls -->
                <div class="swiper-pagination reviews-pagination"></div>
            </div>
            <button class="slider-btn slider-btn--prev reviews-prev" type="button" aria-label="Previous review">
                <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="m15 18-6-6 6-6" /></svg>
            </button>
            <button class="slider-btn slider-btn--next reviews-next" type="button" aria-label="Next review">
                <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="m9 18 6-6-6-6" /></svg>
            </button>
        </div>
    </div>
</section>

<!-- FAQ Accordion -->
<section class="section faq faq--styled" id="faq">
    <div class="container container--narrow">
        <header class="section__head section__head--center">
            <div>
                <p class="eyebrow">Got Questions?</p>
                <h2 class="heading">Frequently Asked Questions</h2>
                <p class="section__sub">
                    Have questions about trade accounts, ordering minimums, or delivery routes? We've got answers.
                </p>
            </div>
        </header>

        <div class="accordion">
            <div class="accordion__item">
                <button class="accordion__trigger" type="button" aria-expanded="false">
                    <span>How quickly can my trade account get verified and approved?</span>
                    <svg class="i accordion__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="m6 9 6 6 6-6" /></svg>
                </button>
                <div class="accordion__content" hidden>
                    <p>Most account applications are reviewed and approved within 2 to 4 business hours after submitting
                        a valid Resale Certificate / Tax ID. Once verified, you gain instant access to live wholesale
                        pricing and online ordering.</p>
                </div>
            </div>

            <div class="accordion__item">
                <button class="accordion__trigger" type="button" aria-expanded="false">
                    <span>What are your minimum order requirements for free delivery?</span>
                    <svg class="i accordion__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="m6 9 6 6 6-6" /></svg>
                </button>
                <div class="accordion__content" hidden>
                    <p>We offer free next-day route delivery on wholesale orders over $500 across North and South
                        Carolina. For warehouse pickup at our Garner, NC distribution center, there is zero order
                        minimum!</p>
                </div>
            </div>

            <div class="accordion__item">
                <button class="accordion__trigger" type="button" aria-expanded="false">
                    <span>Do you offer Net 30 or credit terms for convenience stores?</span>
                    <svg class="i accordion__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="m6 9 6 6 6-6" /></svg>
                </button>
                <div class="accordion__content" hidden>
                    <p>Yes, approved accounts after 30 days of standing ordering history can apply for Net 15 or Net 30
                        credit terms. We accept Credit Cards, ACH Bank Transfers, and Certified Checks on delivery.</p>
                </div>
            </div>

            <div class="accordion__item">
                <button class="accordion__trigger" type="button" aria-expanded="false">
                    <span>How are age-restricted products (Tobacco, Vape) compliant?</span>
                    <svg class="i accordion__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="m6 9 6 6 6-6" /></svg>
                </button>
                <div class="accordion__content" hidden>
                    <p>We are a state-licensed tobacco distributor. All tobacco and vape orders require valid 21+
                        business license verification during account setup. Full tax manifests are attached to every
                        shipment invoice.</p>
                </div>
            </div>

            <div class="accordion__item">
                <button class="accordion__trigger" type="button" aria-expanded="false">
                    <span>What is your policy for damaged goods or returns?</span>
                    <svg class="i accordion__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="m6 9 6 6 6-6" /></svg>
                </button>
                <div class="accordion__content" hidden>
                    <p>Any items damaged during transit can be reported to your delivery driver immediately or via your
                        online account dashboard within 48 hours for an instant credit or replacement on your next
                        delivery.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta" id="account">
    <div class="container">
        <div class="cta__card">
            <div class="cta__content">
                <span class="cta__badge">
                    <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m13 2-2 10h8L7 22l2-10H1z" /></svg>
                    Fast 2-Minute Trade Setup
                </span>
                <h2 class="cta__heading">
                    Unlock Live Case Pricing &amp; Next-Day Truck Delivery
                </h2>
                <p class="cta__desc">
                    Submit your resale certificate to unlock true wholesale case pricing, volume tier rebates, and Net
                    30 credit terms.
                </p>
                <ul class="cta__list">
                    <li><svg class="i cta__check" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M20 6 9 17l-5-5" /></svg><span>Instant account verification (under 2 to 4 business
                            hours)</span></li>
                    <li><svg class="i cta__check" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M20 6 9 17l-5-5" /></svg><span>$0 minimum for Garner, NC warehouse pickup</span>
                    </li>
                    <li><svg class="i cta__check" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M20 6 9 17l-5-5" /></svg><span>Dedicated account manager &amp; standing weekly
                            delivery route</span></li>
                </ul>
                <div class="cta__actions">
                    <a class="btn btn--primary btn--lg" href="#account">Apply For Account <span
                            aria-hidden="true">&rarr;</span></a>
                    <a class="btn btn--ghost btn--lg" href="tel:4784445385">
                        <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                        Call: (478) 444-5385
                    </a>
                </div>
            </div>

                        <div class="cta__form-box" style="background: #ffffff; border-radius: 16px; padding: 32px; box-shadow: 0 15px 35px -5px rgba(0,0,0,0.25); border: 1px solid #d8e4dc;">
                @customer
                @php
                    $activeCust = (auth('customer')->user() && auth('customer')->user()->user_type === 'CUSTOMER') ? auth('customer')->user() : auth('web')->user();
                @endphp
                <div>
                    <div style="display: inline-flex; align-items: center; gap: 6px; background: #ecfdf5; color: #059669; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; padding: 4px 10px; border-radius: 20px; margin-bottom: 12px; border: 1px solid #a7f3d0;">
                        ● Verified Trade Member
                    </div>
                    <h3 style="font-family: 'Barlow Condensed', sans-serif; font-size: 26px; font-weight: 800; text-transform: uppercase; color: #0b2212; margin-bottom: 6px;">
                        Welcome, {{ $activeCust->name }}!
                    </h3>
                    <p style="color: #546b5a; font-size: 13px; margin-bottom: 20px; line-height: 1.5;">
                        Your trade account is verified &mdash; live wholesale case pricing and order dispatch are active.
                    </p>
                    <a href="{{ route('home') }}#deals" class="btn btn--primary btn--block" style="padding: 13px; font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; text-align: center; text-decoration: none; display: block; margin-bottom: 12px; background: #d99b26; color: #0b2212; border: none; box-shadow: 0 4px 14px rgba(217,155,38,0.35);">
                        📦 Browse Catalog & Place Order
                    </a>
                    <div style="text-align: center; font-size: 12px; color: #546b5a;">
                        <a href="{{ route('customer.orders.index') }}" style="color: #144523; font-weight: 800; text-decoration: none;">View Past Invoices & Orders &rarr;</a>
                    </div>
                </div>
                @else
                <div>
                    <div style="display: inline-flex; align-items: center; gap: 6px; background: #fdf6e7; color: #b8801b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; padding: 4px 10px; border-radius: 20px; margin-bottom: 12px; border: 1px solid #fde68a;">
                        ⚡ Fast 2-Minute Trade Setup
                    </div>
                    <h3 style="font-family: 'Barlow Condensed', sans-serif; font-size: 26px; font-weight: 800; text-transform: uppercase; color: #0b2212; margin-bottom: 6px;">
                        Quick Trade Access
                    </h3>
                    <p style="color: #546b5a; font-size: 13px; margin-bottom: 20px; line-height: 1.5;">
                        True wholesale case rates and standing route delivery are visible to approved retailers only.
                    </p>
                    <a href="{{ route('register') }}" class="btn btn--primary btn--block" style="padding: 13px; font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; text-align: center; text-decoration: none; display: block; margin-bottom: 12px; background: #d99b26; color: #0b2212; border: none; box-shadow: 0 4px 14px rgba(217,155,38,0.35);">
                        ✨ Create Trade Account &rarr;
                    </a>
                    <div style="text-align: center; font-size: 12px; color: #546b5a; margin-bottom: 14px;">
                        Already verified? <a href="{{ route('login') }}" style="color: #144523; font-weight: 800; text-decoration: none;">Sign In to Portal &rarr;</a>
                    </div>
                    <p class="cta__secure" style="font-size: 11px; color: #546b5a; display: flex; align-items: center; gap: 6px; border-top: 1px solid #d8e4dc; padding-top: 10px; margin-top: 8px;">
                        <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px; height:14px; color:#144523;">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                        100% Confidential &amp; Tax Compliant
                    </p>
                </div>
                @endcustomer
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.content : '';
        // Product grid container par event listener lagayein
        const productGrid = document.getElementById('product-grid');
        if (productGrid) {
            productGrid.addEventListener('click', function(e) {
                const addBtn = e.target.closest('.btn-add-to-cart');
                if (!addBtn) return;
                const productId = addBtn.dataset.productId;
                const quantity = addBtn.dataset.quantity || 1;
                const originalText = addBtn.innerHTML;
                // Loading state
                addBtn.disabled = true;
                addBtn.innerText = 'Adding...';
                fetch('{{ route("cart.add") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity
                        })
                    })
                    .then(async response => {
                        const data = await response.json();
                        // Agar user login nahi hai to login page par bhejo
                        if (response.status === 401 && data.redirect) {
                            window.location.href = data.redirect;
                            return;
                        }
                        if (data.success) {
                            // Header badge update
                            const badge = document.getElementById('cart-count');
                            if (badge) badge.textContent = data.cart_count;
                            // Success feedback
                            addBtn.innerText = 'Added!';
                            setTimeout(() => {
                                addBtn.innerHTML = originalText;
                                addBtn.disabled = false;
                            }, 1200);
                        } else {
                            alert(data.message || 'Something went wrong.');
                            addBtn.innerHTML = originalText;
                            addBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to add product to cart.');
                        addBtn.innerHTML = originalText;
                        addBtn.disabled = false;
                    });
            });
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush