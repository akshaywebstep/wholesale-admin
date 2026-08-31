@extends('layouts.admin')

@section('title', 'Warehouse & Inventory Hubs')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Warehouse & Inventory Hubs</h1>
            <p class="text-xs text-slate-500 mt-0.5">Central distribution centers, live stock valuations, and facility logistics.</p>
        </div>

        {{-- [DISABLED] Add Warehouse Button
        <div>
            <a href="{{ route('admin.warehouses.create') }}" class="btn btn-primary">Add Warehouse</a>
        </div>
        --}}
    </div>

    <!-- Quick Financial & Operational KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Inventory Valuation</span>
                <span class="text-2xl font-bold text-emerald-700 mt-1 block">${{ number_format($totalValuation ?? 0, 2) }}</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                $
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Stock Units</span>
                <span class="text-2xl font-bold text-slate-900 mt-1 block">{{ number_format($totalStockUnits ?? 0) }} Units</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl">
                📦
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Low-Stock Alert Items</span>
                <span class="text-2xl font-bold {{ ($totalLowStock ?? 0) > 0 ? 'text-amber-600' : 'text-slate-900' }} mt-1 block">
                    {{ $totalLowStock ?? 0 }} Items
                </span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl">
                ⚠️
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between shadow-sm">
        <span class="font-bold">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.warehouses.index') }}" class="flex items-center gap-2 w-full sm:w-80">
            <div class="relative w-full">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search warehouse or manager..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
            </div>
            @if(request('search'))
            <a href="{{ route('admin.warehouses.index') }}" class="text-xs text-slate-500 hover:text-slate-700 underline whitespace-nowrap">
                Clear
            </a>
            @endif
        </form>

        <span class="text-xs text-slate-500 font-medium">
            Active Distribution Facilities: <strong class="text-slate-800">{{ $warehouses->total() }}</strong>
        </span>
    </div>

    <!-- Warehouse Facilities Grid -->
    <div class="grid grid-cols-1 gap-6">
        @forelse($warehouses as $warehouse)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden hover:border-slate-300 transition-all">
            <div class="p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Facility Info -->
                <div class="space-y-2 flex-1">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-full {{ $warehouse->status === 'ACTIVE' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                        <h2 class="text-lg font-bold text-slate-900 tracking-tight">
                            <a href="{{ route('admin.warehouses.show', $warehouse) }}" class="hover:text-blue-600 transition-colors">
                                {{ $warehouse->name }}
                            </a>
                        </h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $warehouse->status === 'ACTIVE' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                            {{ $warehouse->status }}
                        </span>
                    </div>

                    <p class="text-xs text-slate-600 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $warehouse->location }}</span>
                    </p>

                    @if($warehouse->manager_name)
                    <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 pt-1">
                        <span>👤 <strong>Manager:</strong> {{ $warehouse->manager_name }}</span>
                        @if($warehouse->contact_phone)
                        <span>📞 {{ $warehouse->contact_phone }}</span>
                        @endif
                        @if($warehouse->operating_hours)
                        <span>⏰ {{ $warehouse->operating_hours }}</span>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Financial & Unit Highlights -->
                <div class="flex flex-wrap items-center gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200/60 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[10px] font-semibold uppercase">Stock Volume</span>
                        <span class="text-base font-bold text-slate-900">{{ number_format($warehouse->total_stock_units) }} units</span>
                    </div>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <div>
                        <span class="text-slate-400 block text-[10px] font-semibold uppercase">Total Asset Valuation</span>
                        <span class="text-base font-bold text-emerald-700">${{ number_format($warehouse->total_valuation, 2) }}</span>
                    </div>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <div>
                        <span class="text-slate-400 block text-[10px] font-semibold uppercase">Stored SKUs</span>
                        <span class="text-base font-bold text-blue-700">{{ $warehouse->stocks->count() }} Variants</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.warehouses.show', $warehouse) }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Open Inventory Hub
                    </a>
                    @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Warehouse', 'UPDATE'))
                    <a href="{{ route('admin.warehouses.edit', $warehouse) }}"
                        class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors"
                        title="Edit Facility Information">
                        Edit Facility
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center text-slate-400 text-xs bg-white rounded-2xl border border-slate-200">
            No warehouses configured.
        </div>
        @endforelse
    </div>

    @if($warehouses->hasPages())
    <div class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-sm">
        {{ $warehouses->links() }}
    </div>
    @endif

</div>
@endsection