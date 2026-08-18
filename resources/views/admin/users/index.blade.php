    @extends('layouts.admin')

    @section('title', 'Users')

    @section('content')
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">User Management</h1>
            <p class="text-slate-500 text-sm mt-0.5">Manage system users, assigned roles, and account access
                permissions.</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition-all shadow-sm active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Add New User
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

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50 border-b border-slate-200/80 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">User</th>
                        <th class="py-3.5 px-4">Type</th>
                        <th class="py-3.5 px-4">Role</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <!-- User Name & Email Avatar -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 text-slate-700 font-bold flex items-center justify-center text-xs uppercase shrink-0">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- User Type -->
                        <td class="py-3.5 px-4 font-medium text-slate-700 capitalize">
                            {{ $user->user_type ?? 'N/A' }}
                        </td>

                        <!-- Assigned Role (From user_has_role) -->
                        <td class="py-3.5 px-4">
                            @if(isset($user->roles) && $user->roles->count() > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach($user->roles as $role)
                                <span
                                    class="px-2.5 py-0.5 text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200/80 rounded-md">
                                    {{ $role->name }}
                                </span>
                                @endforeach
                            </div>
                            @elseif(isset($user->role))
                            <span
                                class="px-2.5 py-0.5 text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200/80 rounded-md">
                                {{ $user->role->name }}
                            </span>
                            @else
                            <span class="text-xs text-slate-400 italic">No Role Assigned</span>
                            @endif
                        </td>

                        <!-- Status Badge -->
                        <td class="py-3.5 px-4">
                            @if(strtoupper($user->status) === 'ACTIVE')
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                            @elseif(strtoupper($user->status) === 'PENDING')
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                            </span>
                            @elseif(strtoupper($user->status) === 'REJECTED')
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Rejected
                            </span>
                            @else
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> {{ $user->status }}
                            </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(strtoupper($user->status) === 'PENDING')
                                <form action="{{ route('admin.users.approve', $user) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    <button type="submit"
                                        class="text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2.5 py-1.5 rounded-lg transition-colors">
                                        Approve
                                    </button>
                                </form>

                                <form action="{{ route('admin.users.reject', $user) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    <button type="submit"
                                        class="text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 px-2.5 py-1.5 rounded-lg transition-colors">
                                        Reject
                                    </button>
                                </form>
                                @endif

                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                    Edit
                                </a>

                                {{-- Hide Delete Button for Super Admin --}}
                                @php
                                $isSuperAdmin = strtoupper($user->user_type) === 'ADMIN' ||
                                ($user->roles && $user->roles->contains('name', 'Super Admin')) ||
                                (isset($user->role) && $user->role->name === 'Super Admin');
                                @endphp

                                @if(!$isSuperAdmin)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors">
                                        Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400 text-sm">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($users, 'hasPages') && $users->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
    @endsection