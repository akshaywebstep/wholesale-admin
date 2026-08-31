@extends('layouts.admin')

@section('title', 'Category Management')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">

    <!-- Header & Metric Cards -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Category Management</h1>
            <p class="text-xs text-slate-500 mt-0.5">Organize main product departments and sub-category hierarchies.</p>
        </div>
        <div class="flex items-center gap-3">
            @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Category', 'CREATE'))
            <a href="{{ route('admin.categories.create') }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-all shadow-md shadow-blue-200 active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Main Department
            </a>
            @endif
        </div>
    </div>

    <!-- Quick Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Main Departments</span>
                <span class="text-2xl font-bold text-slate-900 mt-1 block">{{ $totalParents ?? $categories->total() }}</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                📁
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Sub-Categories</span>
                <span class="text-2xl font-bold text-slate-900 mt-1 block">{{ $totalSubs ?? 0 }}</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                ↳
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Active Status</span>
                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full mt-1.5 inline-block">
                    ● 100% Online
                </span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg">
                ⚡
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex items-center gap-2 w-full sm:w-80">
            <div class="relative w-full">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search category or sub-category..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
            </div>
            @if(request('search'))
            <a href="{{ route('admin.categories.index') }}" class="text-xs text-slate-500 hover:text-slate-700 underline whitespace-nowrap">
                Clear
            </a>
            @endif
        </form>

        <span class="text-xs text-slate-500 font-medium">
            Showing <strong class="text-slate-800">{{ $categories->firstItem() ?? 0 }}-{{ $categories->lastItem() ?? 0 }}</strong> of <strong class="text-slate-800">{{ $categories->total() }}</strong> departments
        </span>
    </div>

    <!-- Category Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-16">Image</th>
                        <th class="py-3.5 px-4">Department & Sub-Categories</th>
                        <th class="py-3.5 px-4 w-32">Sub-Items</th>
                        <th class="py-3.5 px-4 w-28">Status</th>
                        <th class="py-3.5 px-4 text-right w-48">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($categories as $category)
                    <!-- Parent Category Row -->
                    <tr class="bg-slate-50/80 hover:bg-slate-100/70 transition-colors font-medium">
                        <td class="py-3.5 px-4">
                            @if($category->image)
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 shadow-sm" onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';">
                            @else
                            <div class="w-10 h-10 rounded-xl bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-xs">📁</div>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-900 text-sm">{{ $category->name }}</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 rounded-md">Main Department</span>
                            </div>
                            <span class="text-[11px] text-slate-400 font-mono mt-0.5 block">Slug: {{ $category->slug }}</span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="font-bold text-slate-800 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-lg text-xs">
                                {{ $category->children->count() }} Sub-categories
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            @if(in_array(strtoupper($category->status), ['ACTIVE', '1']))
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Category', 'CREATE'))
                                <a href="{{ route('admin.categories.create', ['parent_id' => $category->id]) }}"
                                    class="text-[11px] font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1.5 rounded-lg transition-colors border border-emerald-200/60" title="Add Sub-Category">
                                    + Sub
                                </a>
                                @endif
                                @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Category', 'UPDATE'))
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                    class="text-[11px] font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg transition-colors">
                                    Edit
                                </a>
                                @endif
                                @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Category', 'DELETE'))
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this parent category? Ensure sub-categories are reassigned first.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[11px] font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 px-2.5 py-1.5 rounded-lg transition-colors">
                                        Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Sub-Categories Rows -->
                    @forelse($category->children as $child)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-2.5 px-4 pl-6">
                            @if($child->image)
                            <img src="{{ $child->image_url }}" alt="{{ $child->name }}" class="w-8 h-8 rounded-lg object-cover border border-slate-200" onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';">
                            @else
                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center text-[10px] font-bold">↳</div>
                            @endif
                        </td>
                        <td class="py-2.5 px-4">
                            <div class="flex items-center gap-2">
                                <span class="text-slate-300 font-mono text-sm">└──</span>
                                <span class="font-semibold text-slate-700 text-xs">{{ $child->name }}</span>
                            </div>
                            <span class="text-[10px] text-slate-400 font-mono pl-6 block">Slug: {{ $child->slug }}</span>
                        </td>
                        <td class="py-2.5 px-4 text-slate-500 text-[11px]">
                            {{ $child->products_count ?? 0 }} Products
                        </td>
                        <td class="py-2.5 px-4">
                            @if(in_array(strtoupper($child->status), ['ACTIVE', '1']))
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200">Inactive</span>
                            @endif
                        </td>
                        <td class="py-2.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Category', 'UPDATE'))
                                <a href="{{ route('admin.categories.edit', $child) }}" class="text-[11px] font-medium text-blue-600 hover:text-blue-800 bg-slate-100 hover:bg-slate-200 px-2 py-1 rounded-lg transition-colors">Edit</a>
                                @endif
                                @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Category', 'DELETE'))
                                <form action="{{ route('admin.categories.destroy', $child) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this sub-category?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[11px] font-medium text-rose-600 hover:text-rose-800 bg-slate-100 hover:bg-slate-200 px-2 py-1 rounded-lg transition-colors">Delete</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-2.5 px-4 pl-14 text-xs text-slate-400 italic bg-slate-50/30">No sub-categories added yet.</td>
                    </tr>
                    @endforelse

                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400 text-sm">No categories found matching your query.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        @if($categories->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $categories->links() }}
        </div>
        @endif
    </div>
</div>
@endsection