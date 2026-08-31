@extends('layouts.admin')

@section('title', 'Administrative Facility Settings: ' . $warehouse->name)

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">

    <!-- Top Action Bar & Breadcrumbs -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.warehouses.show', $warehouse) }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Facility Hub
            </a>
            <div class="flex flex-wrap items-center gap-2.5">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Administrative Facility Settings</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold font-mono {{ $warehouse->status === 'ACTIVE' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                    ● {{ $warehouse->status }}
                </span>
                <span class="font-mono bg-slate-100 text-slate-700 text-xs px-2.5 py-0.5 rounded-md border border-slate-200 font-semibold">
                    Code: {{ $warehouse->code ?: 'HQ-01' }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Configure master distribution facility, management leadership, tax credentials, and freight logistics.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.warehouses.show', $warehouse) }}"
                class="px-4 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all shadow-sm">
                Cancel
            </a>
            <button type="button" onclick="document.getElementById('editWarehouseForm').requestSubmit()"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-md shadow-blue-200 active:scale-[0.98] transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Facility Profile
            </button>
        </div>
    </div>

    <!-- Validation Errors Alert -->
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl text-xs space-y-1 shadow-sm">
        <div class="flex items-center gap-2 font-bold text-sm">
            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Please check the required inputs:</span>
        </div>
        <ul class="list-disc list-inside pl-6 space-y-0.5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form id="editWarehouseForm" action="{{ route('admin.warehouses.update', $warehouse) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- LEFT COLUMN: Primary Configuration Cards (8 Cols) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- CARD 1: Master Administrative Facility Identity -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs">1</span>
                            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Master Facility Identity</h2>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">* Required fields</span>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Facility Name -->
                            <div class="sm:col-span-2">
                                <label for="inputName" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Primary Facility / Hub Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="inputName" value="{{ old('name', $warehouse->name) }}" required
                                    placeholder="e.g. Akshay Central Distribution Hub"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Facility Code -->
                            <div>
                                <label for="inputCode" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Facility Code / Identifier
                                </label>
                                <input type="text" name="code" id="inputCode" value="{{ old('code', $warehouse->code ?: 'HQ-01') }}"
                                    placeholder="e.g. HQ-MAIN-01"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-mono uppercase text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                                @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Operational Status -->
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200/70">
                            <div>
                                <span class="text-xs font-bold text-slate-800 block">Facility Operational Status</span>
                                <span class="text-[11px] text-slate-500">Enable or pause fulfillment & stock dispatch from this hub</span>
                            </div>
                            <select name="status" id="inputStatus" required
                                class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-bold text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none">
                                <option value="ACTIVE" {{ old('status', $warehouse->status) === 'ACTIVE' ? 'selected' : '' }}>● Active Hub</option>
                                <option value="INACTIVE" {{ old('status', $warehouse->status) === 'INACTIVE' ? 'selected' : '' }}>○ Inactive / Closed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: Physical Registered & Freight Location -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs">2</span>
                            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Registered Physical & Dispatch Location</h2>
                        </div>
                    </div>

                    <div>
                        <label for="location" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Complete Physical Location / Dispatch Address <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            <input type="text" name="location" id="location" value="{{ old('location', $warehouse->location) }}" required
                                placeholder="Start typing address or landmark..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                        </div>
                        <span class="text-[11px] text-slate-400 mt-1 block">Printed on dispatch manifests, B2B shipment slips, and invoices.</span>
                        @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- CARD 3: Executive Management & Logistics Leadership -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">3</span>
                            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Operations Leadership & Tax Credentials</h2>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Manager Name -->
                            <div>
                                <label for="inputManager" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Operations Head / Manager
                                </label>
                                <input type="text" name="manager_name" id="inputManager" value="{{ old('manager_name', $warehouse->manager_name) }}"
                                    placeholder="e.g. Rajesh Kumar"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                                @error('manager_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Contact Phone -->
                            <div>
                                <label for="inputPhone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Direct Contact / WhatsApp
                                </label>
                                <input type="text" name="contact_phone" id="inputPhone" value="{{ old('contact_phone', $warehouse->contact_phone) }}"
                                    placeholder="e.g. +91 98160 12345"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                                @error('contact_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Logistics Email -->
                            <div>
                                <label for="inputEmail" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Logistics & Dispatch Email
                                </label>
                                <input type="email" name="contact_email" id="inputEmail" value="{{ old('contact_email', $warehouse->contact_email) }}"
                                    placeholder="dispatch@akshaywholesale.com"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                                @error('contact_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Tax / GSTIN -->
                        <div>
                            <label for="inputTax" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                GSTIN / Business Tax Registration Number (Optional)
                            </label>
                            <input type="text" name="tax_number" id="inputTax" value="{{ old('tax_number', $warehouse->tax_number) }}"
                                placeholder="e.g. 02AAAAA0000A1Z5"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-mono uppercase text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                            @error('tax_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- CARD 4: Operational Hours & Dock Freight Logistics -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">4</span>
                            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Operational Hours & Dock Freight Logistics</h2>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Operating Hours -->
                        <div>
                            <label for="inputHours" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Receiving & Dispatch Operational Hours
                            </label>
                            <input type="text" name="operating_hours" id="inputHours" value="{{ old('operating_hours', $warehouse->operating_hours) }}"
                                placeholder="e.g. Mon - Sat: 8:00 AM - 7:00 PM (Inbound Receiving: 9 AM - 4 PM)"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                            @error('operating_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Dispatch / Dock Notes -->
                        <div>
                            <label for="inputNotes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Freight Inbound/Outbound Dock Guidelines
                            </label>
                            <textarea name="dispatch_notes" id="inputNotes" rows="3"
                                placeholder="e.g. Heavy transport truck docking bay available. Inbound shipments require advance PO notification..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">{{ old('dispatch_notes', $warehouse->dispatch_notes) }}</textarea>
                            @error('dispatch_notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Sticky Sidebar & Live Facility Card (4 Cols) -->
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">

                <!-- SIDEBAR CARD 1: Live Facility Profile Card -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-xs font-bold text-slate-800 uppercase tracking-wide flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Live Facility Profile Card
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">Real-time</span>
                    </div>

                    <!-- Facility Card Preview -->
                    <div class="bg-slate-50 rounded-xl border border-slate-200 p-4 space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span id="previewCode" class="font-mono bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded">
                                    {{ $warehouse->code ?: 'HQ-01' }}
                                </span>
                                <h3 id="previewName" class="font-bold text-slate-900 text-sm mt-1.5 leading-snug">
                                    {{ $warehouse->name }}
                                </h3>
                            </div>
                            <span id="previewStatus" class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $warehouse->status === 'ACTIVE' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                {{ $warehouse->status }}
                            </span>
                        </div>

                        <div class="text-xs text-slate-600 space-y-1.5 pt-2 border-t border-slate-200/80">
                            <p id="previewLocation" class="text-[11px] text-slate-500 line-clamp-2">
                                📍 {{ $warehouse->location }}
                            </p>
                            <p id="previewManager" class="text-[11px] font-semibold text-slate-700">
                                👤 {{ $warehouse->manager_name ?: 'Manager: Operations Head' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR CARD 2: Live Inventory Metrics -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-3.5">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wide border-b border-slate-100 pb-2.5">
                        Current Hub Inventory Stats
                    </h3>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Total Stored Units:</span>
                            <span class="font-bold text-slate-900">{{ number_format($warehouse->total_stock_units) }} units</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Total Asset Valuation:</span>
                            <span class="font-bold text-emerald-700">${{ number_format($warehouse->total_valuation, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Active Variant Lines:</span>
                            <span class="font-bold text-blue-700">{{ $warehouse->stocks->count() }} Variants</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600 pt-2 border-t border-slate-100">
                            <span>Low Stock Alerts:</span>
                            <span class="font-bold {{ $warehouse->low_stock_count > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                                {{ $warehouse->low_stock_count }} items
                            </span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-3">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl text-sm transition-all shadow-md shadow-blue-200 active:scale-[0.98] flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Facility Profile
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

<!-- Real-time Live Preview Sync Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputName = document.getElementById('inputName');
    const inputCode = document.getElementById('inputCode');
    const inputLocation = document.getElementById('location');
    const inputManager = document.getElementById('inputManager');
    const inputStatus = document.getElementById('inputStatus');

    const previewName = document.getElementById('previewName');
    const previewCode = document.getElementById('previewCode');
    const previewLocation = document.getElementById('previewLocation');
    const previewManager = document.getElementById('previewManager');
    const previewStatus = document.getElementById('previewStatus');

    inputName?.addEventListener('input', (e) => {
        previewName.textContent = e.target.value.trim() || 'Facility Name';
    });

    inputCode?.addEventListener('input', (e) => {
        previewCode.textContent = e.target.value.toUpperCase() || 'HQ-01';
    });

    inputLocation?.addEventListener('input', (e) => {
        previewLocation.textContent = '📍 ' + (e.target.value.trim() || 'Location Address');
    });

    inputManager?.addEventListener('input', (e) => {
        previewManager.textContent = '👤 ' + (e.target.value.trim() || 'Manager: Operations Head');
    });

    inputStatus?.addEventListener('change', (e) => {
        const val = e.target.value;
        previewStatus.textContent = val;
        previewStatus.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold ' + 
            (val === 'ACTIVE' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600');
    });
});
</script>
@endsection