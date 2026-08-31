@extends('frontend.layouts.app')

@section('title', 'Order #' . ($order->order_number ?? $order->id) . ' — Carolina Prime Distributors')

@section('content')
<!-- Breadcrumbs -->
<div class="breadcrumbs" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 12px 0;">
    <div class="container" style="font-size: 13px; color: #64748b;">
        <a href="{{ route('home') }}" style="color: #64748b; text-decoration: none;">Home</a> &gt;
        <a href="{{ route('customer.orders.index') }}" style="color: #64748b; text-decoration: none;">My Orders</a> &gt;
        <span style="color: #0f172a; font-weight: 600;">#{{ $order->order_number ?? $order->id }}</span>
    </div>
</div>

<section class="section" style="padding: 36px 0 60px 0; background: #f8fafc;">
    <div class="container">
        
        <!-- Top Action & Status Bar -->
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 24px;">
            <div>
                <a href="{{ route('customer.orders.index') }}" style="text-decoration: none; color: #64748b; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                    &larr; Back to Order History
                </a>
                <h1 style="font-family: 'Barlow Condensed', sans-serif; font-size: 32px; font-weight: 800; color: #0b2212; margin: 0; text-transform: uppercase;">
                    Wholesale Order #{{ $order->order_number ?? $order->id }}
                </h1>
                <p style="color: #64748b; font-size: 13px; margin: 3px 0 0 0;">
                    Placed on <strong>{{ $order->created_at->format('d F Y \a\t h:i A') }}</strong> &middot; Dispatched via Garner, NC Hub
                </p>
            </div>

            <div style="display: flex; gap: 10px; align-items: center;">
                <a href="{{ route('customer.orders.downloadInvoice', $order->id) }}" class="btn btn--primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; text-decoration: none; border-radius: 8px; background: #d99b26; color: #0b2212; border: none; box-shadow: 0 4px 12px rgba(217,155,38,0.25);">
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    <span>Download PDF Invoice</span>
                </a>
            </div>
        </div>

        @php
            $st = strtoupper($order->status);
            $stages = ['PENDING', 'CONFIRMED', 'SHIPPED', 'DELIVERED'];
            $statusIndex = array_search($st, $stages);
            if ($statusIndex === false && $st === 'CANCELLED') {
                $statusIndex = -1;
            }
        @endphp

        <!-- Visual Order Fulfillment Stepper -->
        <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 24px 28px; margin-bottom: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center; position: relative;">
                
                <!-- Stage 1: Placed -->
                <div style="display: flex; flex-direction: column; align-items: center; z-index: 2; text-align: center; width: 120px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: {{ $statusIndex >= 0 ? '#144523' : '#e2e8f0' }}; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;">
                        ✓
                    </div>
                    <strong style="font-size: 12px; color: #0b2212; margin-top: 8px;">Order Placed</strong>
                    <span style="font-size: 10.5px; color: #64748b;">{{ $order->created_at->format('d M') }}</span>
                </div>

                <!-- Line 1 -->
                <div style="flex: 1; height: 3px; background: {{ $statusIndex >= 1 ? '#144523' : '#e2e8f0' }}; margin: 0 4px; margin-bottom: 24px;"></div>

                <!-- Stage 2: Confirmed -->
                <div style="display: flex; flex-direction: column; align-items: center; z-index: 2; text-align: center; width: 120px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: {{ $statusIndex >= 1 ? '#144523' : '#e2e8f0' }}; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;">
                        {{ $statusIndex >= 1 ? '✓' : '2' }}
                    </div>
                    <strong style="font-size: 12px; color: {{ $statusIndex >= 1 ? '#0b2212' : '#94a3b8' }}; margin-top: 8px;">Trade Verified</strong>
                    <span style="font-size: 10.5px; color: #64748b;">Inventory Allocated</span>
                </div>

                <!-- Line 2 -->
                <div style="flex: 1; height: 3px; background: {{ $statusIndex >= 2 ? '#144523' : '#e2e8f0' }}; margin: 0 4px; margin-bottom: 24px;"></div>

                <!-- Stage 3: Dispatched -->
                <div style="display: flex; flex-direction: column; align-items: center; z-index: 2; text-align: center; width: 120px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: {{ $statusIndex >= 2 ? '#144523' : '#e2e8f0' }}; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;">
                        {{ $statusIndex >= 2 ? '✓' : '3' }}
                    </div>
                    <strong style="font-size: 12px; color: {{ $statusIndex >= 2 ? '#0b2212' : '#94a3b8' }}; margin-top: 8px;">Hub Dispatched</strong>
                    <span style="font-size: 10.5px; color: #64748b;">Route En Route</span>
                </div>

                <!-- Line 3 -->
                <div style="flex: 1; height: 3px; background: {{ $statusIndex >= 3 ? '#144523' : '#e2e8f0' }}; margin: 0 4px; margin-bottom: 24px;"></div>

                <!-- Stage 4: Delivered -->
                <div style="display: flex; flex-direction: column; align-items: center; z-index: 2; text-align: center; width: 120px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: {{ $statusIndex >= 3 ? '#144523' : '#e2e8f0' }}; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;">
                        {{ $statusIndex >= 3 ? '✓' : '4' }}
                    </div>
                    <strong style="font-size: 12px; color: {{ $statusIndex >= 3 ? '#0b2212' : '#94a3b8' }}; margin-top: 8px;">Delivered</strong>
                    <span style="font-size: 10.5px; color: #64748b;">Dock Drop-off</span>
                </div>

            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 28px; align-items: start;">
            
            <!-- Left: Order Items Breakdown Card -->
            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                <div style="padding: 18px 24px; border-bottom: 1.5px solid #f1f5f9; background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; font-family: 'Barlow Condensed', sans-serif; font-size: 20px; font-weight: 800; text-transform: uppercase; color: #0b2212;">
                        Purchased Items ({{ $order->items->count() }} Line Items)
                    </h3>
                    <span style="font-size: 12px; font-weight: 700; color: #16a34a; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 3px 10px; border-radius: 20px;">
                        ● Total {{ $order->items->sum('quantity') }} Units
                    </span>
                </div>

                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 13.5px;">
                        <thead>
                            <tr style="background: #fafafa; border-bottom: 1px solid #e2e8f0; text-align: left; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #64748b; letter-spacing: 0.04em;">
                                <th style="padding: 14px 20px;">Product &amp; Variant</th>
                                <th style="padding: 14px 20px;">Wholesale Rate</th>
                                <th style="padding: 14px 20px;">Qty</th>
                                <th style="padding: 14px 20px; text-align: right;">Line Subtotal</th>
                            </tr>
                        </thead>
                        <tbody style="divide-y divide-slate-100;">
                            @foreach($order->items as $item)
                            @php
                                $product = $item->variant->product ?? ($item->product ?? null);
                            @endphp 
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 16px 20px;">
                                    <div style="display: flex; align-items: center; gap: 14px;">
                                        <img src="{{ $product ? $product->featured_image_url : asset('images/product1.png') }}" 
                                             alt="" 
                                             style="width: 52px; height: 52px; object-fit: contain; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; padding: 4px;" 
                                             onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';">
                                        <div>
                                            <a href="{{ $product ? route('shop.product', $product->id) : '#' }}" style="font-weight: 700; color: #0f172a; text-decoration: none; font-size: 13.5px; display: block;">
                                                {{ $product->name ?? 'Product Unavailable' }}
                                            </a>
                                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                                SKU: <span style="font-family: monospace; font-weight: 600; color: #334155;">{{ $item->variant->variant_sku ?? ($product->sku ?? '-') }}</span>
                                                @if(!empty($item->variant->size)) &middot; Pack: <strong>{{ $item->variant->size }}</strong> @endif
                                                @if(!empty($item->variant->color)) &middot; Color: <strong>{{ $item->variant->color }}</strong> @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 16px 20px; color: #334155; font-weight: 600;">
                                    ${{ number_format($item->price, 2) }}
                                </td>
                                <td style="padding: 16px 20px;">
                                    <span style="font-weight: 800; font-size: 14px; color: #0f172a;">{{ $item->quantity }}</span>
                                    <span style="font-size: 11px; color: #64748b;">units</span>
                                </td>
                                <td style="padding: 16px 20px; text-align: right; font-weight: 800; font-family: 'Barlow Condensed', sans-serif; font-size: 17px; color: #144523;">
                                    ${{ number_format($item->price * $item->quantity, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Fulfillment Origin Box -->
                <div style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 20px; font-size: 12px; color: #64748b; display: flex; align-items: center; justify-content: space-between;">
                    <span style="display: flex; align-items: center; gap: 6px;">
                        <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 15px; height: 15px; color: #16a34a;">
                            <rect x="1" y="3" width="15" height="13" />
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                            <circle cx="5.5" cy="18.5" r="2.5" />
                            <circle cx="18.5" cy="18.5" r="2.5" />
                        </svg>
                        Origin Warehouse: <strong style="color: #0f172a;">Garner Central Distribution Hub (NC)</strong>
                    </span>
                    <span style="color: #16a34a; font-weight: 700;">● Insured Freight Transit</span>
                </div>
            </div>

            <!-- Right: Order Summary & Logistics Details -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                
                <!-- Financial Summary Card -->
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 22px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                    <h3 style="margin: 0 0 16px 0; font-family: 'Barlow Condensed', sans-serif; font-size: 20px; font-weight: 800; text-transform: uppercase; color: #0b2212; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 8px;">
                        Order Billing Summary
                    </h3>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: #64748b;">Total Units Ordered:</span>
                        <strong style="color: #0f172a;">{{ $order->items->sum('quantity') }} units</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: #64748b;">Items Subtotal:</span>
                        <span style="color: #0f172a; font-weight: 600;">${{ number_format($order->total_amount, 2) }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: #64748b;">Truck Route Delivery:</span>
                        <span style="color: #16a34a; font-weight: 700;">FREE (Trade Qualified)</span>
                    </div>

                    <div style="border-top: 2px solid #e2e8f0; margin-top: 14px; padding-top: 14px; display: flex; justify-content: space-between; align-items: baseline;">
                        <span style="font-family: 'Barlow Condensed', sans-serif; font-size: 18px; font-weight: 800; text-transform: uppercase; color: #0b2212;">Grand Total:</span>
                        <span style="font-family: 'Barlow Condensed', sans-serif; font-size: 26px; font-weight: 800; color: #144523;">
                            ${{ number_format($order->total_amount, 2) }}
                        </span>
                    </div>
                </div>

                <!-- Retailer & Shipping Address Card -->
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 22px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                    <h4 style="margin: 0 0 12px 0; font-family: 'Barlow Condensed', sans-serif; font-size: 18px; font-weight: 800; text-transform: uppercase; color: #0b2212; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 6px;">
                        Store Delivery Destination
                    </h4>
                    @php
                        $ship = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
                    @endphp
                    <div style="font-size: 13px; line-height: 1.6; color: #475569;">
                        <strong style="color: #0f172a; font-size: 14px; display: block;">{{ $ship['name'] ?? ($order->user->name ?? 'Verified Store') }}</strong>
                        @if(!empty($order->user->business_name))
                            <span style="display: block; color: #16a34a; font-weight: 600; font-size: 12px;">{{ $order->user->business_name }}</span>
                        @endif
                        <div style="margin-top: 6px;">
                            {{ $ship['address_line_1'] ?? '' }}<br>
                            @if(!empty($ship['address_line_2'])) {{ $ship['address_line_2'] }},<br> @endif
                            {{ $ship['city'] ?? '' }}, {{ $ship['state'] ?? '' }} - {{ $ship['pincode'] ?? '' }}<br>
                            {{ $ship['country'] ?? 'India' }}
                        </div>
                        <div style="margin-top: 8px; font-weight: 600; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h4l2 5-2.5 1.5a12 12 0 0 0 6 6L15 14l5 2v4a16 16 0 0 1-16-16Z" />
                            </svg>
                            Phone: {{ $ship['phone'] ?? ($order->user->phone ?? 'N/A') }}
                        </div>
                    </div>
                </div>

                <!-- Support & Assistance Card -->
                <div style="background: #fdf6e7; border: 1.5px solid #fde68a; border-radius: 12px; padding: 18px;">
                    <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; color: #b45309; margin-bottom: 4px;">
                        Need Help With This Order?
                    </div>
                    <p style="font-size: 12px; color: #78350f; margin: 0 0 10px 0; line-height: 1.4;">
                        Contact our central distributor dispatch team for route delivery tracking or invoice updates.
                    </p>
                    <a href="tel:4784445385" style="color: #0b2212; font-weight: 800; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                        📞 (478) 444-5385 &middot; Garner, NC Hub
                    </a>
                </div>

            </div>

        </div>
    </div>
</section>
@endsection