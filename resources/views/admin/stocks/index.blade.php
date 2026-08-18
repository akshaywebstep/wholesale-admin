@extends('layouts.admin')

@section('title', 'Stock Management')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Stock Management</h1>
        <p class="text-slate-500 text-sm mt-0.5">Track inventory quantities and low stock thresholds across warehouses.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.stock.create') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition-all shadow-sm active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Add New Stock
        </a>
    </div>
</div>

@if(session('success'))
<div
    class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
    <span>{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
</div>
@endif

<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr
                    class="bg-slate-50 border-b border-slate-200/80 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <th class="py-3.5 px-4">Product Variant</th>
                    <th class="py-3.5 px-4">Warehouse</th>
                    <th class="py-3.5 px-4">Quantity</th>
                    <th class="py-3.5 px-4">Threshold</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($stocks as $stock)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-3.5 px-4 font-semibold text-slate-800">
                        <div>{{ $stock->productVariant->product->name ?? 'N/A' }}</div>
                        <div class="text-xs text-slate-500 font-normal mt-0.5">
                            @if(!empty($stock->productVariant->size))
                            <span class="inline-block bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded mr-1">Size:
                                {{ $stock->productVariant->size }}</span>
                            @endif
                            @if(!empty($stock->productVariant->color))
                            <span class="inline-block bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded mr-1">Color:
                                {{ $stock->productVariant->color }}</span>
                            @endif
                            <span>(SKU: {{ $stock->productVariant->sku ?? $stock->productVariant->id }})</span>
                        </div>
                    </td>
                    <td class="py-3.5 px-4 text-slate-600">
                        {{ $stock->warehouse->name ?? 'N/A' }}
                    </td>
                    <td class="py-3.5 px-4 font-bold text-slate-900">
                        {{ $stock->quantity }}
                    </td>
                    <td class="py-3.5 px-4 text-slate-500">
                        {{ $stock->threshold }}
                    </td>
                    <td class="py-3.5 px-4">
                        @if($stock->quantity <= $stock->threshold)
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                Low Stock
                            </span>
                            @else
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                In Stock
                            </span>
                            @endif
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.stock.edit', $stock) }}"
                                class="text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('admin.stock.destroy', $stock) }}" method="POST" class="inline-block"
                                onsubmit="return confirm('Delete this stock record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-slate-400 text-sm">No stock entries found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($stocks, 'hasPages') && $stocks->hasPages())
    <div class="p-4 border-t border-slate-200">
        {{ $stocks->links() }}
    </div>
    @endif
</div>
@endsection