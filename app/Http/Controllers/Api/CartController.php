<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // 1. Fetch Logged-in User's Cart
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();

        if ($user->status !== 'ACTIVE') {
            return response()->json([
                'success' => false,
                'message' => 'Your account is pending approval.'
            ], 403);
        }

        $cartItems = Cart::where('user_id', $user->id)
            ->with(['product.images', 'product.priceTiers', 'variant.stocks'])
            ->get();

        $formattedCart = [];
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $product = $item->product;
            $variant = $item->variant;

            // Check Available Stock for variant
            $variantStock = $variant->stocks->sum('quantity');

            // Calculate Tiered Price based on Cart Quantity
            $unitPrice = $product->priceForUser($user, $item->quantity);

            $itemTotal = (float) $unitPrice * $item->quantity;
            $subtotal += $itemTotal;

            // Image URL
            $image = $product->images->first() 
                ? asset('storage/' . $product->images->first()->image_path) 
                : null;

            $formattedCart[] = [
                'cart_id'            => $item->id,
                'product_id'         => $product->id,
                'product_name'       => $product->name,
                'product_sku'        => $product->sku,
                'image'              => $image,
                'variant' => [
                    'id'          => $variant->id,
                    'size'        => $variant->size,
                    'color'       => $variant->color,
                    'variant_sku' => $variant->variant_sku,
                    'stock'       => $variantStock,
                ],
                'quantity'           => $item->quantity,
                'unit_price'         => (float) $unitPrice,
                'item_total'         => (float) $itemTotal,
                'is_stock_available' => $variantStock >= $item->quantity,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart fetched successfully',
            'data'    => [
                'items'       => $formattedCart,
                'total_items' => count($formattedCart),
                'grand_total' => (float) $subtotal,
            ]
        ]);
    }

    // 2. Add Item / Update Quantity in Cart
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id'         => 'required|exists:products,id',
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity'           => 'required|integer|min:1',
        ]);

        $user = auth('sanctum')->user();

        if ($user->status !== 'ACTIVE') {
            return response()->json([
                'success' => false,
                'message' => 'Account not active. Cannot perform action.'
            ], 403);
        }

        // Validate if variant belongs to product
        $variant = ProductVariant::with('stocks')
            ->where('id', $request->product_variant_id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$variant) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid product variant combination.'
            ], 422);
        }

        // Available stock check
        $availableStock = $variant->stocks->sum('quantity');

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_variant_id', $request->product_variant_id)
            ->first();

        $newQuantity = $request->quantity;
        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
        }

        if ($availableStock < $newQuantity) {
            return response()->json([
                'success' => false,
                'message' => "Only {$availableStock} items available in stock."
            ], 422);
        }

        // Create or Update Cart Entry
        $cart = Cart::updateOrCreate(
            [
                'user_id'            => $user->id,
                'product_variant_id' => $request->product_variant_id,
            ],
            [
                'product_id' => $request->product_id,
                'quantity'   => $newQuantity,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'data'    => $cart
        ]);
    }

    // 3. Remove single item from Cart
    public function removeCartItem($id)
    {
        $user = auth('sanctum')->user();
        $cartItem = Cart::where('user_id', $user->id)->where('id', $id)->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart.'
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully.'
        ]);
    }

    // 4. Clear Full Cart
    public function clearCart()
    {
        $user = auth('sanctum')->user();
        Cart::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully.'
        ]);
    }
}