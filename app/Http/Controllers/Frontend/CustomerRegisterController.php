<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CustomerGroup;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerRegisterController extends Controller
{
    public function showForm()
    {
        $countries = Country::orderBy('name')->get();
        $customerGroups = CustomerGroup::orderBy('name')->get();

        return view('frontend.auth.register', compact('countries', 'customerGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'phone'         => 'nullable|string|max:20',
            'password'      => 'required|string|min:6|confirmed',
            'business_name' => 'nullable|string|max:255',
            'gst_number'    => 'nullable|string|max:50',
            'address'       => 'required|string',
            'country_id'    => 'required|exists:countries,id',
            'state_id'      => 'required|exists:states,id',
            'city_id'       => 'required|exists:cities,id',
        ]);

        // Wholesaler customer group fetch karo
        $wholesalerGroup = CustomerGroup::where('name', 'Wholesaler')->first();

        $validated['password']          = Hash::make($validated['password']);
        $validated['user_type']         = 'CUSTOMER';
        $validated['status']            = 'PENDING';
        $validated['customer_group_id'] = $wholesalerGroup->id ?? null;

        $user = User::create($validated);

        // ===== user_has_role table me bhi entry karo =====
        $customerRole = Role::where('name', 'Customer')->first();

        if ($customerRole) {
            $user->roles()->attach($customerRole->id);
        }

        return redirect()->route('login')
            ->with('success', 'Account created successfully! Your account is pending approval. We will notify you within 24 hours.');
    }

    public function getStates($country_id)
    {
        $states = State::where('country_id', $country_id)->orderBy('name')->get();
        return response()->json($states);
    }

    public function getCities($state_id)
    {
        $cities = City::where('state_id', $state_id)->orderBy('name')->get();
        return response()->json($cities);
    }
}