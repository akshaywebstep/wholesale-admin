@extends('layouts.admin')

@section('title', 'Edit Role: ' . $role->name)

@section('content')
<div class="max-w-xl mx-auto space-y-6 pb-12">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.roles.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Roles Matrix
            </a>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Role: {{ $role->name }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">Update role name and operational status.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl text-xs space-y-1 shadow-sm">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                Role Name <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-900 focus:bg-white focus:border-blue-500 outline-none">
        </div>

        <div>
            <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                Status <span class="text-red-500">*</span>
            </label>
            <select name="status" id="status" required
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 outline-none">
                <option value="ACTIVE" {{ old('status', $role->status) === 'ACTIVE' ? 'selected' : '' }}>● Active</option>
                <option value="INACTIVE" {{ old('status', $role->status) === 'INACTIVE' ? 'selected' : '' }}>○ Inactive</option>
            </select>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.roles.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 rounded-xl">Cancel</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-xl text-xs transition-all shadow-md shadow-blue-200">
                Save Role Changes
            </button>
        </div>
    </form>
</div>
@endsection