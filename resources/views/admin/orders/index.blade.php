@extends('layouts.admin')

@section('content')
<div class="p-6 bg-slate-50 min-h-screen font-sans text-slate-700">

    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
            <p class="text-xs text-slate-400 font-medium">Total Orders</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($stats['total']) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
            <p class="text-xs text-amber-600 font-medium">Pending Action</p>
            <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($stats['pending']) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
            <p class="text-xs text-blue-600 font-medium">Confirmed Orders</p>
            <h3 class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($stats['confirmed']) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
            <p class="text-xs text-emerald-600 font-medium">Total Revenue</p>
            <h3 class="text-2xl font-bold text-emerald-600 mt-1">₹{{ number_format($stats['revenue'], 2) }}</h3>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white border border-slate-200/80 rounded-xl p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col md:flex-row items-center gap-3">
            <div class="w-full md:w-48">
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 focus:outline-none focus:border-indigo-500">
                    <option value="">All Statuses</option>
                    @foreach(['PENDING', 'CONFIRMED', 'SHIPPED', 'DELIVERED', 'CANCELLED'] as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <div class="relative flex-1 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Order ID, Customer Name or Email..."
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
            </div>

            <button type="submit" class="w-full md:w-auto px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs rounded-lg transition shadow-sm">
                Filter Orders
            </button>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    <th class="py-3 px-4">ORDER NO</th>
                    <th class="py-3 px-4">CUSTOMER</th>
                    <th class="py-3 px-4">ITEMS</th>
                    <th class="py-3 px-4">AMOUNT</th>
                    <th class="py-3 px-4">STATUS</th>
                    <th class="py-3 px-4">DATE</th>
                    <th class="py-3 px-4 text-right">ACTION</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
                @forelse($orders as $order)
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="py-3.5 px-4 font-semibold text-indigo-600">
                        #{{ $order->order_number ?? $order->id }}
                    </td>
                    <td class="py-3.5 px-4">
                        <div class="font-medium text-slate-800">{{ $order->user->name ?? 'Guest User' }}</div>
                        <div class="text-[11px] text-slate-400">{{ $order->user->email ?? '-' }}</div>
                    </td>
                    <td class="py-3.5 px-4 text-slate-600">
                        {{ $order->items->sum('quantity') }} pcs ({{ $order->items->count() }} items)
                    </td>
                    <td class="py-3.5 px-4 font-semibold text-slate-800">
                        ₹{{ number_format($order->total_amount, 2) }}
                    </td>
                    <td class="py-3.5 px-4">
                        @php
                            $pills = [
                                'PENDING'   => 'bg-amber-50 text-amber-600 border-amber-200',
                                'CONFIRMED' => 'bg-blue-50 text-blue-600 border-blue-200',
                                'SHIPPED'   => 'bg-indigo-50 text-indigo-600 border-indigo-200',
                                'DELIVERED' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                'CANCELLED' => 'bg-rose-50 text-rose-600 border-rose-200',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded border text-[10px] font-semibold {{ $pills[strtoupper($order->status)] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ strtoupper($order->status) }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-slate-500 text-[11px]">
                        {{ $order->created_at->format('d M Y, h:i A') }}
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 font-medium text-slate-700 rounded transition text-xs">
                            Manage
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-slate-400 text-xs">
                        No orders matching your criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection