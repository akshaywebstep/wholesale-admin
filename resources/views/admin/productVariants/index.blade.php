@extends('layouts.admin')

@section('title', 'Product Variants')

@section('content')
<!-- Header & Actions -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Product Variants</h1>
        <p class="text-sm text-slate-500 mt-0.5">Manage sizes, colors, and SKU codes across your product catalog.</p>
    </div>
    <a href="{{ route('admin.productVariants.create', request()->only('product_id')) }}"
       class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition-all shadow-sm shadow-blue-200 active:scale-[0.98]">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Variant
    </a>
</div>

<!-- Alert Message -->
@if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

<!-- Filters & Search Bar -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="w-full sm:w-64">
            <select name="product_id" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none" onchange="this.form.submit()">
                <option value="">All Products</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="flex-1 relative">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by variant SKU..."
                   class="w-full bg-slate-50/50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-medium px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">
                Filter
            </button>
            @if(request()->hasAny(['product_id', 'search']))
                <a href="{{ route('admin.productVariants.index') }}" class="px-4 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Variants Data Table -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50/70 border-b border-slate-100 text-xs font-semibold uppercase text-slate-500">
                <tr>
                    <th class="py-3.5 px-6">Product</th>
                    <th class="py-3.5 px-6">Size</th>
                    <th class="py-3.5 px-6">Color</th>
                    <th class="py-3.5 px-6">Variant SKU</th>
                    <th class="py-3.5 px-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($variants as $variant)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-4 px-6 font-semibold text-slate-800">
                        {{ $variant->product->name ?? '—' }}
                    </td>
                    <td class="py-4 px-6">
                        @if($variant->size)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200/60">{{ $variant->size }}</span>
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        @if($variant->color)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">{{ $variant->color }}</span>
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <span class="font-mono text-xs bg-slate-50 px-2 py-1 rounded border border-slate-200/80 text-slate-600 font-semibold">{{ $variant->variant_sku }}</span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="inline-flex items-center justify-end gap-2">
                            <a href="{{ route('admin.productVariants.edit', $variant) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Variant">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.productVariants.destroy', $variant) }}" method="POST" onsubmit="return confirm('Delete this variant?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Variant">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center">
                        <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <p class="text-sm font-semibold text-slate-700">No variants found</p>
                        <p class="text-xs text-slate-400 mt-1">Try adjusting your filters or create a new variant.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($variants->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $variants->links() }}
        </div>
    @endif
</div>
@endsection