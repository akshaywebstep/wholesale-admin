@extends('layouts.admin')

@section('title', 'Add Stock')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Add Stock Record</h1>
            <p class="text-slate-500 text-sm mt-0.5">Assign stock quantity and threshold to a product variant.</p>
        </div>
        <a href="{{ route('admin.stock.index') }}"
            class="text-slate-600 bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-xl text-sm font-semibold transition-all">
            &larr; Back
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.stock.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Product
                        Variant *</label>
                    <select name="product_variant_id" required
                        class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all">
                        <option value="">Select Variant</option>
                        @foreach($variants as $variant)
                        <option value="{{ $variant->id }}"
                            {{ old('product_variant_id', $stock->product_variant_id ?? '') == $variant->id ? 'selected' : '' }}>
                            {{ $variant->product->name ?? 'Product' }}
                            @if(!empty($variant->size)) | Size: {{ $variant->size }} @endif
                            @if(!empty($variant->color)) | Color: {{ $variant->color }} @endif
                            @if(!empty($variant->variant_name)) | {{ $variant->variant_name }} @endif
                            - (SKU: {{ $variant->sku ?? $variant->id }})
                        </option>
                        @endforeach
                    </select>
                    @error('product_variant_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Warehouse
                        *</label>
                    <select name="warehouse_id" required
                        class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all">
                        <option value="">Select Warehouse</option>
                        @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}"
                            {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('warehouse_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Quantity
                        *</label>
                    <input type="number" name="quantity" value="{{ old('quantity', 0) }}" required min="0"
                        class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all">
                    @error('quantity') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Low Stock
                        Threshold *</label>
                    <input type="number" name="threshold" value="{{ old('threshold', 5) }}" required min="0"
                        class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all">
                    @error('threshold') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.stock.index') }}"
                    class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-all">Cancel</a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-xl text-sm transition-all shadow-sm">Save
                    Stock</button>
            </div>
        </form>
    </div>
</div>
@endsection