@extends('frontend.layouts.app')

@section('title', 'My Orders — Carolina Prime Distributors')

@section('content')
<section class="section" style="padding: 40px 0; min-height: calc(100vh - 450px);">
    <div class="container">
        <header class="section__head" style="margin-bottom: 25px;">
            <div>
                <p class="eyebrow">Account</p>
                <h2 class="heading">My Wholesale Orders</h2>
            </div>
        </header>

        @if($orders->isEmpty())
            <div style="background: #fff; padding: 50px 20px; text-align: center; border-radius: 8px; border: 1px solid #e5e5e5;">
                <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px;">
                    <circle cx="9" cy="21" r="1" />
                    <circle cx="20" cy="21" r="1" />
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                </svg>
                <p style="font-size: 16px; color: #64748b; margin-bottom: 18px;">You have not placed any orders yet.</p>
                <a href="{{ route('home') }}" class="btn btn--primary">Start Shopping</a>
            </div>
        @else
            <div style="background: #fff; border: 1px solid #e5e5e5; border-radius: 8px; overflow-x: auto; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                    <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <tr>
                            <th style="padding: 14px 16px; color: #475569;">Order #</th>
                            <th style="padding: 14px 16px; color: #475569;">Date</th>
                            <th style="padding: 14px 16px; color: #475569;">Total Items</th>
                            <th style="padding: 14px 16px; color: #475569;">Total Amount</th>
                            <th style="padding: 14px 16px; color: #475569;">Status</th>
                            <th style="padding: 14px 16px; text-align: right; color: #475569;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 16px; font-weight: 600; color: #0f172a;">{{ $order->order_number }}</td>
                            <td style="padding: 14px 16px; color: #64748b;">{{ $order->created_at->format('M d, Y') }}</td>
                            <td style="padding: 14px 16px;">{{ $order->items->sum('quantity') }} units</td>
                            <td style="padding: 14px 16px; font-weight: 600; color: #1a8917;">₹{{ number_format($order->total_amount, 2) }}</td>
                            <td style="padding: 14px 16px;">
                                <span style="background: #f1f5f9; color: #0f172a; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 700; text-transform: uppercase;">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td style="padding: 14px 16px; text-align: right;">
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn--outline btn--sm" style="padding: 6px 14px; font-size: 13px; text-decoration: none;">
                                    View Details &rarr;
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px;">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</section>
@endsection