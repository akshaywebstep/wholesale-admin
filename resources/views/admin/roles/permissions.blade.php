@extends('layouts.admin')

@section('title', 'Assign Permissions')

@section('content')
<!-- Header Section -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-3 mb-1">
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Permissions Matrix</h1>
            <span class="px-3 py-1 text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 rounded-full">
                {{ $role->name }}
            </span>
        </div>
        <p class="text-slate-500 text-sm">Configure access control levels and module privileges for this role.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.roles.index') }}" class="text-slate-600 hover:text-slate-800 text-sm font-medium bg-slate-100 px-4 py-2.5 rounded-xl transition-colors">
            Back to Roles
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
@endif

<form action="{{ route('admin.roles.permissions.update', $role) }}" method="POST">
    @csrf

    <!-- Main Grid per Module -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($permissions as $module => $modulePermissions)
            @php
                $moduleSlug = Str::slug($module);
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <!-- Module Header -->
                    <div class="bg-slate-50/80 px-5 py-3.5 border-b border-slate-200/80 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <h3 class="font-bold text-slate-800 text-sm tracking-wide uppercase">{{ $module }}</h3>
                        </div>
                        <button type="button" onclick="toggleModulePermissions('{{ $moduleSlug }}')" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                            Toggle All
                        </button>
                    </div>

                    <!-- Action Checkboxes Grid -->
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3" id="module-{{ $moduleSlug }}">
                        @foreach($modulePermissions as $permission)
                            @php
                                $isChecked = in_array($permission->id, $assignedIds);
                            @endphp
                            <label class="relative flex items-center p-3 rounded-xl border border-slate-200/90 hover:border-slate-300 hover:bg-slate-50/50 cursor-pointer transition-all select-none group">
                                <input type="checkbox" name="permission_ids[]" value="{{ $permission->id }}"
                                    {{ $isChecked ? 'checked' : '' }}
                                    class="perm-checkbox-{{ $moduleSlug }} w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 focus:ring-offset-0 transition">
                                <span class="ml-2.5 text-xs font-medium text-slate-700 group-hover:text-slate-900 capitalize">
                                    {{ Str::replace('_', ' ', $permission->action) }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Sticky / Bottom Action Bar -->
    <div class="mt-8 pt-5 border-t border-slate-200 flex items-center justify-end gap-3">
        <a href="{{ route('admin.roles.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
            Cancel
        </a>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-xl text-sm transition-all shadow-sm active:scale-[0.98]">
            Save Permissions
        </button>
    </div>
</form>

<script>
function toggleModulePermissions(moduleSlug) {
    const checkboxes = document.querySelectorAll(`.perm-checkbox-${moduleSlug}`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
}
</script>
@endsection