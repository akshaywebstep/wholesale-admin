@extends('layouts.admin')

@section('title', 'Add Warehouse')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Add New Warehouse</h1>
            <p class="text-slate-500 text-sm mt-0.5">Create a new storage location for stock management.</p>
        </div>
        <a href="{{ route('admin.warehouses.index') }}" class="text-slate-600 hover:text-slate-800 text-sm font-medium bg-slate-100 px-4 py-2 rounded-xl">Back</a>
    </div>

    <form id="warehouseForm" action="{{ route('admin.warehouses.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Warehouse Name <span class="text-rose-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="e.g. Central Logistics Depot" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 @error('name') border-rose-500 @enderror">
            @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="location" class="block text-sm font-medium text-slate-700 mb-1">Location / Address <span class="text-rose-500">*</span></label>
            <input type="text" name="location" id="location" value="{{ old('location') }}" required placeholder="Start typing address..." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 @error('location') border-rose-500 @enderror" autocomplete="off">
            <p id="location-error" class="text-rose-500 text-xs mt-1 hidden">Please select a valid location from the Google suggestions dropdown.</p>
            @error('location') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status <span class="text-rose-500">*</span></label>
            <select name="status" id="status" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                <option value="active" {{ old('status') === 'ACTIVE' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') === 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.warehouses.index') }}" class="px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-xl">Cancel</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">Save Warehouse</button>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const locationInput = document.getElementById('location');
    const locationError = document.getElementById('location-error');
    const form = document.getElementById('warehouseForm');
    let isValidPlaceSelected = false;

    if (typeof google !== 'undefined' && google.maps && google.maps.places) {
        const autocomplete = new google.maps.places.Autocomplete(locationInput, {
            types: ['geocode', 'establishment']
        });

        autocomplete.addListener('place_changed', function () {
            const place = autocomplete.getPlace();
            if (place && place.geometry) {
                isValidPlaceSelected = true;
                locationInput.value = place.formatted_address || place.name;
                locationError.classList.add('hidden');
                locationInput.classList.remove('border-rose-500');
            } else {
                isValidPlaceSelected = false;
            }
        });

        locationInput.addEventListener('input', function () {
            isValidPlaceSelected = false;
        });

        form.addEventListener('submit', function (e) {
            if (locationInput.value.trim() !== '' && !isValidPlaceSelected) {
                e.preventDefault();
                locationError.classList.remove('hidden');
                locationInput.classList.add('border-rose-500');
                locationInput.focus();
            }
        });
    }
});
</script>
@endsection