@extends('layouts.admin')

@section('title', 'Add User')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Add New User</h1>
            <p class="text-slate-500 text-sm mt-0.5">Create a new system user or customer account.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-xl text-sm font-semibold transition-all">
            &larr; Back to Users
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf

            <!-- Section 1: Basic Information -->
            <div>
                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. John Doe"
                            class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all">
                        @error('name') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="john@example.com"
                            class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all">
                        @error('email') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+1 234 567 890"
                            class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all">
                        @error('phone') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Password *</label>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all">
                        @error('password') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section 2: Access & Account Configuration -->
            <div>
                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Account Type & Status</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">User Type *</label>
                        <select name="user_type" id="user_type" required onchange="toggleFields()"
                            class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all cursor-pointer">
                            <option value="">Select Account Type</option>
                            <option value="STAFF" {{ old('user_type') == 'STAFF' ? 'selected' : '' }}>Staff</option>
                            <option value="CUSTOMER" {{ old('user_type') == 'CUSTOMER' ? 'selected' : '' }}>Customer</option>
                        </select>
                        @error('user_type') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Account Status *</label>
                        <select name="status" required
                            class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all cursor-pointer">
                            <option value="ACTIVE" {{ old('status', 'ACTIVE') == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                            <option value="PENDING" {{ old('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                            <option value="INACTIVE" {{ old('status') == 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
                            <option value="REJECTED" {{ old('status') == 'REJECTED' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('status') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Dynamic STAFF Role Selection -->
                    <div id="role_field" class="sm:col-span-2" style="display:none;">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Assign Role *</label>
                        <select name="role_id"
                            class="w-full px-3.5 py-2.5 bg-purple-50/40 border border-purple-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-purple-500 transition-all cursor-pointer">
                            <option value="">Select System Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Dynamic CUSTOMER Information -->
            <div id="customer_fields" class="space-y-6 pt-2" style="display:none;">
                <hr class="border-slate-100">
                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Customer Details & Location</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Business Name</label>
                        <input type="text" name="business_name" value="{{ old('business_name') }}" placeholder="Company or Shop Name"
                            class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">GST Number</label>
                        <input type="text" name="gst_number" value="{{ old('gst_number') }}" placeholder="22AAAAA0000A1Z5"
                            class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Customer Group *</label>
                        <select name="customer_group_id" id="customer_group_id"
                            class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all cursor-pointer">
                            <option value="">Select Group</option>
                            @foreach($customerGroups as $group)
                                <option value="{{ $group->id }}" {{ old('customer_group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                            @endforeach
                        </select>
                        @error('customer_group_id') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Country *</label>
                        <select name="country_id" id="country_id" onchange="loadStates(this.value)"
                            class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all cursor-pointer">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('country_id') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">State *</label>
                        <select name="state_id" id="state_id" onchange="loadCities(this.value)"
                            class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all cursor-pointer">
                            <option value="">Select State</option>
                        </select>
                        @error('state_id') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">City *</label>
                        <select name="city_id" id="city_id"
                            class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all cursor-pointer">
                            <option value="">Select City</option>
                        </select>
                        @error('city_id') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1.5">Full Address *</label>
                        <textarea name="address" id="address_field" rows="3" placeholder="Street address, building, suite..."
                            class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition-all">{{ old('address') }}</textarea>
                        @error('address') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Form Footer / Actions -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-all">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-xl text-sm transition-all shadow-sm active:scale-[0.98]">
                    Save User Account
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleFields() {
    const type = document.getElementById('user_type').value;
    const isStaff = type === 'STAFF';
    const isCustomer = type === 'CUSTOMER';

    document.getElementById('role_field').style.display = isStaff ? 'block' : 'none';
    document.getElementById('customer_fields').style.display = isCustomer ? 'block' : 'none';

    // Required attribute dynamic toggle
    document.getElementById('customer_group_id').required = isCustomer;
    document.getElementById('country_id').required = isCustomer;
    document.getElementById('state_id').required = isCustomer;
    document.getElementById('city_id').required = isCustomer;
    document.getElementById('address_field').required = isCustomer;
}

async function loadStates(countryId) {
    const stateSelect = document.getElementById('state_id');
    const citySelect = document.getElementById('city_id');
    stateSelect.innerHTML = '<option value="">Select State</option>';
    citySelect.innerHTML = '<option value="">Select City</option>';
    if (!countryId) return;

    try {
        const res = await fetch(`/admin/ajax/states/${countryId}`);
        const states = await res.json();
        states.forEach(state => {
            stateSelect.innerHTML += `<option value="${state.id}">${state.name}</option>`;
        });
    } catch (e) {
        console.error("Failed to load states", e);
    }
}

async function loadCities(stateId) {
    const citySelect = document.getElementById('city_id');
    citySelect.innerHTML = '<option value="">Select City</option>';
    if (!stateId) return;

    try {
        const res = await fetch(`/admin/ajax/cities/${stateId}`);
        const cities = await res.json();
        cities.forEach(city => {
            citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
        });
    } catch (e) {
        console.error("Failed to load cities", e);
    }
}

document.addEventListener('DOMContentLoaded', toggleFields);
</script>
@endsection