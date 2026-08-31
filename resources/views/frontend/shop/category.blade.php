@extends('frontend.layouts.app')

@section('title', $category->name . ' — Carolina Prime Distributors')

@section('content')
<section class="section">
    <div class="container">
        <header class="section__head">
            <div>
                <p class="eyebrow">Shop by department</p>
                <h2 class="heading">{{ $category->name }}</h2>
            </div>
        </header>

        <div class="products" id="product-grid">
            @forelse($products as $product)
            <article class="product" data-cat="{{ $product->category_id }}">
                <div class="product__media">
                    @if($product->is_active && $product->created_at && $product->created_at->gt(now()->subDays(7)))
                        <span class="badge badge--dark">New</span>
                    @endif

                    <a href="{{ route('shop.product', $product->id) }}">
                        <img src="{{ $product->featured_image_url }}"
                            alt="{{ $product->name }}" width="800" height="800" loading="lazy"
                            onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';" />
                    </a>

                    <!-- Quick View Button -->
                    <button class="product__quick" type="button" data-id="{{ $product->id }}">Quick view</button>
                </div>

                <div class="product__body">
                    <p class="product__pack">{{ $category->name }}</p>
                    <a href="{{ route('shop.product', $product->id) }}" style="text-decoration: none; color: inherit;">
                        <h3 class="product__name">{{ $product->name }}</h3>
                    </a>

                    <!-- Dotted line fix: border none & clean spacing -->
                    <p class="product__meta" style="border: none; padding-bottom: 0; margin-bottom: 6px;">
                        SKU {{ $product->sku ?? '-' }} &middot;
                        <span class="{{ $product->is_active ? 'text-success' : 'text-danger' }}">
                            {{ $product->is_active ? 'In stock' : 'Out of stock' }}
                        </span>
                    </p>

                    <!-- Single Clean Dotted Border on Top Only -->
                    <div class="product__price-container" style="border-top: 1px dotted #e5e7eb; border-bottom: none; padding: 10px 0; margin: 8px 0;">
                        @customer
                            @php
                                $activeCust = (auth('customer')->user() && auth('customer')->user()->user_type === 'CUSTOMER') ? auth('customer')->user() : auth('web')->user();
                                $userPrice = $product->priceForUser($activeCust) ?? $product->base_price;
                            @endphp
                            <p class="product__price" style="border: none; margin: 0;">
                                ${{ number_format($userPrice, 2) }}
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
            <div class="products__empty">
                <p>No products available in this category.</p>
            </div>
            @endforelse
        </div>

        @if($products->hasPages())
        <div class="mt-6">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</section>
@endsection