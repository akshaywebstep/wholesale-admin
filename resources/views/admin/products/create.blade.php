@extends('layouts.admin')

@section('title', 'Add Wholesale Product')

@section('content')
<!-- Top Header & Breadcrumb -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <a href="{{ route('admin.products.index') }}"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Catalog
        </a>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Add Wholesale Product</h1>
        <p class="text-sm text-slate-500 mt-0.5">Configure product specifications, multi-warehouse stock, wholesale tiered pricing, and media gallery.</p>
    </div>
</div>

<!-- Global Validation Errors Alert -->
@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3.5 rounded-2xl mb-6 text-sm flex items-start gap-3 shadow-sm">
    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <div class="space-y-1">
        <span class="font-semibold">Please resolve the following input issues:</span>
        <ul class="list-disc list-inside text-xs space-y-0.5 opacity-90">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf

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
                        <span id="variantCountBadge" class="hidden bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
                    </button>

                    <!-- Tab 3: Inventory / Stock -->
                    <button type="button"
                        class="tabBtn whitespace-nowrap px-4 sm:px-5 py-3.5 text-xs sm:text-sm font-medium text-slate-500 hover:text-slate-700 border-b-2 border-transparent flex items-center gap-2 transition-all -mb-px"
                        data-tab="stock">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        3. Stock & Warehouses
                    </button>

                    <!-- Tab 4: Price Tiers (Wholesale) -->
                    <button type="button"
                        class="tabBtn whitespace-nowrap px-4 sm:px-5 py-3.5 text-xs sm:text-sm font-medium text-slate-500 hover:text-slate-700 border-b-2 border-transparent flex items-center gap-2 transition-all -mb-px"
                        data-tab="pricing">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        4. Wholesale Pricing
                        <span id="tierCountBadge" class="hidden bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
                    </button>

                    <!-- Tab 5: Media & Gallery -->
                    <button type="button"
                        class="tabBtn whitespace-nowrap px-4 sm:px-5 py-3.5 text-xs sm:text-sm font-medium text-slate-500 hover:text-slate-700 border-b-2 border-transparent flex items-center gap-2 transition-all -mb-px"
                        data-tab="media">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        5. Media Gallery
                        <span id="imageCountBadge" class="hidden bg-purple-100 text-purple-700 text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
                    </button>
                </div>

                <!-- ============================================== -->
                <!-- TAB 1: General Information                     -->
                <!-- ============================================== -->
                <div class="tabPanel p-6 space-y-5" data-tab-panel="details">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">General Information</h3>
                        <p class="text-xs text-slate-500">Provide the primary catalog specifications and classification.</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Product Name -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Product Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="productName" value="{{ old('name') }}" required
                                placeholder="e.g. Al Fakher Double Apple Hookah Tobacco"
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                            @error('name') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- SKU & Category Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Product SKU Code <span class="text-slate-400 font-normal">(Optional, Auto-generated if blank)</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="sku" id="productSku" value="{{ old('sku') }}"
                                        placeholder="e.g. TOB-ALF-001"
                                        class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono text-slate-800 uppercase focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                </div>
                                @error('sku') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select name="category_id" id="categoryId" required
                                    class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $parent)
                                    <option value="{{ $parent->id }}" {{ old('category_id') == $parent->id ? 'selected' : '' }} class="font-bold text-slate-900">
                                        📁 {{ $parent->name }}
                                    </option>
                                    @foreach($parent->children as $child)
                                    <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $child->name }}
                                    </option>
                                    @endforeach
                                    @endforeach
                                </select>
                                @error('category_id') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
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
                                    <input type="number" step="0.01" min="0" name="base_price" id="basePrice" value="{{ old('base_price') }}" required
                                        placeholder="0.00"
                                        class="w-full bg-slate-50/50 border border-slate-200 rounded-xl pl-8 pr-4 py-2.5 text-sm font-semibold text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                </div>
                                @error('base_price') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Pack Size / Weight
                                </label>
                                <input type="number" step="0.001" min="0" name="weight" id="productWeight" value="{{ old('weight') }}"
                                    placeholder="e.g. 250, 1, 10"
                                    class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                @error('weight') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Unit of Measure <span class="text-red-500">*</span>
                                </label>
                                <select name="unit_id" id="unitId" required
                                    class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                    <option value="">-- Select Unit --</option>
                                    @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }} ({{ $unit->short_code }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('unit_id') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Description & Technical Notes
                            </label>
                            <textarea name="description" rows="4" placeholder="Enter detailed wholesale description, packaging info, ingredients, or usage guidelines..."
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-4 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none resize-y">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- Step Navigation -->
                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="button"
                            class="tabNextBtn inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm shadow-blue-200 active:scale-[0.98]"
                            data-next="variants">
                            Next: Variants & Options
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- TAB 2: Variants & Options                      -->
                <!-- ============================================== -->
                <div class="tabPanel hidden p-6 space-y-5" data-tab-panel="variants">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Product Variants</h3>
                        <p class="text-xs text-slate-500">Configure single item inventory or multiple variants (Sizes, Colors, Flavors, SKUs).</p>
                    </div>

                    <!-- Mode Selector -->
                    <div class="bg-slate-50/80 p-4 rounded-xl border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 uppercase mb-0.5">Product Type Mode</label>
                            <p class="text-xs text-slate-500">Choose whether this product has variants or is a standalone item.</p>
                        </div>
                        <select id="hasVariants" name="has_variants"
                            class="w-full sm:w-60 bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm font-medium text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                            <option value="0">Single Product (No Variants)</option>
                            <option value="1">Multiple Variants (Sizes / Colors)</option>
                        </select>
                    </div>

                    <!-- Single Product Notice -->
                    <div id="singleVariantNotice" class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-900">Single Item Mode Active</h4>
                            <p class="text-xs text-blue-800 mt-0.5">
                                A default SKU will be assigned using the primary product code. You can allocate warehouse stock and inventory levels in the <strong>Stock</strong> tab.
                            </p>
                        </div>
                    </div>

                    <!-- Multi-Variant Builder Rows -->
                    <div id="variantsWrapper" class="hidden space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Defined Variants</span>
                            <button type="button" id="addVariantRow"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Variant
                            </button>
                        </div>

                        <div id="variantRows" class="space-y-3"></div>

                        <button type="button" id="addVariantRowSecondary"
                            class="w-full py-3 border-2 border-dashed border-slate-200 hover:border-blue-400 hover:bg-blue-50/50 rounded-xl text-xs font-semibold text-blue-600 flex items-center justify-center gap-2 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Another Variant
                        </button>
                    </div>

                    <!-- Step Navigation -->
                    <div class="flex justify-between pt-4 border-t border-slate-100">
                        <button type="button"
                            class="tabPrevBtn inline-flex items-center gap-1.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-medium px-5 py-2.5 rounded-xl text-sm transition-all"
                            data-prev="details">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Back: General Info
                        </button>
                        <button type="button"
                            class="tabNextBtn inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm shadow-blue-200 active:scale-[0.98]"
                            data-next="stock">
                            Next: Inventory Stock
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- TAB 3: Inventory & Warehouse Stock             -->
                <!-- ============================================== -->
                <div class="tabPanel hidden p-6 space-y-5" data-tab-panel="stock">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Stock & Inventory Allocation</h3>
                        <p class="text-xs text-slate-500">Set opening stock physical inventory quantity and low-stock notification alerts.</p>
                    </div>

                    <!-- Single Product Stock Box -->
                    <div id="singleStockBox" class="bg-slate-50/80 border border-slate-200/80 rounded-xl p-5 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Primary Product Stock</span>
                            <span class="text-xs bg-slate-200 text-slate-700 px-2 py-0.5 rounded font-mono">Single SKU</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1.5">Opening Stock Quantity (Units) <span class="text-red-500">*</span></label>
                                <input type="number" min="0" name="single_quantity" value="0" required
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1.5">Low-Stock Alert Level <span class="text-red-500">*</span></label>
                                <input type="number" min="0" name="single_threshold" value="5" required
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Multi-Variant Stock Rows Container -->
                    <div id="variantStockWrapper" class="hidden space-y-4">
                        <div class="bg-blue-50/70 border border-blue-200/80 rounded-xl p-3 flex items-center justify-between">
                            <p class="text-xs font-medium text-blue-900">
                                Enter opening stock quantity and low-stock alert level for each variant defined in Step 2.
                            </p>
                        </div>
                        <div id="variantStockRows" class="space-y-3"></div>
                    </div>

                    <!-- Step Navigation -->
                    <div class="flex justify-between pt-4 border-t border-slate-100">
                        <button type="button"
                            class="tabPrevBtn inline-flex items-center gap-1.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-medium px-5 py-2.5 rounded-xl text-sm transition-all"
                            data-prev="variants">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Back: Variants
                        </button>
                        <button type="button"
                            class="tabNextBtn inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm shadow-blue-200 active:scale-[0.98]"
                            data-next="pricing">
                            Next: Wholesale Pricing
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- TAB 4: Wholesale Pricing (Price Tiers)         -->
                <!-- ============================================== -->
                <div class="tabPanel hidden p-6 space-y-5" data-tab-panel="pricing">
                    <div class="border-b border-slate-100 pb-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Wholesale Volume Pricing (B2B)</h3>
                            <p class="text-xs text-slate-500">Provide discounted bulk rates based on order quantity brackets.</p>
                        </div>
                        <button type="button" id="addTierRow"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            + Add Volume Tier
                        </button>
                    </div>

                    <!-- Tier Table Headers (Desktop) -->
                    <div class="hidden sm:grid grid-cols-12 gap-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider px-2">
                        <div class="col-span-4">Min Quantity (MOQ)</div>
                        <div class="col-span-4">Max Quantity (Optional)</div>
                        <div class="col-span-3">Wholesale Price (₹)</div>
                        <div class="col-span-1 text-center">Action</div>
                    </div>

                    <div id="tierRows" class="space-y-3"></div>

                    <div id="noTiersNotice" class="text-center py-8 bg-slate-50/60 rounded-xl border border-dashed border-slate-200 text-slate-400">
                        <svg class="w-8 h-8 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <p class="text-xs font-medium">No wholesale tiers added yet.</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Click "+ Add Volume Tier" above to define quantity-based discount brackets.</p>
                    </div>

                    <!-- Step Navigation -->
                    <div class="flex justify-between pt-4 border-t border-slate-100">
                        <button type="button"
                            class="tabPrevBtn inline-flex items-center gap-1.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-medium px-5 py-2.5 rounded-xl text-sm transition-all"
                            data-prev="stock">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Back: Stock
                        </button>
                        <button type="button"
                            class="tabNextBtn inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm shadow-blue-200 active:scale-[0.98]"
                            data-next="media">
                            Next: Media Gallery
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- TAB 5: Media & Gallery                         -->
                <!-- ============================================== -->
                <div class="tabPanel hidden p-6 space-y-5" data-tab-panel="media">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Product Media Gallery</h3>
                        <p class="text-xs text-slate-500">Upload high-resolution product images (JPG, PNG, WEBP). Multiple files supported.</p>
                    </div>

                    <!-- Drag & Drop Area -->
                    <div class="relative border-2 border-dashed border-slate-300 hover:border-blue-500 rounded-2xl p-8 text-center transition-colors bg-slate-50/50 group cursor-pointer" id="dropzoneBox">
                        <input type="file" name="images[]" id="imageInput" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="space-y-2 pointer-events-none">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto group-hover:scale-110 transition-transform shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm font-bold text-blue-600 block">Click to browse or drag & drop images here</span>
                                <span class="text-xs text-slate-400">PNG, JPG, WEBP or AVIF up to 3MB each</span>
                            </div>
                        </div>
                    </div>

                    <!-- Live Image Previews Container -->
                    <div id="imagePreviewGrid" class="grid grid-cols-2 sm:grid-cols-4 gap-4 hidden"></div>

                    <!-- Step Navigation -->
                    <div class="flex justify-between pt-4 border-t border-slate-100">
                        <button type="button"
                            class="tabPrevBtn inline-flex items-center gap-1.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-medium px-5 py-2.5 rounded-xl text-sm transition-all"
                            data-prev="pricing">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Back: Wholesale Pricing
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- RIGHT SIDEBAR: Publishing Card (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sticky top-6 space-y-6">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-base font-bold text-slate-900 tracking-tight">Publishing & Review</h2>
                    <p class="text-xs text-slate-500">Verify catalog settings before saving.</p>
                </div>

                <!-- Active Status Toggle -->
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <div>
                        <span class="text-sm font-bold text-slate-800 block">Catalog Visibility</span>
                        <span class="text-xs text-slate-500">Publish immediately to B2B portal</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <!-- Summary Checklist -->
                <div class="space-y-2.5 text-xs text-slate-600 bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                    <div class="flex items-center justify-between py-1 border-b border-slate-200/60">
                        <span>Product Type:</span>
                        <span id="summaryProductType" class="font-bold text-slate-800">Single Item</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-200/60">
                        <span>Variants Count:</span>
                        <span id="summaryVariantCount" class="font-bold text-slate-800">1</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-200/60">
                        <span>Price Tiers:</span>
                        <span id="summaryTierCount" class="font-bold text-slate-800">0</span>
                    </div>
                    <div class="flex items-center justify-between py-1">
                        <span>Images Selected:</span>
                        <span id="summaryImageCount" class="font-bold text-slate-800">0</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3 pt-2">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-3 rounded-xl text-sm transition-all shadow-md shadow-blue-200 active:scale-[0.98] flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save & Create Product
                    </button>
                    <a href="{{ route('admin.products.index') }}"
                        class="w-full text-center bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-medium px-4 py-2.5 rounded-xl text-xs transition-all block">
                        Cancel & Discard
                    </a>
                </div>
            </div>
        </div>

    </div>
</form>

<!-- ============================================== -->
<!-- JAVASCRIPT TEMPLATES                           -->
<!-- ============================================== -->

<!-- Variant Row Template (Variants Tab) -->
<template id="variantRowTemplate">
    <div class="variant-row bg-slate-50/80 border border-slate-200 rounded-xl p-4 transition-all">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-3">
                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Size / Weight <span class="text-red-500">*</span></label>
                <input type="text" name="variants[__INDEX__][size]" placeholder="e.g. 250g, 500g, XL" required
                    class="variant-size-input w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
            </div>
            <div class="sm:col-span-3">
                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Color / Flavor</label>
                <input type="text" name="variants[__INDEX__][color]" placeholder="e.g. Double Apple, Red"
                    class="variant-color-input w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
            </div>
            <div class="sm:col-span-5">
                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Variant SKU <span class="text-red-500">*</span></label>
                <input type="text" name="variants[__INDEX__][variant_sku]" placeholder="e.g. ALF-DA-250G" required
                    class="variant-sku-input w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm font-mono text-slate-800 uppercase focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
            </div>
            <div class="sm:col-span-1 flex items-center justify-center">
                <button type="button" class="removeVariantRow text-slate-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Remove Variant">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<!-- Variant Stock Row Template (Stock Tab) -->
<template id="variantStockRowTemplate">
    <div class="stock-row bg-slate-50/80 border border-slate-200 rounded-xl p-4" data-index="__INDEX__">
        <div class="flex items-center justify-between border-b border-slate-200/60 pb-2 mb-3">
            <p class="stock-row-label text-xs font-bold text-slate-800">Variant #__INDEX__</p>
            <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-mono font-semibold">Variant SKU</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Opening Stock Quantity <span class="text-red-500">*</span></label>
                <input type="number" min="0" name="variants[__INDEX__][quantity]" value="0" required
                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm font-semibold text-slate-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Low-Stock Alert Level <span class="text-red-500">*</span></label>
                <input type="number" min="0" name="variants[__INDEX__][threshold]" value="5" required
                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
            </div>
        </div>
    </div>
</template>

<!-- Price Tier Row Template (Wholesale Tab) -->
<template id="tierRowTemplate">
    <div class="tier-row grid grid-cols-1 sm:grid-cols-12 gap-3 items-center bg-slate-50/80 border border-slate-200 rounded-xl p-3 sm:p-2.5">
        <div class="sm:col-span-4">
            <input type="number" min="1" name="price_tiers[__INDEX__][min_qty]" placeholder="MOQ (e.g. 10)" required
                class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
        </div>
        <div class="sm:col-span-4">
            <input type="number" min="1" name="price_tiers[__INDEX__][max_qty]" placeholder="Max (Optional)"
                class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
        </div>
        <div class="sm:col-span-3">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-semibold text-xs">₹</span>
                <input type="number" step="0.01" min="0" name="price_tiers[__INDEX__][price]" placeholder="0.00" required
                    class="w-full bg-white border border-slate-200 rounded-xl pl-7 pr-3 py-2 text-xs font-bold text-slate-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
            </div>
        </div>
        <div class="sm:col-span-1 flex justify-center">
            <button type="button" class="removeTierRow text-slate-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Remove Tier">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </div>
</template>

<!-- ============================================== -->
<!-- JAVASCRIPT LOGIC                               -->
<!-- ============================================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ----------------------------------------------
    // TAB SWITCHING
    // ----------------------------------------------
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

        if (tabName === 'stock') {
            renderVariantStockRows();
        }
    }

    tabBtns.forEach(btn => btn.addEventListener('click', () => switchTab(btn.dataset.tab)));
    document.querySelectorAll('.tabNextBtn').forEach(btn => btn.addEventListener('click', () => switchTab(btn.dataset.next)));
    document.querySelectorAll('.tabPrevBtn').forEach(btn => btn.addEventListener('click', () => switchTab(btn.dataset.prev)));

    // ----------------------------------------------
    // VARIANTS & STOCK
    // ----------------------------------------------
    const hasVariants = document.getElementById('hasVariants');
    const variantsWrapper = document.getElementById('variantsWrapper');
    const singleVariantNotice = document.getElementById('singleVariantNotice');
    const variantRows = document.getElementById('variantRows');
    const addVariantBtn = document.getElementById('addVariantRow');
    const addVariantBtnSec = document.getElementById('addVariantRowSecondary');
    const variantRowTemplate = document.getElementById('variantRowTemplate');
    const variantCountBadge = document.getElementById('variantCountBadge');

    const singleStockBox = document.getElementById('singleStockBox');
    const variantStockWrapper = document.getElementById('variantStockWrapper');
    const variantStockRows = document.getElementById('variantStockRows');
    const variantStockRowTemplate = document.getElementById('variantStockRowTemplate');

    const summaryProductType = document.getElementById('summaryProductType');
    const summaryVariantCount = document.getElementById('summaryVariantCount');

    let variantIndex = 0;

    function updateVariantCount() {
        const count = variantRows.children.length;
        if (hasVariants.value === '1') {
            summaryProductType.textContent = 'Multi-Variant';
            summaryVariantCount.textContent = count;
            if (count > 0) {
                variantCountBadge.textContent = count;
                variantCountBadge.classList.remove('hidden');
            } else {
                variantCountBadge.classList.add('hidden');
            }
        } else {
            summaryProductType.textContent = 'Single Item';
            summaryVariantCount.textContent = '1';
            variantCountBadge.classList.add('hidden');
        }
    }

    function addVariantRow() {
        const html = variantRowTemplate.innerHTML.replaceAll('__INDEX__', variantIndex);
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        variantRows.appendChild(tempDiv.firstElementChild);
        variantIndex++;
        updateVariantCount();
        renderVariantStockRows();
    }

    function toggleVariantMode() {
        if (hasVariants.value === '1') {
            variantsWrapper.classList.remove('hidden');
            singleVariantNotice.classList.add('hidden');
            singleStockBox.classList.add('hidden');
            variantStockWrapper.classList.remove('hidden');
            if (variantRows.children.length === 0) {
                addVariantRow();
            }
            renderVariantStockRows();
        } else {
            variantsWrapper.classList.add('hidden');
            singleVariantNotice.classList.remove('hidden');
            singleStockBox.classList.remove('hidden');
            variantStockWrapper.classList.add('hidden');
            variantRows.innerHTML = '';
            variantStockRows.innerHTML = '';
            variantIndex = 0;
        }
        updateVariantCount();
    }

    hasVariants.addEventListener('change', toggleVariantMode);
    addVariantBtn.addEventListener('click', addVariantRow);
    addVariantBtnSec.addEventListener('click', addVariantRow);

    variantRows.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.removeVariantRow');
        if (removeBtn) {
            removeBtn.closest('.variant-row').remove();
            if (variantRows.children.length === 0) {
                addVariantRow();
            }
            updateVariantCount();
            renderVariantStockRows();
        }
    });

    // Live update stock tab labels when typing variant size/color/sku
    variantRows.addEventListener('input', function(e) {
        if (e.target.matches('.variant-size-input, .variant-color-input, .variant-sku-input')) {
            renderVariantStockRows();
        }
    });

    function getVariantLabel(vRow, fallbackIdx) {
        const size = vRow.querySelector('.variant-size-input')?.value.trim();
        const color = vRow.querySelector('.variant-color-input')?.value.trim();
        const sku = vRow.querySelector('.variant-sku-input')?.value.trim();
        const parts = [];
        if (size) parts.push(size);
        if (color) parts.push(color);
        let label = `Variant #${fallbackIdx + 1}`;
        if (parts.length) label += ` — ${parts.join(' / ')}`;
        if (sku) label += ` [${sku}]`;
        return label;
    }

    function renderVariantStockRows() {
        if (hasVariants.value !== '1') return;

        // Preserve already typed quantity and threshold values
        const currentValues = {};
        variantStockRows.querySelectorAll('.stock-row').forEach(row => {
            const idx = row.dataset.index;
            currentValues[idx] = {
                quantity: row.querySelectorAll('input')[0]?.value ?? '0',
                threshold: row.querySelectorAll('input')[1]?.value ?? '5',
            };
        });

        variantStockRows.innerHTML = '';

        Array.from(variantRows.children).forEach((vRow, i) => {
            const sizeInput = vRow.querySelector('.variant-size-input');
            const match = sizeInput ? sizeInput.name.match(/variants\[(\d+)\]/) : null;
            const idx = match ? match[1] : i;

            const html = variantStockRowTemplate.innerHTML.replaceAll('__INDEX__', idx);
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            const stockRow = tempDiv.firstElementChild;

            stockRow.querySelector('.stock-row-label').textContent = getVariantLabel(vRow, i);

            if (currentValues[idx]) {
                stockRow.querySelectorAll('input')[0].value = currentValues[idx].quantity;
                stockRow.querySelectorAll('input')[1].value = currentValues[idx].threshold;
            }

            variantStockRows.appendChild(stockRow);
        });
    }

    toggleVariantMode();

    // ----------------------------------------------
    // WHOLESALE PRICE TIERS
    // ----------------------------------------------
    const tierRows = document.getElementById('tierRows');
    const addTierBtn = document.getElementById('addTierRow');
    const tierRowTemplate = document.getElementById('tierRowTemplate');
    const tierCountBadge = document.getElementById('tierCountBadge');
    const noTiersNotice = document.getElementById('noTiersNotice');
    const summaryTierCount = document.getElementById('summaryTierCount');
    let tierIndex = 0;

    function updateTierCount() {
        const count = tierRows.children.length;
        summaryTierCount.textContent = count;
        if (count > 0) {
            tierCountBadge.textContent = count;
            tierCountBadge.classList.remove('hidden');
            noTiersNotice.classList.add('hidden');
        } else {
            tierCountBadge.classList.add('hidden');
            noTiersNotice.classList.remove('hidden');
        }
    }

    function addTierRow() {
        const html = tierRowTemplate.innerHTML.replaceAll('__INDEX__', tierIndex);
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        tierRows.appendChild(tempDiv.firstElementChild);
        tierIndex++;
        updateTierCount();
    }

    addTierBtn.addEventListener('click', addTierRow);

    tierRows.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.removeTierRow');
        if (removeBtn) {
            removeBtn.closest('.tier-row').remove();
            updateTierCount();
        }
    });

    // ----------------------------------------------
    // MEDIA & LIVE IMAGE PREVIEWS
    // ----------------------------------------------
    const imageInput = document.getElementById('imageInput');
    const imagePreviewGrid = document.getElementById('imagePreviewGrid');
    const imageCountBadge = document.getElementById('imageCountBadge');
    const summaryImageCount = document.getElementById('summaryImageCount');

    imageInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        imagePreviewGrid.innerHTML = '';

        if (files.length > 0) {
            imagePreviewGrid.classList.remove('hidden');
            imageCountBadge.textContent = files.length;
            imageCountBadge.classList.remove('hidden');
            summaryImageCount.textContent = files.length;

            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const card = document.createElement('div');
                    card.className = 'relative aspect-square rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center group shadow-sm';
                    card.innerHTML = `
                        <img src="${event.target.result}" class="w-full h-full object-contain p-2">
                        <span class="absolute top-2 left-2 bg-slate-900/70 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            #${index + 1}
                        </span>
                        <div class="absolute bottom-0 inset-x-0 bg-slate-900/60 backdrop-blur-xs text-white text-[10px] truncate px-2 py-1 text-center">
                            ${file.name}
                        </div>
                    `;
                    imagePreviewGrid.appendChild(card);
                };
                reader.readAsDataURL(file);
            });
        } else {
            imagePreviewGrid.classList.add('hidden');
            imageCountBadge.classList.add('hidden');
            summaryImageCount.textContent = '0';
        }
    });
});
</script>
@endsection