@extends('layouts.admin')

@section('title', 'Edit Product: ' . $product->name)

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
            <div class="flex flex-wrap items-center gap-2.5">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $product->name }}</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold font-mono {{ $product->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                    {{ $product->is_active ? 'ACTIVE' : 'INACTIVE' }}
                </span>
                <span class="font-mono bg-slate-100 text-slate-700 text-xs px-2.5 py-0.5 rounded-md border border-slate-200">
                    SKU: {{ $product->sku }}
                </span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('shop.product', $product->id) }}" target="_blank"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all shadow-sm">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                View on Storefront
            </a>
            <button type="button" onclick="document.getElementById('editGeneralForm').requestSubmit()"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-md shadow-blue-200 active:scale-[0.98] transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Changes
            </button>
        </div>
    </div>

    <!-- Success & Error Alerts -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl text-xs flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl text-xs flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="space-y-1">
            <p class="font-bold text-sm">Please resolve the input issues:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- LEFT COLUMN: Specifications, Media, Variants, Tiers (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- CARD 1: General Specifications Form -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs">1</span>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">General Specifications</h2>
                    </div>
                </div>

                <form action="{{ route('admin.products.update', $product) }}" method="POST" id="editGeneralForm" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Product Name -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Product Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="inputName" value="{{ old('name', $product->name) }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                    </div>

                    <!-- Category & SKU Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                SKU Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="sku" id="inputSku" value="{{ old('sku', $product->sku) }}" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono uppercase text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" id="inputCategory" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                @foreach($categories as $parent)
                                <option value="{{ $parent->id }}" data-name="{{ $parent->name }}" {{ old('category_id', $product->category_id) == $parent->id ? 'selected' : '' }} class="font-bold text-slate-900">
                                    📁 {{ $parent->name }}
                                </option>
                                @foreach($parent->children as $child)
                                <option value="{{ $child->id }}" data-name="{{ $child->name }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                                    &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $child->name }}
                                </option>
                                @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Price & Weight & Unit Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Base Price ($) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">$</span>
                                <input type="number" step="0.01" min="0" name="base_price" id="inputPrice" value="{{ old('base_price', $product->base_price) }}" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-4 py-2.5 text-sm font-bold text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Net Weight / Item Size
                            </label>
                            <input type="number" step="0.001" min="0" name="weight" value="{{ old('weight', $product->weight) }}"
                                placeholder="e.g. 0.250 for 250g, 1.5 for 1.5kg"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                            <span class="text-[10px] text-slate-400 mt-1 block">💡 In KG (e.g. 0.25 = 250g) or grams</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Unit of Measure <span class="text-red-500">*</span>
                            </label>
                            <select name="unit_id" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }} ({{ $unit->short_code }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Visibility Toggle -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200/80">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Catalog Visibility</span>
                            <span class="text-[11px] text-slate-500">Control storefront and B2B ordering status</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="inputStatus" value="1" class="sr-only peer" {{ $product->is_active ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <!-- Rich Text Description (Quill.js Editor) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Description & Technical Specifications
                            </label>
                            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                                ✨ Rich Text Editor
                            </span>
                        </div>
                        <div id="quillEditor">{!! old('description', $product->description) !!}</div>
                        <input type="hidden" name="description" id="hiddenDescription" value="{{ old('description', $product->description) }}">
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-md shadow-blue-200 active:scale-[0.98] transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save General Specifications
                        </button>
                    </div>
                </form>
            </div>

            <!-- CARD 2: Product Photos & Gallery Manager -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs">2</span>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Product Media Gallery</h2>
                    </div>
                    <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2.5 py-0.5 rounded-full">
                        {{ $product->images->count() }} Uploaded
                    </span>
                </div>

                <!-- Existing Images Grid -->
                @if($product->images->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach($product->images as $index => $img)
                    <div class="relative aspect-square rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center p-2 group shadow-sm">
                        <img src="{{ $img->url }}" alt="Product Image" class="max-w-full max-h-full object-contain pointer-events-none">
                        @if($index === 0)
                        <span class="absolute top-2 left-2 bg-blue-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full">
                            Main
                        </span>
                        @endif
                        <!-- Delete Form -->
                        <form action="{{ route('admin.products.images.destroy', [$product, $img]) }}" method="POST"
                            class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity"
                            onsubmit="return confirm('Are you sure you want to delete this photo?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 bg-red-600 text-white hover:bg-red-700 rounded-lg shadow-md transition-colors" title="Delete Image">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-4 bg-slate-50 border border-dashed border-slate-200 rounded-xl text-center text-xs text-slate-400">
                    No images uploaded yet. Upload pictures below to display on storefront.
                </div>
                @endif

                <!-- Upload New Images Form -->
                <form action="{{ route('admin.products.images.store', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4 pt-2">
                    @csrf
                    <div class="border-2 border-dashed border-slate-200 hover:border-blue-400 bg-slate-50/60 hover:bg-blue-50/30 rounded-2xl p-5 text-center transition-all cursor-pointer relative group">
                        <input type="file" name="images[]" multiple accept="image/*" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="flex flex-col items-center justify-center space-y-1.5 pointer-events-none">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Select new photos to upload</p>
                                <p class="text-[11px] text-slate-400">JPG, PNG, WEBP up to 3MB each</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-sm">
                            Upload Photos
                        </button>
                    </div>
                </form>
            </div>

            <!-- CARD 3: Variants & Stock Inventory Manager -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">3</span>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Variants & Inventory</h2>
                    </div>
                    <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-0.5 rounded-full">
                        {{ $product->variants->count() }} Variants
                    </span>
                </div>

                <!-- Existing Variants Table & Stock Editor -->
                <form action="{{ route('admin.products.stock.update', $product) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="overflow-x-auto rounded-xl border border-slate-200">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead class="bg-slate-50 border-b border-slate-200 font-bold text-slate-600 uppercase">
                                <tr>
                                    <th class="p-3">Variant SKU</th>
                                    <th class="p-3">Size / Spec</th>
                                    <th class="p-3">Color / Flavor</th>
                                    <th class="p-3 w-32">Warehouse Qty</th>
                                    <th class="p-3 w-28">Low Alert</th>
                                    <th class="p-3 text-center w-12">Delete</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($product->variants as $variant)
                                @php $stock = $variant->stocks->first(); @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="p-3 font-mono font-bold text-slate-800">
                                        {{ $variant->variant_sku }}
                                    </td>
                                    <td class="p-3 text-slate-700">
                                        {{ $variant->size ?? 'Standard' }}
                                    </td>
                                    <td class="p-3 text-slate-700">
                                        {{ $variant->color ?? '-' }}
                                    </td>
                                    <td class="p-3">
                                        @if($stock)
                                        <input type="number" min="0"
                                            name="stock[{{ $variant->id }}][{{ $stock->warehouse_id }}][quantity]"
                                            value="{{ $stock->quantity }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold text-slate-900 focus:bg-white focus:border-blue-500 outline-none">
                                        @else
                                        <span class="text-slate-400 italic">No Warehouse Stock</span>
                                        @endif
                                    </td>
                                    <td class="p-3">
                                        @if($stock)
                                        <input type="number" min="0"
                                            name="stock[{{ $variant->id }}][{{ $stock->warehouse_id }}][threshold]"
                                            value="{{ $stock->threshold }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-blue-500 outline-none">
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="p-3 text-center">
                                        @if($product->variants->count() > 1)
                                        <button type="button" onclick="if(confirm('Delete variant {{ $variant->variant_sku }}?')) document.getElementById('delete-variant-{{ $variant->id }}').submit()"
                                            class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="Delete Variant">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        @else
                                        <span class="text-[10px] text-slate-400">Primary</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-slate-400 text-xs">No variants configured.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-sm">
                            Update Stock Levels
                        </button>
                    </div>
                </form>

                <!-- Hidden Delete Variant Forms -->
                @foreach($product->variants as $variant)
                <form id="delete-variant-{{ $variant->id }}" action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
                @endforeach

                <!-- Add New Variant Sub-Form -->
                <div class="pt-4 border-t border-slate-100">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">+ Add New Variant Option</h3>
                    <form action="{{ route('admin.products.variants.store', $product) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-5 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Size / Spec</label>
                            <input type="text" name="size" placeholder="e.g. 500g" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Color / Flavor</label>
                            <input type="text" name="color" placeholder="e.g. Grape" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Variant SKU <span class="text-red-500">*</span></label>
                            <input type="text" name="variant_sku" required placeholder="SKU-NEW" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-mono uppercase outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Initial Qty</label>
                            <input type="number" min="0" name="quantity" value="0" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold outline-none focus:border-blue-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-3 rounded-lg transition-colors">
                                Add Variant
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- CARD 4: Wholesale Bulk Pricing Tiers -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">4</span>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Wholesale Volume Price Tiers</h2>
                    </div>
                    <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-0.5 rounded-full">
                        {{ $product->priceTiers->count() }} Tiers Configured
                    </span>
                </div>

                <!-- Existing Tiers Table -->
                @if($product->priceTiers->count() > 0)
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200 font-bold text-slate-600 uppercase">
                            <tr>
                                <th class="p-3">Order Quantity Range</th>
                                <th class="p-3">Wholesale Rate</th>
                                <th class="p-3">Buyer Savings</th>
                                <th class="p-3 text-center w-12">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($product->priceTiers as $tier)
                            @php
                                $savings = ($product->base_price > 0 && $tier->price < $product->base_price)
                                    ? round((($product->base_price - $tier->price) / $product->base_price) * 100)
                                    : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-3 font-semibold text-slate-800">
                                    {{ $tier->min_qty }} - {{ $tier->max_qty ? $tier->max_qty . ' units' : '∞ (Unlimited)' }}
                                </td>
                                <td class="p-3 font-bold text-emerald-700 text-sm">
                                    ${{ number_format($tier->price, 2) }}
                                </td>
                                <td class="p-3">
                                    @if($savings > 0)
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded">
                                        Save {{ $savings }}%
                                    </span>
                                    @else
                                    <span class="bg-slate-100 text-slate-600 text-[10px] font-medium px-2 py-0.5 rounded">
                                        Tier Rate
                                    </span>
                                    @endif
                                </td>
                                <td class="p-3 text-center">
                                    <form action="{{ route('admin.products.price-tiers.destroy', [$product, $tier]) }}" method="POST"
                                        onsubmit="return confirm('Remove this volume pricing tier?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="Delete Tier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-4 bg-slate-50 border border-dashed border-slate-200 rounded-xl text-center text-xs text-slate-400">
                    No volume tiers configured. Add quantity discounts below to incentivize bulk purchasing.
                </div>
                @endif

                <!-- Add Tier Sub-Form -->
                <div class="pt-4 border-t border-slate-100">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">+ Add New Volume Discount Tier</h3>
                    <form action="{{ route('admin.products.price-tiers.store', $product) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Min Qty <span class="text-red-500">*</span></label>
                            <input type="number" min="1" name="min_qty" required placeholder="10" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Max Qty (Blank = ∞)</label>
                            <input type="number" min="1" name="max_qty" placeholder="e.g. 50" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Tier Rate ($) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" min="0" name="price" required placeholder="0.00" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold text-emerald-700 outline-none focus:border-blue-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold py-2 px-3 rounded-lg transition-colors">
                                Add Tier
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-red-50/50 rounded-2xl border border-red-200 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-sm font-bold text-red-900">Delete This Product</h3>
                    <p class="text-xs text-red-600 mt-0.5">This will permanently delete the product, its variants, gallery photos, and price tiers.</p>
                </div>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this product?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                        Delete Product
                    </button>
                </form>
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
                    <a href="{{ route('shop.product', $product->id) }}" target="_blank" class="text-[10px] text-blue-600 hover:underline font-semibold">
                        Open Page ↗
                    </a>
                </div>

                <!-- Mini Storefront Card -->
                <div class="bg-slate-50/60 rounded-xl border border-slate-200 overflow-hidden group">
                    <div class="relative h-44 bg-slate-100 flex items-center justify-center overflow-hidden p-2">
                        <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $product->total_stock > 0 ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white' }} shadow-sm z-10">
                            {{ $product->total_stock > 0 ? '● In Stock (' . $product->total_stock . ')' : 'Out of Stock' }}
                        </span>
                        <span id="previewStatusBadge" class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $product->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }} z-10">
                            {{ $product->is_active ? 'ACTIVE' : 'INACTIVE' }}
                        </span>
                        <img id="previewImage" src="{{ $product->featured_image_url }}" alt="Preview"
                            class="max-w-full max-h-full object-contain pointer-events-none transition-transform duration-300">
                    </div>

                    <div class="p-4 space-y-2 bg-white">
                        <div class="flex items-center justify-between text-[11px] text-slate-400">
                            <span id="previewSku" class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-slate-600 font-semibold">{{ $product->sku }}</span>
                            <span id="previewCategory" class="truncate max-w-[120px] text-right font-medium text-slate-500">{{ $product->category->name ?? 'Category' }}</span>
                        </div>
                        <h3 id="previewTitle" class="font-bold text-slate-900 text-xs leading-snug line-clamp-2">
                            {{ $product->name }}
                        </h3>
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 block font-medium">Base Price</span>
                                <span id="previewPrice" class="text-sm font-bold text-slate-900">${{ number_format($product->base_price, 2) }}</span>
                            </div>
                            <span class="text-[10px] text-blue-600 bg-blue-50 px-2 py-1 rounded-lg font-bold">
                                Wholesale
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR CARD 2: Quick Metrics & Health -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-3">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wide border-b border-slate-100 pb-2.5">
                    Catalog Metrics
                </h3>

                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center justify-between text-slate-500">
                        <span>Total Inventory:</span>
                        <span class="font-bold text-slate-800">{{ $product->total_stock }} units</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-500">
                        <span>Active Variants:</span>
                        <span class="font-bold text-slate-800">{{ $product->variants->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-500">
                        <span>Volume Tiers:</span>
                        <span class="font-bold text-emerald-600">{{ $product->priceTiers->count() }} Tiers</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-500">
                        <span>Gallery Photos:</span>
                        <span class="font-bold text-purple-600">{{ $product->images->count() }} Photos</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-500 pt-2 border-t border-slate-100">
                        <span>Created:</span>
                        <span class="text-slate-600">{{ $product->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Quill Rich Text Editor
    const quill = new Quill('#quillEditor', {
        theme: 'snow',
        placeholder: 'Enter detailed wholesale description, technical notes, usage guidelines...',
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

    const form = document.getElementById('editGeneralForm');
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

    const previewTitle = document.getElementById('previewTitle');
    const previewPrice = document.getElementById('previewPrice');
    const previewCategory = document.getElementById('previewCategory');
    const previewSku = document.getElementById('previewSku');
    const previewStatus = document.getElementById('previewStatusBadge');

    nameInput?.addEventListener('input', (e) => {
        previewTitle.textContent = e.target.value.trim() || 'Product Name';
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
        previewSku.textContent = e.target.value.toUpperCase() || 'SKU';
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
});
</script>
@endsection