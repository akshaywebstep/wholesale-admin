@extends('frontend.layouts.app')

@section('title', 'Order ' . $order->order_number . ' — Carolina Prime Distributors')

@section('content')
<section class="section" style="padding: 40px 0;">
    <div class="container">
        
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('customer.orders.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px;">
                &larr; Back to My Orders
            </a>
            <span style="background: #f1f5f9; color: #0f172a; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 700; text-transform: uppercase;">
                {{ $order->status }}
            </span>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; align-items: start;">
            
            <!-- Left: Order Items Table -->
            <div style="background: #fff; border: 1px solid #e5e5e5; border-radius: 8px; padding: 25px;">
                <h3 style="margin-top: 0; margin-bottom: 18px; font-size: 18px;">
                    Order Items ({{ $order->order_number }})
                </h3>

                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead style="border-bottom: 1px solid #e2e8f0; text-align: left;">
                        <tr>
                            <th style="padding-bottom: 10px; color: #64748b;">Product</th>
                            <th style="padding-bottom: 10px; color: #64748b;">Variant / SKU</th>
                            <th style="padding-bottom: 10px; color: #64748b;">Price</th>
                            <th style="padding-bottom: 10px; color: #64748b;">Qty</th>
                            <th style="padding-bottom: 10px; text-align: right; color: #64748b;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        @php
                            $product = $item->variant->product ?? null;
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px 0; display: flex; align-items: center; gap: 12px;">
                                @if($product && $product->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="" style="width: 45px; height: 45px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                                @else
                                    <img src="{{ asset('images/product1.png') }}" alt="" style="width: 45px; height: 45px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                                @endif
                                <span>{{ $product->name ?? 'Product Unavailable' }}</span>
                            </td>
                            <td style="padding: 12px 0; color: #64748b;">
                                {{ $item->variant->variant_sku ?? '-' }}
                                @if(!empty($item->variant->size))
                                    <br><small>Size: {{ $item->variant->size }}</small>
                                @endif
                            </td>
                            <td style="padding: 12px 0;">₹{{ number_format($item->price, 2) }}</td>
                            <td style="padding: 12px 0;">{{ $item->quantity }}</td>
                            <td style="padding: 12px 0; text-align: right; font-weight: 600;">
                                ₹{{ number_format($item->price * $item->quantity, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Right: Order & Delivery Summary -->
            <div>
                <div style="background: #fff; border: 1px solid #e5e5e5; border-radius: 8px; padding: 25px; margin-bottom: 20px;">
                    <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 18px;">Order Summary</h3>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                        <span style="color: #64748b;">Order Date:</span>
                        <span>{{ $order->created_at->format('M d, Y h:i A') }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                        <span style="color: #64748b;">Total Units:</span>
                        <span>{{ $order->items->sum('quantity') }}</span>
                    </div>

                    <div style="border-top: 2px solid #e2e8f0; margin-top: 15px; padding-top: 15px; display: flex; justify-content: space-between; font-size: 18px; font-weight: bold;">
                        <span>Grand Total:</span>
                        <span style="color: #1a8917;">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>

                <!-- Shipping Address Card -->
                <div style="background: #fff; border: 1px solid #e5e5e5; border-radius: 8px; padding: 25px;">
                    <h4 style="margin-top: 0; margin-bottom: 12px; font-size: 16px;">Delivery Details</h4>
                    @php
                        $ship = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
                    @endphp
                    <p style="font-size: 14px; line-height: 1.6; color: #475569; margin: 0;">
                        <strong style="color: #0f172a;">{{ $ship['name'] ?? '' }}</strong><br>
                        Phone: {{ $ship['phone'] ?? '' }}<br>
                        {{ $ship['address_line_1'] ?? '' }}, {{ !empty($ship['address_line_2']) ? $ship['address_line_2'] . ',' : '' }}<br>
                        {{ $ship['city'] ?? '' }}, {{ $ship['state'] ?? '' }} - {{ $ship['pincode'] ?? '' }}<br>
                        {{ $ship['country'] ?? 'India' }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection