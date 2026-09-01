<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuickOrderController extends Controller
{
    private function getAuthenticatedUser()
    {
        return Auth::guard('customer')->user() ?: Auth::guard('web')->user();
    }

    /**
     * Show Quick Order & CSV Upload Portal
     */
    public function index()
    {
        $categories = Category::where('status', 'ACTIVE')->orderBy('name')->get();
        $totalActiveProducts = Product::where('is_active', 1)->count();
        $sampleProducts = Product::where('is_active', 1)->with('unit')->take(8)->get();

        return view('frontend.shop.quick-order', compact(
            'categories',
            'totalActiveProducts',
            'sampleProducts'
        ));
    }

    /**
     * Live Autocomplete Search by SKU, Barcode, or Name
     */
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $user = $this->getAuthenticatedUser();
        $words = array_filter(explode(' ', $query));

        $products = Product::where('is_active', 1)
            ->where(function ($q) use ($query, $words) {
                $q->where('sku', 'LIKE', "%{$query}%")
                  ->orWhere('name', 'LIKE', "%{$query}%");

                if (count($words) > 1) {
                    $q->orWhere(function ($subQ) use ($words) {
                        foreach ($words as $word) {
                            $subQ->where(function ($wQ) use ($word) {
                                $wQ->where('name', 'LIKE', "%{$word}%")
                                   ->orWhere('sku', 'LIKE', "%{$word}%");
                            });
                        }
                    });
                }
            })
            ->with(['variants.stocks', 'unit', 'priceTiers', 'images'])
            ->take(15)
            ->get();

        $results = $products->map(function ($product) use ($user) {
            $stock = $product->total_stock;
            $unitName = $product->unit->name ?? 'Unit';

            return [
                'id'          => $product->id,
                'name'        => $product->name,
                'sku'         => $product->sku,
                'base_price'  => (float) $product->base_price,
                'stock'       => $stock,
                'unit'        => $unitName,
                'image'       => $product->featured_image_url,
                'price_tiers' => $product->priceTiers->map(function ($t) {
                    return [
                        'min_qty' => $t->min_qty,
                        'max_qty' => $t->max_qty,
                        'price'   => (float) $t->price,
                    ];
                })->values(),
            ];
        });

        return response()->json($results);
    }

    /**
     * Calculate Tier Price for a Specific Product & Qty
     */
    public function calculatePrice(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $user = $this->getAuthenticatedUser();
        $product = Product::with(['priceTiers', 'variants.stocks'])->findOrFail($request->product_id);
        $qty = (int) $request->quantity;

        $unitPrice = $product->priceForUser($user, $qty);
        $basePrice = (float) $product->base_price;
        $savings = max(0, $basePrice - $unitPrice);
        $lineTotal = round($unitPrice * $qty, 2);
        $stock = $product->total_stock;

        return response()->json([
            'product_id'     => $product->id,
            'quantity'       => $qty,
            'unit_price'     => $unitPrice,
            'base_price'     => $basePrice,
            'savings_unit'   => round($savings, 2),
            'line_total'     => $lineTotal,
            'stock'          => $stock,
            'in_stock'       => $stock >= $qty,
            'tier_discount'  => $savings > 0,
        ]);
    }

    /**
     * Parse & Validate Uploaded Excel / CSV Order Sheet
     */
    public function uploadCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:4096',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $user = $this->getAuthenticatedUser();

        $rows = array_map('str_getcsv', file($path));

        if (empty($rows)) {
            return response()->json([
                'success' => false,
                'message' => 'The uploaded file is empty.',
            ], 422);
        }

        // Determine column indexes
        $header = array_map(function ($col) {
            return strtolower(trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $col)));
        }, $rows[0]);

        $skuIdx = -1;
        $qtyIdx = -1;

        foreach ($header as $i => $h) {
            $cleaned = str_replace(['_', '-', ' '], '', $h);
            if (in_array($cleaned, ['sku', 'itemcode', 'productsku', 'code', 'barcode', 'item', 'itemnumber'])) {
                $skuIdx = $i;
            } elseif (in_array($cleaned, ['orderquantity', 'quantity', 'qty', 'units', 'count', 'amount', 'orderqty', 'orderquantityunits'])) {
                $qtyIdx = $i;
            }
        }

        // If no explicit header matches, default column 0 = SKU, column 1 = Qty
        $startRow = 0;
        if ($skuIdx !== -1 && $qtyIdx !== -1) {
            $startRow = 1; // skip header row
        } else {
            $skuIdx = 0;
            $qtyIdx = 1;
            // Check if first row is non-numeric quantity (a header)
            if (isset($rows[0][1]) && !is_numeric(trim($rows[0][1]))) {
                $startRow = 1;
            }
        }

        $validItems = [];
        $invalidItems = [];
        $grandTotal = 0;
        $totalQty = 0;

        for ($i = $startRow; $i < count($rows); $i++) {
            $row = $rows[$i];
            if (empty($row) || !isset($row[$skuIdx])) {
                continue;
            }

            $rawSku = trim($row[$skuIdx]);
            if (empty($rawSku)) {
                continue;
            }

            $rawQty = isset($row[$qtyIdx]) ? (int) trim($row[$qtyIdx]) : 1;
            if ($rawQty <= 0) {
                $rawQty = 1;
            }

            // Case-insensitive SKU lookup
            $product = Product::where('is_active', 1)
                ->where(function ($q) use ($rawSku) {
                    $q->where('sku', $rawSku)
                      ->orWhereRaw('LOWER(sku) = ?', [strtolower($rawSku)]);
                })
                ->with(['priceTiers', 'variants.stocks', 'unit', 'category'])
                ->first();

            if (!$product) {
                $invalidItems[] = [
                    'row_number' => $i + 1,
                    'sku'        => $rawSku,
                    'quantity'   => $rawQty,
                    'reason'     => 'SKU not found in active catalog',
                ];
                continue;
            }

            $stock = $product->total_stock;
            $unitPrice = $product->priceForUser($user, $rawQty);
            $basePrice = (float) $product->base_price;
            $lineTotal = round($unitPrice * $rawQty, 2);

            $hasStockIssue = $stock < $rawQty;

            $validItems[] = [
                'row_number'      => $i + 1,
                'product_id'      => $product->id,
                'name'            => $product->name,
                'sku'             => $product->sku,
                'image'           => $product->featured_image_url,
                'unit'            => $product->unit->name ?? 'Unit',
                'category'        => $product->category->name ?? 'Wholesale',
                'requested_qty'   => $rawQty,
                'available_stock' => $stock,
                'unit_price'      => $unitPrice,
                'base_price'      => $basePrice,
                'line_total'      => $lineTotal,
                'stock_warning'   => $hasStockIssue,
                'tier_discount'   => $unitPrice < $basePrice,
            ];

            $grandTotal += $lineTotal;
            $totalQty += $rawQty;
        }

        return response()->json([
            'success'            => true,
            'total_rows_parsed'  => count($validItems) + count($invalidItems),
            'valid_count'        => count($validItems),
            'invalid_count'      => count($invalidItems),
            'grand_total'        => round($grandTotal, 2),
            'total_quantity'     => $totalQty,
            'valid_items'        => $validItems,
            'invalid_items'      => $invalidItems,
        ]);
    }

    /**
     * Download Sample CSV Order Template (Formatted with realistic fields)
     */
    public function downloadTemplate(): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="wholesale_b2b_purchase_order.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        // Fetch sample real SKUs from database
        $sampleProducts = Product::where('is_active', 1)->with(['category', 'unit'])->take(6)->get();

        $callback = function () use ($sampleProducts) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Microsoft Excel auto-encoding
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Professional B2B Order Sheet Headers
            fputcsv($handle, [
                'SKU',
                'Order_Quantity',
                'Product_Name',
                'Category',
                'Package_Unit',
                'Base_Price_USD',
                'Store_Notes_Or_Branch'
            ]);

            if ($sampleProducts->isNotEmpty()) {
                foreach ($sampleProducts as $p) {
                    $qty = rand(15, 40);
                    fputcsv($handle, [
                        $p->sku,
                        $qty,
                        $p->name,
                        $p->category->name ?? 'Wholesale',
                        $p->unit->name ?? 'Pack',
                        number_format((float)$p->base_price, 2, '.', ''),
                        'Retail store shelf restock'
                    ]);
                }
            } else {
                fputcsv($handle, ['VAP-ELF-0001', 20, 'Elf Bar BC5000 Disposable Vape Pod (5000 Puffs)', 'Vapes', 'Pack', '650.00', 'Front Display']);
                fputcsv($handle, ['SHI-ALF-250G', 15, 'Al Fakher Premium Shisha Molasses 250g', 'Hookah', 'Box', '450.00', 'Main Counter']);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * 1-Click Bulk Add Valid Items into User Cart
     */
    public function addBulk(Request $request)
    {
        $customer = $this->getAuthenticatedUser();

        if (!$customer) {
            return response()->json([
                'success'  => false,
                'message'  => 'Please login with your customer account to add items to cart.',
                'redirect' => route('login'),
            ], 401);
        }

        $items = $request->input('items', []);

        if (empty($items) || !is_array($items)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid items provided to add to cart.',
            ], 422);
        }

        $addedCount = 0;
        $totalUnits = 0;

        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                $productId = $item['product_id'] ?? null;
                $quantity  = (int) ($item['quantity'] ?? 1);

                if (!$productId || $quantity <= 0) {
                    continue;
                }

                $product = Product::find($productId);
                if (!$product || !$product->is_active) {
                    continue;
                }

                // Find or assign default variant
                $variantId = $item['product_variant_id'] ?? null;
                if (!$variantId) {
                    $defaultVariant = ProductVariant::where('product_id', $productId)->first();
                    $variantId = $defaultVariant ? $defaultVariant->id : null;
                }

                $cartItem = Cart::where('user_id', $customer->id)
                    ->where('product_id', $productId)
                    ->where('product_variant_id', $variantId)
                    ->first();

                if ($cartItem) {
                    $cartItem->quantity += $quantity;
                    $cartItem->save();
                } else {
                    Cart::create([
                        'user_id'            => $customer->id,
                        'product_id'         => $productId,
                        'product_variant_id' => $variantId,
                        'quantity'           => $quantity,
                    ]);
                }

                $addedCount++;
                $totalUnits += $quantity;
            }

            DB::commit();

            $cartCount = Cart::where('user_id', $customer->id)->count();

            return response()->json([
                'success'     => true,
                'message'     => "Successfully added {$addedCount} products ({$totalUnits} units) to your wholesale cart.",
                'cart_count'  => $cartCount,
                'cart_url'    => route('cart.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process bulk cart addition. Please try again.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
