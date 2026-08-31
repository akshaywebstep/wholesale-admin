@extends('layouts.admin')

@section('title', 'Warehouse Hub: ' . $warehouse->name)

@section('content')
@php
    $stocks = $stocks ?? $warehouse->stocks;
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

    <!-- Alerts -->
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between shadow-sm no-print">
        <span class="font-bold">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
    @endif

    <!-- 4 High-Impact KPI Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Metric 1: Valuation -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Wholesale Stock Valuation</span>
            <span class="text-2xl font-bold text-emerald-700 mt-1 block">${{ number_format($warehouse->total_valuation, 2) }}</span>
            <span class="text-[11px] text-slate-400 mt-1 block">Based on base catalog rate</span>
        </div>

        <!-- Metric 2: Units Count -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Total Inventory Stock</span>
            <span class="text-2xl font-bold text-slate-900 mt-1 block">{{ number_format($warehouse->total_stock_units) }} units</span>
            <span class="text-[11px] text-slate-400 mt-1 block">Available across all variants</span>
        </div>

        <!-- Metric 3: Stored SKUs -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Active Stored SKUs</span>
            <span class="text-2xl font-bold text-blue-700 mt-1 block">{{ $warehouse->stocks->count() }} Variants</span>
            <span class="text-[11px] text-slate-400 mt-1 block">Unique catalog lines</span>
        </div>

        <!-- Metric 4: Low Stock Alert -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Low Stock Monitor</span>
            <span class="text-2xl font-bold {{ $warehouse->low_stock_count > 0 ? 'text-amber-600' : 'text-emerald-700' }} mt-1 block">
                {{ $warehouse->low_stock_count > 0 ? $warehouse->low_stock_count . ' Urgent Items' : 'All Levels Healthy' }}
            </span>
            <span class="text-[11px] text-slate-400 mt-1 block">Below warning threshold</span>
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

    <!-- Search & Stock Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4 no-print">
        <form method="GET" action="{{ route('admin.warehouses.show', $warehouse) }}" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <div class="relative w-64">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search stored product or SKU..."
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

        <span class="text-xs text-slate-500 font-medium">
            Showing <strong class="text-slate-800">{{ $stocks->count() }}</strong> inventory line items
        </span>
    </div>

    <!-- Live Stock Inventory Manifest Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-14">Photo</th>
                        <th class="py-3.5 px-4">Product Name & SKU</th>
                        <th class="py-3.5 px-4">Variant Spec</th>
                        <th class="py-3.5 px-4">Category</th>
                        <th class="py-3.5 px-4">Unit Rate</th>
                        <th class="py-3.5 px-4 text-center">Available Stock</th>
                        <th class="py-3.5 px-4">Line Valuation</th>
                        <th class="py-3.5 px-4 text-right no-print">Quick Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($stocks as $stock)
                    @php
                        $product = $stock->productVariant->product ?? null;
                        $variant = $stock->productVariant;
                        $qty = $stock->quantity;
                        $threshold = $stock->threshold;
                        $rate = $product ? $product->base_price : 0;
                        $lineTotal = $qty * $rate;
                    @endphp
                    @if($product)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <!-- Photo -->
                        <td class="py-3 px-4">
                            <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-200 overflow-hidden flex items-center justify-center p-1">
                                <img src="{{ $product->featured_image_url }}" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain" onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';">
                            </div>
                        </td>

                        <!-- Product & SKU -->
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.products.show', $product) }}" class="font-bold text-slate-900 hover:text-blue-600 transition-colors block leading-snug line-clamp-1">
                                {{ $product->name }}
                            </a>
                            <span class="font-mono text-[10px] text-slate-400 font-semibold block mt-0.5">
                                SKU: {{ $variant->variant_sku ?? $product->sku }}
                            </span>
                        </td>

                        <!-- Variant Spec -->
                        <td class="py-3 px-4 text-slate-700">
                            {{ $variant->size ?? 'Standard' }}
                            @if($variant->color)
                            <span class="text-slate-400 block text-[10px]">{{ $variant->color }}</span>
                            @endif
                        </td>

                        <!-- Category -->
                        <td class="py-3 px-4 text-slate-500">
                            {{ $product->category->name ?? 'General' }}
                        </td>

                        <!-- Unit Rate -->
                        <td class="py-3 px-4 font-bold text-slate-900">
                            ${{ number_format($rate, 2) }}
                        </td>

                        <!-- Available Stock -->
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold 
                                {{ $qty > $threshold ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($qty > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                {{ $qty }} units
                            </span>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Alert: {{ $threshold }}</span>
                        </td>

                        <!-- Line Total -->
                        <td class="py-3 px-4 font-bold text-emerald-700">
                            ${{ number_format($lineTotal, 2) }}
                        </td>

                        <!-- Quick Action -->
                        <td class="py-3 px-4 text-right no-print">
                            @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Product', 'UPDATE'))
                            <a href="{{ route('admin.products.edit', $product) }}"
                                class="text-[11px] font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg transition-colors">
                                Edit Stock
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400 text-xs">No items match your search in this warehouse.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection