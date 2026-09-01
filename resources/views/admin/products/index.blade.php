@extends('layouts.admin')

@section('title', 'Wholesale Product Catalog')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Product Catalog</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage catalog listings, multi-warehouse stock, wholesale tiers, and variants.</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- View Mode Switcher Buttons -->
            <div class="inline-flex p-1 bg-slate-200/80 rounded-xl border border-slate-300/60 shadow-inner">
                <button type="button" id="btnViewCards" onclick="setProductViewMode('cards')"
                    class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all flex items-center gap-1.5 bg-white text-slate-900 shadow-sm"
                    title="Grid Cards View">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span>Cards</span>
                </button>
                <button type="button" id="btnViewTable" onclick="setProductViewMode('table')"
                    class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all flex items-center gap-1.5 text-slate-600 hover:text-slate-900"
                    title="Tabular Table View">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span>Table</span>
                </button>
            </div>

            @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Product', 'CREATE'))
            <a href="{{ route('admin.products.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-all shadow-md shadow-blue-200 active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Product
            </a>
            @endif
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-2xl text-xs flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Search & Filtering Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
        <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <!-- Search Keyword -->
            <div class="sm:col-span-4">
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by Product Name or SKU..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                </div>
            </div>

            <!-- Category Filter -->
            <div class="sm:col-span-3">
                <select name="category_id"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                    <option value="">All Categories</option>
                    @foreach($categories as $parent)
                    <option value="{{ $parent->id }}" {{ request('category_id') == $parent->id ? 'selected' : '' }} class="font-bold">
                        📁 {{ $parent->name }}
                    </option>
                    @foreach($parent->children as $child)
                    <option value="{{ $child->id }}" {{ request('category_id') == $child->id ? 'selected' : '' }}>
                        &nbsp;&nbsp;↳ {{ $child->name }}
                    </option>
                    @endforeach
                    @endforeach
                </select>
            </div>

            <!-- Stock Status Filter -->
            <div class="sm:col-span-2">
                <select name="stock_status"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                    <option value="">All Stock Levels</option>
                    <option value="in" {{ request('stock_status') == 'in' ? 'selected' : '' }}>In Stock</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>

            <!-- Active Status Filter -->
            <div class="sm:col-span-2">
                <select name="status"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Listing</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive Listing</option>
                </select>
            </div>

            <!-- Submit & Reset Buttons -->
            <div class="sm:col-span-1 flex items-center gap-1">
                <button type="submit"
                    class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 px-3 rounded-xl text-xs transition-colors text-center" title="Apply Filter">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'category_id', 'stock_status', 'status']))
                <a href="{{ route('admin.products.index') }}"
                    class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs transition-colors" title="Reset Filters">
                    ✕
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- ============================================== -->
    <!-- VIEW MODE 1: GRID CARDS VIEW                   -->
    <!-- ============================================== -->
    <div id="viewContainerCards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
        @php
            $totalStock = $product->total_stock;
        @endphp

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col overflow-hidden group">

            <!-- Card Top: Image & Status Badges -->
            <div class="relative h-60 bg-slate-50/70 border-b border-slate-100 overflow-hidden flex items-center justify-center p-3">

                <!-- Stock Status Badge (Top Left) -->
                <span class="absolute top-2.5 left-2.5 px-2.5 py-1 rounded-full text-[10px] font-bold shadow-sm z-30 pointer-events-none 
                    {{ $totalStock > 10 ? 'bg-emerald-600 text-white shadow-emerald-500/20' : ($totalStock > 0 ? 'bg-amber-600 text-white shadow-amber-500/20' : 'bg-rose-600 text-white shadow-rose-500/20') }}">
                    @if($totalStock > 10)
                        ● In Stock ({{ $totalStock }})
                    @elseif($totalStock > 0)
                        ▲ Low ({{ $totalStock }})
                    @else
                        ✕ Out of Stock
                    @endif
                </span>

                <!-- Active/Inactive Badge (Top Right) -->
                <span class="absolute top-2.5 right-2.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase shadow-sm z-30 pointer-events-none {{ $product->is_active ? 'bg-white text-emerald-700 border border-emerald-300' : 'bg-white text-slate-600 border border-slate-300' }}">
                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                </span>

                <!-- Clickable Image / Multi-Image Smooth Slider -->
                @if($product->images && $product->images->count() > 1)
                <div class="product-card-slider group/slider relative w-full h-full flex items-center justify-center pt-6 pb-2 overflow-hidden" 
                     data-slider 
                     data-current="0" 
                     data-total="{{ $product->images->count() }}">
                    
                    <a href="{{ route('admin.products.show', $product) }}" class="slider-viewport w-full h-full relative overflow-hidden block">
                        <div class="slider-track flex w-full h-full transition-transform duration-700 ease-[cubic-bezier(0.25,1,0.3,1)] will-change-transform">
                            @foreach($product->images as $idx => $img)
                            <div class="slider-slide flex-shrink-0 w-full h-full flex items-center justify-center p-1">
                                <img src="{{ asset('storage/' . $img->image_path) }}" 
                                     alt="{{ $product->name }}"
                                     class="max-w-full max-h-44 object-contain transition-transform duration-500 group-hover:scale-105"
                                     onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';">
                            </div>
                            @endforeach
                        </div>
                    </a>

                    <!-- Prev Arrow Button -->
                    <button type="button" 
                            class="slider-arrow slider-prev absolute left-2 top-1/2 -translate-y-1/2 w-7 h-7 rounded-full bg-white/95 hover:bg-white text-slate-700 hover:text-slate-950 shadow-md flex items-center justify-center opacity-0 group-hover/slider:opacity-100 transition-all duration-300 z-20 cursor-pointer border border-slate-200/90 hover:scale-110 active:scale-95" 
                            aria-label="Previous Image"
                            onclick="event.preventDefault(); event.stopPropagation(); moveAdminCardSlide(this, -1);">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Next Arrow Button -->
                    <button type="button" 
                            class="slider-arrow slider-next absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 rounded-full bg-white/95 hover:bg-white text-slate-700 hover:text-slate-950 shadow-md flex items-center justify-center opacity-0 group-hover/slider:opacity-100 transition-all duration-300 z-20 cursor-pointer border border-slate-200/90 hover:scale-110 active:scale-95" 
                            aria-label="Next Image"
                            onclick="event.preventDefault(); event.stopPropagation(); moveAdminCardSlide(this, 1);">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Slide Indicator Dots -->
                    <div class="slider-dots absolute bottom-2 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-20 pointer-events-none bg-slate-900/60 backdrop-blur-xs px-2 py-0.5 rounded-full shadow-xs">
                        @foreach($product->images as $idx => $img)
                        <span class="slide-dot h-1.5 rounded-full transition-all duration-500 ease-out {{ $idx === 0 ? 'bg-white w-4' : 'bg-white/40 w-1.5' }}" data-dot-index="{{ $idx }}"></span>
                        @endforeach
                    </div>
                </div>
                @else
                <a href="{{ route('admin.products.show', $product) }}" class="w-full h-full flex items-center justify-center pt-6 pb-2">
                    <img src="{{ $product->featured_image_url }}" alt="{{ $product->name }}"
                        class="max-w-full max-h-44 object-contain group-hover:scale-105 transition-transform duration-300"
                        onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';">
                </a>
                @endif
            </div>

            <!-- Card Body -->
            <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                <div>
                    <!-- SKU & Category -->
                    <div class="flex items-center justify-between text-xs text-slate-400 mb-1.5">
                        <span class="font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-600 font-semibold text-[11px]">
                            {{ $product->sku }}
                        </span>
                        <span class="truncate max-w-[120px] text-right font-medium text-slate-500 text-[11px]">
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </span>
                    </div>

                    <!-- Product Name (Link to Show) -->
                    <h3 class="font-bold text-slate-900 text-sm leading-snug line-clamp-2 hover:text-blue-600 transition-colors">
                        <a href="{{ route('admin.products.show', $product) }}">
                            {{ $product->name }}
                        </a>
                    </h3>

                    <!-- Pills: Variants & Tiers -->
                    <div class="flex flex-wrap items-center gap-1.5 mt-2">
                        @if($product->variants->count() > 1)
                        <span class="bg-blue-50 text-blue-700 border border-blue-100 text-[10px] font-bold px-2 py-0.5 rounded">
                            {{ $product->variants->count() }} Variants
                        </span>
                        @endif

                        @if($product->priceTiers->count() > 0)
                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-[10px] font-bold px-2 py-0.5 rounded">
                            ⚡ {{ $product->priceTiers->count() }} Tiers
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Price & Actions Footer -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-slate-400 block font-medium">Base Price</span>
                        <span class="text-sm font-bold text-slate-900">${{ number_format($product->base_price, 2) }}</span>
                    </div>

                    <div class="flex items-center gap-1">
                        <!-- Preview Button -->
                        <a href="{{ route('admin.products.show', $product) }}"
                            class="p-2 text-slate-500 hover:text-purple-600 hover:bg-purple-50 rounded-xl transition-colors"
                            title="Quick Product Preview">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>

                        <!-- Edit Button -->
                        @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Product', 'UPDATE'))
                        @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Product', 'UPDATE'))
                                <a href="{{ route('admin.products.edit', $product) }}"
                            class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors"
                            title="Edit Product">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                                @endif
                        @endif

                        <!-- Delete Button -->
                        @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Product', 'DELETE'))
                        @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Product', 'DELETE'))
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline"
                            onsubmit="return confirm('Are you sure you want to delete this wholesale product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors"
                                title="Delete Product">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                                @endif
                        @endif
                    </div>
                </div>
            </div>

        </div>
        @empty
        <div class="col-span-full bg-white rounded-2xl border border-slate-200/80 p-12 text-center">
            <h3 class="text-base font-bold text-slate-800">No products match your criteria</h3>
            <p class="text-xs text-slate-500 mt-1">Try adjusting your search keywords or active filters.</p>
        </div>
        @endforelse
    </div>

    <!-- ============================================== -->
    <!-- VIEW MODE 2: TABULAR DATA TABLE VIEW           -->
    <!-- ============================================== -->
    <div id="viewContainerTable" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-16">Photo</th>
                        <th class="py-3.5 px-4">Product Title & SKU</th>
                        <th class="py-3.5 px-4">Department</th>
                        <th class="py-3.5 px-4">Base Rate</th>
                        <th class="py-3.5 px-4">Inventory Stock</th>
                        <th class="py-3.5 px-4">Variants</th>
                        <th class="py-3.5 px-4">Tiers</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($products as $product)
                    @php
                        $totalStock = $product->total_stock;
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <!-- Photo -->
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.products.show', $product) }}" class="block w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 overflow-hidden p-1 flex-shrink-0">
                                <img src="{{ $product->featured_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-contain" onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';">
                            </a>
                        </td>

                        <!-- Title & SKU -->
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.products.show', $product) }}" class="font-bold text-slate-900 hover:text-blue-600 transition-colors text-xs block leading-snug line-clamp-1">
                                {{ $product->name }}
                            </a>
                            <span class="font-mono text-[10px] text-slate-400 font-semibold mt-0.5 block">
                                SKU: {{ $product->sku }}
                            </span>
                        </td>

                        <!-- Category -->
                        <td class="py-3 px-4 text-slate-600">
                            <span class="bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-lg text-[11px] font-medium text-slate-700">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>

                        <!-- Base Rate -->
                        <td class="py-3 px-4 font-bold text-slate-900 text-xs">
                            ${{ number_format($product->base_price, 2) }}
                            <span class="text-[10px] text-slate-400 font-normal block">/ {{ $product->unit->short_code ?? 'unit' }}</span>
                        </td>

                        <!-- Stock Level -->
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold 
                                {{ $totalStock > 10 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($totalStock > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $totalStock > 10 ? 'bg-emerald-500' : ($totalStock > 0 ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                                {{ $totalStock > 0 ? $totalStock . ' in stock' : 'Out of Stock' }}
                            </span>
                        </td>

                        <!-- Variants Count -->
                        <td class="py-3 px-4 text-slate-600">
                            @if($product->variants->count() > 1)
                            <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded text-[10px] font-bold">
                                {{ $product->variants->count() }} Variants
                            </span>
                            @else
                            <span class="text-[11px] text-slate-400">Single</span>
                            @endif
                        </td>

                        <!-- Wholesale Tiers Count -->
                        <td class="py-3 px-4">
                            @if($product->priceTiers->count() > 0)
                            <span class="bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded text-[10px] font-bold">
                                {{ $product->priceTiers->count() }} Tiers
                            </span>
                            @else
                            <span class="text-[11px] text-slate-400">-</span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $product->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.products.show', $product) }}"
                                    class="p-1.5 text-purple-600 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors"
                                    title="View Product Preview">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}"
                                    class="p-1.5 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors"
                                    title="Edit Product">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors" title="Delete Product">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-400 text-xs">No products found matching your filter criteria.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-sm">
        {{ $products->links() }}
    </div>
    @endif

</div>

<!-- ============================================== -->
<!-- JAVASCRIPT: VIEW SWITCHER & PERSISTENCE        -->
<!-- ============================================== -->
<script>
function setProductViewMode(mode) {
    const btnCards = document.getElementById('btnViewCards');
    const btnTable = document.getElementById('btnViewTable');
    const containerCards = document.getElementById('viewContainerCards');
    const containerTable = document.getElementById('viewContainerTable');

    if (mode === 'table') {
        containerCards.classList.add('hidden');
        containerTable.classList.remove('hidden');

        btnTable.className = 'px-3 py-1.5 text-xs font-bold rounded-lg transition-all flex items-center gap-1.5 bg-white text-slate-900 shadow-sm';
        btnCards.className = 'px-3 py-1.5 text-xs font-medium rounded-lg transition-all flex items-center gap-1.5 text-slate-600 hover:text-slate-900';

        localStorage.setItem('admin_product_view', 'table');
    } else {
        containerTable.classList.add('hidden');
        containerCards.classList.remove('hidden');

        btnCards.className = 'px-3 py-1.5 text-xs font-bold rounded-lg transition-all flex items-center gap-1.5 bg-white text-slate-900 shadow-sm';
        btnTable.className = 'px-3 py-1.5 text-xs font-medium rounded-lg transition-all flex items-center gap-1.5 text-slate-600 hover:text-slate-900';

        localStorage.setItem('admin_product_view', 'cards');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const savedMode = localStorage.getItem('admin_product_view') || 'cards';
    setProductViewMode(savedMode);
    initProductCardSliders();
});

// ==============================================
// MULTI-IMAGE SMOOTH TRACK SLIDER (AUTO & ARROWS)
// ==============================================
function moveAdminCardSlide(btnOrSlider, direction, targetIdx = null) {
    const slider = btnOrSlider.hasAttribute('data-slider') ? btnOrSlider : btnOrSlider.closest('[data-slider]');
    if (!slider) return;

    const track = slider.querySelector('.slider-track');
    if (!track) return;

    const total = parseInt(slider.dataset.total || '1', 10);
    if (total <= 1) return;

    let current = parseInt(slider.dataset.current || '0', 10);
    let next = targetIdx !== null ? targetIdx : (current + direction + total) % total;

    slider.dataset.current = next;
    track.style.transform = `translate3d(-${next * 100}%, 0, 0)`;

    const dots = slider.querySelectorAll('.slide-dot');
    dots.forEach((dot, i) => {
        if (i === next) {
            dot.className = 'slide-dot h-1.5 rounded-full transition-all duration-500 ease-out bg-white w-4';
        } else {
            dot.className = 'slide-dot h-1.5 rounded-full transition-all duration-500 ease-out bg-white/40 w-1.5';
        }
    });
}

function initProductCardSliders() {
    const sliders = document.querySelectorAll('[data-slider]');
    sliders.forEach((slider, sliderIdx) => {
        const total = parseInt(slider.dataset.total || '1', 10);
        if (total <= 1) return;

        let intervalId = null;

        const startAutoSlide = () => {
            if (intervalId) clearInterval(intervalId);
            intervalId = setInterval(() => {
                moveAdminCardSlide(slider, 1);
            }, 3800);
        };

        const stopAutoSlide = () => {
            if (intervalId) clearInterval(intervalId);
        };

        // Smooth pause on mouse hover so user can easily view or click controls
        slider.addEventListener('mouseenter', stopAutoSlide);
        slider.addEventListener('mouseleave', startAutoSlide);

        // Stagger initial delays across cards so cards transition organically
        setTimeout(startAutoSlide, ((sliderIdx % 4) + 1) * 900);
    });
}
</script>
@endsection