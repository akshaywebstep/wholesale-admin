@extends('layouts.admin')

@section('title', 'Roles & Privileges Matrix')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to User Management
            </a>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Roles & Permissions Matrix</h1>
            <p class="text-xs text-slate-500 mt-0.5">Define employee security roles and grant granular privileges across all wholesale modules.</p>
        </div>

        <div>
            @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Role', 'CREATE'))
            <a href="{{ route('admin.roles.create') }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-all shadow-md shadow-blue-200 active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Role
            </a>
            @endif
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between shadow-sm">
        <span class="font-bold">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs flex items-center justify-between shadow-sm">
        <span class="font-bold">{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">&times;</button>
    </div>
    @endif

    <!-- Roles Grid Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($roles as $role)
        @php
            $permCount = $role->permissions->count();
            $percent = $totalPermissions > 0 ? round(($permCount / $totalPermissions) * 100) : 0;
            $isSuperAdmin = strtolower($role->name) === 'super admin';
        @endphp
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5 flex flex-col justify-between hover:border-slate-300 transition-all">
            <div class="space-y-4">
                <!-- Role Title & Badge -->
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <span class="font-mono bg-purple-50 text-purple-700 text-[10px] font-bold px-2 py-0.5 rounded border border-purple-200">
                            Role #{{ $role->id }}
                        </span>
                        <h2 class="text-lg font-bold text-slate-900 mt-1.5 flex items-center gap-2">
                            <span>{{ $role->name }}</span>
                            @if($isSuperAdmin)
                            <span class="text-xs text-amber-500" title="Super Administrator with full access">👑</span>
                            @endif
                        </h2>
                    </div>

                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $role->status === 'ACTIVE' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                        {{ $role->status }}
                    </span>
                </div>

                <!-- Assigned Users Count -->
                <div class="flex items-center justify-between text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-200/60">
                    <span>Assigned Staff Members:</span>
                    <a href="{{ route('admin.users.index', ['role_id' => $role->id]) }}" class="font-bold text-blue-600 hover:underline">
                        {{ $role->users_count }} Users →
                    </a>
                </div>

                <!-- Permissions Progress Bar -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Granted Privileges:</span>
                        <span class="font-bold {{ $isSuperAdmin ? 'text-purple-700' : 'text-slate-900' }}">
                            {{ $isSuperAdmin ? 'Full Access (All Modules)' : "{$permCount} / {$totalPermissions} Actions" }}
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div class="h-2 rounded-full {{ $isSuperAdmin ? 'bg-purple-600' : ($percent > 50 ? 'bg-blue-600' : 'bg-amber-500') }}" style="width: {{ $isSuperAdmin ? 100 : $percent }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Role', 'UPDATE'))
                    <a href="{{ route('admin.roles.permissions', $role) }}"
                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Configure Privileges
                </a>
                    @endif

                @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Role', 'UPDATE'))
                    <a href="{{ route('admin.roles.edit', $role) }}"
                    class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors"
                    title="Edit Role Name">
                    Edit
                </a>
                    @endif

                @if(!$isSuperAdmin && strtolower($role->name) !== 'customer')
                @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Role', 'DELETE'))
                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline-block"
                    onsubmit="return confirm('Are you sure you want to delete role: {{ $role->name }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 transition-colors" title="Delete Role">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
                    @endif
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-3 p-12 text-center text-slate-400 text-xs bg-white rounded-2xl border border-slate-200">
            No roles configured.
        </div>
        @endforelse
    </div>

</div>
@endsection