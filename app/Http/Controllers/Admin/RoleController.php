<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Roles & Privileges Directory
     */
    public function index(Request $request)
    {
        $roles = Role::where('name', '!=', 'Customer')
            ->withCount('users')
            ->with(['permissions'])
            ->latest('id')
            ->paginate(15);

        $totalPermissions = Permission::count();

        return view('admin.roles.index', compact('roles', 'totalPermissions'));
    }

    /**
     * Show Create Role Form
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store New Role
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255|unique:roles,name',
            'status' => 'required|in:ACTIVE,INACTIVE,active,inactive',
        ]);

        $validated['status'] = strtoupper($validated['status']);

        $role = Role::create($validated);

        return redirect()->route('admin.roles.permissions', $role)
            ->with('success', "Role '{$role->name}' created successfully. Now assign permissions.");
    }

    /**
     * Show Edit Role Form
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update Role
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255|unique:roles,name,' . $role->id,
            'status' => 'required|in:ACTIVE,INACTIVE,active,inactive',
        ]);

        $validated['status'] = strtoupper($validated['status']);

        $role->update($validated);

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$role->name}' updated successfully.");
    }

    /**
     * Delete Role (with Protection)
     */
    public function destroy(Role $role)
    {
        if (in_array(strtolower($role->name), ['super admin', 'customer'])) {
            return redirect()->back()->with('error', "System protected role '{$role->name}' cannot be deleted.");
        }

        if ($role->users()->count() > 0) {
            return redirect()->back()->with('error', "Role '{$role->name}' cannot be deleted because it is assigned to {$role->users()->count()} user(s).");
        }

        $name = $role->name;
        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$name}' deleted successfully.");
    }

    /**
     * Show Granular Permissions Matrix for Role
     */
    public function permissions(Role $role)
    {
        $permissions = Permission::orderBy('module')->orderBy('action')->get()->groupBy('module');
        $assignedIds = $role->permissions()->pluck('permissions.id')->toArray();
        $totalSystemPermissions = Permission::count();

        return view('admin.roles.permissions', compact('role', 'permissions', 'assignedIds', 'totalSystemPermissions'));
    }

    /**
     * Sync Permissions for Role
     */
    public function updatePermissions(Request $request, Role $role)
    {
        $permissionIds = $request->input('permission_ids', []);
        $role->permissions()->sync($permissionIds);

        return redirect()->route('admin.roles.index')
            ->with('success', count($permissionIds) . " permissions updated for '{$role->name}'.");
    }
}