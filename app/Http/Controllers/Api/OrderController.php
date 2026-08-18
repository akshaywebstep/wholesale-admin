<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $user = auth('sanctum')->user();

        $paginatedOrders = Order::where('user_id', $user->id)
            ->with(['items.variant.product'])
            ->latest()
            ->paginate($request->get('per_page', 10));

        // Response Data Transformation (Unwanted fields remove karne ke liye)
        $cleanOrders = collect($paginatedOrders->items())->map(function ($order) {
            return [
                'id'               => $order->id,
                'order_number'     => $order->order_number,
                'status'           => $order->status,
                'total_amount'     => (float) $order->total_amount,
                'created_at'       => $order->created_at->format('Y-m-d H:i:s'),
                'shipping_address' => $order->shipping_address,
                'items'            => $order->items->map(function ($item) {
                    return [
                        'id'            => $item->id,
                        'product_name'  => $item->variant->product->name ?? null,
                        'variant_sku'   => $item->variant->variant_sku ?? null,
                        'size'          => $item->variant->size ?? null,
                        'color'         => $item->variant->color ?? null,
                        'quantity'      => $item->quantity,
                        'price'         => (float) $item->price,
                        'total'         => (float) ($item->price * $item->quantity),
                    ];
                }),
            ];
        });

        return response()->json([
            'success'      => true,
            'message'      => 'Orders fetched successfully',
            'data'         => $cleanOrders,
            'total_orders' => $paginatedOrders->total(),
            'current_page' => $paginatedOrders->currentPage(),
            'last_page'    => $paginatedOrders->lastPage(),
        ]);
    }

    public function show($id)
    {
        $user = auth('sanctum')->user();

        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['items.variant.product'])
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order details fetched successfully',
            'data'    => $order
        ]);
    }
    public function checkout(Request $request)
    {
        // 1. Validation
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'cart_ids'   => 'nullable|array',
            'cart_ids.*' => 'integer|exists:carts,id',
        ]);

        $user = auth('sanctum')->user();

        if ($user->status !== 'ACTIVE') {
            return response()->json([
                'success' => false,
                'message' => 'Your account is pending approval.'
            ], 403);
        }

        // 2. Fetch Selected Address
        $address = Address::where('id', $request->address_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid delivery address selected.'
            ], 422);
        }

        // 3. Fetch Cart Items (All or Selected cart_ids)
        $cartQuery = Cart::where('user_id', $user->id)
            ->with(['product.priceTiers', 'variant.stocks']);

        if ($request->filled('cart_ids')) {
            $cartQuery->whereIn('id', $request->cart_ids);
        }

        $cartItems = $cartQuery->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No items found in cart for checkout.'
            ], 400);
        }

        // 4. Stock Validation
        foreach ($cartItems as $item) {
            $availableStock = $item->variant->stocks->sum('quantity');
            if ($availableStock < $item->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock for variant ID {$item->product_variant_id}. Available: {$availableStock}"
                ], 422);
            }
        }

        // 5. Database Transaction
        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $itemsToProcess = [];
            $cartIdsToDelete = [];

            foreach ($cartItems as $item) {
                $unitPrice = $item->product->base_price;
                if ($user->customer_group_id) {
                    $tier = $item->product->priceTiers
                        ->where('customer_group_id', $user->customer_group_id)
                        ->first();
                    if ($tier) {
                        $unitPrice = $tier->price;
                    }
                }

                $totalAmount += ((float) $unitPrice * $item->quantity);

                $itemsToProcess[] = [
                    'product_variant_id' => $item->product_variant_id,
                    'quantity'           => $item->quantity,
                    'price'              => $unitPrice,
                ];

                $cartIdsToDelete[] = $item->id;
            }

            // Save Order Record
            $order = Order::create([
                'order_number'     => 'ORD-' . strtoupper(Str::random(8)),
                'user_id'          => $user->id,
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

            // Save Order Items & Deduct Stock
            foreach ($itemsToProcess as $itemData) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_variant_id' => $itemData['product_variant_id'],
                    'quantity'           => $itemData['quantity'],
                    'price'              => $itemData['price'],
                ]);

                $stockRecord = Stock::where('product_variant_id', $itemData['product_variant_id'])->first();
                if ($stockRecord) {
                    $stockRecord->decrement('quantity', $itemData['quantity']);
                }
            }

            // Remove Checked-out Items from Cart
            Cart::whereIn('id', $cartIdsToDelete)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'data'    => [
                    'order_id'     => $order->id,
                    'order_number' => $order->order_number,
                    'total_amount' => (float) $order->total_amount,
                    'status'       => $order->status,
                    'shipping_to'  => $order->shipping_address,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Checkout failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadInvoice(Request $request, $id)
    {
        // Order fetch karein aur confirm karein ki yeh usi user ka order hai
        $order = Order::with(['user', 'items.product', 'items.variant', 'invoice'])
            ->where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$order) {
            return response()->json([
                'status'  => false,
                'message' => 'Order not found.'
            ], 404);
        }

        if (!$order->invoice) {
            return response()->json([
                'status'  => false,
                'message' => 'Invoice not generated for this order yet.'
            ], 400);
        }

        // PDF View render karke download file Stream/Response return karein
        $pdf = Pdf::loadView('admin.orders.invoice-pdf', compact('order'));

        return $pdf->download($order->invoice->invoice_number . '.pdf');
    }
}
