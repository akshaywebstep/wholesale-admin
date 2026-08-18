@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')
<!-- Header & Navigation -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Products
        </a>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Add New Product</h1>
        <p class="text-sm text-slate-500 mt-0.5">Fill in the basic information to list a new item in your catalog.</p>
    </div>
</div>

<!-- Global Error Alert -->
@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6 text-sm flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="space-y-1">
            <span class="font-semibold">Please correct the following errors:</span>
            <ul class="list-disc list-inside text-xs space-y-0.5 opacity-90">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form action="{{ route('admin.products.store') }}" method="POST">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form Details (Left 2 Columns) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h2 class="text-base font-semibold text-slate-800 mb-4 pb-3 border-b border-slate-100">General Details</h2>
                
                <div class="space-y-4">
                    <!-- Product Name -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1.5">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Slim Fit Cotton Formal Shirt" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        @error('name') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Category & Base Price Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1.5">Category <span class="text-red-500">*</span></label>
                            <select name="category_id" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                <option value="">Select Category</option>
                                @foreach($categories as $parent)
                                    <option value="{{ $parent->id }}" {{ old('category_id') == $parent->id ? 'selected' : '' }} class="font-semibold">
                                        {{ $parent->name }}
                                    </option>
                                    @foreach($parent->children as $child)
                                        <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $child->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1.5">Base Price (₹) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-semibold text-sm">₹</span>
                                <input type="number" step="0.01" name="base_price" value="{{ old('base_price') }}" placeholder="0.00" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl pl-8 pr-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                            </div>
                            @error('base_price') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-1.5">Description</label>
                        <textarea name="description" rows="4" placeholder="Add detailed product description..." class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-4 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none resize-y">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar / Status Card (Right 1 Column) -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h2 class="text-base font-semibold text-slate-800 mb-4 pb-3 border-b border-slate-100">Publishing Status</h2>
                
                <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl border border-slate-100 mb-6">
                    <div>
                        <span class="text-sm font-semibold text-slate-800 block">Product Status</span>
                        <span class="text-xs text-slate-500">Visible on storefront when active</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex flex-col gap-2 pt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition-all shadow-sm shadow-blue-200 active:scale-[0.98]">
                        Save & Continue
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="w-full text-center bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-medium px-4 py-2.5 rounded-xl text-sm transition-all">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection