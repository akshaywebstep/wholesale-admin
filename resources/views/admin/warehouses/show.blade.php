@extends('layouts.admin') {{-- Change layout name if your master layout is different (e.g. layouts.admin) --}}

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.warehouses.index') }}"
                    class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $warehouse->name }}</h1>
                    <p class="text-slate-500 text-sm mt-0.5">Warehouse details and assigned stock inventory.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.warehouses.edit', $warehouse->id) }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-xl text-sm transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Warehouse
            </a>
        </div>
    </div>

    <!-- Warehouse Details Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Warehouse Name</span>
            <p class="text-lg font-bold text-slate-800 mt-1">{{ $warehouse->name }}</p>
            <p class="text-xs text-slate-400 mt-1">ID: #{{ $warehouse->id }}</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Status</span>
            <div class="mt-2">
                @if(strtolower($warehouse->status) === 'active')
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                </span>
                @else
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Inactive
                </span>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Stock Records</span>
            <p class="text-lg font-bold text-slate-800 mt-1">{{ $warehouse->stocks->count() }} Records</p>
        </div>
    </div>

    <!-- Location Card -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Location / Address</span>
        <p class="text-slate-700 mt-1 text-sm leading-relaxed">{{ $warehouse->location }}</p>
    </div>

    <!-- Stock Inventory Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-800 text-base">Assigned Stock Items</h2>
            <span class="text-xs text-slate-400 font-medium">{{ $warehouse->stocks->count() }} items available</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr
                        class="bg-slate-50/75 border-b border-slate-200/80 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                        <th class="px-6 py-3.5">#ID</th>
                        <th class="px-6 py-3.5">Product</th>
                        <th class="px-6 py-3.5">Variant / SKU</th>
                        <th class="px-6 py-3.5">Quantity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($warehouse->stocks as $stock)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-xs font-mono text-slate-400">#{{ $stock->id }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $stock->productVariant->product->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $stock->productVariant->title ?? $stock->productVariant->sku ?? 'Default Variant' }}
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold {{ ($stock->quantity ?? 0) > 5 ? 'bg-slate-100 text-slate-800' : 'bg-rose-50 text-rose-700' }}">
                                {{ $stock->quantity ?? 0 }} in stock
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-sm">
                            No stock items assigned to this warehouse yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection