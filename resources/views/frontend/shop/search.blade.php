@extends('frontend.layouts.app')

@section('title', ($query ? 'Search: "' . $query . '"' : 'Search Products') . ' — Carolina Prime Distributors')

@section('content')
<section class="section">
    <div class="container">
        <header class="section__head" style="margin-bottom: 24px;">
            <div>
                <p class="eyebrow">Wholesale Catalog Search</p>
                <h1 class="heading">
                    @if(!empty($query))
                        Search Results for <span style="color: var(--orange-600, #b8801b);">"{{ $query }}"</span>
                    @else
                        All Wholesale Products
                    @endif
                </h1>
                <p class="section__sub">
                    Showing {{ $products->total() }} {{ \Illuminate\Support\Str::plural('product', $products->total()) }} matching your wholesale search query.
                </p>
            </div>
            @if(!empty($query))
            <a class="textlink" href="{{ route('shop.search') }}">
                Clear Search <span aria-hidden="true">&times;</span>
            </a>
            @endif
        </header>

        <div class="products" id="product-grid">
            @forelse($products as $product)
            <article class="product" data-cat="{{ $product->category_id }}" data-product-id="{{ $product->id }}">
                <div class="product__media" data-slider data-current="0" data-total="{{ $product->images->count() }}" style="position: relative; overflow: hidden;">
                    @if($product->is_active && $product->created_at && $product->created_at->gt(now()->subDays(7)))
                        <span class="badge badge--dark" style="z-index: 20;">New</span>
                    @endif

                    <a href="{{ route('shop.product', $product->id) }}" class="slider-viewport" style="display:block; width:100%; height:100%; overflow:hidden;">
                        @if($product->images && $product->images->count() > 1)
                        <div class="slider-track" style="display:flex; width:100%; height:100%; transition:transform 0.65s cubic-bezier(0.25, 1, 0.3, 1); will-change:transform;">
                            @foreach($product->images as $idx => $img)
                            <div class="slider-slide" style="flex:0 0 100%; width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                    alt="{{ $product->name }}" width="800" height="800" loading="lazy"
                                    style="width:100%; height:100%; object-fit:contain;"
                                    onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';" />
                            </div>
                            @endforeach
                        </div>
                        @else
                        <img src="{{ $product->featured_image_url }}"
                            alt="{{ $product->name }}" width="800" height="800" loading="lazy"
                            style="width:100%; height:100%; object-fit:contain;"
                            onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';" />
                        @endif
                    </a>

                    @if($product->images && $product->images->count() > 1)
                    <!-- Prev Arrow -->
                    <button type="button" class="card-slider-arrow card-slider-prev" onclick="event.preventDefault(); event.stopPropagation(); moveFrontendSlide(this, -1);">
                        &#10094;
                    </button>
                    <!-- Next Arrow -->
                    <button type="button" class="card-slider-arrow card-slider-next" onclick="event.preventDefault(); event.stopPropagation(); moveFrontendSlide(this, 1);">
                        &#10095;
                    </button>
                    @endif

                    <!-- Quick View Button -->
                    <button class="product__quick" type="button" data-id="{{ $product->id }}">Quick view</button>
                </div>

                <div class="product__body">
                    <p class="product__pack">{{ $product->category->name ?? 'Wholesale Supply' }}</p>
                    <a href="{{ route('shop.product', $product->id) }}" style="text-decoration: none; color: inherit;">
                        <h3 class="product__name" title="{{ $product->name }}">{{ $product->name }}</h3>
                    </a>

                    <div class="product__meta">
                        <span style="font-weight: 500;">SKU: {{ $product->sku ?? '-' }}</span>
                        <span>&middot;</span>
                        <span style="color: {{ $product->is_active ? '#16a34a' : '#dc2626' }}; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $product->is_active ? '#22c55e' : '#ef4444' }}; display: inline-block;"></span>
                            {{ $product->is_active ? 'In stock' : 'Out of stock' }}
                        </span>
                    </div>

                    <!-- Price Container -->
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
            <div class="products__empty" style="grid-column: 1 / -1; padding: 60px 20px; text-align: center; background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 12px; margin: 20px 0;">
                <div style="font-size: 40px; margin-bottom: 12px;">🔍</div>
                <h3 style="font-family: 'Barlow Condensed', sans-serif; font-size: 24px; font-weight: 800; text-transform: uppercase; color: #0f172a; margin-bottom: 8px;">
                    No Wholesale Products Found
                </h3>
                <p style="color: #64748b; font-size: 14px; max-width: 480px; margin: 0 auto 20px;">
                    We couldn't find any items matching <strong>"{{ $query }}"</strong>. Try searching by SKU, brand name, or broader terms like "vape", "shisha", or "e-liquid".
                </p>
                <div style="display: inline-flex; gap: 12px;">
                    <a href="{{ route('home') }}" class="btn btn--primary" style="padding: 10px 20px; font-size: 13px;">
                        &larr; Back to Home
                    </a>
                    <a href="{{ route('shop.search') }}" class="btn btn--ghost" style="padding: 10px 20px; font-size: 13px; color: #0f172a; border: 1px solid #cbd5e1;">
                        Browse Full Catalog
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        @if($products->hasPages())
        <div class="mt-6" style="margin-top: 32px; display: flex; justify-content: center;">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</section>
@endsection
