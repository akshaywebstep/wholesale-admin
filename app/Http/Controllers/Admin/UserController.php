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

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $countries = Country::where('status', 'ACTIVE')->get();
        $customerGroups = CustomerGroup::where('status', 'ACTIVE')->get();

        $superAdminTaken = Role::where('name', 'Super Admin')
            ->whereHas('users') // roles model mein users() relation chahiye hoga
            ->exists();

        $roles = Role::where('status', 'ACTIVE')
            ->when($superAdminTaken, function ($query) {
                $query->where('name', '!=', 'Super Admin');
            })
            ->get();

        return view('admin.users.create', compact('countries', 'customerGroups', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'user_type' => 'required|in:CUSTOMER,STAFF',
            'business_name' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50',
            'address' => 'required_if:user_type,CUSTOMER|nullable|string',
            'status' => 'required|in:PENDING,ACTIVE,INACTIVE,REJECTED',
            'customer_group_id' => 'required_if:user_type,CUSTOMER|nullable|exists:customer_groups,id',
            'country_id' => 'required_if:user_type,CUSTOMER|nullable|exists:countries,id',
            'state_id' => 'required_if:user_type,CUSTOMER|nullable|exists:states,id',
            'city_id' => 'required_if:user_type,CUSTOMER|nullable|exists:cities,id',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $roleId = $validated['role_id'] ?? null;
        unset($validated['role_id']);

        $user = User::create($validated);

        if ($roleId) {
            $user->roles()->attach($roleId);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $countries = Country::where('status', 'ACTIVE')->get();
        $states = State::where('country_id', $user->country_id)->get();
        $cities = City::where('state_id', $user->state_id)->get();
        $customerGroups = CustomerGroup::where('status', 'ACTIVE')->get();
        $assignedRoleId = $user->roles()->first()?->id;

        $superAdminTaken = Role::where('name', 'Super Admin')
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', '!=', $user->id);
            })
            ->exists();

        $roles = Role::where('status', 'ACTIVE')
            ->when($superAdminTaken, function ($query) {
                $query->where('name', '!=', 'Super Admin');
            })
            ->get();

        return view('admin.users.edit', compact('user', 'countries', 'states', 'cities', 'customerGroups', 'roles', 'assignedRoleId'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'user_type' => 'required|in:CUSTOMER,STAFF',
            'business_name' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50',
            'address' => 'required_if:user_type,CUSTOMER|nullable|string',
            'status' => 'required|in:PENDING,ACTIVE,INACTIVE,REJECTED',
            'customer_group_id' => 'required_if:user_type,CUSTOMER|nullable|exists:customer_groups,id',
            'country_id' => 'required_if:user_type,CUSTOMER|nullable|exists:countries,id',
            'state_id' => 'required_if:user_type,CUSTOMER|nullable|exists:states,id',
            'city_id' => 'required_if:user_type,CUSTOMER|nullable|exists:cities,id',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $roleId = $validated['role_id'] ?? null;
        unset($validated['role_id']);

        $user->update($validated);

        if ($roleId) {
            $user->roles()->sync([$roleId]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function approve(User $user)
    {
        $user->update(['status' => 'ACTIVE']);
        return back()->with('success', $user->name . ' approved.');
    }

    public function reject(User $user)
    {
        $user->update(['status' => 'REJECTED']);
        return back()->with('success', $user->name . ' rejected.');
    }

    public function getStates(Country $country)
    {
        return $country->states()->where('status', 'ACTIVE')->get();
    }

    public function getCities(State $state)
    {
        return $state->cities()->where('status', 'ACTIVE')->get();
    }
}
