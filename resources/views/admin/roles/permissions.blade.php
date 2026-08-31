@extends('layouts.admin')

@section('title', 'Configure Permissions: ' . $role->name)

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.roles.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Roles Matrix
            </a>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Privileges for Role: {{ $role->name }}</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold font-mono bg-purple-50 text-purple-700 border border-purple-200">
                    {{ count($assignedIds) }} of {{ $totalSystemPermissions }} Granted
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Check or uncheck the specific module actions this role is permitted to perform.</p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" onclick="toggleAllSystemPermissions()"
                class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all shadow-sm">
                Toggle All Permissions
            </button>
            <button type="button" onclick="document.getElementById('permissionsForm').requestSubmit()"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-md shadow-blue-200 active:scale-[0.98] transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Privileges
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between shadow-sm">
        <span class="font-bold">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
    @endif

    <form id="permissionsForm" action="{{ route('admin.roles.permissions.update', $role) }}" method="POST">
        @csrf

        <!-- Module Permission Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($permissions as $module => $modulePermissions)
            @php
                $moduleSlug = Str::slug($module);
                $moduleIcons = [
                    'Product'   => '📦',
                    'Category'  => '📁',
                    'Warehouse' => '🏢',
                    'Order'     => '📋',
                    'Stock'     => '📊',
                    'User'      => '👥',
                    'Role'      => '🛡️',
                ];
                $icon = $moduleIcons[$module] ?? '⚙️';
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between hover:border-slate-300 transition-all">
                <div>
                    <!-- Module Header -->
                    <div class="bg-slate-50 px-5 py-3.5 border-b border-slate-200/80 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-base">{{ $icon }}</span>
                            <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wide">{{ $module }} Module</h3>
                        </div>
                        <button type="button" onclick="toggleModulePermissions('{{ $moduleSlug }}')"
                            class="text-[11px] font-bold text-blue-600 hover:text-blue-800 transition-colors">
                            Toggle All
                        </button>
                    </div>

                    <!-- Action Checkbox Tiles -->
                    <div class="p-4 space-y-2.5" id="module-{{ $moduleSlug }}">
                        @foreach($modulePermissions as $permission)
                        @php
                            $isChecked = in_array($permission->id, $assignedIds);
                            $action = strtoupper($permission->action);
                            $badgeColor = match($action) {
                                'CREATE'   => 'text-emerald-700 bg-emerald-50 border-emerald-200',
                                'VIEW', 'VIEW_ALL' => 'text-blue-700 bg-blue-50 border-blue-200',
                                'UPDATE'   => 'text-amber-700 bg-amber-50 border-amber-200',
                                'DELETE'   => 'text-red-700 bg-red-50 border-red-200',
                                default    => 'text-slate-700 bg-slate-50 border-slate-200',
                            };
                        @endphp
                        <label class="flex items-center justify-between p-3 rounded-xl border border-slate-200/70 hover:border-blue-400 hover:bg-blue-50/20 cursor-pointer transition-all select-none">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="permission_ids[]" value="{{ $permission->id }}"
                                    {{ $isChecked ? 'checked' : '' }}
                                    class="perm-checkbox perm-checkbox-{{ $moduleSlug }} w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                                <span class="text-xs font-semibold text-slate-800">
                                    {{ Str::replace('_', ' ', $permission->action) }}
                                </span>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded border {{ $badgeColor }}">
                                {{ $permission->action }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Floating Bottom Save Bar -->
        <div class="mt-8 pt-5 border-t border-slate-200 flex items-center justify-between">
            <span class="text-xs text-slate-500 font-medium">Changes will take effect on next login / request for staff assigned to <strong>{{ $role->name }}</strong>.</span>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.roles.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition-all shadow-md shadow-blue-200 active:scale-[0.98]">
                    Save Permissions Matrix
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function toggleModulePermissions(moduleSlug) {
    const checkboxes = document.querySelectorAll(`.perm-checkbox-${moduleSlug}`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => { cb.checked = !allChecked; });
}

function toggleAllSystemPermissions() {
    const checkboxes = document.querySelectorAll('.perm-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => { cb.checked = !allChecked; });
}
</script>
@endsection