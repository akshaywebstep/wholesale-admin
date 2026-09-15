@extends('frontend.layouts.app')

@section('title', 'Carolina Prime Distributors | Wholesale Supply for Convenience & Smoke Shops')

@section('content')

<!-- Hero Banner Section (Full Screen Width) -->
<section class="hero-section">
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide hero-slide" style="background-image: url('{{ asset('images/hero-warehouse.jpg') }}');">
                <div class="hero-overlay-green"></div>
                <div class="hero-content-wrapper">
                    <div class="hero-text-box">
                        <span class="hero-badge hero-badge-gold">
                            ★ Premier B2B Wholesale Distributor
                        </span>
                        <h1 class="hero-title">
                            Wholesale Supply That Arrives <span style="color: #d99b26;">Before You Open</span>
                        </h1>
                        <p class="hero-desc">
                            Over 15,000 SKUs across cigars, smoke shop goods, medicine, convenience store snacks, and general merchandise with fast route delivery.
                        </p>
                        <div class="hero-btn-group">
                            @customer
                            <a href="{{ route('shop.quick-order.index') }}" class="hero-btn hero-btn-gold">
                                Quick Order &rarr;
                            </a>
                            @else
                            <a href="{{ route('register') }}" class="hero-btn hero-btn-gold">
                                Apply For Trade Account &rarr;
                            </a>
                            <a href="{{ route('login') }}" class="hero-btn hero-btn-outline">
                                Trade Member Login
                            </a>
                            @endcustomer
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="swiper-slide hero-slide" style="background-image: url('{{ asset('images/hero-warehouse.jpg') }}');">
                <div class="hero-overlay-blue"></div>
                <div class="hero-content-wrapper">
                    <div class="hero-text-box">
                        <span class="hero-badge hero-badge-blue">
                            ⚡ Direct Factory Distributor
                        </span>
                        <h2 class="hero-title">
                            Top-Selling Brands &amp; <span style="color: #60a5fa;">Master Carton Pricing</span>
                        </h2>
                        <p class="hero-desc">
                            Guaranteed authentic manufacturer batch verification, weekly restocks, and true wholesale pricing for retail business owners.
                        </p>
                        <div class="hero-btn-group">
                            @customer
                            <a href="{{ route('shop.quick-order.index') }}" class="hero-btn hero-btn-blue">
                                Browse Master Cases &rarr;
                            </a>
                            @else
                            <a href="{{ route('register') }}" class="hero-btn hero-btn-blue">
                                Register Your Store &rarr;
                            </a>
                            @endcustomer
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sleek Frosted Glass Chevron Navigation Controls -->
        <button type="button" class="hero-arrow hero-prev" aria-label="Previous slide">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
        <button type="button" class="hero-arrow hero-next" aria-label="Next slide">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>
        <div class="swiper-pagination hero-pagination"></div>
    </div>
</section>

<!-- Features Row (Star Importers 4-Pillars Style) -->
<section class="trust" aria-label="Why retailers buy from us" style="background: #ffffff; border-bottom: 1px solid #CFCFCF; padding: 24px 0;">
    <div class="container trust__grid" style="max-width: 1400px; margin: 0 auto; padding: 0 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <article class="trust__item" style="display: flex; flex-direction: column; align-items: center; text-align: center; border: none; padding: 8px;">
            <span class="trust__icon" aria-hidden="true" style="width: 68px; height: 68px; border-radius: 50%; border: 1px solid #CFCFCF; display: flex; align-items: center; justify-content: center; background: #ffffff; color: #000000; margin-bottom: 12px; box-shadow: 0 3px 6px rgba(0,0,0,0.04);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width: 28px; height: 28px;">
                    <path d="M2 7h11v9H2zM13 10h4l4 3v3h-8z" />
                    <circle cx="6" cy="18" r="1.6" />
                    <circle cx="17" cy="18" r="1.6" />
                </svg>
            </span>
            <h3 style="font-size: 19px; font-weight: 800; color: #144523; margin: 0 0 4px; font-family: 'Barlow Condensed', sans-serif; text-transform: uppercase;">Delivery</h3>
            <p style="font-size: 13px; color: #716C6C; margin: 0;">On-Time Delivery, Every Time</p>
        </article>

        <article class="trust__item" style="display: flex; flex-direction: column; align-items: center; text-align: center; border: none; padding: 8px;">
            <span class="trust__icon" aria-hidden="true" style="width: 68px; height: 68px; border-radius: 50%; border: 1px solid #CFCFCF; display: flex; align-items: center; justify-content: center; background: #ffffff; color: #000000; margin-bottom: 12px; box-shadow: 0 3px 6px rgba(0,0,0,0.04);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width: 28px; height: 28px;">
                    <rect x="2" y="5" width="20" height="14" rx="2" />
                    <line x1="2" y1="10" x2="22" y2="10" />
                </svg>
            </span>
            <h3 style="font-size: 19px; font-weight: 800; color: #144523; margin: 0 0 4px; font-family: 'Barlow Condensed', sans-serif; text-transform: uppercase;">Secure Payment</h3>
            <p style="font-size: 13px; color: #716C6C; margin: 0;">Pay, Trust, Repeat</p>
        </article>

        <article class="trust__item" style="display: flex; flex-direction: column; align-items: center; text-align: center; border: none; padding: 8px;">
            <span class="trust__icon" aria-hidden="true" style="width: 68px; height: 68px; border-radius: 50%; border: 1px solid #CFCFCF; display: flex; align-items: center; justify-content: center; background: #ffffff; color: #000000; margin-bottom: 12px; box-shadow: 0 3px 6px rgba(0,0,0,0.04);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width: 28px; height: 28px;">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                </svg>
            </span>
            <h3 style="font-size: 19px; font-weight: 800; color: #144523; margin: 0 0 4px; font-family: 'Barlow Condensed', sans-serif; text-transform: uppercase;">Best Products</h3>
            <p style="font-size: 13px; color: #716C6C; margin: 0;">All of our products are made with care and covered</p>
        </article>

        <article class="trust__item" style="display: flex; flex-direction: column; align-items: center; text-align: center; border: none; padding: 8px;">
            <span class="trust__icon" aria-hidden="true" style="width: 68px; height: 68px; border-radius: 50%; border: 1px solid #CFCFCF; display: flex; align-items: center; justify-content: center; background: #ffffff; color: #000000; margin-bottom: 12px; box-shadow: 0 3px 6px rgba(0,0,0,0.04);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width: 28px; height: 28px;">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
            </span>
            <h3 style="font-size: 19px; font-weight: 800; color: #144523; margin: 0 0 4px; font-family: 'Barlow Condensed', sans-serif; text-transform: uppercase;">Support</h3>
            <p style="font-size: 13px; color: #716C6C; margin: 0;">Contact us for any question</p>
        </article>
    </div>
