@extends('layouts.admin')

@section('content')
<div class="p-6 bg-slate-50 min-h-screen font-sans text-slate-700">
    <!-- Top Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.orders.index') }}"
                class="text-xs font-semibold text-indigo-600 hover:underline flex items-center gap-1 mb-1">
                &larr; Back to Orders List
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Order #{{ $order->order_number }}</h1>
            <p class="text-xs text-slate-400">Placed on {{ $order->created_at->format('d F Y \a\t h:i A') }}</p>
        </div>

        <!-- Order Quick Status Update Form -->
        <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
            <span class="text-xs font-semibold text-slate-500">Update Status:</span>
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST"
                class="flex items-center gap-2">
                @csrf
                <select name="status"
                    class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:outline-none focus:border-indigo-500">
                    @foreach(['PENDING', 'CONFIRMED', 'SHIPPED', 'DELIVERED', 'CANCELLED'] as $st)
                    <option value="{{ $st }}" {{ strtoupper($order->status) === $st ? 'selected' : '' }}>{{ $st }}
                    </option>
                    @endforeach
                </select>
                <button type="submit"
                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-lg transition shadow-sm">
                    Save Changes
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Column: Order Line Items -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-sm">Order Items</h3>
                </div>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50/30">
                            <th class="py-3 px-4">PRODUCT</th>
                            <th class="py-3 px-4">UNIT PRICE</th>
                            <th class="py-3 px-4">QTY</th>
                            <th class="py-3 px-4 text-right">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="py-3.5 px-4">
                                <div class="font-medium text-slate-800">{{ $item->product->name ?? ($item->variant->product->name ?? 'N/A') }}</div>
                                @if($item->variant)
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    SKU: {{ $item->variant->variant_sku }} | Size: {{ $item->variant->size }} | Color:
                                    {{ $item->variant->color }}
                                </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">₹{{ number_format($item->price, 2) }}</td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $item->quantity }}</td>
                            <td class="py-3.5 px-4 text-right font-semibold text-slate-800">
                                ₹{{ number_format($item->price * $item->quantity, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div
                    class="p-4 bg-slate-50/50 border-t border-slate-100 flex justify-between items-center text-sm font-bold text-slate-800">
                    <span>Grand Total</span>
                    <span class="text-base text-indigo-600">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Customer & Delivery Info -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white border border-slate-200/80 rounded-xl p-4 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm mb-3 border-b border-slate-100 pb-2">Customer Details</h3>
                <div class="text-xs space-y-2">
                    <p class="font-semibold text-slate-900 text-sm">{{ $order->user->name ?? 'Guest User' }}</p>
                    <p class="text-slate-500"><span class="font-medium text-slate-700">Email:</span>
                        {{ $order->user->email ?? 'N/A' }}</p>
                    <p class="text-slate-500"><span class="font-medium text-slate-700">Phone:</span>
                        {{ $order->user->phone ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white border border-slate-200/80 rounded-xl p-4 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm mb-3 border-b border-slate-100 pb-2">Shipping Address</h3>
                @if(is_array($order->shipping_address) || is_object($order->shipping_address))
                <div class="text-xs space-y-1 text-slate-600">
                    <p class="font-semibold text-slate-800">{{ $order->shipping_address['name'] ?? '' }}</p>
                    <p>{{ $order->shipping_address['address_line_1'] ?? '' }}</p>
                    <p>{{ $order->shipping_address['address_line_2'] ?? '' }}</p>
                    <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} -
                        {{ $order->shipping_address['pincode'] ?? '' }}</p>
                    <p class="font-medium text-slate-800 mt-1">Phone: {{ $order->shipping_address['phone'] ?? '' }}</p>
                </div>
                @else
                <p class="text-xs text-slate-400">No structured shipping address recorded.</p>
                @endif
            </div>

            <!-- Billing & Invoice Section -->
            <div class="bg-white border border-slate-200/80 rounded-xl p-4 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm mb-3 border-b border-slate-100 pb-2">Billing & Invoice</h3>
                @if($order->invoice)
                <div class="text-xs space-y-2">
                    <p class="text-emerald-600 font-semibold">Invoice Generated</p>
                    <p class="text-slate-500">Invoice No: {{ $order->invoice->invoice_number }}</p>

                    <!-- Download Button -->
                    <a href="{{ route('admin.orders.downloadInvoice', $order->id) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs rounded-lg transition shadow-sm mt-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download Invoice
                    </a>
                </div>
                @else
                <p class="text-xs text-amber-600">Invoice will generate automatically when order is set to
                    <strong>CONFIRMED</strong>.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection