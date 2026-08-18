<header class="h-16 bg-white border-b border-slate-200/80 px-6 flex items-center justify-between sticky top-0 z-10 shadow-sm">
    <div class="flex items-center gap-4">
        <span class="text-sm font-medium text-slate-500">Overview</span>
    </div>

    <!-- Admin Profile / Logout Section -->
    <div class="flex items-center gap-4">
        <!-- User Info -->
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <span class="text-sm font-semibold text-slate-700">{{ auth()->user()->name ?? 'Admin User' }}</span>
        </div>

        <!-- Divider -->
        <div class="h-4 w-px bg-slate-200"></div>

        <!-- Logout Form  -->
        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
            @csrf
            <button type="submit" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all border border-slate-200 hover:border-rose-200" 
                    title="Logout">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </div>
</header>