@extends('frontend.layouts.app')

@section('title', $product->name . ' | Carolina Prime')

@section('content')
<section class="section">
    <div class="container">
        <div class="product-detail-layout"
            style="display: grid; grid-template-columns: 360px 1fr; gap: 40px; margin-top: 20px; align-items: start;">

            <!-- Compact Product Gallery Box -->
            <div class="product-gallery"
                style="width: 100%; height: fit-content; background: #fff; padding: 12px; border: 1px solid #e5e5e5; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.05);">
                @if($product->images->isNotEmpty())
                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}"
                    style="width: 100%; height: 340px; object-fit: cover; display: block; border-radius: 6px;" />
                @else
                <img src="{{ asset('images/product1.png') }}" alt="{{ $product->name }}"
                    style="width: 100%; height: 340px; object-fit: cover; display: block; border-radius: 6px;" />
                @endif
            </div>

            <!-- Product Controls & Cart Form -->
            <div class="product-info">
                <p style="color: #666; font-size: 14px; text-transform: uppercase;">
                    {{ $product->category->name ?? 'General' }}</p>
                <h1 style="font-size: 28px; margin: 8px 0;">{{ $product->name }}</h1>
                <p style="color: #777;">SKU: <strong id="display-sku">{{ $product->sku }}</strong></p>

                <!-- Pricing Display -->
                <div style="margin: 20px 0;">
                    @if(auth('customer')->check())
                    <h2 style="color: #1a8917; font-size: 26px;">
                        ₹<span
                            id="calculated-price">{{ number_format($product->priceForUser(auth('customer')->user()), 2) }}</span>
                        <span style="font-size: 14px; color: #666; font-weight: normal;">/ unit</span>
                    </h2>
                    @else
                    <a href="{{ route('login') }}" class="btn btn--outline">Log In To See Price</a>
                    @endif
                </div>

                <!-- Wholesale Tier Price Table -->
                @if($product->priceTiers->isNotEmpty() && auth('customer')->check())
                <div style="margin: 20px 0; background: #f9f9f9; padding: 15px; border-radius: 6px;">
                    <strong>Wholesale Quantity Discounts:</strong>
                    <table style="width: 100%; margin-top: 8px; font-size: 14px; border-collapse: collapse;">
                        <thead>
                            <tr style="text-align: left; border-bottom: 1px solid #ddd;">
                                <th style="padding: 4px;">Qty Range</th>
                                <th style="padding: 4px;">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->priceTiers as $tier)
                            <tr>
                                <td style="padding: 4px;">{{ $tier->min_qty }} {{ $tier->max_qty ? '- ' . $tier->max_qty . ' units' : '+ units' }}</td>
                                <td style="padding: 4px; font-weight: 700; color: #16a34a;">₹{{ number_format($tier->price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <form id="add-to-cart-form" style="margin-top: 25px;">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <!-- Variant Selection -->
                    @if($product->variants->isNotEmpty())
                    <div style="margin-bottom: 18px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 6px;">Select Variant:</label>
                        <select name="product_variant_id" id="variant-select" class="form-control"
                            style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
                            @foreach($product->variants as $variant)
                            <option value="{{ $variant->id }}" data-sku="{{ $variant->variant_sku }}">
                                {{ $variant->size ? 'Size: ' . $variant->size : '' }}
                                {{ $variant->color ? '| Color: ' . $variant->color : '' }}
                                ({{ $variant->variant_sku }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Quantity Input -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 6px;">Quantity:</label>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input type="number" name="quantity" id="product-qty" value="1" min="1"
                                style="width: 100px; padding: 10px; border-radius: 6px; border: 1px solid #ccc;" />
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submit-cart-btn" class="btn btn--primary btn--block"
                        style="padding: 12px; width: 100%; cursor: pointer;">
                        Add to Cart
                    </button>
                    <div id="cart-msg" style="margin-top: 10px; font-weight: 500;"></div>
                </form>

            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const variantSelect = document.getElementById('variant-select');
        const skuDisplay = document.getElementById('display-sku');
        const cartForm = document.getElementById('add-to-cart-form');
        const cartMsg = document.getElementById('cart-msg');
        const submitBtn = document.getElementById('submit-cart-btn');
        // Live update SKU when variant changes
        if (variantSelect) {
            variantSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.dataset.sku) {
                    skuDisplay.textContent = selectedOption.dataset.sku;
                }
            });
        }
        // Dynamic Cart Submission
        cartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.content : '';
            const formData = new FormData(cartForm);
            submitBtn.disabled = true;
            submitBtn.innerText = 'Adding...';
            fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        product_id: formData.get('product_id'),
                        product_variant_id: formData.get('product_variant_id') || null,
                        quantity: formData.get('quantity')
                    })
                })
                .then(async res => {
                    const data = await res.json();
                    if (res.status === 401 && data.redirect) {
                        window.location.href = data.redirect;
                        return;
                    }
                    if (data.success) {
                        cartMsg.style.color = 'green';
                        cartMsg.textContent = 'Item added to order successfully!';
                        // Update header badge
                        const badge = document.getElementById('cart-count');
                        if (badge) badge.textContent = data.cart_count;
                    } else {
                        cartMsg.style.color = 'red';
                        cartMsg.textContent = data.message || 'Failed to add item.';
                    }
                })
                .catch(err => {
                    cartMsg.style.color = 'red';
                    cartMsg.textContent = 'Something went wrong.';
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Add to Order';
                });
        });
    });
</script>
@endpush