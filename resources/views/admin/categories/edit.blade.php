@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-slate-800">
            @if($category->parent_id)
                Edit Sub-Category
            @else
                Edit Category
            @endif
        </h1>
        @if($category->parent)
            <p class="text-sm text-slate-500 mt-1">Under: <span class="font-medium text-slate-700">{{ $category->parent->name }}</span></p>
        @else
            <p class="text-sm text-slate-500 mt-1">This is a top-level category</p>
        @endif
    </div>
    <a href="{{ route('admin.categories.index') }}" class="text-sm text-slate-500">← Back</a>
</div>

<form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
    @csrf
    @method('PUT')

    <div>
        <label class="text-sm text-slate-600">Name</label>
        <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full border rounded-lg p-2 mt-1">
        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    @if($category->parent_id)
        {{-- Sub-category: parent fixed, dropdown hidden --}}
        <div>
            <label class="text-sm text-slate-600">Parent Category</label>
            <input type="text" value="{{ $category->parent->name }}" disabled class="w-full border rounded-lg p-2 mt-1 bg-slate-100 text-slate-500">
            <input type="hidden" name="parent_id" value="{{ $category->parent_id }}">
        </div>
    @else
        {{-- Top-level category: no parent field needed --}}
        <input type="hidden" name="parent_id" value="">
    @endif

    <div>
        <label class="text-sm text-slate-600">Image</label>
        <input type="file" name="image" class="w-full border rounded-lg p-2 mt-1">
        @if($category->image)
            <img src="{{ $category->image_url }}" class="w-16 h-16 rounded object-cover mt-2" onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';">
        @endif
        @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="text-sm text-slate-600">Status</label>
        <select name="status" class="w-full border rounded-lg p-2 mt-1">
            <option value="ACTIVE" {{ $category->status == 'ACTIVE' ? 'selected' : '' }}>Active</option>
            <option value="INACTIVE" {{ $category->status == 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <div class="sm:col-span-2">
        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm">Update Category</button>
    </div>
</form>
@endsection