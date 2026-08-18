@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Category Management</h1>
        <p class="text-slate-500 text-sm mt-0.5">Organize parent categories and sub-category hierarchies.</p>
    </div>
    <div>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition-all shadow-sm active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Main Category
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between">
        <span>{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">&times;</button>
    </div>
@endif

<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200/80 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <th class="py-3.5 px-4 w-20">Image</th>
                    <th class="py-3.5 px-4">Category Name</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($categories as $category)
                    <!-- Parent Category Row -->
                    <tr class="bg-slate-50/70 hover:bg-slate-100/60 transition-colors font-medium">
                        <td class="py-3 px-4">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 shadow-sm">
                            @else
                                <div class="w-10 h-10 rounded-xl bg-slate-200/60 text-slate-400 flex items-center justify-center font-semibold text-xs">N/A</div>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-slate-900 font-bold">
                            {{ $category->name }}
                            <span class="ml-2 px-2 py-0.5 text-[10px] font-medium bg-slate-200 text-slate-600 rounded-md">Parent</span>
                        </td>
                        <td class="py-3 px-4">
                            @if(in_array(strtoupper($category->status), ['ACTIVE', '1']))
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.categories.create', ['parent_id' => $category->id]) }}" class="text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition-colors border border-emerald-200/60">
                                    + Sub-Category
                                </a>
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this parent category? Ensure sub-categories and products are reassigned first.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Sub-Categories Children Rows -->
                    @forelse($category->children as $child)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-2.5 px-4 pl-8">
                                @if($child->image)
                                    <img src="{{ asset('storage/' . $child->image) }}" alt="{{ $child->name }}" class="w-8 h-8 rounded-lg object-cover border border-slate-200">
                                @else
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center text-[10px]">N/A</div>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-slate-600">
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-300 font-mono text-sm">└──</span>
                                    <span>{{ $child->name }}</span>
                                </div>
                            </td>
                            <td class="py-2.5 px-4">
                                @if(in_array(strtoupper($child->status), ['ACTIVE', '1']))
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">Inactive</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $child) }}" class="text-xs font-medium text-blue-600 hover:text-blue-800 bg-slate-100 hover:bg-slate-200 px-2.5 py-1 rounded-md transition-colors">Edit</a>
                                    <form action="{{ route('admin.categories.destroy', $child) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this sub-category?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-rose-600 hover:text-rose-800 bg-slate-100 hover:bg-slate-200 px-2.5 py-1 rounded-md transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-2.5 px-4 pl-14 text-xs text-slate-400 italic bg-slate-50/20">No sub-categories added yet.</td>
                        </tr>
                    @endforelse

                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-slate-400 text-sm">No categories found in system.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection