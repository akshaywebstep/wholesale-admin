@php
    $activeCust = (auth('customer')->user() && auth('customer')->user()->user_type === 'CUSTOMER') 
        ? auth('customer')->user() 
        : (auth('web')->check() ? auth('web')->user() : null);
@endphp

<div class="star-product-card">
    <div>
        <!-- Product Image Container with Hover Actions (Star Importers Style) -->
        <div class="star-card-image-wrap">
            <a href="{{ route('shop.product', $product->id) }}" class="star-image-link">
                @if($product->featured_image_url)
                <img src="{{ $product->featured_image_url }}" alt="{{ $product->name }}" loading="lazy" />
                @elseif($product->images && $product->images->isNotEmpty())
                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" loading="lazy" />
                @else
                <div class="star-no-image">No Image</div>
                @endif
            </a>

            @if($product->created_at && $product->created_at->gt(now()->subDays(14)))
            <span class="star-new-badge">NEW</span>
            @endif

            <!-- Hover Circle Action Buttons: Wishlist & Quick View -->
            <div class="star-hover-actions">
                <button type="button" class="star-action-btn btn-wishlist" title="Add to Wishlist" data-id="{{ $product->id }}">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                </button>
                <button type="button" class="star-action-btn product__quick" title="Quick View" data-id="{{ $product->id }}">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Product Title -->
        <a href="{{ route('shop.product', $product->id) }}" class="star-card-title-link">
            <h3 class="star-card-title" title="{{ $product->name }}">
                {{ $product->name }}
            </h3>
        </a>

        <!-- Star Rating -->
        <div class="star-card-rating">
            <span class="stars">★★★★★</span>
            <span class="rating-count">({{ (($product->id * 3) % 17) + 5 }})</span>
        </div>

        <!-- SKU & Stock Indicator -->
        <div class="star-card-meta">
            <span class="sku">SKU: {{ $product->sku }}</span>
            <span class="stock-badge">
                <span class="stock-dot"></span>
                In stock
            </span>
        </div>
    </div>

    <!-- Bottom Price & Order Action -->
    <div class="star-card-bottom">
        <div class="star-card-price">
            @customer
            <div class="price-val">
                ${{ number_format($product->priceForUser($activeCust), 2) }}
                @if($product->unit)
                <span class="unit">/ {{ $product->unit->name }}</span>
                @endif
            </div>
            @else
            <a href="{{ route('login') }}" class="price-login-link">
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <span>Log In To See Price</span>
            </a>
            @endcustomer
        </div>

        @customer
        <a href="{{ route('shop.product', $product->id) }}" class="btn-star-order">
            Add to order
        </a>
        @else
        <a href="{{ route('login') }}" class="btn-star-login">
            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <span>Log In To Order</span>
        </a>
        @endcustomer
    </div>
</div>