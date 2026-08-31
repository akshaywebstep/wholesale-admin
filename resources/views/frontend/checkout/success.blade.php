@extends('frontend.layouts.app')

@section('title', 'Order Placed Successfully | Carolina Prime Distributors')

@section('content')
<section class="section" style="padding: 60px 0;">
    <div class="container" style="max-width: 650px; margin: 0 auto; background: #fff; padding: 35px; border-radius: 12px; border: 1px solid #e5e5e5; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        
        <!-- Success Icon -->
        <div style="width: 70px; height: 70px; background: #e8f5e9; color: #2e7d32; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <h2 style="font-size: 26px; margin: 0 0 10px; color: #1a1a1a;">Thank You For Your Order!</h2>
        <p style="color: #666; margin-bottom: 25px; font-size: 15px;">
            Your wholesale order has been submitted successfully and is pending confirmation.
        </p>

        <!-- Order Summary Box -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; text-align: left; margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 14px;">
                <span style="color: #64748b;">Order Number:</span>
                <strong style="color: #1e293b;">{{ $order->order_number }}</strong>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 14px;">
                <span style="color: #64748b;">Order Status:</span>
                <span style="background: #e2e8f0; color: #0f172a; padding: 3px 10px; border-radius: 4px; font-size: 12px; font-weight: 700; text-transform: uppercase;">
                    {{ $order->status ?? 'PENDING' }}
                </span>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 14px;">
                <span style="color: #64748b;">Total Amount:</span>
                <strong style="color: #1a8917; font-size: 16px;">${{ number_format($order->total_amount, 2) }}</strong>
            </div>

            <div style="border-top: 1px dashed #cbd5e1; margin-top: 14px; padding-top: 14px; font-size: 13px; color: #475569;">
                <strong style="color: #1e293b;">Shipping Address:</strong><br>
                @php
                    $ship = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
                @endphp
                {{ $ship['name'] ?? '' }} ({{ $ship['phone'] ?? '' }})<br>
                {{ $ship['address_line_1'] ?? '' }}, {{ !empty($ship['address_line_2']) ? $ship['address_line_2'] . ',' : '' }}<br>
                {{ $ship['city'] ?? '' }}, {{ $ship['state'] ?? '' }} - {{ $ship['pincode'] ?? '' }}
            </div>
        </div>

        <a href="{{ route('home') }}" class="btn btn--primary" style="display: inline-block; padding: 12px 28px; text-decoration: none;">
            Continue Shopping
        </a>
    </div>
</section>
@endsection