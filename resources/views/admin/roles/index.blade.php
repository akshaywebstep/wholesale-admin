@extends('layouts.admin')

@section('title', 'Roles')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Role Management</h1>
        <p class="text-slate-500 text-sm mt-0.5">Manage user roles, system permissions, and access levels.</p>
    </div>
    <div>
        <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition-all shadow-sm active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add New Role
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
                    <th class="py-3.5 px-4">Role Name</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($roles as $role)
                    @php
                        // Check if role is Super Admin / Admin
                        $isSuperAdmin = in_array(strtolower($role->name), ['super admin', 'superadmin', 'admin']);
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4 font-semibold text-slate-900">
                            <div class="flex items-center gap-2">
                                <span>{{ $role->name }}</span>
                                @if($isSuperAdmin)
                                    <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200 rounded-md">Protected System Role</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            @if(in_array(strtoupper($role->status ?? 'ACTIVE'), ['ACTIVE', '1']))
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.roles.permissions', $role) }}" class="text-xs font-semibold text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200 px-3 py-1.5 rounded-lg transition-colors">
                                    Permissions
                                </a>

                                {{-- Super Admin cannot be Edited or Deleted --}}
                                @if(!$isSuperAdmin)
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs font-semibold text-slate-400 bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-lg select-none cursor-not-allowed" title="Super Admin role is protected from editing or deleting">
                                        Protected
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-8 text-center text-slate-400 text-sm">No roles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($roles, 'hasPages') && $roles->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $roles->links() }}
        </div>
    @endif
</div>
@endsection