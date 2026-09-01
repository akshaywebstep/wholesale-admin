<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Roles & Privileges Directory (Staff Roles Only)
     */
    public function index(Request $request)
    {
        $roles = Role::whereNotIn('name', ['Super Admin', 'super admin', 'SUPER ADMIN', 'Admin', 'admin', 'Customer', 'customer'])
            ->withCount('users')
            ->with(['permissions', 'users' => function($q) {
                $q->take(4);
            }])
            ->latest('id')
            ->paginate(12);

        $totalPermissions = Permission::count();
        $totalStaffRolesCount = Role::whereNotIn('name', ['Super Admin', 'super admin', 'SUPER ADMIN', 'Admin', 'admin', 'Customer', 'customer'])->count();
        $activeStaffRolesCount = Role::whereNotIn('name', ['Super Admin', 'super admin', 'SUPER ADMIN', 'Admin', 'admin', 'Customer', 'customer'])->where('status', 'ACTIVE')->count();

        return view('admin.roles.index', compact('roles', 'totalPermissions', 'totalStaffRolesCount', 'activeStaffRolesCount'));
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
        if ($request->has('name')) {
            $request->merge(['name' => trim($request->name)]);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name',
                function ($attribute, $value, $fail) {
                    $normalized = strtolower(trim($value));
                    if (in_array($normalized, ['super admin', 'admin', 'customer'])) {
                        $fail("The role name '{$value}' is reserved by the system and cannot be created.");
                        return;
                    }
                    // Case-insensitive duplicate check
                    $exists = Role::whereRaw('LOWER(name) = ?', [$normalized])->exists();
                    if ($exists) {
                        $fail("A role with the name '{$value}' already exists.");
                    }
                }
            ],
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
        if (in_array(strtolower(trim($role->name)), ['super admin', 'customer'])) {
            return redirect()->route('admin.roles.index')
                ->with('error', "System role '{$role->name}' is protected and cannot be edited.");
        }

        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update Role
     */
    public function update(Request $request, Role $role)
    {
        if (in_array(strtolower(trim($role->name)), ['super admin', 'customer'])) {
            return redirect()->route('admin.roles.index')
                ->with('error', "System role '{$role->name}' is protected and cannot be modified.");
        }

        if ($request->has('name')) {
            $request->merge(['name' => trim($request->name)]);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name,' . $role->id,
                function ($attribute, $value, $fail) use ($role) {
                    $normalized = strtolower(trim($value));
                    if (in_array($normalized, ['super admin', 'admin', 'customer'])) {
                        $fail("The role name '{$value}' is reserved by the system.");
                        return;
                    }
                    // Case-insensitive duplicate check against other roles
                    $exists = Role::where('id', '!=', $role->id)
                        ->whereRaw('LOWER(name) = ?', [$normalized])
                        ->exists();
                    if ($exists) {
                        $fail("A role with the name '{$value}' already exists.");
                    }
                }
            ],
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