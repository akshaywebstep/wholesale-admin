@extends('frontend.layouts.app')

@section('title', 'My Wholesale Orders — Carolina Prime Distributors')

@section('content')
<!-- Breadcrumbs -->
<div class="breadcrumbs" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 12px 0;">
    <div class="container" style="font-size: 13px; color: #64748b;">
        <a href="{{ route('home') }}" style="color: #64748b; text-decoration: none;">Home</a> &gt;
        <span style="color: #64748b;">Trade Portal</span> &gt;
        <span style="color: #0f172a; font-weight: 600;">My Orders</span>
    </div>
</div>

<section class="section" style="padding: 36px 0 60px 0; background: #f8fafc; min-height: calc(100vh - 400px);">
    <div class="container">
        
        <!-- Header & Account Bar -->
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 28px;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 6px; background: #ecfdf5; color: #059669; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; padding: 4px 10px; border-radius: 20px; margin-bottom: 8px; border: 1px solid #a7f3d0;">
                    ● Verified Trade Member
                </div>
                <h1 style="font-family: 'Barlow Condensed', sans-serif; font-size: 32px; font-weight: 800; color: #0b2212; margin: 0; text-transform: uppercase; letter-spacing: 0.02em;">
                    Wholesale Order History &amp; Invoices
                </h1>
                <p style="color: #546b5a; font-size: 13.5px; margin: 4px 0 0 0;">
                    Track pending order dispatches, download GST/tax compliant invoices, and manage past inventory purchases.
                </p>
            </div>

            <div>
                <a href="{{ route('home') }}#deals" class="btn btn--primary" style="padding: 11px 22px; font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; background: #d99b26; color: #0b2212; border: none; box-shadow: 0 4px 12px rgba(217,155,38,0.25);">
                    <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px; height:16px;">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                    New Wholesale Order &rarr;
                </a>
            </div>
        </div>

        @php
            $cust = auth('customer')->user() ?: auth('web')->user();
            $allOrders = \App\Models\Order::where('user_id', $cust->id)->get();
            $totalOrdersCount = $allOrders->count();
            $pendingOrdersCount = $allOrders->whereIn('status', ['PENDING', 'CONFIRMED', 'SHIPPED'])->count();
            $deliveredCount = $allOrders->where('status', 'DELIVERED')->count();
            $totalSpend = $allOrders->whereNotIn('status', ['CANCELLED'])->sum('total_amount');
        @endphp

        <!-- Quick B2B Order Metric Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 28px;">
            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Total Orders Placed</div>
                <div style="font-family: 'Barlow Condensed', sans-serif; font-size: 28px; font-weight: 800; color: #0b2212; margin-top: 4px;">
                    {{ number_format($totalOrdersCount) }}
                </div>
                <div style="font-size: 11.5px; color: #16a34a; font-weight: 600; margin-top: 2px;">Trade Account Lifetime</div>
            </div>

            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                <div style="font-size: 11px; font-weight: 700; color: #b45309; text-transform: uppercase; letter-spacing: 0.05em;">In-Process &amp; Transit</div>
                <div style="font-family: 'Barlow Condensed', sans-serif; font-size: 28px; font-weight: 800; color: #b45309; margin-top: 4px;">
                    {{ number_format($pendingOrdersCount) }}
                </div>
                <div style="font-size: 11.5px; color: #b45309; font-weight: 600; margin-top: 2px;">Garner Hub Fulfillment</div>
            </div>

            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                <div style="font-size: 11px; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: 0.05em;">Delivered Shipments</div>
                <div style="font-family: 'Barlow Condensed', sans-serif; font-size: 28px; font-weight: 800; color: #059669; margin-top: 4px;">
                    {{ number_format($deliveredCount) }}
                </div>
                <div style="font-size: 11.5px; color: #059669; font-weight: 600; margin-top: 2px;">Successfully Received</div>
            </div>

            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                <div style="font-size: 11px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.05em;">Total Wholesale Volume</div>
                <div style="font-family: 'Barlow Condensed', sans-serif; font-size: 28px; font-weight: 800; color: #144523; margin-top: 4px;">
                    ${{ number_format($totalSpend, 2) }}
                </div>
                <div style="font-size: 11.5px; color: #144523; font-weight: 600; margin-top: 2px;">Active Account Spend</div>
            </div>
        </div>

        @if($orders->isEmpty())
            <div style="background: #ffffff; padding: 60px 24px; text-align: center; border-radius: 16px; border: 1.5px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: #fdf6e7; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                    <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="#d99b26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                </div>
                <h3 style="font-family: 'Barlow Condensed', sans-serif; font-size: 24px; font-weight: 800; color: #0b2212; margin: 0 0 8px 0; text-transform: uppercase;">
                    No Wholesale Orders Placed Yet
                </h3>
                <p style="font-size: 14px; color: #64748b; max-width: 440px; margin: 0 auto 24px auto; line-height: 1.5;">
                    Your trade account is verified and ready for wholesale volume orders. Explore master cases and bulk tiered rates across our 15,000+ SKUs.
                </p>
                <a href="{{ route('home') }}#deals" class="btn btn--primary" style="padding: 12px 28px; font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px; text-decoration: none; background: #d99b26; color: #0b2212; border: none; box-shadow: 0 4px 12px rgba(217,155,38,0.3);">
                    📦 Explore Wholesale Catalog &rarr;
                </a>
            </div>
        @else
            <!-- Orders Data Card -->
            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13.5px;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1.5px solid #e2e8f0; color: #475569; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">
                                <th style="padding: 16px 20px;">Order #</th>
                                <th style="padding: 16px 20px;">Date &amp; Time</th>
                                <th style="padding: 16px 20px;">Purchased Items</th>
                                <th style="padding: 16px 20px;">Total Amount</th>
                                <th style="padding: 16px 20px;">Fulfillment Status</th>
                                <th style="padding: 16px 20px; text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody style="divide-y divide-slate-100;">
                            @foreach($orders as $order)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                                <td style="padding: 16px 20px; font-weight: 800; color: #0b2212; font-family: monospace; font-size: 14px;">
                                    #{{ $order->order_number ?? $order->id }}
                                </td>
                                <td style="padding: 16px 20px; color: #64748b; font-size: 12.5px;">
                                    <div style="font-weight: 600; color: #334155;">{{ $order->created_at->format('M d, Y') }}</div>
                                    <div style="font-size: 11px; color: #94a3b8;">{{ $order->created_at->format('h:i A') }}</div>
                                </td>
                                <td style="padding: 16px 20px; color: #334155;">
                                    <div style="font-weight: 700; color: #0f172a;">
                                        {{ $order->items->sum('quantity') }} units
                                        <span style="font-weight: normal; color: #64748b; font-size: 12px;">({{ $order->items->count() }} unique SKUs)</span>
                                    </div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                        @foreach($order->items->take(2) as $idx => $it)
                                            {{ $it->product->name ?? ($it->variant->product->name ?? 'SKU Item') }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                        @if($order->items->count() > 2)
                                            <span style="color: #94a3b8;">+{{ $order->items->count() - 2 }} more</span>
                                        @endif
                                    </div>
                                </td>
                                <td style="padding: 16px 20px; font-weight: 800; font-family: 'Barlow Condensed', sans-serif; font-size: 18px; color: #144523;">
                                    ${{ number_format($order->total_amount, 2) }}
                                </td>
                                <td style="padding: 16px 20px;">
                                    @php
                                        $st = strtoupper($order->status);
                                        $styles = [
                                            'PENDING'   => ['bg' => '#fef3c7', 'text' => '#b45309', 'border' => '#fde68a', 'label' => 'Order Received'],
                                            'CONFIRMED' => ['bg' => '#eff6ff', 'text' => '#1d4ed8', 'border' => '#bfdbfe', 'label' => 'Confirmed & Queued'],
                                            'SHIPPED'   => ['bg' => '#eef2ff', 'text' => '#4338ca', 'border' => '#c7d2fe', 'label' => 'In Transit / Dispatched'],
                                            'DELIVERED' => ['bg' => '#ecfdf5', 'text' => '#059669', 'border' => '#a7f3d0', 'label' => 'Delivered'],
                                            'CANCELLED' => ['bg' => '#ffe4e6', 'text' => '#e11d48', 'border' => '#fecdd3', 'label' => 'Cancelled'],
                                        ];
                                        $curr = $styles[$st] ?? ['bg' => '#f1f5f9', 'text' => '#475569', 'border' => '#cbd5e1', 'label' => $st];
                                    @endphp
                                    <span style="display: inline-flex; align-items: center; gap: 5px; background: {{ $curr['bg'] }}; color: {{ $curr['text'] }}; border: 1px solid {{ $curr['border'] }}; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.03em;">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $curr['text'] }};"></span>
                                        {{ $curr['label'] }}
                                    </span>
                                </td>
                                <td style="padding: 16px 20px; text-align: right; white-space: nowrap;">
                                    <a href="{{ route('customer.orders.downloadInvoice', $order->id) }}" class="btn btn--sm" style="display: inline-flex; align-items: center; gap: 5px; padding: 7px 12px; font-size: 12px; font-weight: 700; text-decoration: none; border: 1.5px solid #cbd5e1; background: #ffffff; color: #334155; border-radius: 6px; margin-right: 6px; transition: all 0.15s;" title="Download Official Tax Invoice">
                                        <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                            <polyline points="7 10 12 15 17 10"/>
                                            <line x1="12" y1="15" x2="12" y2="3"/>
                                        </svg>
                                        <span>Invoice</span>
                                    </a>
                                    <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn--outline btn--sm" style="display: inline-flex; align-items: center; gap: 4px; padding: 7px 14px; font-size: 12px; font-weight: 700; text-decoration: none; border-radius: 6px;">
                                        <span>Details</span> &rarr;
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="margin-top: 24px; display: flex; justify-content: center;">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</section>
@endsection