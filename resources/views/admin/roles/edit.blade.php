@extends('layouts.admin')

@section('title', 'Edit Role')

@section('content')
<h1 class="text-2xl font-semibold text-slate-800 mb-6">Edit Role</h1>

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 max-w-xl">
    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-sm text-slate-600 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $role->name) }}" class="w-full border border-slate-200 rounded-lg p-2">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm text-slate-600 mb-1">Status</label>
            <select name="status" class="w-full border border-slate-200 rounded-lg p-2">
                <option value="ACTIVE" {{ $role->status === 'ACTIVE' ? 'selected' : '' }}>Active</option>
                <option value="INACTIVE" {{ $role->status === 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">Update</button>
            <a href="{{ route('admin.roles.index') }}" class="border border-slate-200 px-4 py-2 rounded-lg text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection