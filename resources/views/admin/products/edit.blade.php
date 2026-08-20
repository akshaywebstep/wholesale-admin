@extends('layouts.admin')

@section('title', 'Edit Product: ' . $product->name)

@section('content')
<!-- Header Section -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <a href="{{ route('admin.products.index') }}"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Catalog
        </a>
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $product->name }}</h1>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold font-mono {{ $product->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                {{ $product->is_active ? 'ACTIVE' : 'INACTIVE' }}
            </span>
            <span class="font-mono bg-slate-100 text-slate-700 text-xs px-2.5 py-0.5 rounded-md border border-slate-200">
                SKU: {{ $product->sku }}
            </span>
        </div>
    </div>
</div>

<!-- Alerts -->
@if(session('success'))
<div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-2xl mb-6 text-sm flex items-center gap-3 shadow-sm">
    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <span class="font-medium">{{ session('success') }}</span>
</div>
@endif

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3.5 rounded-2xl mb-6 text-sm flex items-start gap-3 shadow-sm">
    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <div class="space-y-1">
        <span class="font-semibold">Please review the following input errors:</span>
        <ul class="list-disc list-inside text-xs space-y-0.5 opacity-90">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <!-- MAIN TABS CONTAINER (8 cols) -->
    <div class="lg:col-span-8 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">

            <!-- 5 Tab Buttons Header -->
            <div class="flex border-b border-slate-100 px-2 overflow-x-auto bg-slate-50/50 scrollbar-thin">
                <!-- Tab 1: Details -->
                <button type="button"
                    class="tabBtn active-tab whitespace-nowrap px-4 sm:px-5 py-3.5 text-xs sm:text-sm font-semibold text-blue-600 border-b-2 border-blue-600 flex items-center gap-2 transition-all -mb-px"
                    data-tab="details">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    1. General Info
                </button>

                <!-- Tab 2: Variants -->
                <button type="button"
                    class="tabBtn whitespace-nowrap px-4 sm:px-5 py-3.5 text-xs sm:text-sm font-medium text-slate-500 hover:text-slate-700 border-b-2 border-transparent flex items-center gap-2 transition-all -mb-px"
                    data-tab="variants">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    2. Variants
                    <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $product->variants->count() }}</span>
                </button>

                <!-- Tab 3: Inventory / Stock -->
                <button type="button"
                    class="tabBtn whitespace-nowrap px-4 sm:px-5 py-3.5 text-xs sm:text-sm font-medium text-slate-500 hover:text-slate-700 border-b-2 border-transparent flex items-center gap-2 transition-all -mb-px"
                    data-tab="stock">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    3. Stock & Inventory
                    <span class="bg-slate-200 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $product->total_stock }} pcs</span>
                </button>

                <!-- Tab 4: Price Tiers (Wholesale) -->
                <button type="button"
                    class="tabBtn whitespace-nowrap px-4 sm:px-5 py-3.5 text-xs sm:text-sm font-medium text-slate-500 hover:text-slate-700 border-b-2 border-transparent flex items-center gap-2 transition-all -mb-px"
                    data-tab="pricing">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    4. Wholesale Pricing
                    <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $product->priceTiers->count() }}</span>
                </button>

                <!-- Tab 5: Media & Gallery -->
                <button type="button"
                    class="tabBtn whitespace-nowrap px-4 sm:px-5 py-3.5 text-xs sm:text-sm font-medium text-slate-500 hover:text-slate-700 border-b-2 border-transparent flex items-center gap-2 transition-all -mb-px"
                    data-tab="media">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    5. Media Gallery
                    <span class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $product->images->count() }}</span>
                </button>
            </div>

            <!-- ============================================== -->
            <!-- TAB 1: General Information (Edit Form)         -->
            <!-- ============================================== -->
            <div class="tabPanel p-6 space-y-5" data-tab-panel="details">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">General Specifications</h3>
                    <p class="text-xs text-slate-500">Update basic product attributes, category classification, and retail price.</p>
                </div>

                <form action="{{ route('admin.products.update', $product) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Product Name -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                    </div>

                    <!-- SKU & Category Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                SKU Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono text-slate-800 uppercase focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" required
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                @foreach($categories as $parent)
                                <option value="{{ $parent->id }}" {{ old('category_id', $product->category_id) == $parent->id ? 'selected' : '' }} class="font-bold text-slate-900">
                                    📁 {{ $parent->name }}
                                </option>
                                @foreach($parent->children as $child)
                                <option value="{{ $child->id }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                                    &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $child->name }}
                                </option>
                                @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Price & Weight & Unit Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Base / Retail Price (₹) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">₹</span>
                                <input type="number" step="0.01" min="0" name="base_price" value="{{ old('base_price', $product->base_price) }}" required
                                    class="w-full bg-slate-50/50 border border-slate-200 rounded-xl pl-8 pr-4 py-2.5 text-sm font-semibold text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Pack Size / Weight
                            </label>
                            <input type="number" step="0.001" min="0" name="weight" value="{{ old('weight', $product->weight) }}"
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Unit of Measure <span class="text-red-500">*</span>
                            </label>
                            <select name="unit_id" required
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }} ({{ $unit->short_code }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Status Toggle -->
                    <div class="flex items-center justify-between p-4 bg-slate-50/80 rounded-xl border border-slate-200/80">
                        <div>
                            <span class="text-sm font-bold text-slate-800 block">Catalog Visibility Status</span>
                            <span class="text-xs text-slate-500">Active products appear across storefront and wholesale ordering</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $product->is_active ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Description & Technical Notes
                        </label>
                        <textarea name="description" rows="4"
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-4 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none resize-y">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl text-sm transition-all shadow-md shadow-blue-200 active:scale-[0.98] flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save General Details
                        </button>
                    </div>
                </form>
            </div>

            <!-- ============================================== -->
            <!-- TAB 2: Variants & Options                      -->
            <!-- ============================================== -->
            <div class="tabPanel hidden p-6 space-y-6" data-tab-panel="variants">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Product Variants</h3>
                        <p class="text-xs text-slate-500">Manage SKUs, sizes, colors, and options for this product.</p>
                    </div>
                </div>

                <!-- Existing Variants Table -->
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-[11px] font-bold uppercase text-slate-600 border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Size / Weight</th>
                                <th class="py-3 px-4">Color / Flavor</th>
                                <th class="py-3 px-4">Variant SKU</th>
                                <th class="py-3 px-4 text-center">Physical Stock</th>
                                <th class="py-3 px-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($product->variants as $variant)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 px-4 font-semibold text-slate-800">
                                    {{ $variant->size ?? 'Single' }}
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    {{ $variant->color ?? '-' }}
                                </td>
                                <td class="py-3 px-4 font-mono text-xs font-semibold text-blue-600">
                                    {{ $variant->variant_sku }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $variant->stocks->sum('quantity') > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $variant->stocks->sum('quantity') }} pcs
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    @if($product->variants->count() > 1)
                                    <form action="{{ route('admin.products.variants.destroy', $variant) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Delete this variant? Stock history will be removed.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Variant">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-[10px] text-slate-400 italic">Primary Variant</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 text-xs">No variants configured.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Inline Form: Add New Variant -->
                <div class="bg-slate-50/80 rounded-2xl border border-slate-200 p-5 space-y-4">
                    <div class="border-b border-slate-200/60 pb-2">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">+ Add New Product Variant</h4>
                    </div>

                    <form action="{{ route('admin.products.variants.store', $product) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                            <div class="sm:col-span-3">
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Size / Weight <span class="text-red-500">*</span></label>
                                <input type="text" name="size" placeholder="e.g. 500g, 1kg, XL" required
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
                            </div>
                            <div class="sm:col-span-3">
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Color / Flavor</label>
                                <input type="text" name="color" placeholder="e.g. Mint, Blue"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
                            </div>
                            <div class="sm:col-span-4">
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Variant SKU <span class="text-red-500">*</span></label>
                                <input type="text" name="variant_sku" placeholder="e.g. {{ $product->sku }}-500G" required
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono uppercase text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
                            </div>
                            <div class="sm:col-span-2">
                                <button type="submit"
                                    class="w-full bg-slate-900 hover:bg-black text-white font-bold py-2.5 rounded-xl text-xs transition-colors shadow-sm">
                                    + Add Variant
                                </button>
                            </div>
                        </div>

                        <!-- Initial Stock Allocation for new variant -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3 pt-3 border-t border-slate-200/50">
                            <div>
                                <label class="block text-[10px] font-semibold text-slate-600 uppercase mb-1">Opening Stock Quantity</label>
                                <input type="number" min="0" name="quantity" value="0"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-800 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-slate-600 uppercase mb-1">Low Alert Threshold</label>
                                <input type="number" min="0" name="threshold" value="5"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-700 outline-none">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- TAB 3: Inventory & Warehouse Stock             -->
            <!-- ============================================== -->
            <div class="tabPanel hidden p-6 space-y-6" data-tab-panel="stock">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Stock Inventory & Alerts</h3>
                    <p class="text-xs text-slate-500">Update live inventory count and low-stock notification alerts.</p>
                </div>

                <form action="{{ route('admin.products.stock.update', $product) }}" method="POST" class="space-y-4">
                    @csrf

                    @php $stockRowIndex = 0; @endphp
                    @foreach($product->variants as $variant)
                    <div class="bg-slate-50/80 rounded-2xl border border-slate-200 p-4 space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                            <div>
                                <span class="font-bold text-xs text-slate-800 uppercase">
                                    Variant: {{ $variant->size ?? 'Single' }} {{ $variant->color ? '('.$variant->color.')' : '' }}
                                </span>
                                <span class="font-mono text-xs text-blue-600 ml-2 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                                    {{ $variant->variant_sku }}
                                </span>
                            </div>
                            <span class="text-xs font-bold {{ $variant->stocks->sum('quantity') > 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                Total: {{ $variant->stocks->sum('quantity') }} in stock
                            </span>
                        </div>

                        @if($variant->stocks->isNotEmpty())
                            @foreach($variant->stocks as $stock)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-center bg-white p-3 rounded-xl border border-slate-200">
                                <input type="hidden" name="stocks[{{ $stockRowIndex }}][stock_id]" value="{{ $stock->id }}">
                                <input type="hidden" name="stocks[{{ $stockRowIndex }}][variant_id]" value="{{ $variant->id }}">

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Stock Quantity (Units) <span class="text-red-500">*</span></label>
                                    <input type="number" min="0" name="stocks[{{ $stockRowIndex }}][quantity]" value="{{ $stock->quantity }}" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-900 focus:bg-white focus:border-blue-500 outline-none">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Low Alert Level <span class="text-red-500">*</span></label>
                                    <input type="number" min="0" name="stocks[{{ $stockRowIndex }}][threshold]" value="{{ $stock->threshold }}" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:bg-white focus:border-blue-500 outline-none">
                                </div>
                            </div>
                            @php $stockRowIndex++; @endphp
                            @endforeach
                        @else
                            <!-- No stock record yet: allow assigning one -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-center bg-white p-3 rounded-xl border border-slate-200">
                                <input type="hidden" name="stocks[{{ $stockRowIndex }}][variant_id]" value="{{ $variant->id }}">

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Stock Quantity (Units) <span class="text-red-500">*</span></label>
                                    <input type="number" min="0" name="stocks[{{ $stockRowIndex }}][quantity]" value="0" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-900 focus:bg-white focus:border-blue-500 outline-none">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Low Alert Level <span class="text-red-500">*</span></label>
                                    <input type="number" min="0" name="stocks[{{ $stockRowIndex }}][threshold]" value="5" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:bg-white focus:border-blue-500 outline-none">
                                </div>
                            </div>
                            @php $stockRowIndex++; @endphp
                        @endif
                    </div>
                    @endforeach

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl text-sm transition-all shadow-md shadow-blue-200 active:scale-[0.98] flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Inventory Levels
                        </button>
                    </div>
                </form>
            </div>

            <!-- ============================================== -->
            <!-- TAB 4: Wholesale Pricing (Price Tiers)         -->
            <!-- ============================================== -->
            <div class="tabPanel hidden p-6 space-y-6" data-tab-panel="pricing">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Wholesale Volume Tiers (B2B)</h3>
                        <p class="text-xs text-slate-500">Tiered bulk discount brackets by order quantity.</p>
                    </div>
                </div>

                <!-- Existing Tiers Table -->
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-[11px] font-bold uppercase text-slate-600 border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Min Qty (MOQ)</th>
                                <th class="py-3 px-4">Max Qty</th>
                                <th class="py-3 px-4">Wholesale Price</th>
                                <th class="py-3 px-4">Est. Savings</th>
                                <th class="py-3 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($product->priceTiers as $tier)
                            @php
                                $savings = $product->base_price > 0 ? round((($product->base_price - $tier->price) / $product->base_price) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 px-4 text-slate-800 font-bold">
                                    {{ $tier->min_qty }} units
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    {{ $tier->max_qty ? $tier->max_qty . ' units' : '∞ (No limit)' }}
                                </td>
                                <td class="py-3 px-4 font-bold text-slate-900">
                                    ₹{{ number_format($tier->price, 2) }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($savings > 0)
                                    <span class="inline-flex items-center text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">
                                        ↓ {{ $savings }}% OFF
                                    </span>
                                    @else
                                    <span class="text-xs text-slate-400">Standard</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <form action="{{ route('admin.products.price-tiers.destroy', $tier) }}" method="POST"
                                        onsubmit="return confirm('Remove this bulk pricing tier?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Tier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 text-xs">No wholesale price tiers added yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Add Tier Form -->
                <div class="bg-slate-50/80 rounded-2xl border border-slate-200 p-5 space-y-4">
                    <div class="border-b border-slate-200/60 pb-2">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">+ Add Wholesale Volume Tier</h4>
                    </div>

                    <form action="{{ route('admin.products.price-tiers.store', $product) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                            <div class="sm:col-span-4">
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Min Qty (MOQ) <span class="text-red-500">*</span></label>
                                <input type="number" min="1" name="min_qty" required placeholder="e.g. 10"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
                            </div>

                            <div class="sm:col-span-4">
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Max Qty (Optional)</label>
                                <input type="number" min="1" name="max_qty" placeholder="Optional"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Price (₹) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" min="0" name="price" required placeholder="0.00"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
                            </div>

                            <div class="sm:col-span-2">
                                <button type="submit"
                                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl text-xs transition-colors shadow-sm">
                                    + Add Tier
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- TAB 5: Media & Gallery                         -->
            <!-- ============================================== -->
            <div class="tabPanel hidden p-6 space-y-6" data-tab-panel="media">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Product Media Gallery</h3>
                    <p class="text-xs text-slate-500">Manage existing media thumbnails and upload new product photos.</p>
                </div>

                <!-- Existing Images Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @forelse($product->images as $image)
                    <div class="group relative aspect-square rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center shadow-sm">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}"
                            class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300">

                        <form action="{{ route('admin.products.images.destroy', $image) }}" method="POST"
                            class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"
                            onsubmit="return confirm('Permanently delete this image?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white p-2.5 rounded-full shadow-lg transition-transform active:scale-90" title="Delete Image">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="col-span-full py-10 text-center bg-slate-50/60 rounded-2xl border border-dashed border-slate-200 text-slate-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-xs font-semibold text-slate-500">No media photos uploaded yet</p>
                    </div>
                    @endforelse
                </div>

                <!-- Upload More Images Form -->
                <form action="{{ route('admin.products.images.store', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4 pt-2">
                    @csrf
                    <div class="relative border-2 border-dashed border-slate-300 hover:border-blue-500 rounded-2xl p-6 text-center transition-colors bg-slate-50/50 group cursor-pointer">
                        <input type="file" name="images[]" multiple accept="image/*" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="space-y-2 pointer-events-none">
                            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mx-auto group-hover:scale-110 transition-transform shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-blue-600 block">Click or Drag additional product photos here</span>
                            <span class="text-[11px] text-slate-400">PNG, JPG, WEBP, AVIF up to 3MB</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-bold py-2.5 rounded-xl text-xs transition-colors shadow-sm">
                        Upload Selected Images
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- RIGHT SIDEBAR: Overview & Stats Card (4 cols) -->
    <div class="lg:col-span-4 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sticky top-6 space-y-6">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-slate-900 tracking-tight">Product Summary</h2>
                <p class="text-xs text-slate-500">Live operational overview of this product.</p>
            </div>

            <!-- Total Stock Metric -->
            <div class="p-4 rounded-xl border {{ $product->total_stock > 10 ? 'bg-emerald-50 border-emerald-200' : ($product->total_stock > 0 ? 'bg-amber-50 border-amber-200' : 'bg-rose-50 border-rose-200') }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider {{ $product->total_stock > 10 ? 'text-emerald-800' : ($product->total_stock > 0 ? 'text-amber-800' : 'text-rose-800') }}">
                        @if($product->total_stock > 10)
                            ✓ In Stock
                        @elseif($product->total_stock > 0)
                            ⚠ Low Stock
                        @else
                            ✕ Out of Stock
                        @endif
                    </span>
                    <span class="text-xl font-bold font-mono text-slate-900">{{ $product->total_stock }}</span>
                </div>
                <span class="text-[11px] text-slate-600 block mt-1">Total physical inventory across all variants</span>
            </div>

            <!-- Stats List -->
            <div class="space-y-2.5 text-xs text-slate-600 bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                <div class="flex items-center justify-between py-1 border-b border-slate-200/60">
                    <span>Retail Price:</span>
                    <span class="font-bold text-slate-900">₹{{ number_format($product->base_price, 2) }}</span>
                </div>
                <div class="flex items-center justify-between py-1 border-b border-slate-200/60">
                    <span>Pack Unit:</span>
                    <span class="font-semibold text-slate-800">{{ $product->formatted_weight ?: 'N/A' }}</span>
                </div>
                <div class="flex items-center justify-between py-1 border-b border-slate-200/60">
                    <span>Category:</span>
                    <span class="font-semibold text-slate-800">{{ $product->category->name ?? 'None' }}</span>
                </div>
                <div class="flex items-center justify-between py-1 border-b border-slate-200/60">
                    <span>Variants Count:</span>
                    <span class="font-bold text-slate-900">{{ $product->variants->count() }}</span>
                </div>
                <div class="flex items-center justify-between py-1 border-b border-slate-200/60">
                    <span>Price Tiers:</span>
                    <span class="font-bold text-slate-900">{{ $product->priceTiers->count() }}</span>
                </div>
                <div class="flex items-center justify-between py-1">
                    <span>Last Updated:</span>
                    <span class="text-slate-500 font-mono">{{ $product->updated_at->format('M d, Y H:i') }}</span>
                </div>
            </div>

            <!-- Danger Zone: Delete Product -->
            <div class="pt-2 border-t border-slate-100">
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                    onsubmit="return confirm('Warning: Deleting this product will remove all its variants, images, stock records, and tier pricing. Proceed?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full bg-white hover:bg-rose-50 border border-rose-200 text-rose-600 hover:text-rose-700 font-semibold px-4 py-2.5 rounded-xl text-xs transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Entire Product
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>

<!-- Tab Switching Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabBtns = document.querySelectorAll('.tabBtn');
    const tabPanels = document.querySelectorAll('.tabPanel');

    function switchTab(tabName) {
        tabBtns.forEach(btn => {
            const isActive = btn.dataset.tab === tabName;
            btn.classList.toggle('active-tab', isActive);
            btn.classList.toggle('text-blue-600', isActive);
            btn.classList.toggle('border-blue-600', isActive);
            btn.classList.toggle('font-semibold', isActive);
            btn.classList.toggle('text-slate-500', !isActive);
            btn.classList.toggle('border-transparent', !isActive);
            btn.classList.toggle('font-medium', !isActive);
        });

        tabPanels.forEach(panel => {
            panel.classList.toggle('hidden', panel.dataset.tabPanel !== tabName);
        });
    }

    tabBtns.forEach(btn => btn.addEventListener('click', () => switchTab(btn.dataset.tab)));
});
</script>
@endsection