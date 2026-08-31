@extends('layouts.admin')

@section('content')
<div class="p-6 bg-slate-50 min-h-screen font-sans text-slate-700">
    
    <!-- Top Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.orders.index') }}"
                class="text-xs font-bold text-indigo-600 hover:underline flex items-center gap-1.5 mb-1.5">
                &larr; Back to All Wholesale Orders
            </a>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Order #{{ $order->order_number ?? $order->id }}</h1>
                @php
                    $pills = [
                        'PENDING'   => 'bg-amber-50 text-amber-600 border-amber-200',
                        'CONFIRMED' => 'bg-blue-50 text-blue-600 border-blue-200',
                        'SHIPPED'   => 'bg-indigo-50 text-indigo-600 border-indigo-200',
                        'DELIVERED' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                        'CANCELLED' => 'bg-rose-50 text-rose-600 border-rose-200',
                    ];
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg border text-xs font-black {{ $pills[strtoupper($order->status)] ?? 'bg-slate-100 text-slate-600' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    {{ strtoupper($order->status) }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Placed on {{ $order->created_at->format('d F Y \a\t h:i A') }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Order', 'UPDATE'))
            <div class="bg-white p-2.5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-3">
                <span class="text-xs font-bold text-slate-500 pl-2">Status:</span>
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST"
                    class="flex items-center gap-2">
                    @csrf
                    <select name="status"
                        class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                        @foreach(['PENDING', 'CONFIRMED', 'SHIPPED', 'DELIVERED', 'CANCELLED'] as $st)
                        <option value="{{ $st }}" {{ strtoupper($order->status) === $st ? 'selected' : '' }}>{{ $st }}
                        </option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition shadow-sm">
                        Update Status
                    </button>
                </form>
            </div>
            @endif

            <a href="{{ route('admin.orders.downloadInvoice', $order->id) }}"
                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-2xl transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                PDF Invoice
            </a>
        </div>
    </div>

    <!-- Fulfillment Stepper -->
    @php
        $st = strtoupper($order->status);
        $stages = ['PENDING', 'CONFIRMED', 'SHIPPED', 'DELIVERED'];
        $statusIndex = array_search($st, $stages);
        if ($statusIndex === false && $st === 'CANCELLED') {
            $statusIndex = -1;
        }
    @endphp
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 mb-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs {{ $statusIndex >= 0 ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-400' }}">
                    ✓
                </div>
                <span class="text-xs font-bold text-slate-800 mt-2">1. Order Placed</span>
                <span class="text-[10px] text-slate-400">{{ $order->created_at->format('d M, h:i A') }}</span>
            </div>
            <div class="h-0.5 flex-1 {{ $statusIndex >= 1 ? 'bg-indigo-600' : 'bg-slate-200' }} -mt-6"></div>
            
            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs {{ $statusIndex >= 1 ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-400' }}">
                    {{ $statusIndex >= 1 ? '✓' : '2' }}
                </div>
                <span class="text-xs font-bold text-slate-800 mt-2">2. Confirmed &amp; Invoiced</span>
                <span class="text-[10px] text-slate-400">Stock Allocated</span>
            </div>
            <div class="h-0.5 flex-1 {{ $statusIndex >= 2 ? 'bg-indigo-600' : 'bg-slate-200' }} -mt-6"></div>
            
            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs {{ $statusIndex >= 2 ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-400' }}">
                    {{ $statusIndex >= 2 ? '✓' : '3' }}
                </div>
                <span class="text-xs font-bold text-slate-800 mt-2">3. Dispatched (Garner Hub)</span>
                <span class="text-[10px] text-slate-400">Route Freight</span>
            </div>
            <div class="h-0.5 flex-1 {{ $statusIndex >= 3 ? 'bg-indigo-600' : 'bg-slate-200' }} -mt-6"></div>
            
            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs {{ $statusIndex >= 3 ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400' }}">
                    {{ $statusIndex >= 3 ? '✓' : '4' }}
                </div>
                <span class="text-xs font-bold text-slate-800 mt-2">4. Delivered</span>
                <span class="text-[10px] text-slate-400">Store Received</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Column: Order Line Items -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 text-sm">Line Items ({{ $order->items->count() }})</h3>
                    <span class="text-xs font-semibold text-slate-500">Total Units: {{ $order->items->sum('quantity') }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50/30">
                                <th class="py-3.5 px-4">PRODUCT DETAILS</th>
                                <th class="py-3.5 px-4">UNIT WHOLESALE</th>
                                <th class="py-3.5 px-4">QUANTITY</th>
                                <th class="py-3.5 px-4 text-right">LINE TOTAL</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach($order->items as $item)
                            @php
                                $product = $item->variant->product ?? ($item->product ?? null);
                            @endphp
                            <tr>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $product ? $product->featured_image_url : asset('images/product1.png') }}" alt="" class="w-10 h-10 object-contain rounded-lg border border-slate-200 bg-slate-50 p-1 shrink-0" onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';">
                                        <div>
                                            <div class="font-bold text-slate-800 text-sm">{{ $product->name ?? 'Product Unavailable' }}</div>
                                            <div class="text-[11px] text-slate-400 mt-0.5">
                                                SKU: <span class="font-mono text-slate-600 font-semibold">{{ $item->variant->variant_sku ?? ($product->sku ?? '-') }}</span>
                                                @if(!empty($item->variant->size)) &middot; Pack: {{ $item->variant->size }} @endif
                                                @if(!empty($item->variant->color)) &middot; Color: {{ $item->variant->color }} @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-slate-700 font-semibold">${{ number_format($item->price, 2) }}</td>
                                <td class="py-4 px-4 font-black text-slate-900">{{ $item->quantity }} <span class="text-[11px] text-slate-400 font-normal">units</span></td>
                                <td class="py-4 px-4 text-right font-black text-slate-900 text-sm">
                                    ${{ number_format($item->price * $item->quantity, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex justify-between items-center text-sm font-bold text-slate-800">
                    <span class="text-xs uppercase tracking-wider text-slate-500">Order Grand Total</span>
                    <span class="text-xl font-black text-indigo-600">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Customer & Delivery Info -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm mb-3 border-b border-slate-100 pb-2 flex items-center justify-between">
                    <span>Customer Profile</span>
                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">VERIFIED B2B</span>
                </h3>
                <div class="text-xs space-y-2">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Buyer Name</span>
                        <p class="font-bold text-slate-900 text-sm">{{ $order->user->name ?? 'Guest User' }}</p>
                    </div>
                    @if(!empty($order->user->business_name))
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Business Name</span>
                        <p class="font-semibold text-emerald-700">{{ $order->user->business_name }}</p>
                    </div>
                    @endif
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Email Address</span>
                        <p class="text-slate-700 font-medium">{{ $order->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Phone Number</span>
                        <p class="text-slate-700 font-medium">{{ $order->user->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm mb-3 border-b border-slate-100 pb-2">
                    Shipping &amp; Delivery Destination
                </h3>
                @php
                    $ship = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
                @endphp
                @if(!empty($ship))
                <div class="text-xs space-y-1 text-slate-600">
                    <p class="font-bold text-slate-800">{{ $ship['name'] ?? '' }}</p>
                    <p>{{ $ship['address_line_1'] ?? '' }}</p>
                    @if(!empty($ship['address_line_2']))
                        <p>{{ $ship['address_line_2'] }}</p>
                    @endif
                    <p class="font-medium text-slate-700">{{ $ship['city'] ?? '' }}, {{ $ship['state'] ?? '' }} - {{ $ship['pincode'] ?? '' }}</p>
                    <p class="text-slate-400">{{ $ship['country'] ?? 'India' }}</p>
                    <p class="font-bold text-slate-800 mt-2">Contact: {{ $ship['phone'] ?? '' }}</p>
                </div>
                @else
                <p class="text-xs text-slate-400">No structured shipping address recorded.</p>
                @endif
            </div>

            <!-- Billing & Invoice Section -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm mb-3 border-b border-slate-100 pb-2">Invoice Record</h3>
                @if($order->invoice)
                <div class="text-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Invoice Number:</span>
                        <strong class="font-mono text-slate-800">{{ $order->invoice->invoice_number }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Status:</span>
                        <span class="text-emerald-600 font-bold">● Active Generated</span>
                    </div>

                    <a href="{{ route('admin.orders.downloadInvoice', $order->id) }}"
                        class="w-full justify-center inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition shadow-sm mt-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download PDF Invoice
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