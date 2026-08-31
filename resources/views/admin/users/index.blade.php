@extends('layouts.admin')

@section('title', 'User & Staff Directory')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">User & Access Management</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage internal company staff, role permissions, and B2B wholesale customer accounts.</p>
        </div>

        <div class="flex items-center gap-3">
            @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Role', 'VIEW'))
            <a href="{{ route('admin.roles.index') }}"
                class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold px-4 py-2.5 rounded-xl text-xs transition-all shadow-sm">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Manage Roles & Permissions
            </a>
            @endif
            @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'User', 'CREATE'))
            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-all shadow-md shadow-blue-200 active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New User / Staff
            </a>
            @endif
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Users -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Total Directory</span>
                <span class="text-2xl font-bold text-slate-900 mt-1 block">{{ $totalCount }} Accounts</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-lg">
                👥
            </div>
        </div>

        <!-- Staff Members -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Staff & Admins</span>
                <span class="text-2xl font-bold text-blue-700 mt-1 block">{{ $staffCount }} Members</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                🛡️
            </div>
        </div>

        <!-- B2B Customers -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">B2B Customers</span>
                <span class="text-2xl font-bold text-emerald-700 mt-1 block">{{ $customerCount }} Retailers</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                🏢
            </div>
        </div>

        <!-- Pending Verifications -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Pending Approvals</span>
                <span class="text-2xl font-bold {{ $pendingCount > 0 ? 'text-amber-600' : 'text-slate-900' }} mt-1 block">
                    {{ $pendingCount }} Waiting
                </span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                ⏳
            </div>
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

    <!-- Segmented Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200/80 pb-px overflow-x-auto">
        <a href="{{ route('admin.users.index', ['tab' => 'all']) }}"
            class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 {{ $tab === 'all' ? 'border-blue-600 text-blue-600 bg-white shadow-sm' : 'border-transparent text-slate-500 hover:text-slate-800 hover:bg-slate-100/50' }}">
            <span>All Directory</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono {{ $tab === 'all' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-600' }}">
                {{ $totalCount }}
            </span>
        </a>

        <a href="{{ route('admin.users.index', ['tab' => 'staff']) }}"
            class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 {{ $tab === 'staff' ? 'border-blue-600 text-blue-600 bg-white shadow-sm' : 'border-transparent text-slate-500 hover:text-slate-800 hover:bg-slate-100/50' }}">
            <span>🛡️ Internal Staff & Roles</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono {{ $tab === 'staff' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-600' }}">
                {{ $staffCount }}
            </span>
        </a>

        <a href="{{ route('admin.users.index', ['tab' => 'customers']) }}"
            class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 {{ $tab === 'customers' ? 'border-blue-600 text-blue-600 bg-white shadow-sm' : 'border-transparent text-slate-500 hover:text-slate-800 hover:bg-slate-100/50' }}">
            <span>🏢 B2B Wholesale Customers</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono {{ $tab === 'customers' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-600' }}">
                {{ $customerCount }}
            </span>
        </a>

        <a href="{{ route('admin.users.index', ['tab' => 'pending']) }}"
            class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 {{ $tab === 'pending' ? 'border-amber-500 text-amber-600 bg-white shadow-sm' : 'border-transparent text-slate-500 hover:text-slate-800 hover:bg-slate-100/50' }}">
            <span>⏳ Pending Approvals</span>
            @if($pendingCount > 0)
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono bg-amber-100 text-amber-800 font-bold animate-pulse">
                {{ $pendingCount }}
            </span>
            @endif
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <input type="hidden" name="tab" value="{{ $tab }}">

            <!-- Search -->
            <div class="relative w-72">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, phone, shop..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
            </div>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()"
                class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:bg-white outline-none">
                <option value="">All Statuses</option>
                <option value="ACTIVE" {{ request('status') === 'ACTIVE' ? 'selected' : '' }}>Active</option>
                <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Pending</option>
                <option value="INACTIVE" {{ request('status') === 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
                <option value="REJECTED" {{ request('status') === 'REJECTED' ? 'selected' : '' }}>Rejected</option>
            </select>

            <!-- Role Filter -->
            @if($tab === 'staff' || $tab === 'all')
            <select name="role_id" onchange="this.form.submit()"
                class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:bg-white outline-none">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
            @endif

            @if(request()->hasAny(['search', 'status', 'role_id']))
            <a href="{{ route('admin.users.index', ['tab' => $tab]) }}" class="text-xs text-slate-500 hover:text-slate-700 underline">
                Clear Filters
            </a>
            @endif
        </form>

        <span class="text-xs text-slate-500 font-medium">
            Showing <strong class="text-slate-800">{{ $users->total() }}</strong> accounts
        </span>
    </div>

    <!-- Users Directory Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">User / Identity</th>
                        <th class="py-3.5 px-4">Account Type</th>
                        <th class="py-3.5 px-4">Role / Permissions</th>
                        <th class="py-3.5 px-4">Business & Location</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <!-- User / Avatar -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shrink-0 
                                    {{ $user->user_type === 'ADMIN' ? 'bg-purple-100 text-purple-700 border border-purple-200' : ($user->user_type === 'STAFF' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-emerald-100 text-emerald-700 border border-emerald-200') }}">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                        <span>{{ $user->name }}</span>
                                        @if($user->id === 1 || $user->user_type === 'ADMIN')
                                        <span class="bg-purple-50 text-purple-700 text-[10px] font-bold px-1.5 py-0.2 rounded border border-purple-200">Super</span>
                                        @endif
                                    </div>
                                    <span class="text-slate-500 block text-[11px]">{{ $user->email }}</span>
                                    @if($user->phone)
                                    <span class="text-slate-400 text-[10px] block">📞 {{ $user->phone }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Account Type -->
                        <td class="py-3.5 px-4">
                            @if($user->user_type === 'ADMIN')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                👑 Super Admin
                            </span>
                            @elseif($user->user_type === 'STAFF')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                🛡️ Staff Member
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                🏢 B2B Customer
                            </span>
                            @endif
                        </td>

                        <!-- Role / Access Level -->
                        <td class="py-3.5 px-4">
                            @if($user->user_type === 'CUSTOMER')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    🛒 {{ $user->customerGroup->name ?? 'Wholesale Buyer' }}
                                </span>
                            @elseif($user->roles->count() > 0)
                                @foreach($user->roles as $role)
                                <a href="{{ route('admin.roles.permissions', $role) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 hover:bg-purple-50 hover:text-purple-700 text-slate-700 border border-slate-200 transition-colors"
                                    title="Click to view/edit privileges for {{ $role->name }}">
                                    <span>🔐 {{ $role->name }}</span>
                                </a>
                                @endforeach
                            @else
                                <span class="text-slate-400 italic text-[11px]">Staff (No Role)</span>
                            @endif
                        </td>

                        <!-- Business & Location -->
                        <td class="py-3.5 px-4 text-slate-600">
                            @if($user->business_name)
                            <div class="font-bold text-slate-800">{{ $user->business_name }}</div>
                            @if($user->gst_number)
                            <span class="font-mono text-[10px] text-slate-400 block">GST: {{ $user->gst_number }}</span>
                            @endif
                            @else
                            <span class="text-slate-400 italic">Internal Staff</span>
                            @endif

                            @if($user->city || $user->state)
                            <span class="text-[10px] text-slate-400 block mt-0.5">
                                📍 {{ $user->city->name ?? '' }}{{ $user->state ? ', ' . $user->state->name : '' }}
                            </span>
                            @endif
                        </td>

                        <!-- Status Badge -->
                        <td class="py-3.5 px-4 text-center">
                            @if(strtoupper($user->status) === 'ACTIVE')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                            @elseif(strtoupper($user->status) === 'PENDING')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending Review
                            </span>
                            @elseif(strtoupper($user->status) === 'REJECTED')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
                                Rejected
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                Inactive
                            </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                            <!-- 1-Click Approve / Reject (for Pending accounts) -->
                            @if(strtoupper($user->status) === 'PENDING')
                            <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] transition-colors shadow-sm">
                                    ✓ Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.users.reject', $user) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 font-bold text-[11px] transition-colors">
                                    ✗ Reject
                                </button>
                            </form>
                            @endif

                            <!-- Toggle Status Button -->
                            @if($user->id !== 1 && $user->id !== auth()->id())
                            <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors" title="Toggle Active / Inactive Status">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </button>
                            </form>
                            @endif

                            <!-- Edit Profile -->
                            @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'User', 'UPDATE'))
                            <a href="{{ route('admin.users.edit', $user) }}"
                                class="inline-block p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition-colors"
                                title="Edit User">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            @endif

                            <!-- Delete User -->
                            @if($user->id !== 1 && $user->id !== auth()->id())
                            @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'User', 'DELETE'))
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block"
                                onsubmit="return confirm('Are you sure you want to delete user: {{ $user->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors" title="Delete User">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400 text-xs">
                            No accounts found matching your filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>
@endsection