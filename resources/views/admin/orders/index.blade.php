@extends('layouts.admin')

@section('content')
<div class="p-6 bg-slate-50 min-h-screen font-sans text-slate-700">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <span>E-Commerce &amp; B2B Wholesale</span>
                <span>/</span>
                <span class="text-indigo-600">Orders Management</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Wholesale Orders &amp; Fulfillment</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage customer purchase orders, dispatch shipments, and generate tax invoices.</p>
        </div>
    </div>

    <!-- Header Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Orders</span>
                <span class="p-2 rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </span>
            </div>
            <h3 class="text-2xl font-black text-slate-900 mt-2">{{ number_format($stats['total']) }}</h3>
            <span class="text-[11px] text-slate-400 font-medium">All Lifetime Orders</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Pending Action</span>
                <span class="p-2 rounded-xl bg-amber-50 text-amber-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <h3 class="text-2xl font-black text-amber-600 mt-2">{{ number_format($stats['pending']) }}</h3>
            <span class="text-[11px] text-amber-600/80 font-medium">Awaiting Confirmation</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Confirmed Orders</span>
                <span class="p-2 rounded-xl bg-blue-50 text-blue-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <h3 class="text-2xl font-black text-blue-600 mt-2">{{ number_format($stats['confirmed']) }}</h3>
            <span class="text-[11px] text-blue-600/80 font-medium">Ready for Dispatch</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Gross Revenue</span>
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <h3 class="text-2xl font-black text-emerald-600 mt-2">${{ number_format($stats['revenue'], 2) }}</h3>
            <span class="text-[11px] text-emerald-600/80 font-medium">Confirmed Volume</span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col md:flex-row items-center gap-3">
            <div class="w-full md:w-52">
                <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-indigo-500">
                    <option value="">All Statuses</option>
                    @foreach(['PENDING', 'CONFIRMED', 'SHIPPED', 'DELIVERED', 'CANCELLED'] as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <div class="relative flex-1 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Order #, Customer Name, Business or Email..."
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
            </div>

            <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter Orders
            </button>

            @if(request('status') || request('search'))
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-5">ORDER #</th>
                        <th class="py-4 px-5">CUSTOMER &amp; BUSINESS</th>
                        <th class="py-4 px-5">PURCHASED UNITS</th>
                        <th class="py-4 px-5">GROSS AMOUNT</th>
                        <th class="py-4 px-5">STATUS</th>
                        <th class="py-4 px-5">DATE</th>
                        <th class="py-4 px-5 text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-5">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="font-bold text-indigo-600 hover:underline font-mono">
                                #{{ $order->order_number ?? $order->id }}
                            </a>
                            <div class="text-[11px] text-slate-400 mt-0.5">
                                Origin: {{ $order->warehouse->name ?? 'Garner Hub' }}
                            </div>
                        </td>
                        <td class="py-4 px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 text-xs shrink-0">
                                    {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800">{{ $order->user->name ?? 'Guest Buyer' }}</div>
                                    @if(!empty($order->user->business_name))
                                        <div class="text-[11px] text-emerald-600 font-semibold">{{ $order->user->business_name }}</div>
                                    @else
                                        <div class="text-[11px] text-slate-400">{{ $order->user->email ?? '-' }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-5 text-slate-600">
                            <span class="font-bold text-slate-800">{{ $order->items->sum('quantity') }}</span> units
                            <span class="text-[11px] text-slate-400 block">({{ $order->items->count() }} line items)</span>
                        </td>
                        <td class="py-4 px-5 font-black text-slate-900 text-sm">
                            ${{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="py-4 px-5">
                            @php
                                $pills = [
                                    'PENDING'   => 'bg-amber-50 text-amber-600 border-amber-200',
                                    'CONFIRMED' => 'bg-blue-50 text-blue-600 border-blue-200',
                                    'SHIPPED'   => 'bg-indigo-50 text-indigo-600 border-indigo-200',
                                    'DELIVERED' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                    'CANCELLED' => 'bg-rose-50 text-rose-600 border-rose-200',
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border text-[11px] font-bold {{ $pills[strtoupper($order->status)] ?? 'bg-slate-100 text-slate-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ strtoupper($order->status) }}
                            </span>
                        </td>
                        <td class="py-4 px-5 text-slate-500 text-[11px]">
                            <div class="font-medium text-slate-700">{{ $order->created_at->format('d M Y') }}</div>
                            <div class="text-[10px] text-slate-400">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="py-4 px-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.orders.downloadInvoice', $order->id) }}" class="p-1.5 hover:bg-slate-100 text-slate-500 hover:text-indigo-600 rounded-lg transition" title="Download Invoice">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </a>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-600 font-bold text-indigo-600 hover:text-white rounded-lg transition text-xs">
                                    Manage &rarr;
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center text-slate-400 text-xs">
                            <div class="max-w-xs mx-auto text-center">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="font-bold text-slate-700">No orders found</p>
                                <p class="text-[11px] text-slate-400 mt-1">Try adjusting your status filters or search term.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        {{ $orders->links() }}
    </div>
</div>
@endsection