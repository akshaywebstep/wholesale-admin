<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    // List all addresses of logged-in user
    public function index()
    {
        $addresses = Address::where('user_id', auth('sanctum')->id())->get();

        return response()->json([
            'success' => true,
            'data'    => $addresses
        ]);
    }

    // Save new address
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string',
            'phone'          => 'required|string',
            'address_line_1' => 'required|string',
            'city'           => 'required|string',
            'state'          => 'required|string',
            'pincode'        => 'required|string',
        ]);

        $address = Address::create([
            'user_id'        => auth('sanctum')->id(),
            'name'           => $request->name,
            'phone'          => $request->phone,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city'           => $request->city,
            'state'          => $request->state,
            'pincode'        => $request->pincode,
            'country'        => $request->country ?? 'India',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Address saved successfully',
            'data'    => $address
        ]);
    }
}