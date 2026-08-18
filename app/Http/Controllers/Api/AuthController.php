<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Customer Registration API
    public function register(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users',
            'phone'             => 'required|string|max:20',
            'password'          => 'required|string|min:6',
            'customer_group_id' => 'required|exists:customer_groups,id', // Wholesaler, Retailer, Distributor ID
            'business_name'     => 'nullable|string|max:255',
            'gst_number'        => 'nullable|string|max:50',
            'country_id'        => 'nullable|integer',
            'state_id'          => 'nullable|integer',
            'city_id'           => 'nullable|integer',
            'address'           => 'nullable|string',
        ]);

        // 1. Create User with PENDING status & CUSTOMER user_type
        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'password'          => Hash::make($request->password),
            'user_type'         => 'CUSTOMER',
            'status'            => 'PENDING',
            'customer_group_id' => $request->customer_group_id,
            'business_name'     => $request->business_name,
            'gst_number'        => $request->gst_number,
            'country_id'        => $request->country_id,
            'state_id'          => $request->state_id,
            'city_id'           => $request->city_id,
            'address'           => $request->address,
        ]);

        // 2. Assign 'Customer' Role in user_has_role pivot table
        $customerRole = Role::where('name', 'Customer')->first();
        if ($customerRole) {
            $user->assignRole($customerRole);
        }

        return response()->json([
            'success' => true,
            'message' => 'Registration successful! Your account is pending admin approval.',
            'data'    => $user->load('customerGroup')
        ], 201);
    }

    // Customer Login API
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        // Check if account status is ACTIVE
        if ($user->status !== 'ACTIVE') {
            return response()->json([
                'success' => false,
                'message' => 'Your account status is ' . $user->status . '. Please wait for Super Admin approval.'
            ], 403);
        }

        // Generate Token after successful approval check
        $user->tokens()->delete();
        $token = $user->createToken('customer_auth_token')->plainTextToken;

        return response()->json([
            'success'      => true,
            'message'      => 'Login successful',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'data'         => $user->load('customerGroup')
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Profile data fetched successfully.',
            'data'    => $request->user()->load(['roles', 'customerGroup', 'country', 'state', 'city'])
        ]);
    }

    /**
     * Verify if Sanctum Token is valid
     */
    public function verifyToken(Request $request)
    {
        return response()->json([
            'status'  => true,
            'message' => 'Token is valid.',
            'data'    => [
                'user' => $request->user()
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
