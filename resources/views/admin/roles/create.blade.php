@extends('layouts.admin')

@section('title', 'Add Role')

@section('content')
<h1 class="text-2xl font-semibold text-slate-800 mb-6">Add Role</h1>

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 max-w-xl">
    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm text-slate-600 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-slate-200 rounded-lg p-2">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm text-slate-600 mb-1">Status</label>
            <select name="status" class="w-full border border-slate-200 rounded-lg p-2">
                <option value="ACTIVE">Active</option>
                <option value="INACTIVE">Inactive</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">Save</button>
            <a href="{{ route('admin.roles.index') }}" class="border border-slate-200 px-4 py-2 rounded-lg text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection