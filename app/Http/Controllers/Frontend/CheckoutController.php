<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return redirect()->route('login')->with('error', 'Please login to proceed.');
        }

        $cartItems = Cart::where('user_id', $customer->id)
            ->with(['product.priceTiers', 'product.images', 'variant'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $addresses = Address::where('user_id', $customer->id)->latest()->get();

        $total = 0;
        foreach ($cartItems as $item) {
            $price = $item->product->base_price;
            if ($customer->customer_group_id) {
                $tier = $item->product->priceTiers
                    ->where('customer_group_id', $customer->customer_group_id)
                    ->first();
                if ($tier) $price = $tier->price;
            }
            $total += ((float) $price * $item->quantity);
        }

        return view('frontend.checkout.index', compact('cartItems', 'addresses', 'total', 'customer'));
    }

    public function process(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect()->route('login')->with('error', 'Please login.');
        }

        if ($customer->status !== 'ACTIVE') {
            return redirect()->back()->with('error', 'Your account is pending approval.');
        }

        // Address Validation & Handling
        if ($request->input('address_choice') === 'new') {
            $request->validate([
                'name'           => 'required|string|max:255',
                'phone'          => 'required|string|max:20',
                'address_line_1' => 'required|string|max:255',
                'address_line_2' => 'nullable|string|max:255',
                'city'           => 'required|string|max:100',
                'state'          => 'required|string|max:100',
                'pincode'        => 'required|string|max:20',
                'country'        => 'nullable|string|max:100',
            ]);

            $address = Address::create([
                'user_id'        => $customer->id,
                'name'           => $request->name,
                'phone'          => $request->phone,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'city'           => $request->city,
                'state'          => $request->state,
                'pincode'        => $request->pincode,
                'country'        => $request->country ?? 'India',
            ]);
        } else {
            $request->validate([
                'selected_address_id' => 'required|exists:addresses,id',
            ]);

            $address = Address::where('id', $request->selected_address_id)
                ->where('user_id', $customer->id)
                ->firstOrFail();
        }

        // Cart & Stock verification
        $cartItems = Cart::where('user_id', $customer->id)->with(['product.priceTiers', 'variant.stocks'])->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty.');
        }

        foreach ($cartItems as $item) {
            $available = $item->variant && $item->variant->stocks ? $item->variant->stocks->sum('quantity') : 0;
            if ($available < $item->quantity) {
                return redirect()->back()->with('error', "Stock low for {$item->product->name}.");
            }
        }

        // Order Creation Transaction
        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $itemsToProcess = [];

            // 1. Cart ke first variant se Warehouse ID fetch karein jisme stock available ho
            $firstCartItem = $cartItems->first();
            $availableStock = Stock::where('product_variant_id', $firstCartItem->product_variant_id)
                ->where('quantity', '>', 0)
                ->first();

            $warehouseId = $availableStock ? $availableStock->warehouse_id : null;

            foreach ($cartItems as $item) {
                $unitPrice = $item->product->base_price;
                if ($customer->customer_group_id) {
                    $tier = $item->product->priceTiers->where('customer_group_id', $customer->customer_group_id)->first();
                    if ($tier) $unitPrice = $tier->price;
                }

                $totalAmount += ((float)$unitPrice * $item->quantity);
                $itemsToProcess[] = [
                    'product_variant_id' => $item->product_variant_id,
                    'quantity'           => $item->quantity,
                    'price'              => $unitPrice,
                ];
            }

            // 2. Order me warehouse_id pass karein
            $order = Order::create([
                'order_number'     => 'ORD-' . strtoupper(Str::random(8)),
                'user_id'          => $customer->id,
                'warehouse_id'     => $warehouseId, // Selected Warehouse ID
                'address_id'       => $address->id,
                'shipping_address' => [
                    'name'           => $address->name,
                    'phone'          => $address->phone,
                    'address_line_1' => $address->address_line_1,
                    'address_line_2' => $address->address_line_2,
                    'city'           => $address->city,
                    'state'          => $address->state,
                    'pincode'        => $address->pincode,
                    'country'        => $address->country,
                ],
                'total_amount'     => $totalAmount,
                'status'           => 'PENDING',
            ]);

            // 3. Items insert karein aur usi warehouse se stock minus karein
            foreach ($itemsToProcess as $itemData) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_variant_id' => $itemData['product_variant_id'],
                    'quantity'           => $itemData['quantity'],
                    'price'              => $itemData['price'],
                ]);

                // Usi specific warehouse se stock decrement karein
                $stockQuery = Stock::where('product_variant_id', $itemData['product_variant_id']);
                if ($warehouseId) {
                    $stockQuery->where('warehouse_id', $warehouseId);
                }
                
                $stockRecord = $stockQuery->first();
                if ($stockRecord) {
                    $stockRecord->decrement('quantity', $itemData['quantity']);
                }
            }

            Cart::where('user_id', $customer->id)->delete();
            DB::commit();

            return redirect()->route('checkout.success', $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    public function success($id)
    {
        $customer = Auth::guard('customer')->user();
        $order = Order::where('id', $id)->where('user_id', $customer->id)->with('items.variant.product')->firstOrFail();
        return view('frontend.checkout.success', compact('order'));
    }
}