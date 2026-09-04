@extends('layouts.admin')

@section('title', 'Warehouse Hub: ' . $warehouse->name)

@section('content')
@php
    $stocks = $stocks ?? $warehouse->stocks;

    // Group stocks by product so each unique product appears only once
    $groupedStocks = $stocks->groupBy(function($st) {
        return $st->productVariant->product_id ?? 0;
    })->filter(function($items, $key) {
        return $key > 0 && $items->first()->productVariant && $items->first()->productVariant->product;
    });
@endphp
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #printInventoryManifest, #printInventoryManifest * {
        visibility: visible;
    }
    #printInventoryManifest {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .no-print {
        display: none !important;
    }
}
</style>

<div class="max-w-7xl mx-auto space-y-6 pb-12" id="printInventoryManifest">

    <!-- Top Action Bar & Breadcrumbs -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 no-print">
        <div>
            <a href="{{ route('admin.warehouses.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Warehouses List
            </a>
            <div class="flex flex-wrap items-center gap-2.5">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $warehouse->name }}</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold font-mono {{ $warehouse->status === 'ACTIVE' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                    ● {{ $warehouse->status }}
                </span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all shadow-sm">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Stock Manifest
            </button>
            @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Warehouse', 'UPDATE'))
            <a href="{{ route('admin.warehouses.edit', $warehouse) }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-md shadow-blue-200 active:scale-[0.98] transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Facility Profile
            </a>
            @endif
        </div>
    </div>

    <!-- Facility Overview Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Metric 1: Total Valuation -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg border border-emerald-100">
                $
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Total Inventory Valuation</span>
                <span class="text-2xl font-bold text-emerald-700 mt-1 block">${{ number_format($warehouse->total_valuation, 2) }}</span>
                <span class="text-[10px] text-slate-400 mt-0.5 block">Based on base catalog rate</span>
            </div>
        </div>

        <!-- Metric 2: Total Units Stored -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg border border-blue-100">
                📦
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Physical Stock on Hand</span>
                <span class="text-2xl font-bold text-slate-900 mt-1 block">{{ number_format($warehouse->total_stock_count) }} units</span>
                <span class="text-[10px] text-slate-400 mt-0.5 block">Available across all variants</span>
            </div>
        </div>

        <!-- Metric 3: Total Products & SKUs Tracked -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg border border-indigo-100">
                🔖
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Products / SKUs</span>
                <span class="text-2xl font-bold text-indigo-600 mt-1 block">{{ $groupedStocks->count() }} products</span>
                <span class="text-[10px] text-slate-400 mt-0.5 block">{{ $stocks->count() }} unique variant lines</span>
            </div>
        </div>

        <!-- Metric 4: Low Stock Warnings -->
        @php
            $lowStockCount = $warehouse->stocks()->whereColumn('quantity', '<=', 'threshold')->count();
        @endphp
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl {{ $lowStockCount > 0 ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-slate-50 text-slate-400 border-slate-100' }} flex items-center justify-center font-bold text-lg border">
                ⚠️
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Replenishment Alerts</span>
                <span class="text-2xl font-bold {{ $lowStockCount > 0 ? 'text-amber-600' : 'text-slate-800' }} mt-1 block">
                    {{ $lowStockCount > 0 ? $lowStockCount . ' items critical' : 'All Levels Healthy' }}
                </span>
                <span class="text-[10px] text-slate-400 mt-0.5 block">Below warning threshold</span>
            </div>
        </div>
    </div>

    <!-- Facility Logistics & Dispatch Operations Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2.5">
            Facility Logistics & Operations Profile
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="text-slate-400 block font-medium">Physical Location:</span>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $warehouse->location }}</p>
            </div>

            <div>
                <span class="text-slate-400 block font-medium">Warehouse Manager:</span>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $warehouse->manager_name ?: 'Not Assigned' }}</p>
            </div>

            <div>
                <span class="text-slate-400 block font-medium">Contact & Support:</span>
                <p class="font-semibold text-slate-800 mt-0.5">
                    {{ $warehouse->contact_phone ?: '-' }}
                    @if($warehouse->contact_email)
                    <br><span class="text-slate-500">{{ $warehouse->contact_email }}</span>
                    @endif
                </p>
            </div>

            <div>
                <span class="text-slate-400 block font-medium">Receiving & Dispatch Hours:</span>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $warehouse->operating_hours ?: 'Standard Business Hours' }}</p>
            </div>
        </div>

        @if($warehouse->dispatch_notes)
        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200/60 text-xs text-slate-600">
            <strong>Dispatch & Logistics Notes:</strong> {{ $warehouse->dispatch_notes }}
        </div>
        @endif
    </div>

    <!-- Search, Stock Filter Bar & View Switcher -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 no-print">
        <form method="GET" action="{{ route('admin.warehouses.show', $warehouse) }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div class="relative w-64">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product name or SKU..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
            </div>

            <select name="stock_filter" onchange="this.form.submit()"
                class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:bg-white outline-none">
                <option value="">All Stock Levels</option>
                <option value="healthy" {{ request('stock_filter') == 'healthy' ? 'selected' : '' }}>Healthy Stock</option>
                <option value="low" {{ request('stock_filter') == 'low' ? 'selected' : '' }}>Low Stock Alerts</option>
                <option value="out" {{ request('stock_filter') == 'out' ? 'selected' : '' }}>Out of Stock</option>
            </select>

            @if(request()->hasAny(['search', 'stock_filter']))
            <a href="{{ route('admin.warehouses.show', $warehouse) }}" class="text-xs text-slate-500 hover:text-slate-700 underline">
                Clear Filters
            </a>
            @endif
        </form>

        <div class="flex items-center justify-between md:justify-end gap-3.5">
            <!-- View Mode Switcher Buttons (Table vs Grid) -->
            <div class="inline-flex p-1 bg-slate-200/80 rounded-xl border border-slate-300/60 shadow-inner">
                <button type="button" id="btnViewTable" onclick="setWarehouseViewMode('table')"
                    class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all flex items-center gap-1.5 bg-white text-slate-900 shadow-sm"
                    title="Tabular Table View">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span>Table</span>
                </button>
                <button type="button" id="btnViewCards" onclick="setWarehouseViewMode('cards')"
                    class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all flex items-center gap-1.5 text-slate-600 hover:text-slate-900"
                    title="Grid Cards View">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span>Grid</span>
                </button>
            </div>

            <span class="text-xs text-slate-500 font-medium">
                Showing <strong class="text-slate-800">{{ $groupedStocks->count() }}</strong> products (<span class="text-slate-600">{{ $stocks->count() }} variants</span>)
            </span>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- VIEW MODE 1: LIVE STOCK INVENTORY TABLE        -->
    <!-- ============================================== -->
    <div id="viewContainerTable" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-14">Photo</th>
                        <th class="py-3.5 px-4">Product Name & SKU</th>
                        <th class="py-3.5 px-4">Category</th>
                        <th class="py-3.5 px-4 text-center">Variants</th>
                        <th class="py-3.5 px-4">Base Rate</th>
                        <th class="py-3.5 px-4 text-center">Total Stock in Hub</th>
                        <th class="py-3.5 px-4">Line Valuation</th>
                        <th class="py-3.5 px-4 text-right no-print">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($groupedStocks as $productId => $items)
                    @php
                        $product = $items->first()->productVariant->product ?? null;
                        $totalQty = $items->sum('quantity');
                        $hasLowStock = $items->contains(fn($st) => $st->quantity > 0 && $st->quantity <= $st->threshold);
                        $hasOutOfStock = $items->contains(fn($st) => $st->quantity <= 0);
                        $rate = $product ? $product->base_price : 0;
                        $lineTotal = $items->sum(function($st) use ($rate) {
                            $vRate = $rate + ($st->productVariant->price_difference ?? 0);
                            return $st->quantity * $vRate;
                        });

                        // Prepare modal payload JSON
                        $modalData = [
                            'id' => $product->id,
                            'name' => $product->name,
                            'sku' => $product->sku,
                            'category' => $product->category->name ?? 'General',
                            'image' => $product->featured_image_url,
                            'base_price' => number_format($rate, 2),
                            'total_stock' => number_format($totalQty),
                            'total_value' => number_format($lineTotal, 2),
                            'edit_url' => route('admin.products.edit', $product),
                            'show_url' => route('admin.products.show', $product),
                            'variants' => $items->map(function($st) use ($rate, $product) {
                                $v = $st->productVariant;
                                $vRate = $rate + ($v->price_difference ?? 0);
                                return [
                                    'id' => $st->id,
                                    'variant_id' => $v->id,
                                    'sku' => $v->variant_sku ?? $product->sku,
                                    'size' => $v->size ?: 'Standard',
                                    'color' => $v->color ?: '-',
                                    'rate' => number_format($vRate, 2),
                                    'quantity' => $st->quantity,
                                    'threshold' => $st->threshold,
                                    'line_total' => number_format($st->quantity * $vRate, 2),
                                    'status' => $st->quantity > $st->threshold ? 'healthy' : ($st->quantity > 0 ? 'low' : 'out'),
                                ];
                            })->values(),
                        ];
                    @endphp
                    @if($product)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <!-- Photo -->
                        <td class="py-3.5 px-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 overflow-hidden flex items-center justify-center p-1">
                                <img src="{{ $product->featured_image_url }}" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain" onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';">
                            </div>
                        </td>

                        <!-- Product & SKU -->
                        <td class="py-3.5 px-4 max-w-xs">
                            <a href="{{ route('admin.products.show', $product) }}" class="font-bold text-slate-900 hover:text-blue-600 transition-colors block leading-snug line-clamp-1">
                                {{ $product->name }}
                            </a>
                            <span class="font-mono text-[10px] text-slate-400 font-semibold block mt-0.5">
                                SKU: {{ $product->sku }}
                            </span>
                        </td>

                        <!-- Category -->
                        <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700">
                                {{ $product->category->name ?? 'General' }}
                            </span>
                        </td>

                        <!-- Variants Pill / Modal Trigger -->
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            <button type="button"
                                data-product='@json($modalData)'
                                onclick="openVariantModal(this)"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200/80 rounded-xl text-xs font-bold transition-all shadow-xs group cursor-pointer">
                                <span>📦 {{ $items->count() }} Variants</span>
                                <span class="text-[10px] bg-blue-600 text-white rounded px-1 py-0.2 group-hover:scale-105 transition-transform">View ↗</span>
                            </button>
                        </td>

                        <!-- Unit Rate -->
                        <td class="py-3.5 px-4 font-bold text-slate-900 whitespace-nowrap">
                            ${{ number_format($rate, 2) }}
                        </td>

                        <!-- Total Available Stock -->
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold
                                {{ $totalQty > 0 ? ($hasLowStock ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                {{ number_format($totalQty) }} units
                            </span>
                            @if($hasLowStock)
                            <span class="text-[10px] text-amber-600 block mt-0.5 font-medium">⚠️ Low stock alert</span>
                            @elseif($hasOutOfStock)
                            <span class="text-[10px] text-rose-600 block mt-0.5 font-medium">⚠️ Variant out of stock</span>
                            @endif
                        </td>

                        <!-- Line Total -->
                        <td class="py-3.5 px-4 font-bold text-emerald-700 whitespace-nowrap">
                            ${{ number_format($lineTotal, 2) }}
                        </td>

                        <!-- Quick Actions -->
                        <td class="py-3.5 px-4 text-right no-print whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                <button type="button"
                                    data-product='@json($modalData)'
                                    onclick="openVariantModal(this)"
                                    class="text-[11px] font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Variants
                                </button>
                                @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Product', 'UPDATE'))
                                <a href="{{ route('admin.products.edit', $product) }}"
                                    class="text-[11px] font-semibold text-slate-600 hover:text-blue-600 bg-slate-100 hover:bg-slate-200 px-2.5 py-1.5 rounded-lg transition-colors">
                                    Edit
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400 text-xs">No products match your search or filter in this warehouse.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- VIEW MODE 2: LIVE STOCK INVENTORY GRID CARDS   -->
    <!-- ============================================== -->
    <div id="viewContainerCards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 hidden">
        @forelse($groupedStocks as $productId => $items)
        @php
            $product = $items->first()->productVariant->product ?? null;
            $totalQty = $items->sum('quantity');
            $hasLowStock = $items->contains(fn($st) => $st->quantity > 0 && $st->quantity <= $st->threshold);
            $hasOutOfStock = $items->contains(fn($st) => $st->quantity <= 0);
            $rate = $product ? $product->base_price : 0;
            $lineTotal = $items->sum(function($st) use ($rate) {
                $vRate = $rate + ($st->productVariant->price_difference ?? 0);
                return $st->quantity * $vRate;
            });

            // Prepare modal payload JSON
            $modalData = [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'category' => $product->category->name ?? 'General',
                'image' => $product->featured_image_url,
                'base_price' => number_format($rate, 2),
                'total_stock' => number_format($totalQty),
                'total_value' => number_format($lineTotal, 2),
                'edit_url' => route('admin.products.edit', $product),
                'show_url' => route('admin.products.show', $product),
                'variants' => $items->map(function($st) use ($rate, $product) {
                    $v = $st->productVariant;
                    $vRate = $rate + ($v->price_difference ?? 0);
                    return [
                        'id' => $st->id,
                        'variant_id' => $v->id,
                        'sku' => $v->variant_sku ?? $product->sku,
                        'size' => $v->size ?: 'Standard',
                        'color' => $v->color ?: '-',
                        'rate' => number_format($vRate, 2),
                        'quantity' => $st->quantity,
                        'threshold' => $st->threshold,
                        'line_total' => number_format($st->quantity * $vRate, 2),
                        'status' => $st->quantity > $st->threshold ? 'healthy' : ($st->quantity > 0 ? 'low' : 'out'),
                    ];
                })->values(),
            ];
        @endphp
        @if($product)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all p-4 flex flex-col justify-between group">
            <div>
                <!-- Top Media & Status Badges -->
                <div class="relative w-full h-48 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden flex items-center justify-center p-3 mb-3">
                    <img src="{{ $product->featured_image_url }}" alt="{{ $product->name }}"
                        class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-300"
                        onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';">

                    <span class="absolute top-2.5 left-2.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-white/95 backdrop-blur-sm text-slate-700 border border-slate-200 shadow-xs">
                        {{ $product->category->name ?? 'General' }}
                    </span>

                    <span class="absolute top-2.5 right-2.5 inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold shadow-xs
                        {{ $totalQty > 0 ? ($hasLowStock ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                        {{ number_format($totalQty) }} units
                    </span>
                </div>

                <!-- Product Name & SKU -->
                <a href="{{ route('admin.products.show', $product) }}"
                    class="font-bold text-slate-900 hover:text-blue-600 transition-colors text-xs line-clamp-2 leading-snug">
                    {{ $product->name }}
                </a>
                <div class="font-mono text-[10px] text-slate-400 font-semibold mt-1">
                    Master SKU: {{ $product->sku }}
                </div>

                <!-- Clickable Interactive Variant Trigger Block -->
                <button type="button"
                    data-product='@json($modalData)'
                    onclick="openVariantModal(this)"
                    class="w-full mt-3 p-2.5 bg-gradient-to-r from-blue-50/90 to-indigo-50/90 hover:from-blue-100 hover:to-indigo-100 border border-blue-200/80 rounded-xl flex items-center justify-between text-xs font-bold text-blue-900 transition-all group/btn shadow-xs cursor-pointer">
                    <div class="flex items-center gap-2 text-left">
                        <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center text-[11px] shadow-xs">📦</span>
                        <div>
                            <div class="text-xs font-bold text-blue-900 leading-tight">{{ $items->count() }} Variants in Stock</div>
                            <div class="text-[10px] text-blue-600/80 font-medium">Click to view breakdown</div>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-md bg-white border border-blue-200 text-blue-600 text-[10px] font-bold group-hover/btn:bg-blue-600 group-hover/btn:text-white transition-colors">
                        View ↗
                    </span>
                </button>
            </div>

            <!-- Financials & Actions -->
            <div class="mt-4">
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-slate-400 block uppercase font-medium">Base Rate</span>
                        <span class="text-xs font-bold text-slate-900">${{ number_format($rate, 2) }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-slate-400 block uppercase font-medium">Total Line Value</span>
                        <span class="text-xs font-bold text-emerald-700">${{ number_format($lineTotal, 2) }}</span>
                    </div>
                </div>

                <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between no-print gap-2">
                    <button type="button"
                        data-product='@json($modalData)'
                        onclick="openVariantModal(this)"
                        class="flex-1 py-1.5 px-2.5 rounded-xl text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200/70 transition-colors flex items-center justify-center gap-1 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        View All Variants
                    </button>

                    @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Product', 'UPDATE'))
                    <a href="{{ route('admin.products.edit', $product) }}"
                        class="text-xs font-semibold text-slate-600 hover:text-blue-600 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-xl transition-colors inline-flex items-center">
                        Edit
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif
        @empty
        <div class="col-span-full py-12 text-center text-slate-400 text-xs bg-white rounded-2xl border border-slate-200">
            No products match your search or filter in this warehouse.
        </div>
        @endforelse
    </div>

</div>

<!-- ============================================================== -->
<!-- INTERACTIVE POPUP MODAL: PRODUCT VARIANTS BREAKDOWN            -->
<!-- ============================================================== -->
<div id="warehouseVariantModal"
    class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity duration-200"
    role="dialog" aria-modal="true" aria-labelledby="modalProductTitle">

    <!-- Modal Card Backdrop Overlay for click to close -->
    <div class="absolute inset-0" onclick="closeVariantModal()"></div>

    <!-- Modal Dialog Window -->
    <div class="relative bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-3xl overflow-hidden flex flex-col max-h-[90vh] z-10 animate-in fade-in zoom-in-95 duration-200">

        <!-- Header -->
        <div class="p-5 sm:p-6 bg-slate-50 border-b border-slate-100 flex items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white border border-slate-200 p-1.5 shrink-0 flex items-center justify-center shadow-xs">
                    <img id="modalProductImg" src="" alt="Product" class="max-w-full max-h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span id="modalProductCategory" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200"></span>
                        <span id="modalProductSku" class="text-xs font-mono text-slate-400 font-semibold"></span>
                    </div>
                    <h3 id="modalProductTitle" class="text-base sm:text-lg font-bold text-slate-900 leading-snug mt-1 line-clamp-1"></h3>
                    <p class="text-xs text-slate-500 mt-0.5">Warehouse Location: <strong class="text-slate-700">{{ $warehouse->name }}</strong></p>
                </div>
            </div>

            <button type="button" onclick="closeVariantModal()"
                class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-white border border-transparent hover:border-slate-200 transition-all cursor-pointer"
                title="Close Window (Esc)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Quick Summary Stats Bar -->
        <div class="grid grid-cols-3 divide-x divide-slate-100 bg-white border-b border-slate-100 text-center py-3">
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Total Variants</span>
                <span id="modalVariantsCount" class="text-sm sm:text-base font-bold text-slate-900 mt-0.5 block"></span>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Hub Stock Available</span>
                <span id="modalTotalStock" class="text-sm sm:text-base font-bold text-blue-600 mt-0.5 block"></span>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Combined Line Value</span>
                <span id="modalTotalValuation" class="text-sm sm:text-base font-bold text-emerald-700 mt-0.5 block"></span>
            </div>
        </div>

        <!-- Variant Breakdown Table -->
        <div class="overflow-y-auto p-4 sm:p-6 flex-1">
            <div class="rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-3.5">Variant Spec / Pack</th>
                            <th class="py-3 px-3.5">Color / Flavor</th>
                            <th class="py-3 px-3.5">Variant SKU</th>
                            <th class="py-3 px-3.5">Unit Rate</th>
                            <th class="py-3 px-3.5 text-center">In Stock</th>
                            <th class="py-3 px-3.5 text-center">Alert At</th>
                            <th class="py-3 px-3.5 text-right">Line Total</th>
                        </tr>
                    </thead>
                    <tbody id="modalVariantsTbody" class="divide-y divide-slate-100">
                        <!-- Dynamic Variant Rows Injected by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="p-4 sm:p-5 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
            <button type="button" onclick="closeVariantModal()"
                class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 hover:bg-slate-100 rounded-xl transition-all cursor-pointer">
                Close
            </button>

            <div class="flex items-center gap-2">
                <a id="modalProductShowLink" href="#" target="_blank"
                    class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition-all inline-flex items-center gap-1.5">
                    <span>Full Product Details</span>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
                @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Product', 'UPDATE'))
                <a id="modalProductEditLink" href="#"
                    class="px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-200 transition-all inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit Inventory / Tiers</span>
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- JAVASCRIPT: VIEW SWITCHER & MODAL LOGIC        -->
<!-- ============================================== -->
<script>
// Switch between Table and Grid views with localStorage remembrance
function setWarehouseViewMode(mode) {
    const btnCards = document.getElementById('btnViewCards');
    const btnTable = document.getElementById('btnViewTable');
    const containerCards = document.getElementById('viewContainerCards');
    const containerTable = document.getElementById('viewContainerTable');

    if (!containerCards || !containerTable) return;

    if (mode === 'cards') {
        containerTable.classList.add('hidden');
        containerCards.classList.remove('hidden');

        btnCards.className = 'px-3 py-1.5 text-xs font-bold rounded-lg transition-all flex items-center gap-1.5 bg-white text-slate-900 shadow-sm';
        btnTable.className = 'px-3 py-1.5 text-xs font-medium rounded-lg transition-all flex items-center gap-1.5 text-slate-600 hover:text-slate-900';

        localStorage.setItem('admin_warehouse_view', 'cards');
    } else {
        containerCards.classList.add('hidden');
        containerTable.classList.remove('hidden');

        btnTable.className = 'px-3 py-1.5 text-xs font-bold rounded-lg transition-all flex items-center gap-1.5 bg-white text-slate-900 shadow-sm';
        btnCards.className = 'px-3 py-1.5 text-xs font-medium rounded-lg transition-all flex items-center gap-1.5 text-slate-600 hover:text-slate-900';

        localStorage.setItem('admin_warehouse_view', 'table');
    }
}

// Open Variant Breakdown Modal
function openVariantModal(buttonEl) {
    try {
        const raw = buttonEl.getAttribute('data-product');
        if (!raw) return;
        const data = JSON.parse(raw);

        document.getElementById('modalProductTitle').textContent = data.name;
        document.getElementById('modalProductCategory').textContent = data.category;
        document.getElementById('modalProductSku').textContent = 'SKU: ' + data.sku;
        document.getElementById('modalProductImg').src = data.image;
        document.getElementById('modalProductImg').onerror = function() {
            this.onerror = null;
            this.src = '{{ asset("images/product1.png") }}';
        };

        document.getElementById('modalVariantsCount').textContent = data.variants.length + ' variants';
        document.getElementById('modalTotalStock').textContent = data.total_stock + ' units';
        document.getElementById('modalTotalValuation').textContent = '$' + data.total_value;

        const showLink = document.getElementById('modalProductShowLink');
        if (showLink) showLink.href = data.show_url;

        const editLink = document.getElementById('modalProductEditLink');
        if (editLink) editLink.href = data.edit_url;

        const tbody = document.getElementById('modalVariantsTbody');
        tbody.innerHTML = '';

        data.variants.forEach(v => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-50 transition-colors';

            let badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
            if (v.status === 'low') badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
            if (v.status === 'out') badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';

            tr.innerHTML = `
                <td class="py-3 px-3.5 font-bold text-slate-800">${escapeHtml(v.size)}</td>
                <td class="py-3 px-3.5 text-slate-600">${escapeHtml(v.color)}</td>
                <td class="py-3 px-3.5 font-mono text-[11px] text-slate-400 font-semibold">${escapeHtml(v.sku)}</td>
                <td class="py-3 px-3.5 font-bold text-slate-900">$${v.rate}</td>
                <td class="py-3 px-3.5 text-center">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold border ${badgeClass}">
                        ${v.quantity} units
                    </span>
                </td>
                <td class="py-3 px-3.5 text-center text-slate-400 font-mono">${v.threshold}</td>
                <td class="py-3 px-3.5 text-right font-bold text-emerald-700">$${v.line_total}</td>
            `;
            tbody.appendChild(tr);
        });

        const modal = document.getElementById('warehouseVariantModal');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    } catch (e) {
        console.error('Error opening variant modal:', e);
    }
}

// Close Variant Modal
function closeVariantModal() {
    const modal = document.getElementById('warehouseVariantModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}

// Helper: Escape HTML strings
function escapeHtml(text) {
    if (!text) return '-';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Global Event Listeners (Escape key to close modal & default saved view)
document.addEventListener('DOMContentLoaded', () => {
    const saved = localStorage.getItem('admin_warehouse_view') || 'table';
    setWarehouseViewMode(saved);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeVariantModal();
        }
    });
});
</script>
@endsection
