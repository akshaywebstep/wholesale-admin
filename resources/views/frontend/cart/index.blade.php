@extends('frontend.layouts.app')

@section('title', 'Your Cart | Carolina Prime Distributors')

@section('content')

<section class="section" id="top">
    <div class="container">
        <header class="section__head">
            <div>
                <p class="eyebrow">Your Order</p>
                <h2 class="heading">Shopping Cart</h2>
                <p class="section__sub">Review your items before checkout. Prices reflect your approved wholesale
                    tier.</p>
            </div>
        </header>

        @if($cartItems->isEmpty())
        <div class="cart-empty">
            <svg class="i" viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="9" cy="21" r="1" />
                <circle cx="20" cy="21" r="1" />
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
            </svg>
            <p class="products__empty">Your cart is empty.</p>
            <a class="btn btn--primary" href="{{ route('home') }}">Continue shopping <span
                    aria-hidden="true">&rarr;</span></a>
        </div>
        @else
        <div class="cart-layout">
            <div class="cart-table-wrap">
                <table class="cart-table" id="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cart-table-body">
                        @foreach($cartItems as $item)
                        @php
                        $price = $item->product ? $item->product->priceForUser(auth('customer')->user()) : 0;
                        $lineTotal = $price * $item->quantity;
                        @endphp
                        <tr data-cart-id="{{ $item->id }}">
                            <td class="cart-table__product">
                                @if($item->product && $item->product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}"
                                    alt="{{ $item->product->name }}" width="64" height="64" />
                                @else
                                <img src="{{ asset('images/product1.png') }}" alt="" width="64" height="64" />
                                @endif
                                <span>{{ $item->product->name ?? 'Product unavailable' }}</span>
                            </td>
                            <td>{{ $item->product->sku ?? '-' }}</td>
                            <td class="cart-item-price">₹{{ number_format($price, 2) }}</td>
                            <td>
                                <div class="qty-stepper">
                                    <button type="button" class="qty-btn qty-btn--minus" data-cart-id="{{ $item->id }}"
                                        aria-label="Decrease quantity">&minus;</button>
                                    <input type="number" min="1" value="{{ $item->quantity }}" class="cart-qty-input"
                                        data-cart-id="{{ $item->id }}" />
                                    <button type="button" class="qty-btn qty-btn--plus" data-cart-id="{{ $item->id }}"
                                        aria-label="Increase quantity">+</button>
                                </div>
                            </td>
                            <td class="cart-item-line-total">₹{{ number_format($lineTotal, 2) }}</td>
                            <td>
                                <button type="button" class="cart-remove-btn" data-cart-id="{{ $item->id }}"
                                    aria-label="Remove item">
                                    <svg class="i" viewBox="0 0 24 24" width="18" height="18" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M3 6h18" />
                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <aside class="cart-summary card">
                <h3 class="cart-summary__title">Order Summary</h3>
                <div class="cart-summary__row">
                    <span>Items</span>
                    <span id="cart-summary-count">{{ $cartItems->sum('quantity') }}</span>
                </div>
                <div class="cart-summary__row cart-summary__row--total">
                    <span>Total</span>
                    <strong id="cart-grand-total">₹{{ number_format($total, 2) }}</strong>
                </div>
                <a href="{{ route('checkout.index') }}" class="btn btn--primary btn--block"
                    style="text-align:center; display:block; text-decoration:none;">
                    Proceed to Checkout
                </a>
                <a class="btn btn--ghost btn--block" href="{{ route('home') }}">Continue shopping</a>
            </aside>
        </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.content : '';
        const tableBody = document.getElementById('cart-table-body');

        function updateHeaderBadge(count) {
            const badge = document.getElementById('cart-count');
            if (badge) badge.textContent = count;
        }

        function updateSummaryCount() {
            let total = 0;
            document.querySelectorAll('.cart-qty-input').forEach(function(input) {
                total += parseInt(input.value, 10) || 0;
            });
            const summaryCount = document.getElementById('cart-summary-count');
            if (summaryCount) summaryCount.textContent = total;
        }

        function sendUpdate(cartId, quantity, row) {
            fetch('{{ route("cart.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        cart_id: cartId,
                        quantity: quantity
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        row.querySelector('.cart-item-line-total').textContent = '₹' + data.line_total;
                        document.getElementById('cart-grand-total').textContent = '₹' + data.cart_total;
                        updateHeaderBadge(data.cart_count);
                        updateSummaryCount();
                    }
                });
        }

        function removeRow(cartId, row) {
            fetch('{{ route("cart.remove") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        cart_id: cartId
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        row.remove();
                        document.getElementById('cart-grand-total').textContent = '₹' + data.cart_total;
                        updateHeaderBadge(data.cart_count);
                        if (data.cart_count === 0) {
                            location.reload();
                        } else {
                            updateSummaryCount();
                        }
                    }
                });
        }
        // Quantity input change (typing directly)
        tableBody.addEventListener('change', function(e) {
            if (e.target.classList.contains('cart-qty-input')) {
                const row = e.target.closest('tr');
                const cartId = e.target.dataset.cartId;
                let qty = parseInt(e.target.value, 10);
                if (isNaN(qty) || qty < 1) qty = 1;
                e.target.value = qty;
                sendUpdate(cartId, qty, row);
            }
        });
        // Plus / minus buttons
        tableBody.addEventListener('click', function(e) {
            const minusBtn = e.target.closest('.qty-btn--minus');
            const plusBtn = e.target.closest('.qty-btn--plus');
            const removeBtn = e.target.closest('.cart-remove-btn');
            if (minusBtn || plusBtn) {
                const cartId = (minusBtn || plusBtn).dataset.cartId;
                const row = (minusBtn || plusBtn).closest('tr');
                const input = row.querySelector('.cart-qty-input');
                let qty = parseInt(input.value, 10) || 1;
                qty = minusBtn ? Math.max(1, qty - 1) : qty + 1;
                input.value = qty;
                sendUpdate(cartId, qty, row);
            }
            if (removeBtn) {
                const cartId = removeBtn.dataset.cartId;
                const row = removeBtn.closest('tr');
                removeRow(cartId, row);
            }
        });
    });
</script>
@endpush