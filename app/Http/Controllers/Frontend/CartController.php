<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Show the full cart page.
     */
    public function index()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login')->with('error', 'Please login to view your cart.');
        }

        $customer = Auth::guard('customer')->user();

        $cartItems = Cart::with(['product.images', 'variant'])
            ->where('user_id', $customer->id)
            ->latest()
            ->get();

        $total = 0;
        foreach ($cartItems as $item) {
            if ($item->product) {
                $price = $item->product->priceForUser($customer, $item->quantity);
                $total += $price * $item->quantity;
            }
        }

        return view('frontend.cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add a product to the cart (AJAX).
     */
    public function add(Request $request)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json([
                'success'  => false,
                'message'  => 'Please login to add items to your cart.',
                'redirect' => route('login'),
            ], 401);
        }

        $request->validate([
            'product_id'         => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity'           => 'nullable|integer|min:1',
        ]);

        $customer = Auth::guard('customer')->user();
        $quantity = (int) $request->input('quantity', 1);

        // Agar variant ID request me nahi hai, to product_variants table se default fetch karein
        $variantId = $request->product_variant_id;
        if (!$variantId) {
            $defaultVariant = ProductVariant::where('product_id', $request->product_id)->first();
            $variantId = $defaultVariant ? $defaultVariant->id : null;
        }

        // Existing item check karein
        $cartItem = Cart::where('user_id', $customer->id)
            ->where('product_id', $request->product_id)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id'            => $customer->id,
                'product_id'         => $request->product_id,
                'product_variant_id' => $variantId,
                'quantity'           => $quantity,
            ]);
        }

        // Unique product items count (Row count)
        $cartCount = Cart::where('user_id', $customer->id)->count();

        return response()->json([
            'success'    => true,
            'message'    => 'Added to cart successfully.',
            'cart_count' => (int) $cartCount,
        ]);
    }

    /**
     * Update the quantity of a cart row (AJAX).
     */
    public function update(Request $request)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 401);
        }

        $request->validate([
            'cart_id'  => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $customer = Auth::guard('customer')->user();

        $cartItem = Cart::where('id', $request->cart_id)
            ->where('user_id', $customer->id)
            ->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        $lineTotal = 0;
        if ($cartItem->product) {
            $price = $cartItem->product->priceForUser($customer, $cartItem->quantity);
            $lineTotal = $price * $cartItem->quantity;
        }

        $cartTotal = $this->calculateCartTotal($customer);
        $cartCount = Cart::where('user_id', $customer->id)->count();

        return response()->json([
            'success'    => true,
            'line_total' => number_format($lineTotal, 2),
            'cart_total' => number_format($cartTotal, 2),
            'cart_count' => (int) $cartCount,
        ]);
    }

    /**
     * Remove a cart row (AJAX).
     */
    public function remove(Request $request)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 401);
        }

        $request->validate([
            'cart_id' => 'required|exists:carts,id',
        ]);

        $customer = Auth::guard('customer')->user();

        Cart::where('id', $request->cart_id)
            ->where('user_id', $customer->id)
            ->delete();

        $cartTotal = $this->calculateCartTotal($customer);
        $cartCount = Cart::where('user_id', $customer->id)->count();

        return response()->json([
            'success'    => true,
            'cart_total' => number_format($cartTotal, 2),
            'cart_count' => (int) $cartCount,
        ]);
    }

    /**
     * Return just the current cart count.
     */
    public function count()
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json(['cart_count' => 0]);
        }

        $customer  = Auth::guard('customer')->user();
        $cartCount = Cart::where('user_id', $customer->id)->count();

        return response()->json(['cart_count' => (int) $cartCount]);
    }

    /**
     * Helper to recompute the grand total for a customer's cart.
     */
    private function calculateCartTotal($customer)
    {
        $total = 0;

        $items = Cart::with('product')->where('user_id', $customer->id)->get();

        foreach ($items as $item) {
            if ($item->product) {
                $price  = $item->product->priceForUser($customer, $item->quantity);
                $total += $price * $item->quantity;
            }
        }

        return $total;
    }
}