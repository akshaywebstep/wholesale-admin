@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<!-- Header Section -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Products
        </a>
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $product->name }}</h1>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $product->is_active ? 'bg-emerald-500/10 text-emerald-700 border border-emerald-200' : 'bg-slate-500/10 text-slate-600 border border-slate-200' }}">
                {{ $product->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>
</div>

<!-- Success Alert -->
@if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

<!-- Error Alert -->
@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6 text-sm flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="space-y-1">
            <span class="font-semibold">Please check the fields:</span>
            <ul class="list-disc list-inside text-xs space-y-0.5 opacity-90">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column (Main Details, Variants, Pricing Tiers) -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Basic Info Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <h2 class="text-base font-semibold text-slate-800 mb-4 pb-3 border-b border-slate-100">Product Details</h2>
            
            <form action="{{ route('admin.products.update', $product) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1.5">Product Name</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1.5">SKU Code</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1.5">Category</label>
                            <select name="category_id" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                @foreach($categories as $parent)
                                    <option value="{{ $parent->id }}" {{ $product->category_id == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                    @foreach($parent->children as $child)
                                        <option value="{{ $child->id }}" {{ $product->category_id == $child->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $child->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1.5">Base Price (₹)</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-semibold text-sm">₹</span>
                                <input type="number" step="0.01" name="base_price" value="{{ old('base_price', $product->base_price) }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl pl-8 pr-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                            </div>
                        </div>

                        <div class="flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer mt-4">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $product->is_active ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ml-3 text-sm font-semibold text-slate-700">Active Listing</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1.5">Description</label>
                        <textarea name="description" rows="3" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-4 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none resize-y">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 text-right">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm shadow-blue-200 active:scale-[0.98]">
                        Update Details
                    </button>
                </div>
            </form>
        </div>

        <!-- Variants Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Variants (Size / Color)</h2>
                    <p class="text-xs text-slate-500">Manage specific inventory SKUs and options</p>
                </div>
                <a href="{{ route('admin.productVariants.index', ['product_id' => $product->id]) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                    Manage Variants
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs font-semibold uppercase text-slate-400 border-b border-slate-100">
                            <th class="pb-2">Size</th>
                            <th class="pb-2">Color</th>
                            <th class="pb-2">Variant SKU</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($product->variants as $variant)
                        <tr>
                            <td class="py-3 font-medium text-slate-800">{{ $variant->size ?? '-' }}</td>
                            <td class="py-3 font-medium text-slate-800">{{ $variant->color ?? '-' }}</td>
                            <td class="py-3 font-mono text-xs text-slate-500">{{ $variant->variant_sku }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-slate-400 text-xs">No variants configured for this product yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Price Tiers Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <h2 class="text-base font-semibold text-slate-800 mb-1">Price Tiers (Bulk Pricing)</h2>
            <p class="text-xs text-slate-500 mb-4 pb-3 border-b border-slate-100">Set tiered discount rates based on order quantity</p>

            <div class="overflow-x-auto mb-6">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs font-semibold uppercase text-slate-400 border-b border-slate-100">
                            <th class="pb-2">Group</th>
                            <th class="pb-2">Min Qty</th>
                            <th class="pb-2">Max Qty</th>
                            <th class="pb-2">Price</th>
                            <th class="pb-2 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($product->priceTiers as $tier)
                        <tr>
                            <td class="py-3 font-medium text-slate-700">{{ $tier->customerGroup->name ?? 'All Groups' }}</td>
                            <td class="py-3 text-slate-600">{{ $tier->min_qty }}</td>
                            <td class="py-3 text-slate-600">{{ $tier->max_qty ?? '∞' }}</td>
                            <td class="py-3 font-bold text-slate-900">₹{{ number_format($tier->price, 2) }}</td>
                            <td class="py-3 text-right">
                                <form action="{{ route('admin.products.price-tiers.destroy', $tier) }}" method="POST" onsubmit="return confirm('Remove this price tier?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400 text-xs">No custom bulk pricing tiers added yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Add Tier Inline Form -->
            <form action="{{ route('admin.products.price-tiers.store', $product) }}" method="POST" class="bg-slate-50/70 p-4 rounded-xl border border-slate-200/60">
                @csrf
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 items-end">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Customer Group</label>
                        <select name="customer_group_id" class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs text-slate-700 outline-none focus:border-blue-500">
                            <option value="">All</option>
                            @foreach($customerGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Min Qty</label>
                        <input type="number" name="min_qty" required min="1" placeholder="1" class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs text-slate-700 outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Max Qty</label>
                        <input type="number" name="max_qty" placeholder="Optional" class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs text-slate-700 outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Price (₹)</label>
                        <input type="number" step="0.01" name="price" required placeholder="0.00" class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs text-slate-700 outline-none focus:border-blue-500">
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-medium py-2 rounded-lg text-xs transition-colors shadow-sm">
                            + Add Tier
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <!-- Right Column (Media Gallery & Upload) -->
    <div class="space-y-6">
        
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <h2 class="text-base font-semibold text-slate-800 mb-1">Product Media</h2>
            <p class="text-xs text-slate-500 mb-4 pb-3 border-b border-slate-100">Upload multiple images for catalog display</p>

            <!-- Media Grid -->
            <div class="grid grid-cols-3 gap-3 mb-6">
                @forelse($product->images as $image)
                <div class="group relative aspect-square rounded-xl bg-slate-50 border border-slate-200 overflow-hidden flex items-center justify-center">
                    <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-contain p-1">
                    
                    <form action="{{ route('admin.products.images.destroy', $image) }}" method="POST" class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center" onsubmit="return confirm('Remove image?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full shadow-lg transition-transform active:scale-95" title="Delete Image">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
                @empty
                <div class="col-span-3 py-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                    <svg class="w-8 h-8 text-slate-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-xs text-slate-400 font-medium">No images uploaded</p>
                </div>
                @endforelse
            </div>

            <!-- Upload Area -->
            <form action="{{ route('admin.products.images.store', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div class="relative border-2 border-dashed border-slate-200 hover:border-blue-400 rounded-xl p-4 text-center transition-colors bg-slate-50/50">
                    <input type="file" name="images[]" accept="image/*" multiple required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <svg class="w-6 h-6 text-slate-400 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span class="text-xs font-semibold text-blue-600 block">Click or Drag images here</span>
                    <span class="text-[10px] text-slate-400">PNG, JPG, WEBP up to 2MB</span>
                </div>

                <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-medium py-2 rounded-xl text-xs transition-colors shadow-sm">
                    Upload Images
                </button>
            </form>
        </div>

    </div>

</div>
@endsection