@extends('layouts.admin')

@section('title', 'Staff Roles & Security Privileges')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">

    <!-- ============================================== -->
    <!-- 1. HEADER SECTION & ACTIONS                    -->
    <!-- ============================================== -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-purple-600 bg-purple-50 px-2.5 py-1 rounded-md w-fit mb-2">
                <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                Staff Access & Security Control
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Staff Roles & Privileges</h1>
            <p class="text-xs md:text-sm text-slate-500 mt-1">
                Define departmental security roles and grant granular read/write/delete privileges to your team members.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index', ['tab' => 'staff']) }}"
                class="px-4 py-2.5 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all border border-slate-200/80">
                Staff Directory &rarr;
            </a>

            @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Role', 'CREATE'))
            <a href="{{ route('admin.roles.create') }}"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold px-5 py-2.5 rounded-xl text-xs transition-all shadow-md shadow-purple-500/20 active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Create Staff Role
            </a>
            @endif
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between shadow-xs">
        <span class="font-bold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </span>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs flex items-center justify-between shadow-xs">
        <span class="font-bold flex items-center gap-2">
            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </span>
        <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">&times;</button>
    </div>
    @endif

    <!-- ============================================== -->
    <!-- 2. QUICK STATS BAR                             -->
    <!-- ============================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Configured Staff Roles</span>
                <div class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $totalStaffRolesCount }} Roles</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                {{ $activeStaffRolesCount }} Active
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Granular Security Actions</span>
                <div class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $totalPermissions }} Privileges</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-4 text-white shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-purple-300 uppercase tracking-wider">Master Security Mode</span>
                <div class="text-sm font-bold text-white mt-1">Super Admin (Full Access)</div>
            </div>
            <span class="w-8 h-8 rounded-lg bg-white/10 text-amber-400 flex items-center justify-center text-sm shadow-xs" title="Protected Master Account">
                👑
            </span>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 3. STAFF ROLES CARDS GRID (NO DELETE BUTTON)   -->
    <!-- ============================================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($roles as $role)
        @php
            $permCount = $role->permissions->count();
            $percent = $totalPermissions > 0 ? round(($permCount / $totalPermissions) * 100) : 0;
        @endphp
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5 flex flex-col justify-between hover:shadow-md hover:border-slate-300 transition-all group">
            <div class="space-y-4">
                
                <!-- Role Header: Title & Status -->
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <span class="font-mono bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200">
                            Role #{{ $role->id }}
                        </span>
                        <h2 class="text-lg font-bold text-slate-900 mt-1.5 flex items-center gap-2">
                            <span>{{ $role->name }}</span>
                        </h2>
                    </div>

                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $role->status === 'ACTIVE' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                        {{ $role->status }}
                    </span>
                </div>

                <!-- Assigned Staff Members -->
                <div class="flex items-center justify-between text-xs text-slate-600 bg-slate-50 p-3.5 rounded-xl border border-slate-200/60">
                    <span class="font-medium">Assigned Staff Members:</span>
                    <a href="{{ route('admin.users.index', ['role_id' => $role->id, 'tab' => 'staff']) }}" class="font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 hover:underline">
                        <span>{{ $role->users_count }} Staff</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- Granted Permissions Progress Bar -->
                <div class="space-y-2 pt-1">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Access Scope:</span>
                        <span class="font-bold text-slate-900">
                            {{ $permCount }} / {{ $totalPermissions }} Actions ({{ $percent }}%)
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div class="h-2 rounded-full transition-all duration-500 {{ $percent > 60 ? 'bg-purple-600' : ($percent > 25 ? 'bg-blue-600' : 'bg-amber-500') }}" style="width: {{ max($percent, 4) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Action Controls (Configure Privileges + Edit Name, NO DELETE BUTTON) -->
            <div class="pt-4 border-t border-slate-100 flex items-center gap-2">
                @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Role', 'UPDATE'))
                <a href="{{ route('admin.roles.permissions', $role) }}"
                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl transition-all shadow-xs active:scale-[0.98]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Configure Privileges
                </a>

                <a href="{{ route('admin.roles.edit', $role) }}"
                    class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors border border-slate-200/80"
                    title="Edit Role Name">
                    Edit
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full p-12 text-center text-slate-500 bg-white rounded-2xl border border-slate-200 space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto text-xl font-bold">
                🛡️
            </div>
            <h3 class="text-base font-bold text-slate-900">No Custom Staff Roles Created Yet</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto">
                Create specific operational roles (e.g. Sales Manager, Warehouse Dispatcher, Billing Operator) and assign modular privileges.
            </p>
            @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Role', 'CREATE'))
            <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-600 bg-purple-50 px-4 py-2 rounded-xl hover:bg-purple-100 transition-colors">
                + Create First Role
            </a>
            @endif
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($roles->hasPages())
    <div class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
        {{ $roles->links() }}
    </div>
    @endif

</div>
@endsection