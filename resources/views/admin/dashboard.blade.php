@extends('layouts.admin')

@section('title', 'Admin Dashboard - Wholesale Operations')

@section('content')
<div class="space-y-8">

    <!-- ============================================== -->
    <!-- 1. EXECUTIVE HEADER & ACTIONS                  -->
    <!-- ============================================== -->
    <div
        class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div
                class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md w-fit mb-2">
                <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                Wholesale Portal Live Overview
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                Welcome back, {{ auth()->user()->name }} 👋
            </h1>
            <p class="text-slate-500 text-sm mt-1">
                Here is a summary of your wholesale orders, inventory health, and recent retailer activity for <span
                    class="font-medium text-slate-700">{{ now()->format('l, F j, Y') }}</span>.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all border border-slate-200/80">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                Orders
                @if($pendingOrdersCount > 0)
                <span
                    class="px-2 py-0.5 text-xs font-bold rounded-full bg-amber-500 text-white animate-pulse">{{ $pendingOrdersCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.products.create') }}"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition-all shadow-md shadow-blue-500/20 active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                Add Product
            </a>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. EXECUTIVE KPI CARDS GRID                    -->
    <!-- ============================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

        <!-- Gross Revenue -->
        <div
            class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md hover:border-slate-300 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Gross Revenue</span>
                <div
                    class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-slate-900 tracking-tight">${{ number_format($totalRevenue, 2) }}
                </div>
                <div class="flex items-center gap-1.5 mt-1.5 text-xs text-slate-500 font-medium">
                    <span class="text-emerald-600 font-bold flex items-center gap-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        Avg ${{ number_format($avgOrderValue, 0) }}
                    </span>
                    <span>/ order</span>
                </div>
            </div>
        </div>

        <!-- Total Wholesale Orders -->
        <div
            class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md hover:border-slate-300 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Orders</span>
                <div
                    class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $totalOrders }}</div>
                <div class="flex items-center gap-1.5 mt-1.5 text-xs text-slate-500 font-medium">
                    <span class="text-amber-600 font-bold">{{ $pendingOrdersCount }}</span>
                    <span>needs fulfillment</span>
                </div>
            </div>
        </div>

        <!-- Active Products -->
        <div
            class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md hover:border-slate-300 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Active SKUs</span>
                <div
                    class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $totalProducts }}</div>
                <div class="flex items-center gap-1.5 mt-1.5 text-xs text-slate-500 font-medium">
                    <span class="text-indigo-600 font-bold">{{ $topCategories->count() }}+ Categories</span>
                </div>
            </div>
        </div>

        <!-- Trade Retailers (Customers) -->
        <div
            class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md hover:border-slate-300 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Trade Retailers</span>
                <div
                    class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $totalCustomers }}</div>
                <div class="flex items-center gap-1.5 mt-1.5 text-xs text-purple-600 font-semibold">
                    <span>Verified B2B Accounts</span>
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div
            class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md hover:border-slate-300 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Stock Alerts</span>
                <div
                    class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                </div>
            </div>
            <div class="mt-3">
                <div
                    class="text-2xl font-black {{ $lowStockCount > 0 ? 'text-amber-600' : 'text-slate-900' }} tracking-tight">
                    {{ $lowStockCount }}
                </div>
                <div class="flex items-center gap-1.5 mt-1.5 text-xs text-slate-500 font-medium">
                    <span
                        class="{{ $lowStockCount > 0 ? 'text-amber-600 font-bold' : 'text-emerald-600 font-semibold' }}">
                        {{ $lowStockCount > 0 ? 'Action required' : 'Inventory optimal' }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- ============================================== -->
    <!-- 3. ANALYTICS & REVENUE CHARTS SECTION          -->
    <!-- ============================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Revenue & Orders Chart -->
        <div
            class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-6">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900">Revenue & Sales Trends</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daily wholesale order volume and monetary performance (Last
                        7 Days)</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-semibold">
                    <div class="flex items-center gap-1.5 text-blue-600">
                        <span class="w-3 h-3 rounded-full bg-blue-600 inline-block"></span>
                        Revenue ($)
                    </div>
                    <div class="flex items-center gap-1.5 text-emerald-500">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                        Orders
                    </div>
                </div>
            </div>

            <!-- Canvas for Chart.js -->
            <div class="relative h-64 md:h-72 w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Right 1 Col: Order Pipeline & Status Breakdown -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Order Fulfillment Pipeline</h3>
                <p class="text-xs text-slate-500 mt-0.5">Live status of orders across fulfillment centers</p>

                <!-- Doughnut Chart Container -->
                <div class="relative h-44 my-4 flex items-center justify-center">
                    <canvas id="orderStatusChart"></canvas>
                </div>

                <!-- Status Bars Breakdown -->
                <div class="space-y-2.5 pt-2 border-t border-slate-100">
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 font-medium text-slate-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Pending Review
                        </span>
                        <span class="font-bold text-slate-900">{{ $ordersByStatus['PENDING'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 font-medium text-slate-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Processing / Packing
                        </span>
                        <span class="font-bold text-slate-900">{{ $ordersByStatus['PROCESSING'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 font-medium text-slate-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> In Transit (Shipped)
                        </span>
                        <span class="font-bold text-slate-900">{{ $ordersByStatus['SHIPPED'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 font-medium text-slate-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Delivered / Completed
                        </span>
                        <span class="font-bold text-slate-900">{{ $ordersByStatus['DELIVERED'] }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Active Warehouses: <strong
                        class="text-slate-800">{{ $activeWarehousesCount }} Locations</strong></span>
                <a href="{{ route('admin.orders.index') }}" class="font-bold text-blue-600 hover:text-blue-700">View
                    Pipeline &rarr;</a>
            </div>
        </div>

    </div>

    <!-- ============================================== -->
    <!-- 4. RECENT ORDERS & INVENTORY ALERTS TABLES     -->
    <!-- ============================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Recent Wholesale Orders -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Recent Wholesale Orders</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Latest transactions from registered wholesale buyers</p>
                </div>
                <a href="{{ route('admin.orders.index') }}"
                    class="text-xs font-bold text-blue-600 hover:text-blue-700 bg-blue-50 px-3 py-1.5 rounded-lg transition-colors">
                    View All Orders
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3.5">Order #</th>
                            <th class="px-5 py-3.5">Retailer / Buyer</th>
                            <th class="px-5 py-3.5">Items</th>
                            <th class="px-5 py-3.5">Total Amount</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                        @forelse($recentOrders as $order)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4 font-bold text-slate-900">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="text-blue-600 hover:underline">
                                    {{ $order->order_number }}
                                </a>
                                <div class="text-[11px] font-normal text-slate-400 mt-0.5">
                                    {{ $order->created_at->format('M d, Y h:i A') }}
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900">
                                    {{ $order->user->business_name ?? $order->user->name }}</div>
                                <div class="text-[11px] text-slate-500">{{ $order->user->email ?? 'Guest' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-semibold">
                                    {{ $order->items->sum('quantity') }} units ({{ $order->items->count() }} SKUs)
                                </span>
                            </td>
                            <td class="px-5 py-4 font-extrabold text-slate-900 text-sm">
                                ${{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="px-5 py-4">
                                @if($order->status === 'DELIVERED')
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    ● Delivered
                                </span>
                                @elseif($order->status === 'SHIPPED')
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800">
                                    ✈ Shipped
                                </span>
                                @elseif($order->status === 'PROCESSING')
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                                    ⏳ Processing
                                </span>
                                @elseif($order->status === 'CONFIRMED')
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-cyan-100 text-cyan-800">
                                    ✓ Confirmed
                                </span>
                                @elseif($order->status === 'CANCELLED')
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                    ✕ Cancelled
                                </span>
                                @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                    ▲ Pending
                                </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 hover:text-blue-600 bg-white border border-slate-200 hover:border-blue-300 px-3 py-1.5 rounded-lg shadow-2xs transition-all">
                                    Manage
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                                No wholesale orders recorded yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right 1 Col: Low Stock & Restock Alerts -->
        <div
            class="bg-white rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between overflow-hidden">
            <div>
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Inventory Watchlist</h3>
                        <p class="text-xs text-slate-500 mt-0.5">SKUs near replenishment threshold</p>
                    </div>
                    <span
                        class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        {{ $lowStockCount }} Alert{{ $lowStockCount == 1 ? '' : 's' }}
                    </span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($lowStockProducts as $product)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/70 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="{{ $product->featured_image_url }}" alt="{{ $product->name }}"
                                class="w-11 h-11 rounded-lg object-contain bg-slate-50 p-1 border border-slate-200 shrink-0"
                                onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';">
                            <div class="min-w-0">
                                <a href="{{ route('admin.products.show', $product) }}"
                                    class="text-xs font-bold text-slate-900 hover:text-blue-600 truncate block">
                                    {{ $product->name }}
                                </a>
                                <div class="text-[11px] text-slate-400 font-mono mt-0.5">
                                    SKU: {{ $product->sku }}
                                </div>
                            </div>
                        </div>

                        <div class="text-right shrink-0 pl-3">
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-black {{ $product->total_stock <= 5 ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $product->total_stock }} in stock
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-400 text-xs">
                        <svg class="w-8 h-8 text-emerald-500 mx-auto mb-2" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        All inventory levels are fully stocked!
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                <a href="{{ route('admin.products.index') }}"
                    class="w-full inline-flex items-center justify-center gap-1.5 text-xs font-bold text-slate-700 hover:text-blue-600 bg-white border border-slate-200 hover:border-blue-300 py-2.5 rounded-xl transition-all shadow-2xs">
                    <span>Manage Inventory & Stock</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
        </div>

    </div>

    <!-- ============================================== -->
    <!-- 5. TOP CATALOG PRODUCTS & TRADE CUSTOMERS     -->
    <!-- ============================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Top Products Showcase -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Featured Catalog Products</h3>
                    <p class="text-xs text-slate-500 mt-0.5">High-demand items across key categories</p>
                </div>
                <a href="{{ route('admin.products.index') }}"
                    class="text-xs font-bold text-blue-600 hover:text-blue-700 bg-blue-50 px-3 py-1.5 rounded-lg transition-colors">
                    Catalog &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($topProducts as $product)
                <div class="p-4 flex items-center justify-between hover:bg-slate-50/70 transition-colors">
                    <div class="flex items-center gap-3 min-w-0">
                        <img src="{{ $product->featured_image_url }}" alt="{{ $product->name }}"
                            class="w-12 h-12 rounded-xl object-contain bg-slate-50 p-1 border border-slate-200 shrink-0"
                            onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';">
                        <div class="min-w-0">
                            <a href="{{ route('admin.products.show', $product) }}"
                                class="text-xs font-bold text-slate-900 hover:text-blue-600 truncate block">
                                {{ $product->name }}
                            </a>
                            <div class="flex items-center gap-2 mt-0.5 text-[11px] text-slate-500">
                                <span
                                    class="font-semibold text-slate-700">{{ $product->category->name ?? 'General' }}</span>
                                <span>&middot;</span>
                                <span class="text-emerald-700 font-bold">{{ $product->total_stock }} in stock</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-right shrink-0 pl-4">
                        <div class="text-sm font-black text-slate-900">${{ number_format($product->base_price, 2) }}
                        </div>
                        <div class="text-[10px] text-slate-400 font-medium">Base Wholesale</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Registered Trade Customers -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Verified Trade Retailers</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Latest B2B buyer accounts onboarded</p>
                </div>
                <a href="{{ route('admin.users.index') }}"
                    class="text-xs font-bold text-purple-600 hover:text-purple-700 bg-purple-50 px-3 py-1.5 rounded-lg transition-colors">
                    All Customers &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($recentCustomers as $customer)
                <div class="p-4 flex items-center justify-between hover:bg-slate-50/70 transition-colors">
                    <div class="flex items-center gap-3 min-w-0">
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 text-white font-black text-sm flex items-center justify-center shrink-0 shadow-xs">
                            {{ strtoupper(substr($customer->business_name ?? $customer->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-slate-900 truncate">
                                {{ $customer->business_name ?: $customer->name }}
                            </div>
                            <div class="text-[11px] text-slate-500 truncate mt-0.5">
                                {{ $customer->name }} &middot; {{ $customer->email }}
                            </div>
                        </div>
                    </div>

                    <div class="text-right shrink-0 pl-3">
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Active Trade
                        </span>
                        <div class="text-[10px] text-slate-400 mt-0.5">{{ $customer->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- ============================================== -->
    <!-- 6. FAST MANAGEMENT SHORTCUTS                   -->
    <!-- ============================================== -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl p-6 text-white shadow-md">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-extrabold tracking-tight">Wholesale Portal Administration</h3>
                <p class="text-xs text-slate-400 mt-0.5">Quickly configure store settings, catalog taxonomy, and staff
                    permissions</p>
            </div>
            <span
                class="text-xs font-mono text-emerald-400 font-semibold bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20 w-fit">
                System Status: Healthy & Online
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
            <a href="{{ route('admin.categories.index') }}"
                class="p-3.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition-all group flex items-center gap-3">
                <div class="p-2 rounded-lg bg-blue-500/20 text-blue-400 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                </div>
                <div>
                    <div class="text-xs font-bold text-white">Categories</div>
                    <div class="text-[11px] text-slate-400">{{ $topCategories->count() }}+ Lines</div>
                </div>
            </a>

            <a href="{{ route('admin.products.index') }}"
                class="p-3.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition-all group flex items-center gap-3">
                <div
                    class="p-2 rounded-lg bg-emerald-500/20 text-emerald-400 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <div>
                    <div class="text-xs font-bold text-white">Product Catalog</div>
                    <div class="text-[11px] text-slate-400">{{ $totalProducts }} SKUs</div>
                </div>
            </a>

            <a href="{{ route('admin.orders.index') }}"
                class="p-3.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition-all group flex items-center gap-3">
                <div class="p-2 rounded-lg bg-purple-500/20 text-purple-400 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
                <div>
                    <div class="text-xs font-bold text-white">Fulfillment</div>
                    <div class="text-[11px] text-slate-400">{{ $totalOrders }} Orders</div>
                </div>
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="p-3.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition-all group flex items-center gap-3">
                <div class="p-2 rounded-lg bg-amber-500/20 text-amber-400 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                </div>
                <div>
                    <div class="text-xs font-bold text-white">Staff & Roles</div>
                    <div class="text-[11px] text-slate-400">Access Control</div>
                </div>
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<!-- Chart.js (Local with CDN fallback) -->
<script src="{{ asset('js/chart.min.js') }}"></script>
<script>
    if (typeof Chart === 'undefined') {
        document.write('<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"><\/script>');
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js failed to load.');
            return;
        }

        // ----------------------------------------------------
        // 1. REVENUE & ORDERS AREA CHART
        // ----------------------------------------------------
        const revCtx = document.getElementById('revenueChart');
        if (revCtx) {
            const labels = {!! json_encode($chartLabels) !!};
            const revenueData = {!! json_encode($revenueTrend) !!};
            const ordersData = {!! json_encode($ordersTrend) !!};

            new Chart(revCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Gross Revenue ($)',
                            data: revenueData,
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.08)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2.5,
                            pointBackgroundColor: '#2563eb',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Orders Count',
                            data: ordersData,
                            borderColor: '#10b981',
                            backgroundColor: 'transparent',
                            borderDash: [4, 4],
                            borderWidth: 2,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 3.5,
                            pointHoverRadius: 5,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#ffffff',
                            bodyColor: '#e2e8f0',
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (context.dataset.yAxisID === 'y') {
                                        return label + ': $' + Number(context.parsed.y).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    }
                                    return label + ': ' + context.parsed.y + ' orders';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                callback: function(value) {
                                    return '$' + Number(value).toLocaleString();
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

        // ----------------------------------------------------
        // 2. ORDER STATUS DOUGHNUT CHART
        // ----------------------------------------------------
        const statusCtx = document.getElementById('orderStatusChart');
        if (statusCtx) {
            const statusCounts = [
                {{ (int)($ordersByStatus['PENDING'] ?? 0) }},
                {{ (int)($ordersByStatus['PROCESSING'] ?? 0) }},
                {{ (int)($ordersByStatus['SHIPPED'] ?? 0) }},
                {{ (int)($ordersByStatus['DELIVERED'] ?? 0) }}
            ];
            
            const total = statusCounts.reduce((a, b) => a + b, 0);
            const dataValues = total > 0 ? statusCounts : [1];
            const bgColors = total > 0 
                ? ['#f59e0b', '#3b82f6', '#6366f1', '#10b981'] 
                : ['#e2e8f0'];
            
            new Chart(statusCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Pending Review', 'Processing', 'In Transit (Shipped)', 'Delivered'],
                    datasets: [{
                        data: dataValues,
                        backgroundColor: bgColors,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: total > 0
                        }
                    }
                }
            });
        }
    });
</script>
@endpush