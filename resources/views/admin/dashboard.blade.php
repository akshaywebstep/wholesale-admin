@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Top Banner / Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard Overview</h1>
        <p class="text-slate-500 text-sm mt-0.5">Welcome back, <span class="font-semibold text-slate-700">{{ auth()->user()->name }}</span> 👋 Here's what's happening today.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition-all shadow-sm shadow-blue-200 active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Product
        </a>
    </div>
</div>

<!-- Stats Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    
    <!-- Total Orders Card -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Orders</span>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline justify-between">
            <h3 class="text-3xl font-bold text-slate-800 tracking-tight">{{ $totalOrders }}</h3>
            <span class="inline-flex items-center text-xs font-medium text-slate-400">Total lifetime</span>
        </div>
    </div>

    <!-- Total Products Card -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Products</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline justify-between">
            <h3 class="text-3xl font-bold text-slate-800 tracking-tight">{{ $totalProducts }}</h3>
            <span class="inline-flex items-center text-xs font-medium text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-md">Catalog</span>
        </div>
    </div>

    <!-- Total Customers Card -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Customers</span>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline justify-between">
            <h3 class="text-3xl font-bold text-slate-800 tracking-tight">{{ $totalCustomers }}</h3>
            <span class="inline-flex items-center text-xs font-medium text-slate-400">Registered</span>
        </div>
    </div>

    <!-- Low Stock Items Card -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Low Stock Items</span>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline justify-between">
            <h3 class="text-3xl font-bold text-slate-800 tracking-tight">{{ $lowStockCount }}</h3>
            <span class="inline-flex items-center text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md">Action needed</span>
        </div>
    </div>

</div>

<!-- Main Content Layout (Quick Actions & Setup) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Getting Started Box -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Store Setup Guide</h2>
                <p class="text-sm text-slate-500 mt-0.5">Follow these quick links to populate your inventory and manage users.</p>
            </div>
            <span class="bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full border border-blue-100">Quick Start</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
            <!-- Action 1 -->
            <a href="{{ route('admin.categories.index') }}" class="group p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:border-blue-200 hover:shadow-sm transition-all flex items-start gap-3">
                <div class="p-2 bg-white group-hover:bg-blue-600 text-slate-600 group-hover:text-white rounded-lg transition-colors border border-slate-200/60 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition-colors">Manage Categories</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Organize items into collections.</p>
                </div>
            </a>

            <!-- Action 2 -->
            <a href="{{ route('admin.products.index') }}" class="group p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:border-blue-200 hover:shadow-sm transition-all flex items-start gap-3">
                <div class="p-2 bg-white group-hover:bg-blue-600 text-slate-600 group-hover:text-white rounded-lg transition-colors border border-slate-200/60 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition-colors">Add Products</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Create your core product lines.</p>
                </div>
            </a>

            <!-- Action 3 -->
            <a href="{{ route('admin.productVariants.index') }}" class="group p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:border-blue-200 hover:shadow-sm transition-all flex items-start gap-3">
                <div class="p-2 bg-white group-hover:bg-blue-600 text-slate-600 group-hover:text-white rounded-lg transition-colors border border-slate-200/60 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition-colors">Configure Variants</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Set sizes, colors, and SKU codes.</p>
                </div>
            </a>

            <!-- Action 4 -->
            <a href="{{ route('admin.users.index') }}" class="group p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:border-blue-200 hover:shadow-sm transition-all flex items-start gap-3">
                <div class="p-2 bg-white group-hover:bg-blue-600 text-slate-600 group-hover:text-white rounded-lg transition-colors border border-slate-200/60 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition-colors">User Management</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Assign admin roles and access.</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Right Column: System Overview Widget -->
    <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-6 text-white flex flex-col justify-between shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-blue-400 bg-blue-500/10 px-3 py-1 rounded-full mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                System Status
            </div>
            <h3 class="text-xl font-bold tracking-tight">Wholesale Portal Active</h3>
            <p class="text-slate-400 text-xs mt-2 leading-relaxed">
                Your store dashboard is configured. Real-time metrics for sales, inventory updates, and stock alerts will reflect automatically.
            </p>
        </div>

        <div class="pt-6 border-t border-slate-700/60 mt-6 flex items-center justify-between text-xs text-slate-400">
            <span>Laravel Environment</span>
            <span class="font-mono text-emerald-400 font-semibold">Production Ready</span>
        </div>
    </div>

</div>
@endsection