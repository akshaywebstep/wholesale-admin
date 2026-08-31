@extends('layouts.admin')

@section('title', 'Edit User: ' . $user->name)

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to User Directory
            </a>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Profile: {{ $user->name }}</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold font-mono {{ $user->status === 'ACTIVE' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                    ● {{ $user->status }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Update user credentials, role permissions, and wholesale store settings.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}"
                class="px-4 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all shadow-sm">
                Cancel
            </a>
            <button type="button" onclick="document.getElementById('editUserForm').requestSubmit()"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-md shadow-blue-200 active:scale-[0.98] transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Changes
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
            <span>Please resolve the following errors:</span>
        </div>
        <ul class="list-disc list-inside pl-6 space-y-0.5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form id="editUserForm" action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- LEFT COLUMN: Account Configuration Forms (8 Cols) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- CARD 1: Account Type Selection -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                    <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2.5">
                        1. Account Type & Access Classification
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="relative flex items-start p-4 rounded-xl border-2 cursor-pointer transition-all user-type-card {{ old('user_type', $user->user_type) !== 'CUSTOMER' ? 'border-blue-500 bg-blue-50/20' : 'border-slate-200' }}" id="cardStaff">
                            <input type="radio" name="user_type" value="{{ $user->user_type === 'ADMIN' ? 'ADMIN' : 'STAFF' }}" {{ $user->user_type !== 'CUSTOMER' ? 'checked' : '' }}
                                class="mt-1 w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500" onchange="toggleUserTypeForm('STAFF')">
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-slate-900">🛡️ Internal Staff Member</span>
                                <span class="block text-[11px] text-slate-500 mt-0.5">Company employees, warehouse operators, and managers.</span>
                            </div>
                        </label>

                        <label class="relative flex items-start p-4 rounded-xl border-2 cursor-pointer transition-all user-type-card {{ old('user_type', $user->user_type) === 'CUSTOMER' ? 'border-emerald-500 bg-emerald-50/20' : 'border-slate-200' }}" id="cardCustomer">
                            <input type="radio" name="user_type" value="CUSTOMER" {{ old('user_type', $user->user_type) === 'CUSTOMER' ? 'checked' : '' }}
                                class="mt-1 w-4 h-4 text-emerald-600 border-slate-300 focus:ring-emerald-500" onchange="toggleUserTypeForm('CUSTOMER')">
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-slate-900">🏢 B2B Wholesale Customer</span>
                                <span class="block text-[11px] text-slate-500 mt-0.5">Smoke shop retailers, convenience stores, and B2B buyers.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- CARD 2: Basic Identity & Credentials -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2.5">
                        2. Basic Identity & Login Credentials
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div>
                            <label for="inputName" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="inputName" value="{{ old('name', $user->name) }}" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label for="inputEmail" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Official Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="inputEmail" value="{{ old('email', $user->email) }}" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="inputPhone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Mobile / Phone Number
                            </label>
                            <input type="text" name="phone" id="inputPhone" value="{{ old('phone', $user->phone) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                        </div>

                        <!-- Password (Optional on Edit) -->
                        <div>
                            <label for="inputPassword" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Reset Password (Leave blank to keep existing)
                            </label>
                            <input type="password" name="password" id="inputPassword" minlength="6"
                                placeholder="Enter new password if updating"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                        </div>
                    </div>

                    <!-- Status Selection -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200/70">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Account Status</span>
                            <span class="text-[11px] text-slate-500">Active accounts can login to the portal</span>
                        </div>
                        <select name="status" id="inputStatus" required
                            class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-bold text-slate-800 focus:border-blue-500 outline-none">
                            <option value="ACTIVE" {{ old('status', $user->status) === 'ACTIVE' ? 'selected' : '' }}>● Active</option>
                            <option value="PENDING" {{ old('status', $user->status) === 'PENDING' ? 'selected' : '' }}>⏳ Pending Verification</option>
                            <option value="INACTIVE" {{ old('status', $user->status) === 'INACTIVE' ? 'selected' : '' }}>○ Inactive / Suspended</option>
                            <option value="REJECTED" {{ old('status', $user->status) === 'REJECTED' ? 'selected' : '' }}>✗ Rejected</option>
                        </select>
                    </div>
                </div>

                <!-- CARD 3A: Staff Role Assignment (Visible if Staff selected) -->
                <div id="sectionStaff" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5 {{ $user->user_type === 'CUSTOMER' ? 'hidden' : '' }}">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                            3. Role & Module Privileges Assignment
                        </h2>
                        <a href="{{ route('admin.roles.index') }}" class="text-[11px] text-blue-600 hover:underline font-semibold">
                            + Manage Roles
                        </a>
                    </div>

                    <div>
                        <label for="inputRole" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Assigned Staff Role
                        </label>
                        <select name="role_id" id="inputRole"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-500 outline-none">
                            <option value="">-- Choose a Role --</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" data-name="{{ $role->name }}" {{ old('role_id', $assignedRoleId) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }} ({{ $role->permissions->count() }} Privileges Granted)
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- CARD 3B: B2B Customer Business & Tax Info (Visible if Customer selected) -->
                <div id="sectionCustomer" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5 {{ $user->user_type !== 'CUSTOMER' ? 'hidden' : '' }}">
                    <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2.5">
                        3. Wholesale Business & GST Profile
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Business / Store Name -->
                        <div>
                            <label for="inputBusiness" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Retail Shop / Business Name
                            </label>
                            <input type="text" name="business_name" id="inputBusiness" value="{{ old('business_name', $user->business_name) }}"
                                placeholder="e.g. Apex Smoke & C-Store"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 outline-none">
                        </div>

                        <!-- GSTIN / Tax ID -->
                        <div>
                            <label for="inputGst" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                GST Number / Tax ID
                            </label>
                            <input type="text" name="gst_number" id="inputGst" value="{{ old('gst_number', $user->gst_number) }}"
                                placeholder="e.g. 02AAAAA0000A1Z5"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono uppercase text-slate-800 focus:bg-white focus:border-blue-500 outline-none">
                        </div>

                        <!-- Customer Tier Group -->
                        <div class="sm:col-span-2">
                            <label for="inputGroup" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Wholesale Customer Tier / Group
                            </label>
                            <select name="customer_group_id" id="inputGroup"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 outline-none">
                                <option value="">-- Standard Wholesale Buyer --</option>
                                @foreach($customerGroups as $group)
                                <option value="{{ $group->id }}" {{ old('customer_group_id', $user->customer_group_id) == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Physical Address -->
                        <div class="sm:col-span-2">
                            <label for="inputAddress" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Registered Store Address
                            </label>
                            <input type="text" name="address" id="inputAddress" value="{{ old('address', $user->address) }}"
                                placeholder="Street address, shop number..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 outline-none">
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Sticky Profile Card (4 Cols) -->
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">

                <!-- Profile Card -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-xs font-bold text-slate-800 uppercase tracking-wide flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Live User Profile Card
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">ID #{{ $user->id }}</span>
                    </div>

                    <!-- Profile Card UI -->
                    <div class="bg-slate-50 rounded-xl border border-slate-200 p-5 space-y-4 text-center">
                        <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-700 font-bold text-xl flex items-center justify-center mx-auto border-2 border-white shadow-sm" id="previewAvatar">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 text-base leading-snug" id="previewName">
                                {{ $user->name }}
                            </h3>
                            <span class="text-xs text-slate-500 block" id="previewEmail">{{ $user->email }}</span>
                            <span class="text-[11px] text-slate-400 block mt-0.5" id="previewPhone">{{ $user->phone ?: 'No Phone Added' }}</span>
                        </div>

                        <div class="pt-3 border-t border-slate-200/80 flex items-center justify-center gap-2">
                            <span id="previewTypeBadge" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                {{ $user->user_type }}
                            </span>
                            <span id="previewRoleBadge" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700">
                                Role: {{ $user->roles->first()->name ?? 'Customer' }}
                            </span>
                        </div>
                    </div>

                    <!-- Direct Submit Button -->
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl text-sm transition-all shadow-md shadow-blue-200 active:scale-[0.98] flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save User Profile
                    </button>
                </div>

            </div>

        </div>
    </form>
</div>

<script>
function toggleUserTypeForm(type) {
    const sectionStaff = document.getElementById('sectionStaff');
    const sectionCustomer = document.getElementById('sectionCustomer');
    const cardStaff = document.getElementById('cardStaff');
    const cardCustomer = document.getElementById('cardCustomer');
    const previewTypeBadge = document.getElementById('previewTypeBadge');
    const previewRoleBadge = document.getElementById('previewRoleBadge');

    if (type === 'STAFF' || type === 'ADMIN') {
        sectionStaff.classList.remove('hidden');
        sectionCustomer.classList.add('hidden');
        cardStaff.className = 'relative flex items-start p-4 rounded-xl border-2 border-blue-500 bg-blue-50/20 cursor-pointer transition-all user-type-card';
        cardCustomer.className = 'relative flex items-start p-4 rounded-xl border-2 border-slate-200 hover:border-slate-300 cursor-pointer transition-all user-type-card';
        previewTypeBadge.textContent = '🛡️ Staff Member';
        previewTypeBadge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200';
        previewRoleBadge.classList.remove('hidden');
    } else {
        sectionStaff.classList.add('hidden');
        sectionCustomer.classList.remove('hidden');
        cardCustomer.className = 'relative flex items-start p-4 rounded-xl border-2 border-emerald-500 bg-emerald-50/20 cursor-pointer transition-all user-type-card';
        cardStaff.className = 'relative flex items-start p-4 rounded-xl border-2 border-slate-200 hover:border-slate-300 cursor-pointer transition-all user-type-card';
        previewTypeBadge.textContent = '🏢 B2B Customer';
        previewTypeBadge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200';
        previewRoleBadge.classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const inputName = document.getElementById('inputName');
    const inputEmail = document.getElementById('inputEmail');
    const inputPhone = document.getElementById('inputPhone');
    const inputRole = document.getElementById('inputRole');

    const previewAvatar = document.getElementById('previewAvatar');
    const previewName = document.getElementById('previewName');
    const previewEmail = document.getElementById('previewEmail');
    const previewPhone = document.getElementById('previewPhone');
    const previewRoleBadge = document.getElementById('previewRoleBadge');

    inputName?.addEventListener('input', (e) => {
        const val = e.target.value.trim();
        previewName.textContent = val || 'User Full Name';
        previewAvatar.textContent = val ? val.substring(0, 2).toUpperCase() : 'US';
    });

    inputEmail?.addEventListener('input', (e) => {
        previewEmail.textContent = e.target.value.trim() || 'user@company.com';
    });

    inputPhone?.addEventListener('input', (e) => {
        previewPhone.textContent = e.target.value.trim() || '+91 00000 00000';
    });

    inputRole?.addEventListener('change', (e) => {
        const opt = e.target.selectedOptions[0];
        const roleName = opt ? opt.getAttribute('data-name') : 'No Role';
        previewRoleBadge.textContent = 'Role: ' + (roleName || 'Unassigned');
    });
});
</script>
@endsection