</section>

<!-- Star Importers "OVER 15,000 PRODUCTS UNDER ONE ROOF" Banner Strip -->
<div class="star-roof-banner" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 20px 20px; text-align: center;">
    <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 20px;">
        <h2 style="font-family: 'Barlow Condensed', sans-serif; font-size: clamp(22px, 3.5vw, 34px); font-weight: 900; text-transform: uppercase; color: #0b2212; letter-spacing: 0.04em; margin: 0;">
            OVER 15,000 PRODUCTS UNDER ONE ROOF!
        </h2>
        <p style="font-size: 14px; color: #64748b; margin-top: 6px; font-weight: 500; margin-bottom: 0;">
            Hookah &bull; Disposable Vapes &bull; Cigars &bull; Wraps &bull; Rolling Accessories &bull; Beverages &bull; Snacks
        </p>
    </div>
</div>

<!-- =========================================================================
     DYNAMIC CATEGORY SHOWCASE SECTIONS (Exact Star Importers Style)
     Single Row Scroller - Edge Chevron Navigation - Title Case Heading
     ========================================================================= -->
@foreach($categorySections as $sec)
<section class="section star-carousel-section" id="cat-sec-{{ $sec->category->id }}" style="padding: 36px 0 24px 0; background: #ffffff;">
    <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 24px;">

        <!-- Category Title (Exact Star Importers Style) -->
        <div class="cat-section-header" style="margin-bottom: 22px;">
            <h2 class="cat-section-title">
                {{ $sec->category->name }}
            </h2>
        </div>

        <!-- Carousel Wrapper with Left & Right Edge Chevron Arrows -->
        <div class="star-carousel-wrapper">
            <!-- Left Chevron Arrow -->
            <button type="button" class="cat-prev-{{ $sec->category->id }} star-carousel-arrow star-carousel-prev" aria-label="Previous">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>

            <!-- Single Horizontal Row Scroller (5 cards per row on desktop - Star Importers Style) -->
            <div class="swiper cat-swiper-container cat-swiper-{{ $sec->category->id }}" data-cat-id="{{ $sec->category->id }}">
                <div class="swiper-wrapper">
                    @foreach($sec->products as $product)
                    <div class="swiper-slide">
                        @include('frontend.partials.product-card-star', ['product' => $product])
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Chevron Arrow -->
            <button type="button" class="cat-next-{{ $sec->category->id }} star-carousel-arrow star-carousel-next" aria-label="Next">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>
    </div>
</section>
@endforeach


@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swiper !== 'undefined') {
            // 1. Hero Swiper
            new Swiper('.hero-swiper', {
                loop: true,
                speed: 750,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                pagination: {
                    el: '.hero-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.hero-next',
                    prevEl: '.hero-prev',
                },
            });

            // 2. Category Swipers (Guaranteed Single Row Scroller, 5 Cards on Desktop - Star Importers Style)
            document.querySelectorAll('.cat-swiper-container').forEach(function(el) {
                const catId = el.dataset.catId;
                new Swiper(el, {
                    slidesPerView: 1.35,
                    spaceBetween: 14,
                    watchOverflow: true,
                    navigation: {
                        nextEl: '.cat-next-' + catId,
                        prevEl: '.cat-prev-' + catId,
                    },
                    breakpoints: {
                        480: { slidesPerView: 2, spaceBetween: 14 },
                        768: { slidesPerView: 3, spaceBetween: 16 },
                        1024: { slidesPerView: 4, spaceBetween: 16 },
                        1280: { slidesPerView: 5, spaceBetween: 18 },
                    }
                });
            });

            // 3. Brands Swiper
            new Swiper('.brands-swiper', {
                slidesPerView: 3,
                spaceBetween: 16,
                loop: true,
                autoplay: { delay: 2500, disableOnInteraction: false },
                breakpoints: {
                    640: { slidesPerView: 4, spaceBetween: 20 },
                    768: { slidesPerView: 6, spaceBetween: 24 },
                    1024: { slidesPerView: 8, spaceBetween: 24 },
                }
            });
        }
    });
</script>
@endpush
