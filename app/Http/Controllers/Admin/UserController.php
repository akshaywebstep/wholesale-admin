<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\CustomerGroup;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * User Directory with Tabbed Views & KPIs
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'all');
        $query = User::with(['roles', 'customerGroup', 'city', 'state', 'country'])->withCount('orders');

        // Tab Filtering
        if ($tab === 'staff') {
            $query->whereIn('user_type', ['ADMIN', 'STAFF']);
        } elseif ($tab === 'customers') {
            $query->where('user_type', 'CUSTOMER');
        } elseif ($tab === 'pending') {
            $query->where('status', 'PENDING');
        }

        // Live Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%")
                  ->orWhere('gst_number', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', strtoupper($request->status));
        }

        // Role Filter
        if ($request->filled('role_id')) {
            $roleId = $request->role_id;
            $query->whereHas('roles', function ($q) use ($roleId) {
                $q->where('roles.id', $roleId);
            });
        }

        $users = $query->latest('id')->paginate(15)->withQueryString();

        // High-level Directory Counts
        $totalCount    = User::count();
        $staffCount    = User::whereIn('user_type', ['ADMIN', 'STAFF'])->count();
        $customerCount = User::where('user_type', 'CUSTOMER')->count();
        $pendingCount  = User::where('status', 'PENDING')->count();

        $roles = Role::where('status', 'ACTIVE')->where('name', '!=', 'Customer')->get();

        return view('admin.users.index', compact(
            'users',
            'tab',
            'totalCount',
            'staffCount',
            'customerCount',
            'pendingCount',
            'roles'
        ));
    }

    /**
     * Show User Creation Form
     */
    public function create()
    {
        $countries = Country::where('status', 'ACTIVE')->get();
        $customerGroups = CustomerGroup::where('status', 'ACTIVE')->get();
        $roles = Role::where('status', 'ACTIVE')->where('name', '!=', 'Customer')->get();

        return view('admin.users.create', compact('countries', 'customerGroups', 'roles'));
    }

    /**
     * Store New User (Staff or B2B Customer)
     */
    public function store(Request $request)
    {
        $userType = $request->input('user_type', 'STAFF');

        $rules = [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email',
            'phone'     => 'nullable|string|max:25',
            'password'  => 'required|string|min:6',
            'user_type' => 'required|in:ADMIN,STAFF,CUSTOMER',
            'status'    => 'required|in:ACTIVE,PENDING,INACTIVE,REJECTED',
        ];

        if ($userType === 'CUSTOMER') {
            $rules['business_name']     = 'nullable|string|max:255';
            $rules['gst_number']        = 'nullable|string|max:50';
            $rules['customer_group_id'] = 'nullable|exists:customer_groups,id';
            $rules['address']           = 'nullable|string|max:500';
            $rules['country_id']        = 'nullable|exists:countries,id';
            $rules['state_id']          = 'nullable|exists:states,id';
            $rules['city_id']           = 'nullable|exists:cities,id';
        } else {
            $rules['role_id']           = 'required|exists:roles,id';
        }

        $validated = $request->validate($rules);
        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = strtoupper($validated['status']);

        $roleId = $validated['role_id'] ?? null;
        unset($validated['role_id']);

        $user = User::create($validated);

        if ($userType === 'CUSTOMER') {
            // Automatically assign Customer role if exists
            $customerRole = Role::where('name', 'Customer')->first();
            if ($customerRole) {
                $user->roles()->sync([$customerRole->id]);
            }
        } elseif ($roleId) {
            $user->roles()->sync([$roleId]);
        }

        $redirectTab = $userType === 'CUSTOMER' ? 'customers' : 'staff';

        return redirect()->route('admin.users.index', ['tab' => $redirectTab])
            ->with('success', ($userType === 'CUSTOMER' ? 'B2B Customer account' : 'Staff member') . ' created successfully.');
    }

    /**
     * Show User Edit Form
     */
    public function edit(User $user)
    {
        $countries = Country::where('status', 'ACTIVE')->get();
        $states = $user->country_id ? State::where('country_id', $user->country_id)->get() : collect();
        $cities = $user->state_id ? City::where('state_id', $user->state_id)->get() : collect();
        $customerGroups = CustomerGroup::where('status', 'ACTIVE')->get();
        $assignedRoleId = $user->roles()->first()?->id;

        $roles = Role::where('status', 'ACTIVE')->where('name', '!=', 'Customer')->get();

        return view('admin.users.edit', compact(
            'user',
            'countries',
            'states',
            'cities',
            'customerGroups',
            'roles',
            'assignedRoleId'
        ));
    }

    /**
     * Update User Profile & Role
     */
    public function update(Request $request, User $user)
    {
        $userType = $request->input('user_type', $user->user_type);

        $rules = [
            'name'      => 'required|string|max:255',
            'email'     => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone'     => 'nullable|string|max:25',
            'password'  => 'nullable|string|min:6',
            'user_type' => 'required|in:ADMIN,STAFF,CUSTOMER',
            'status'    => 'required|in:ACTIVE,PENDING,INACTIVE,REJECTED',
        ];

        if ($userType === 'CUSTOMER') {
            $rules['business_name']     = 'nullable|string|max:255';
            $rules['gst_number']        = 'nullable|string|max:50';
            $rules['customer_group_id'] = 'nullable|exists:customer_groups,id';
            $rules['address']           = 'nullable|string|max:500';
            $rules['country_id']        = 'nullable|exists:countries,id';
            $rules['state_id']          = 'nullable|exists:states,id';
            $rules['city_id']           = 'nullable|exists:cities,id';
        } else {
            $rules['role_id']           = 'nullable|exists:roles,id';
        }

        $validated = $request->validate($rules);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['status'] = strtoupper($validated['status']);
        $roleId = $validated['role_id'] ?? null;
        unset($validated['role_id']);

        $user->update($validated);

        if ($userType === 'CUSTOMER') {
            $customerRole = Role::where('name', 'Customer')->first();
            if ($customerRole) {
                $user->roles()->sync([$customerRole->id]);
            }
        } elseif ($roleId) {
            $user->roles()->sync([$roleId]);
        }

        $redirectTab = $user->user_type === 'CUSTOMER' ? 'customers' : 'staff';

        return redirect()->route('admin.users.index', ['tab' => $redirectTab])
            ->with('success', 'User profile updated successfully.');
    }

    /**
     * Delete User (with Protection)
     */
    public function destroy(User $user)
    {
        if ($user->id === 1 || $user->user_type === 'ADMIN' || $user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Primary Super Administrator cannot be deleted.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->back()->with('success', "User '{$name}' has been deleted.");
    }

    /**
     * 1-Click Approve Customer Account
     */
    public function approve(User $user)
    {
        $user->update(['status' => 'ACTIVE']);
        return redirect()->back()->with('success', "Account for '{$user->name}' ({$user->business_name}) has been approved.");
    }

    /**
     * 1-Click Reject Customer Account
     */
    public function reject(User $user)
    {
        $user->update(['status' => 'REJECTED']);
        return redirect()->back()->with('success', "Account for '{$user->name}' has been marked as rejected.");
    }

    /**
     * 1-Click Toggle Active / Inactive Status
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === 1 || $user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Cannot toggle status of current active administrator.');
        }

        $newStatus = $user->status === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';
        $user->update(['status' => $newStatus]);

        return redirect()->back()->with('success', "Status for '{$user->name}' updated to {$newStatus}.");
    }

    /**
     * Ajax location helper
     */
    public function getStates(Country $country)
    {
        return response()->json($country->states()->where('status', 'ACTIVE')->get());
    }

    /**
     * Ajax location helper
     */
    public function getCities(State $state)
    {
        return response()->json($state->cities()->where('status', 'ACTIVE')->get());
    }
}