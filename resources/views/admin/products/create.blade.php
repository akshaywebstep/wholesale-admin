@extends('layouts.admin')

@section('title', 'Add New Wholesale Product')

@section('content')
<!-- Quill Rich Text Editor CDN -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<style>
    .ql-toolbar.ql-snow {
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
        border-color: #e2e8f0;
        background: #f8fafc;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
        border-color: #e2e8f0;
        background: #ffffff;
        font-family: inherit;
        font-size: 0.875rem;
    }
    .ql-editor {
        min-height: 180px;
    }
</style>

<div class="max-w-7xl mx-auto space-y-6 pb-12">

    <!-- Top Action Bar & Breadcrumbs -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.products.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Products Catalog
            </a>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Add Wholesale Product</h1>
            <p class="text-xs text-slate-500 mt-0.5">Create catalog listings with multi-tier bulk pricing, inventory, and rich product specifications.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}"
                class="px-4 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all shadow-sm">
                Cancel
            </a>
            <button type="button" onclick="document.getElementById('productForm').requestSubmit()"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-md shadow-blue-200 active:scale-[0.98] transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Publish Product
            </button>
        </div>
    </div>

    <!-- Validation Errors Alert -->
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl text-xs flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="space-y-1">
            <p class="font-bold text-sm">Please check the required form inputs:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Main Creation Form -->
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- LEFT COLUMN: Primary Configuration (8 Cols) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- CARD 1: Basic Specifications -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs">1</span>
                            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Basic Specifications</h2>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">* Required fields</span>
                    </div>

                    <div class="space-y-4">
                        <!-- Product Title -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Product Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="inputName" value="{{ old('name') }}" required
                                placeholder="e.g. Al Fakher Double Apple Hookah Tobacco 250g"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Category & Unit Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select name="category_id" id="inputCategory" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $parent)
                                    <option value="{{ $parent->id }}" data-name="{{ $parent->name }}" {{ old('category_id') == $parent->id ? 'selected' : '' }} class="font-bold text-slate-900">
                                        📁 {{ $parent->name }}
                                    </option>
                                    @foreach($parent->children as $child)
                                    <option value="{{ $child->id }}" data-name="{{ $child->name }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $child->name }}
                                    </option>
                                    @endforeach
                                    @endforeach
                                </select>
                                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Unit of Measure <span class="text-red-500">*</span>
                                </label>
                                <select name="unit_id" id="inputUnit" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                    <option value="">-- Select Unit --</option>
                                    @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" data-unit="{{ $unit->short_code }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }} ({{ $unit->short_code }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('unit_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Price & Weight Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Base Wholesale / Retail Price ($) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">$</span>
                                    <input type="number" step="0.01" min="0" name="base_price" id="inputPrice" value="{{ old('base_price') }}" required
                                        placeholder="0.00"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-4 py-2.5 text-sm font-bold text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                </div>
                                @error('base_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Net Weight / Item Size (Optional)
                                </label>
                                <input type="number" step="0.001" min="0" name="weight" id="inputWeight" value="{{ old('weight') }}"
                                    placeholder="e.g. 0.250 (for 250g) or 1.5 (for 1.5kg)"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                <span class="text-[11px] text-slate-400 mt-1 block">💡 Enter in KG (e.g. 0.25 = 250g, 1 = 1kg) or exact grams (e.g. 250)</span>
                                @error('weight') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Rich Text Description (Quill.js Editor) -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Description & Specifications
                                </label>
                                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                                    ✨ Rich Text Editor
                                </span>
                            </div>
                            
                            <!-- Quill Editor Container -->
                            <div id="quillEditor">{!! old('description') !!}</div>
                            <input type="hidden" name="description" id="hiddenDescription" value="{{ old('description') }}">
                        </div>
                    </div>
                </div>

                <!-- CARD 2: Media & Photo Gallery -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs">2</span>
                            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Product Photos & Media</h2>
                        </div>
                        <span id="photoCountBadge" class="hidden bg-purple-100 text-purple-700 text-xs font-bold px-2.5 py-0.5 rounded-full">0 Photos Selected</span>
                    </div>

                    <!-- Drag & Drop Upload Zone -->
                    <div class="border-2 border-dashed border-slate-200 hover:border-blue-400 bg-slate-50/60 hover:bg-blue-50/30 rounded-2xl p-6 text-center transition-all cursor-pointer relative group">
                        <input type="file" name="images[]" id="fileImages" multiple accept="image/*"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="flex flex-col items-center justify-center space-y-2 pointer-events-none">
                            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Click to upload or drag & drop</p>
                                <p class="text-xs text-slate-400 mt-0.5">PNG, JPG, WEBP, AVIF up to 3MB each. Multiple images allowed.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Image Thumbnails Preview Grid -->
                    <div id="imagePreviewGrid" class="grid grid-cols-2 sm:grid-cols-4 gap-3 hidden">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>

                <!-- CARD 3: Inventory & Variants Mode -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">3</span>
                            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Inventory & Variants</h2>
                        </div>

                        <!-- Segmented Switcher -->
                        <div class="inline-flex p-1 bg-slate-100 rounded-xl">
                            <button type="button" id="btnModeSingle" onclick="setProductMode(0)"
                                class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all bg-white text-slate-900 shadow-sm">
                                Single Product
                            </button>
                            <button type="button" id="btnModeVariants" onclick="setProductMode(1)"
                                class="px-3 py-1.5 text-xs font-medium text-slate-500 rounded-lg transition-all hover:text-slate-900">
                                Has Variants
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="has_variants" id="hasVariants" value="{{ old('has_variants', '0') }}">

                    <!-- SINGLE PRODUCT INVENTORY -->
                    <div id="sectionSingleInventory" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Initial Stock Quantity <span class="text-red-500">*</span>
                                </label>
                                <input type="number" min="0" name="single_quantity" id="inputSingleQty" value="{{ old('single_quantity', 0) }}"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                <span class="text-[11px] text-slate-400 mt-1 block">Stock available in Main Warehouse</span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Low Stock Warning Alert
                                </label>
                                <input type="number" min="0" name="single_threshold" id="inputSingleThreshold" value="{{ old('single_threshold', 5) }}"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                <span class="text-[11px] text-slate-400 mt-1 block">Alert triggered when stock falls below this</span>
                            </div>
                        </div>
                    </div>

                    <!-- MULTIPLE VARIANTS BUILDER -->
                    <div id="sectionVariantsBuilder" class="space-y-4 hidden">
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-slate-500">Add different variations like Sizes, Colors, or Flavors with independent stock.</p>
                            <button type="button" onclick="addVariantRow()"
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Variant Option
                            </button>
                        </div>

                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead class="bg-slate-50 border-b border-slate-200 font-bold text-slate-600 uppercase">
                                    <tr>
                                        <th class="p-3">Size / Spec</th>
                                        <th class="p-3">Color / Flavor</th>
                                        <th class="p-3">Variant SKU <span class="text-red-500">*</span></th>
                                        <th class="p-3 w-28">Stock Qty</th>
                                        <th class="p-3 w-24">Low Alert</th>
                                        <th class="p-3 text-center w-12">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="variantsTableBody" class="divide-y divide-slate-100">
                                    <!-- Dynamic Variant Rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- CARD 4: Wholesale Bulk Pricing Tiers -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">4</span>
                            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Wholesale Volume Price Tiers</h2>
                        </div>
                        <button type="button" onclick="addPriceTierRow()"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Volume Tier
                        </button>
                    </div>

                    <p class="text-xs text-slate-500">Reward bulk buyers with discounted pricing when they purchase larger order quantities.</p>

                    <div class="overflow-x-auto rounded-xl border border-slate-200">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead class="bg-slate-50 border-b border-slate-200 font-bold text-slate-600 uppercase">
                                <tr>
                                    <th class="p-3 w-36">Min Qty</th>
                                    <th class="p-3 w-36">Max Qty (Blank = ∞)</th>
                                    <th class="p-3">Wholesale Tier Rate ($)</th>
                                    <th class="p-3 w-32">Savings Badge</th>
                                    <th class="p-3 text-center w-12">Action</th>
                                </tr>
                            </thead>
                            <tbody id="priceTiersTableBody" class="divide-y divide-slate-100">
                                <!-- Dynamic Price Tier Rows -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Sticky Sidebar & Live Preview (4 Cols) -->
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">

                <!-- SIDEBAR CARD 1: Live Storefront Card Preview -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-3.5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-xs font-bold text-slate-800 uppercase tracking-wide flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Live Storefront Preview
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">Real-time</span>
                    </div>

                    <!-- Mini Storefront Card -->
                    <div class="bg-slate-50/60 rounded-xl border border-slate-200 overflow-hidden group">
                        <div class="relative h-44 bg-slate-100 flex items-center justify-center overflow-hidden p-2">
                            <span id="previewStockBadge" class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white shadow-sm z-10">
                                ● In Stock
                            </span>
                            <span id="previewStatusBadge" class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 z-10">
                                ACTIVE
                            </span>
                            <img id="previewImage" src="{{ asset('images/product1.png') }}" alt="Preview"
                                class="max-w-full max-h-full object-contain pointer-events-none transition-transform duration-300">
                        </div>

                        <div class="p-4 space-y-2 bg-white">
                            <div class="flex items-center justify-between text-[11px] text-slate-400">
                                <span id="previewSku" class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-slate-600 font-semibold">SKU-AUTO</span>
                                <span id="previewCategory" class="truncate max-w-[120px] text-right font-medium text-slate-500">Category</span>
                            </div>
                            <h3 id="previewTitle" class="font-bold text-slate-900 text-xs leading-snug line-clamp-2">
                                Product Name will appear here
                            </h3>
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] text-slate-400 block font-medium">Base Price</span>
                                    <span id="previewPrice" class="text-sm font-bold text-slate-900">$0.00</span>
                                </div>
                                <span class="text-[10px] text-blue-600 bg-blue-50 px-2 py-1 rounded-lg font-bold">
                                    Wholesale
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR CARD 2: Publishing & SKU Generator -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wide border-b border-slate-100 pb-2.5">
                        Publishing & SKU Settings
                    </h3>

                    <!-- Status Switch -->
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200/60">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Catalog Visibility</span>
                            <span class="text-[11px] text-slate-500">Show in wholesale store</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="inputStatus" value="1" class="sr-only peer" checked>
                            <div class="w-10 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <!-- SKU Input & Auto-Generator -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                SKU Code
                            </label>
                            <button type="button" onclick="autoGenerateSku()"
                                class="text-[11px] font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 transition-colors" title="Generate SKU from Category and Name">
                                ⚡ Auto Generate
                            </button>
                        </div>
                        <input type="text" name="sku" id="inputSku" value="{{ old('sku') }}"
                            placeholder="e.g. CAT-PRD-0001"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono uppercase text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        <span class="text-[11px] text-slate-400 mt-1 block">Leave blank for automatic assignment</span>
                    </div>

                    <!-- Final Action Button -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl text-sm transition-all shadow-md shadow-blue-200 active:scale-[0.98] flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save & Publish Product
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

<!-- ============================================== -->
<!-- JAVASCRIPT LOGIC & INTERACTIVITY               -->
<!-- ============================================== -->
<script>
let variantCount = 0;
let tierCount = 0;

// 1. Single vs Multiple Variants Mode Switcher
function setProductMode(mode) {
    const hasVariantsInput = document.getElementById('hasVariants');
    const btnSingle = document.getElementById('btnModeSingle');
    const btnVariants = document.getElementById('btnModeVariants');
    const sectionSingle = document.getElementById('sectionSingleInventory');
    const sectionVariants = document.getElementById('sectionVariantsBuilder');

    hasVariantsInput.value = mode;

    if (mode === 1) {
        btnVariants.className = 'px-3 py-1.5 text-xs font-bold rounded-lg transition-all bg-white text-slate-900 shadow-sm';
        btnSingle.className = 'px-3 py-1.5 text-xs font-medium text-slate-500 rounded-lg transition-all hover:text-slate-900';
        sectionSingle.classList.add('hidden');
        sectionVariants.classList.remove('hidden');
        if (document.querySelectorAll('#variantsTableBody tr').length === 0) {
            addVariantRow('Standard', 'Default');
        }
    } else {
        btnSingle.className = 'px-3 py-1.5 text-xs font-bold rounded-lg transition-all bg-white text-slate-900 shadow-sm';
        btnVariants.className = 'px-3 py-1.5 text-xs font-medium text-slate-500 rounded-lg transition-all hover:text-slate-900';
        sectionSingle.classList.remove('hidden');
        sectionVariants.classList.add('hidden');
    }
}

// 2. Add Variant Row
function addVariantRow(size = '', color = '', qty = 10, threshold = 5) {
    const tbody = document.getElementById('variantsTableBody');
    const index = variantCount++;
    const baseSku = document.getElementById('inputSku').value.trim() || 'VAR';
    const autoSku = baseSku + '-V' + (index + 1);

    const tr = document.createElement('tr');
    tr.id = `variant-row-${index}`;
    tr.className = 'hover:bg-slate-50/50 transition-colors';
    tr.innerHTML = `
        <td class="p-2.5">
            <input type="text" name="variants[${index}][size]" value="${size}" placeholder="e.g. 250g, XL"
                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-800 outline-none focus:bg-white focus:border-blue-500">
        </td>
        <td class="p-2.5">
            <input type="text" name="variants[${index}][color]" value="${color}" placeholder="e.g. Mint, Red"
                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-800 outline-none focus:bg-white focus:border-blue-500">
        </td>
        <td class="p-2.5">
            <input type="text" name="variants[${index}][variant_sku]" value="${autoSku}" required placeholder="SKU"
                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-mono uppercase text-slate-800 outline-none focus:bg-white focus:border-blue-500">
        </td>
        <td class="p-2.5">
            <input type="number" min="0" name="variants[${index}][quantity]" value="${qty}"
                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold text-slate-900 outline-none focus:bg-white focus:border-blue-500">
        </td>
        <td class="p-2.5">
            <input type="number" min="0" name="variants[${index}][threshold]" value="${threshold}"
                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-800 outline-none focus:bg-white focus:border-blue-500">
        </td>
        <td class="p-2.5 text-center">
            <button type="button" onclick="document.getElementById('variant-row-${index}').remove()"
                class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="Remove Variant">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
}

// 3. Add Wholesale Price Tier Row
function addPriceTierRow(minQty = 10, maxQty = '', price = '') {
    const tbody = document.getElementById('priceTiersTableBody');
    const index = tierCount++;

    const tr = document.createElement('tr');
    tr.id = `tier-row-${index}`;
    tr.className = 'hover:bg-slate-50/50 transition-colors';
    tr.innerHTML = `
        <td class="p-2.5">
            <input type="number" min="1" name="price_tiers[${index}][min_qty]" value="${minQty}" required
                placeholder="10"
                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold text-slate-800 outline-none focus:bg-white focus:border-blue-500">
        </td>
        <td class="p-2.5">
            <input type="number" min="1" name="price_tiers[${index}][max_qty]" value="${maxQty}"
                placeholder="Leave blank for ∞"
                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-800 outline-none focus:bg-white focus:border-blue-500">
        </td>
        <td class="p-2.5">
            <div class="relative">
                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">$</span>
                <input type="number" step="0.01" min="0" name="price_tiers[${index}][price]" value="${price}" required
                    placeholder="0.00" oninput="calculateSavings(${index})"
                    class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-6 pr-2.5 py-1.5 text-xs font-bold text-emerald-700 outline-none focus:bg-white focus:border-blue-500 tier-price-input">
            </div>
        </td>
        <td class="p-2.5">
            <span id="tier-badge-${index}" class="inline-block bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold px-2 py-0.5 rounded">
                Tier Active
            </span>
        </td>
        <td class="p-2.5 text-center">
            <button type="button" onclick="document.getElementById('tier-row-${index}').remove()"
                class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="Remove Tier">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
}

function calculateSavings(index) {
    const basePrice = parseFloat(document.getElementById('inputPrice').value) || 0;
    const tierInput = document.querySelector(`#tier-row-${index} .tier-price-input`);
    const badge = document.getElementById(`tier-badge-${index}`);
    if (!tierInput || !badge) return;

    const tierPrice = parseFloat(tierInput.value) || 0;
    if (basePrice > 0 && tierPrice > 0 && tierPrice < basePrice) {
        const savings = Math.round(((basePrice - tierPrice) / basePrice) * 100);
        badge.textContent = `Save ${savings}%`;
        badge.className = 'inline-block bg-emerald-100 text-emerald-800 border border-emerald-300 text-[10px] font-bold px-2 py-0.5 rounded';
    } else {
        badge.textContent = 'Tier Active';
        badge.className = 'inline-block bg-slate-100 text-slate-600 border border-slate-200 text-[10px] font-medium px-2 py-0.5 rounded';
    }
}

// 4. Auto-Generate SKU
function autoGenerateSku() {
    const name = document.getElementById('inputName').value.trim();
    const catSelect = document.getElementById('inputCategory');
    const catName = catSelect.options[catSelect.selectedIndex]?.dataset.name || 'CAT';

    const catCode = catName.replace(/[^A-Za-z0-9]/g, '').substring(0, 3).toUpperCase() || 'CAT';
    const nameCode = name.replace(/[^A-Za-z0-9]/g, '').substring(0, 3).toUpperCase() || 'PRD';
    const randomNum = Math.floor(1000 + Math.random() * 9000);

    const generated = `${catCode}-${nameCode}-${randomNum}`;
    document.getElementById('inputSku').value = generated;
    document.getElementById('previewSku').textContent = generated;
}

// 5. Real-Time Live Preview Sync & Quill Editor Init
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Quill Rich Text Editor
    const quill = new Quill('#quillEditor', {
        theme: 'snow',
        placeholder: 'Enter detailed wholesale description, packaging specs, ingredients, or brand highlights...',
        modules: {
            toolbar: [
                [{ 'header': [2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['blockquote'],
                ['clean']
            ]
        }
    });

    const form = document.getElementById('productForm');
    const hiddenDesc = document.getElementById('hiddenDescription');

    form.addEventListener('submit', () => {
        const html = quill.root.innerHTML;
        hiddenDesc.value = (html === '<p><br></p>') ? '' : html;
    });

    const nameInput = document.getElementById('inputName');
    const priceInput = document.getElementById('inputPrice');
    const catSelect = document.getElementById('inputCategory');
    const skuInput = document.getElementById('inputSku');
    const statusCheck = document.getElementById('inputStatus');
    const fileImages = document.getElementById('fileImages');
    const previewGrid = document.getElementById('imagePreviewGrid');
    const countBadge = document.getElementById('photoCountBadge');

    const previewTitle = document.getElementById('previewTitle');
    const previewPrice = document.getElementById('previewPrice');
    const previewCategory = document.getElementById('previewCategory');
    const previewSku = document.getElementById('previewSku');
    const previewStatus = document.getElementById('previewStatusBadge');
    const previewImage = document.getElementById('previewImage');

    nameInput?.addEventListener('input', (e) => {
        previewTitle.textContent = e.target.value.trim() || 'Product Name will appear here';
    });

    priceInput?.addEventListener('input', (e) => {
        const val = parseFloat(e.target.value) || 0;
        previewPrice.textContent = `$${val.toFixed(2)}`;
    });

    catSelect?.addEventListener('change', (e) => {
        const text = e.target.options[e.target.selectedIndex]?.text?.replace(/^[📁↳\s]+/, '') || 'Category';
        previewCategory.textContent = text;
    });

    skuInput?.addEventListener('input', (e) => {
        previewSku.textContent = e.target.value.toUpperCase() || 'SKU-AUTO';
    });

    statusCheck?.addEventListener('change', (e) => {
        if (e.target.checked) {
            previewStatus.textContent = 'ACTIVE';
            previewStatus.className = 'absolute top-2.5 right-2.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 z-10';
        } else {
            previewStatus.textContent = 'INACTIVE';
            previewStatus.className = 'absolute top-2.5 right-2.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-600 z-10';
        }
    });

    // File Preview Handler
    fileImages?.addEventListener('change', function () {
        previewGrid.innerHTML = '';
        const files = Array.from(this.files);

        if (files.length > 0) {
            previewGrid.classList.remove('hidden');
            countBadge.textContent = `${files.length} Photo${files.length > 1 ? 's' : ''} Selected`;
            countBadge.classList.remove('hidden');

            // Set main preview to first file
            const readerFirst = new FileReader();
            readerFirst.onload = (e) => { previewImage.src = e.target.result; };
            readerFirst.readAsDataURL(files[0]);

            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const card = document.createElement('div');
                    card.className = 'relative aspect-square rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center p-2 group';
                    card.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}" class="max-w-full max-h-full object-contain">
                        ${index === 0 ? '<span class="absolute top-1.5 left-1.5 text-[9px] font-bold bg-blue-600 text-white px-1.5 py-0.5 rounded">Main</span>' : ''}
                    `;
                    previewGrid.appendChild(card);
                };
                reader.readAsDataURL(file);
            });
        } else {
            previewGrid.classList.add('hidden');
            countBadge.classList.add('hidden');
            previewImage.src = '{{ asset("images/product1.png") }}';
        }
    });
});
</script>
@endsection