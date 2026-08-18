<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:ACTIVE,INACTIVE',
        ]);

        Role::create($validated);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:ACTIVE,INACTIVE',
        ]);

        $role->update($validated);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }

    public function permissions(Role $role)
    {
        $permissions = Permission::orderBy('module')->orderBy('action')->get()->groupBy('module');
        $assignedIds = $role->permissions()->pluck('permissions.id')->toArray();

        return view('admin.roles.permissions', compact('role', 'permissions', 'assignedIds'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $role->permissions()->sync($request->input('permission_ids', []));

        return redirect()->route('admin.roles.index')->with('success', 'Permissions updated for ' . $role->name);
    }
}