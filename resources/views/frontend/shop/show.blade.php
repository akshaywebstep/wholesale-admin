@extends('frontend.layouts.app')

@section('title', $product->name . ' - Wholesale Order | Carolina Prime')

@section('content')
<!-- Breadcrumbs -->
<div class="breadcrumbs" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 12px 0;">
    <div class="container" style="font-size: 13px; color: #64748b;">
        <a href="{{ route('home') }}" style="color: #64748b; text-decoration: none;">Home</a> &gt;
        @if($product->category)
        <a href="{{ route('shop.category', $product->category->id) }}" style="color: #64748b; text-decoration: none;">
            {{ $product->category->name }}
        </a> &gt;
        @endif
        <span style="color: #0f172a; font-weight: 600;">{{ $product->name }}</span>
    </div>
</div>

<section class="section" style="padding: 40px 0; background: #ffffff;">
    <div class="container">
        <!-- Main Top 2-Column Product Layout -->
        <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 48px; align-items: start;">
            
            <!-- Left: Product Media Gallery & Quick Specifications -->
            <div class="product-gallery">
                <div style="background: radial-gradient(circle at center, #ffffff 0%, #f8fafc 100%); border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 28px; text-align: center; overflow: hidden; position: relative; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                    <img id="main-product-image" src="{{ $product->featured_image_url }}"
                        alt="{{ $product->name }}"
                        style="width: 100%; max-height: 440px; object-fit: contain; filter: drop-shadow(0 14px 28px rgba(11,34,18,0.15));"
                        onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';" />
                </div>

                <!-- Fast Wholesale Logistics Specs Box (Integrated right under image) -->
                <div style="margin-top: 20px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 20px;">
                    <h4 style="font-family: 'Barlow Condensed', sans-serif; font-size: 16px; font-weight: 800; text-transform: uppercase; color: #0b2212; margin: 0 0 12px 0; display: flex; align-items: center; gap: 8px;">
                        <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; color: #16a34a;">
                            <rect x="1" y="3" width="15" height="13" />
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                            <circle cx="5.5" cy="18.5" r="2.5" />
                            <circle cx="18.5" cy="18.5" r="2.5" />
                        </svg>
                        Wholesale Distribution Specs
                    </h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 12px;">
                        <div style="color: #64748b;">Dispatch Hub: <strong style="color: #0f172a; display: block;">Garner, NC Central</strong></div>
                        <div style="color: #64748b;">Route Delivery: <strong style="color: #16a34a; display: block;">Next-Day Drop-off</strong></div>
                        <div style="color: #64748b;">Packaging: <strong style="color: #0f172a; display: block;">{{ $product->unit->name ?? 'Standard Case' }}</strong></div>
                        <div style="color: #64748b;">Weight: <strong style="color: #0f172a; display: block;">{{ $product->formatted_weight ?? 'Standard' }}</strong></div>
                    </div>
                </div>
            </div>

            <!-- Right: Product Information, Specifications, Pricing & Order Formulation -->
            <div class="product-details">
                @if($product->category)
                <span style="display: inline-block; background: #fdf6e7; color: #b8801b; border: 1px solid #fde68a; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; padding: 4px 10px; border-radius: 20px; margin-bottom: 12px;">
                    {{ $product->category->name }}
                </span>
                @endif

                <h1 style="font-family: 'Barlow Condensed', sans-serif; font-size: 32px; font-weight: 800; color: #0b2212; line-height: 1.15; margin: 0 0 10px 0; text-transform: uppercase;">
                    {{ $product->name }}
                </h1>

                <p style="font-size: 13px; color: #546b5a; margin-bottom: 16px;">
                    SKU: <strong id="display-sku" style="color: #0b2212;">{{ $product->sku ?? '-' }}</strong>
                    &middot; <span style="color: #16a34a; font-weight: 700;">● Active Inventory</span>
                    @if($product->formatted_weight)
                    &middot; <span>Net Weight: <strong>{{ $product->formatted_weight }}</strong></span>
                    @endif
                </p>

                <!-- Product Specifications & Overview (Integrated TOP right under title & SKU) -->
                @if($product->description)
                <div style="margin-bottom: 22px; background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                    <h3 style="font-family: 'Barlow Condensed', sans-serif; font-size: 18px; font-weight: 800; color: #0b2212; margin: 0 0 10px 0; text-transform: uppercase; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                        <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; color: #d99b26;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                            <polyline points="10 9 9 9 8 9" />
                        </svg>
                        Product Specifications &amp; Overview
                    </h3>
                    <div class="product-description-content" style="color: #334155; font-size: 13.5px; line-height: 1.65;">
                        {!! $product->description !!}
                    </div>
                </div>
                @endif

                <!-- Pricing Display Block -->
                <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px 22px; margin-bottom: 22px;">
                    @customer
                    @php
                        $activeCust = (auth('customer')->user() && auth('customer')->user()->user_type === 'CUSTOMER') ? auth('customer')->user() : auth('web')->user();
                        $custPrice = $product->priceForUser($activeCust) ?? $product->base_price;
                    @endphp
                    <div style="display: flex; align-items: baseline; gap: 8px;">
                        <span style="font-size: 12px; color: #546b5a; font-weight: 700; text-transform: uppercase;">Unit Wholesale Rate:</span>
                        <h2 style="color: #16a34a; font-size: 32px; font-weight: 800; font-family: 'Barlow Condensed', sans-serif; margin: 0;">
                            $<span id="calculated-unit-price">{{ number_format($custPrice, 2) }}</span>
                        </h2>
                        <span style="font-size: 13px; color: #64748b;">/ {{ $product->unit->name ?? 'unit' }}</span>
                    </div>
                    <div style="margin-top: 6px; font-size: 13px; color: #334155;">
                        Estimated Line Total: <strong style="color: #0b2212; font-size: 17px; font-family: 'Barlow Condensed', sans-serif;">$<span id="calculated-line-total">{{ number_format($custPrice, 2) }}</span></strong>
                    </div>
                    @else
                    <div style="text-align: center; padding: 8px 0;">
                        <p style="color: #64748b; font-size: 13px; margin-bottom: 8px;">Wholesale pricing is restricted to verified trade accounts.</p>
                        <a href="{{ route('login') }}" class="btn btn--outline" style="display: inline-block; padding: 9px 20px; font-size: 13px; font-weight: 700;">
                            🔒 Log In / Register to View Wholesale Rates
                        </a>
                    </div>
                    @endcustomer
                </div>

                <!-- Wholesale Tier Price Table -->
                @customer
                @if($product->priceTiers->isNotEmpty())
                <div style="margin-bottom: 22px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden;">
                    <div style="background: #f1f5f9; padding: 10px 14px; font-size: 12px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.04em;">
                        ⚡ Bulk Quantity Volume Discounts
                    </div>
                    <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                        <thead>
                            <tr style="text-align: left; background: #fafafa; border-bottom: 1px solid #e2e8f0; color: #64748b;">
                                <th style="padding: 8px 14px;">Quantity Tier</th>
                                <th style="padding: 8px 14px; text-align: right;">Unit Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->priceTiers as $tier)
                            <tr class="tier-row" data-min="{{ $tier->min_qty }}" data-max="{{ $tier->max_qty ?? 999999 }}" data-price="{{ (float)$tier->price }}"
                                style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                                <td style="padding: 8px 14px; font-weight: 500;">
                                    {{ $tier->min_qty }} {{ $tier->max_qty ? '- ' . $tier->max_qty . ' units' : '+ units' }}
                                </td>
                                <td style="padding: 8px 14px; font-weight: 700; color: #16a34a; text-align: right;">
                                    ${{ number_format($tier->price, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @endcustomer

                <!-- Order Formulation Form -->
                <form id="add-to-cart-form" method="POST" action="{{ route('cart.add') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <!-- Professional Variant / Pack Configuration Selector (Pills) -->
                    @if($product->variants->isNotEmpty())
                    <div style="margin-bottom: 22px;">
                        <label style="display: block; font-size: 12px; font-weight: 800; color: #0b2212; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.05em;">
                            Select Pack Configuration / Variant:
                        </label>
                        <div class="variant-pills-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px;">
                            @foreach($product->variants as $index => $variant)
                            <label class="variant-pill-label {{ $index === 0 ? 'is-active-pill' : '' }}"
                                   data-sku="{{ $variant->variant_sku }}"
                                   style="cursor: pointer; display: block; position: relative;">
                                <input type="radio" name="product_variant_id" value="{{ $variant->id }}"
                                       {{ $index === 0 ? 'checked' : '' }}
                                       class="variant-radio-input" style="display: none;" />
                                <div class="variant-pill-card"
                                     style="border: 2px solid {{ $index === 0 ? '#144523' : '#cbd5e1' }}; background: {{ $index === 0 ? '#f0fdf4' : '#ffffff' }}; border-radius: 10px; padding: 12px 14px; transition: all 0.2s ease;">
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <strong style="font-size: 13px; color: #0f172a;">
                                            {{ $variant->size ?: ($variant->color ?: 'Standard Pack') }}
                                        </strong>
                                        <span class="variant-pill-indicator"
                                              style="width: 18px; height: 18px; border-radius: 50%; border: 1.5px solid {{ $index === 0 ? '#144523' : '#94a3b8' }}; background: {{ $index === 0 ? '#144523' : 'transparent' }}; display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 11px; font-weight: bold;">
                                            {{ $index === 0 ? '✓' : '' }}
                                        </span>
                                    </div>
                                    @if($variant->color && $variant->size)
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Color: {{ $variant->color }}</div>
                                    @endif
                                    <div style="font-size: 11px; color: #475569; margin-top: 4px; font-weight: 500;">
                                        SKU: <span style="font-family: monospace;">{{ $variant->variant_sku }}</span>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Quantity Input with Stepper Controls -->
                    <div style="margin-bottom: 22px;">
                        <label style="display: block; font-size: 12px; font-weight: 800; color: #0b2212; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.04em;">
                            Order Quantity:
                        </label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <button type="button" id="btn-qty-minus"
                                style="width: 44px; height: 44px; background: #f1f5f9; border: 1.5px solid #cbd5e1; border-radius: 8px; font-weight: 800; font-size: 20px; color: #334155; cursor: pointer; transition: all 0.15s;">
                                -
                            </button>
                            <input type="number" name="quantity" id="product-qty" value="1" min="1" step="1"
                                style="width: 100px; height: 44px; text-align: center; font-size: 17px; font-weight: 800; border-radius: 8px; border: 2px solid #cbd5e1; outline: none; background:#ffffff;" />
                            <button type="button" id="btn-qty-plus"
                                style="width: 44px; height: 44px; background: #f1f5f9; border: 1.5px solid #cbd5e1; border-radius: 8px; font-weight: 800; font-size: 20px; color: #334155; cursor: pointer; transition: all 0.15s;">
                                +
                            </button>
                            <span style="font-size: 14px; color: #64748b; margin-left: 8px; font-weight: 600;">{{ $product->unit->name ?? 'Units' }}</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    @customer
                    <button type="submit" id="submit-cart-btn" class="btn btn--primary btn--block"
                        style="padding: 15px; width: 100%; cursor: pointer; font-size: 15px; font-weight: 800; border-radius: 8px; text-transform: uppercase; letter-spacing: 0.05em; background: #d99b26; color: #0b2212; border: none; box-shadow: 0 4px 14px rgba(217,155,38,0.35); transition: all 0.2s;">
                        🛒 Add <span id="btn-qty-label">1</span> {{ $product->unit->name ?? 'Units' }} to Wholesale Cart
                    </button>
                    @else
                    <a href="{{ route('login') }}" class="btn btn--primary btn--block"
                        style="padding: 15px; width: 100%; display: block; text-align: center; text-decoration: none; font-size: 14px; font-weight: 800; border-radius: 8px; text-transform: uppercase; letter-spacing: 0.05em; background: #d99b26; color: #0b2212; border: none; box-shadow: 0 4px 14px rgba(217,155,38,0.35);">
                        🔒 Sign In to Place Wholesale Order
                    </a>
                    @endcustomer

                    <div id="cart-msg" style="margin-top: 14px; font-size: 13px; font-weight: 600; text-align: center;"></div>
                </form>

            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const basePrice = {{ (float) $product->base_price }};
    const tiers = [
        @foreach($product->priceTiers as $t)
        { min: {{ (int)$t->min_qty }}, max: {{ (int)($t->max_qty ?? 9999999) }}, price: {{ (float)$t->price }} },
        @endforeach
    ];

    const qtyInput = document.getElementById('product-qty');
    const btnMinus = document.getElementById('btn-qty-minus');
    const btnPlus = document.getElementById('btn-qty-plus');
    const btnQtyLabel = document.getElementById('btn-qty-label');
    const unitPriceEl = document.getElementById('calculated-unit-price');
    const lineTotalEl = document.getElementById('calculated-line-total');
    const skuDisplay = document.getElementById('display-sku');
    const cartForm = document.getElementById('add-to-cart-form');
    const cartMsg = document.getElementById('cart-msg');
    const submitBtn = document.getElementById('submit-cart-btn');

    // 1. Live Dynamic Pricing Calculation based on Quantity
    function recalculatePricing() {
        if (!qtyInput) return;
        let qty = parseInt(qtyInput.value) || 1;
        if (qty < 1) qty = 1;
        qtyInput.value = qty;

        if (btnQtyLabel) btnQtyLabel.textContent = qty;

        // Determine matching tier
        let unitRate = basePrice;
        for (let i = 0; i < tiers.length; i++) {
            if (qty >= tiers[i].min && qty <= tiers[i].max) {
                unitRate = tiers[i].price;
                break;
            }
        }

        // Highlight active tier in table
        document.querySelectorAll('.tier-row').forEach(row => {
            const rMin = parseInt(row.dataset.min);
            const rMax = parseInt(row.dataset.max);
            if (qty >= rMin && qty <= rMax) {
                row.style.backgroundColor = '#ecfdf5';
                row.style.fontWeight = '700';
            } else {
                row.style.backgroundColor = '';
                row.style.fontWeight = '500';
            }
        });

        if (unitPriceEl) {
            unitPriceEl.textContent = unitRate.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        if (lineTotalEl) {
            const lineTotal = unitRate * qty;
            lineTotalEl.textContent = lineTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }

    if (qtyInput) {
        qtyInput.addEventListener('input', recalculatePricing);
        qtyInput.addEventListener('change', recalculatePricing);
    }
    if (btnMinus) {
        btnMinus.addEventListener('click', function() {
            if (!qtyInput) return;
            let current = parseInt(qtyInput.value) || 1;
            if (current > 1) {
                qtyInput.value = current - 1;
                recalculatePricing();
            }
        });
    }
    if (btnPlus) {
        btnPlus.addEventListener('click', function() {
            if (!qtyInput) return;
            let current = parseInt(qtyInput.value) || 1;
            qtyInput.value = current + 1;
            recalculatePricing();
        });
    }

    // Initial pricing sync
    if (qtyInput) {
        recalculatePricing();
    }

    // 2. Interactive Variant Pill Selection & Live SKU Switcher
    const variantLabels = document.querySelectorAll('.variant-pill-label');
    variantLabels.forEach(label => {
        label.addEventListener('click', function() {
            // Unselect all
            variantLabels.forEach(l => {
                const card = l.querySelector('.variant-pill-card');
                const ind = l.querySelector('.variant-pill-indicator');
                if (card) {
                    card.style.borderColor = '#cbd5e1';
                    card.style.backgroundColor = '#ffffff';
                }
                if (ind) {
                    ind.style.borderColor = '#94a3b8';
                    ind.style.backgroundColor = 'transparent';
                    ind.textContent = '';
                }
            });

            // Select active
            const radio = this.querySelector('.variant-radio-input');
            if (radio) radio.checked = true;

            const card = this.querySelector('.variant-pill-card');
            const ind = this.querySelector('.variant-pill-indicator');
            if (card) {
                card.style.borderColor = '#144523';
                card.style.backgroundColor = '#f0fdf4';
            }
            if (ind) {
                ind.style.borderColor = '#144523';
                ind.style.backgroundColor = '#144523';
                ind.textContent = '✓';
            }

            // Live SKU update
            if (this.dataset.sku && skuDisplay) {
                skuDisplay.textContent = this.dataset.sku;
            }
        });
    });

    // 3. Robust Dynamic Cart Submission
    if (cartForm && submitBtn) {
        cartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.content : '';
            const formData = new FormData(cartForm);

            submitBtn.disabled = true;
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.innerText = 'ADDING TO CART...';
            if (cartMsg) cartMsg.textContent = '';

            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    product_id: parseInt(formData.get('product_id')),
                    product_variant_id: formData.get('product_variant_id') ? parseInt(formData.get('product_variant_id')) : null,
                    quantity: parseInt(formData.get('quantity')) || 1
                })
            })
            .then(async res => {
                const data = await res.json();
                if (res.status === 401 && data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }
                if (data.success) {
                    if (cartMsg) {
                        cartMsg.style.color = '#16a34a';
                        cartMsg.innerHTML = '✓ Added ' + (formData.get('quantity') || 1) + ' unit(s) to your wholesale cart! <a href="{{ route("cart.index") }}" style="color:#2563eb; text-decoration:underline; font-weight:700;">View Cart &rarr;</a>';
                    }
                    
                    // Update header badge
                    const badge = document.getElementById('cart-count');
                    if (badge) badge.textContent = data.cart_count;
                } else {
                    if (cartMsg) {
                        cartMsg.style.color = '#dc2626';
                        cartMsg.textContent = '✗ ' + (data.message || 'Failed to add item to cart.');
                    }
                }
            })
            .catch(err => {
                console.error('Cart add error:', err);
                if (cartMsg) {
                    cartMsg.style.color = '#dc2626';
                    cartMsg.textContent = '✗ Network error while updating cart. Please try again.';
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
    }
});
</script>
@endpush