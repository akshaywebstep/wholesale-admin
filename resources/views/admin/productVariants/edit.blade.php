@extends('layouts.admin')

@section('title', 'Edit Variant')

@section('content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <a href="{{ route('admin.productVariants.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Variants
        </a>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Variant</h1>
        <p class="text-sm text-slate-500 mt-0.5">Update configuration details for SKU: <span class="font-mono text-slate-700 font-semibold">{{ $variant->variant_sku }}</span></p>
    </div>
</div>

<!-- Error Alert -->
@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6 text-sm flex items-start gap-3 max-w-2xl">
        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="space-y-1">
            <span class="font-semibold">Please check the inputs:</span>
            <ul class="list-disc list-inside text-xs space-y-0.5 opacity-90">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<!-- Form Card -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 max-w-2xl">
    <form action="{{ route('admin.productVariants.update', $variant) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-5">
            <!-- Product Select -->
            <div>
                <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1.5">Parent Product <span class="text-red-500">*</span></label>
                <select name="product_id" required class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none @error('product_id') border-red-300 @enderror">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id', $variant->product_id) == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                @error('product_id') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Size & Color Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1.5">Size</label>
                    <input type="text" name="size" value="{{ old('size', $variant->size) }}"
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1.5">Color</label>
                    <input type="text" name="color" value="{{ old('color', $variant->color) }}"
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                </div>
            </div>

            <!-- Variant SKU -->
            <div>
                <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1.5">Variant SKU <span class="text-red-500">*</span></label>
                <input type="text" name="variant_sku" value="{{ old('variant_sku', $variant->variant_sku) }}" required
                       class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none @error('variant_sku') border-red-300 @enderror">
                @error('variant_sku') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-slate-100 flex items-center gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm shadow-blue-200 active:scale-[0.98]">
                Update Variant
            </button>
            <a href="{{ route('admin.productVariants.index') }}" class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-medium px-5 py-2.5 rounded-xl text-sm transition-all">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection