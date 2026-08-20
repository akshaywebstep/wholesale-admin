<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // 1. User check (Logged in & Active status)
        $user = auth('sanctum')->user();
        $canSeePrice = ($user && $user->status === 'ACTIVE');

        // 2. Query with relationships
        $query = Product::with(['category.parent', 'images', 'variants.stocks', 'priceTiers'])
            ->where('is_active', 1);

        // Category Filter (Parent or Sub-category ID)
        if ($request->filled('category_id')) {
            $catId = $request->category_id;
            
            // Check if selected category has sub-categories
            $subCategoryIds = Category::where('parent_id', $catId)->pluck('id')->toArray();

            if (!empty($subCategoryIds)) {
                $subCategoryIds[] = $catId;
                $query->whereIn('category_id', $subCategoryIds);
            } else {
                $query->where('category_id', $catId);
            }
        }

        // Search Filter (Product Name or SKU)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        // 3. Paginate
        $products = $query->paginate(15);

        // 4. Clean Data Mapping
        $items = [];
        foreach ($products as $product) {
            
            // Calculate Total Stock from all variants
            $totalStock = 0;
            foreach ($product->variants as $variant) {
                $totalStock += $variant->stocks->sum('quantity');
            }

            $finalPrice = $product->base_price;

            // Image Mapping
            $images = $product->images->map(function ($img) {
                return asset('storage/' . $img->image_path);
            });

            // Handle Category & SubCategory Mapping
            $categoryData = null;
            if ($product->category) {
                // Agar main product category ke paas parent category hai (yaani product sub-category mein tagged hai)
                if ($product->category->parent) {
                    $categoryData = [
                        'id'          => $product->category->parent->id,
                        'name'        => $product->category->parent->name,
                        'sub_category' => [
                            'id'        => $product->category->id,
                            'name'      => $product->category->name,
                            'parent_id' => $product->category->parent_id,
                        ]
                    ];
                } else {
                    // Agar direct Main Category se link hai
                    $categoryData = [
                        'id'          => $product->category->id,
                        'name'        => $product->category->name,
                        'sub_category' => null
                    ];
                }
            }

            $items[] = [
                'id'          => $product->id,
                'name'        => $product->name,
                'sku'         => $product->sku,
                'category'    => $categoryData,
                'description' => $product->description,
                'show_price'  => $canSeePrice,
                'price'       => $canSeePrice ? (float)$finalPrice : null,
                'base_price'  => $canSeePrice ? (float)$product->base_price : null,
                'stock'       => $totalStock,
                'in_stock'    => $totalStock > 0,
                'images'      => $images,
                'price_tiers' => $product->priceTiers->map(fn($t) => [
                    'min_qty' => $t->min_qty,
                    'max_qty' => $t->max_qty,
                    'price'   => (float) $t->price,
                ]),
                'variants'    => $product->variants->map(function ($v) {
                    return [
                        'id'          => $v->id,
                        'size'        => $v->size,
                        'color'       => $v->color,
                        'variant_sku' => $v->variant_sku,
                        'stock'       => $v->stocks->sum('quantity'),
                    ];
                }),
            ];
        }

        // 5. Clean Response Output
        return response()->json([
            'success'      => true,
            'message'      => 'Products fetched successfully',
            'data'         => $items,
            'pagination'   => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'per_page'     => $products->perPage(),
                'total'        => $products->total(),
            ]
        ]);
    }
}