@extends('layouts.admin')

@section('title', 'Add Category')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-slate-800">
            @if($selectedParent)
                Add Sub-Category
            @else
                Add Category
            @endif
        </h1>
        @if($selectedParent)
            <p class="text-sm text-slate-500 mt-1">Adding under: <span class="font-medium text-slate-700">{{ $selectedParent->name }}</span></p>
        @else
            <p class="text-sm text-slate-500 mt-1">This will be a top-level category</p>
        @endif
    </div>
    <a href="{{ route('admin.categories.index') }}" class="text-sm text-slate-500">← Back</a>
</div>

@if($selectedParent)
<div class="bg-blue-50 text-blue-700 px-4 py-3 rounded-lg mb-4 text-sm">
    You're adding a sub-category inside <strong>{{ $selectedParent->name }}</strong>.
</div>
@endif

<form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
    @csrf

    <div>
        <label class="text-sm text-slate-600">Name</label>
        <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded-lg p-2 mt-1">
        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    @if($selectedParent)
        {{-- Sub-category: parent fixed, dropdown hidden --}}
        <div>
            <label class="text-sm text-slate-600">Parent Category</label>
            <input type="text" value="{{ $selectedParent->name }}" disabled class="w-full border rounded-lg p-2 mt-1 bg-slate-100 text-slate-500">
            <input type="hidden" name="parent_id" value="{{ $selectedParent->id }}">
        </div>
    @else
        {{-- Top-level category: no parent field needed at all --}}
        <input type="hidden" name="parent_id" value="">
    @endif

    <div>
        <label class="text-sm text-slate-600">Image</label>
        <input type="file" name="image" class="w-full border rounded-lg p-2 mt-1">
        @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="text-sm text-slate-600">Status</label>
        <select name="status" class="w-full border rounded-lg p-2 mt-1">
            <option value="ACTIVE" {{ old('status') == 'ACTIVE' ? 'selected' : '' }}>Active</option>
            <option value="INACTIVE" {{ old('status') == 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <div class="sm:col-span-2">
        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm">Save Category</button>
    </div>
</form>
@endsection