@extends('layouts.admin')

@section('title', 'Warehouses')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Warehouses</h1>
        <p class="text-slate-500 text-sm mt-0.5">Manage your inventory storage locations and statuses.</p>
    </div>
    <div>
        <a href="{{ route('admin.warehouses.create') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition-all shadow-sm active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Add Warehouse
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

@if(session('error'))
<div
    class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between">
    <span>{{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">&times;</button>
</div>
@endif

<!-- Filters -->
<div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm mb-6">
    <form method="GET" action="{{ route('admin.warehouses.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or location..."
                class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
        </div>
        <div>
            <select name="status"
                class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'ACTIVE' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit"
                class="bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium px-4 py-2 rounded-xl transition-all">Filter</button>
            <a href="{{ route('admin.warehouses.index') }}"
                class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium px-4 py-2 rounded-xl transition-all">Reset</a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr
                    class="bg-slate-50 border-b border-slate-200/80 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <th class="py-3.5 px-4">#ID</th>
                    <th class="py-3.5 px-4">Warehouse Name</th>
                    <th class="py-3.5 px-4">Location</th>
                    <th class="py-3.5 px-4">Stock Items</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($warehouses as $warehouse)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-3.5 px-4 font-mono text-slate-500">#{{ $warehouse->id }}</td>
                    <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $warehouse->name }}</td>
                    <td class="py-3.5 px-4 text-slate-600 max-w-xs truncate">{{ $warehouse->location }}</td>
                    <td class="py-3.5 px-4">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $warehouse->stocks_count }} Records
                        </span>
                    </td>
                    <td class="py-3.5 px-4">
                        @if($warehouse->status === 'ACTIVE')
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                        </span>
                        @else
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Inactive
                        </span>
                        @endif
                    </td>
                    <td class="py-3.5 px-4 text-right space-x-2">
                        <a href="{{ route('admin.warehouses.show', $warehouse->id) }}"
                            class="text-xs font-medium px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                            View
                        </a>
                        <a href="{{ route('admin.warehouses.edit', $warehouse) }}"
                            class="text-blue-600 hover:text-blue-800 font-medium text-xs bg-blue-50 px-2.5 py-1.5 rounded-lg transition-colors">Edit</a>
                        <form action="{{ route('admin.warehouses.destroy', $warehouse) }}" method="POST"
                            class="inline-block"
                            onsubmit="return confirm('Are you sure you want to delete this warehouse?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-rose-600 hover:text-rose-800 font-medium text-xs bg-rose-50 px-2.5 py-1.5 rounded-lg transition-colors">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-slate-400 text-sm">No warehouses found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($warehouses->hasPages())
    <div class="p-4 border-t border-slate-200">
        {{ $warehouses->links() }}
    </div>
    @endif
</div>
@endsection