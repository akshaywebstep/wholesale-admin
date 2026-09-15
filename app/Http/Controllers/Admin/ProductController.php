<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductPriceTier;
use App\Models\ProductVariant;
use App\Models\Stock;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private function saveAndSyncImage($file, string $folder = 'products'): string
    {
        $path = $file->store($folder, 'public');
        $storageSource = storage_path('app/public/' . $path);
        $publicTarget = public_path('storage/' . $path);

        \Illuminate\Support\Facades\File::ensureDirectoryExists(dirname($publicTarget));
        if (\Illuminate\Support\Facades\File::exists($storageSource)) {
            \Illuminate\Support\Facades\File::copy($storageSource, $publicTarget);
        }

        return $path;
    }

    private function getDefaultWarehouseId(): int
    {
        $warehouse = Warehouse::where('status', 'ACTIVE')->first() ?? Warehouse::first();
        if (!$warehouse) {
            $warehouse = Warehouse::create([
                'name'     => 'Main Warehouse',
                'code'     => 'MAIN',
                'location' => 'Primary Store',
                'status'   => 'ACTIVE',
            ]);
        }
        return $warehouse->id;
    }

    public function index(Request $request)
    {
        $query = Product::with(['images', 'category', 'unit', 'variants.stocks', 'priceTiers']);

        // Search by Name or SKU
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter by Status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by Stock Status
        if ($request->filled('stock_status')) {
            $stockStatus = $request->input('stock_status');
            if ($stockStatus === 'out') {
                $query->whereDoesntHave('variants.stocks', function ($q) {
                    $q->where('quantity', '>', 0);
                });
            } elseif ($stockStatus === 'low') {
                $query->whereHas('variants.stocks', function ($q) {
                    $q->where('quantity', '>', 0)->whereColumn('quantity', '<=', 'threshold');
                });
            } elseif ($stockStatus === 'in') {
                $query->whereHas('variants.stocks', function ($q) {
                    $q->whereColumn('quantity', '>', 'threshold');
                });
            }
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        $categories = Category::whereNull('parent_id')
            ->where('status', 'ACTIVE')
            ->with(['children' => fn($q) => $q->where('status', 'ACTIVE')])
            ->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function exportExcel(Request $request)
    {
        $query = Product::with(['images', 'category.parent', 'unit', 'variants.stocks', 'priceTiers']);

        // Search by Name or SKU
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter by Status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $products = $query->orderBy('id', 'asc')->get();
        $filename = 'wholesale_products_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM so Microsoft Excel correctly renders all special characters
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // CSV Column Headers
            fputcsv($file, [
                'Product ID',
                'SKU / Item Code',
                'Product Name',
                'Department (Main)',
                'Sub-Category',
                'Base Price ($)',
                'Total Stock (Units)',
                'Stock Status',
                'Product Status',
                'Wholesale Volume Tier Pricing',
                'Variants Count',
                'Primary Image URL',
                'Description'
            ]);

            foreach ($products as $p) {
                $mainDept = $p->category ? ($p->category->parent ? $p->category->parent->name : $p->category->name) : 'General';
                $subCat = ($p->category && $p->category->parent) ? $p->category->name : '';
                $tiersStr = $p->priceTiers->map(fn($t) => $t->min_qty . '+: $' . number_format((float)$t->price, 2))->join(' | ');
                $imgUrl = $p->images->first() ? $p->images->first()->url : '';
                $stockStatus = $p->total_stock > 0 ? 'In Stock' : 'Out of Stock';

                fputcsv($file, [
                    $p->id,
                    $p->sku,
                    $p->name,
                    $mainDept,
                    $subCat,
                    number_format((float)$p->base_price, 2),
                    $p->total_stock,
                    $stockStatus,
                    $p->is_active ? 'Active' : 'Inactive',
                    $tiersStr ?: 'Standard',
                    $p->variants->count(),
                    $imgUrl,
                    strip_tags($p->description ?? '')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')
            ->where('status', 'ACTIVE')
            ->with(['children' => fn($q) => $q->where('status', 'ACTIVE')])
            ->get();

        $units = Unit::all();

        return view('admin.products.create', compact('categories', 'units'));
    }

    public function show(Product $product)
    {
        $product->load(['images', 'category.parent', 'unit', 'variants.stocks.warehouse', 'priceTiers']);

        return view('admin.products.show', compact('product'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'sku'          => 'nullable|string|max:100|unique:products,sku',
            'category_id'  => 'required|exists:categories,id',
            'base_price'   => 'required|numeric|min:0',
            'unit_id'      => 'required|exists:units,id',
            'weight'       => 'nullable|numeric|min:0',
            'description'  => 'nullable|string',
            'is_active'    => 'nullable|boolean',

            'images'       => 'nullable|array',
            'images.*'     => 'nullable|file|mimes:jpeg,png,jpg,webp,avif|max:3072',

            'has_variants'             => 'required|in:0,1',
            'variants'                 => 'required_if:has_variants,1|array',
            'variants.*.size'          => 'nullable|string|max:50',
            'variants.*.color'         => 'nullable|string|max:50',
            'variants.*.variant_sku'   => 'required_if:has_variants,1|string|max:100|distinct',
            'variants.*.quantity'      => 'nullable|integer|min:0',
            'variants.*.threshold'     => 'nullable|integer|min:0',

            'single_quantity'          => 'required_if:has_variants,0|nullable|integer|min:0',
            'single_threshold'         => 'required_if:has_variants,0|nullable|integer|min:0',

            'price_tiers'              => 'nullable|array',
            'price_tiers.*.min_qty'    => 'required_with:price_tiers|integer|min:1',
            'price_tiers.*.max_qty'    => 'nullable|integer|gte:price_tiers.*.min_qty',
            'price_tiers.*.price'      => 'required_with:price_tiers|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $defaultWarehouseId = $this->getDefaultWarehouseId();

            $sku = !empty($validated['sku'])
                ? strtoupper(trim($validated['sku']))
                : $this->generateSku($validated['category_id'], $validated['name']);

            $product = Product::create([
                'name'        => $validated['name'],
                'sku'         => $sku,
                'description' => $validated['description'] ?? null,
                'category_id' => $validated['category_id'],
                'unit_id'     => $validated['unit_id'],
                'weight'      => $validated['weight'] ?? null,
                'base_price'  => $validated['base_price'],
                'is_active'   => $request->has('is_active'),
            ]);

            // ---- Images Upload ----
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    if ($imageFile && $imageFile->isValid()) {
                        $path = $this->saveAndSyncImage($imageFile, 'products');
                        $product->images()->create([
                            'image_path' => $path,
                        ]);
                    }
                }
            }

            // ---- Variants + Stock ----
            if ($request->input('has_variants') == '1' && $request->has('variants')) {
                foreach ($request->input('variants') as $variantData) {
                    if (empty($variantData['variant_sku'])) continue;

                    $variant = $product->variants()->create([
                        'size'        => !empty($variantData['size']) ? trim($variantData['size']) : 'Standard',
                        'color'       => !empty($variantData['color']) ? trim($variantData['color']) : null,
                        'variant_sku' => strtoupper(trim($variantData['variant_sku'])),
                    ]);

                    $variant->stocks()->create([
                        'warehouse_id' => $defaultWarehouseId,
                        'quantity'     => $variantData['quantity'] ?? 0,
                        'threshold'    => $variantData['threshold'] ?? 5,
                    ]);
                }
            } else {
                // Single Variant
                $variant = $product->variants()->create([
                    'size'        => 'Single',
                    'color'       => null,
                    'variant_sku' => $product->sku,
                ]);

                $variant->stocks()->create([
                    'warehouse_id' => $defaultWarehouseId,
                    'quantity'     => $request->input('single_quantity', 0),
                    'threshold'    => $request->input('single_threshold', 5),
                ]);
            }

            // ---- Wholesale Price Tiers (Quantity-Based) ----
            if ($request->has('price_tiers')) {
                foreach ($request->input('price_tiers') as $tier) {
                    if (!empty($tier['min_qty']) && isset($tier['price']) && $tier['price'] !== '') {
                        $product->priceTiers()->create([
                            'min_qty' => (int) $tier['min_qty'],
                            'max_qty' => !empty($tier['max_qty']) ? (int) $tier['max_qty'] : null,
                            'price'   => (float) $tier['price'],
                        ]);
                    }
                }
            }

            return redirect()->route('admin.products.edit', $product)
                ->with('success', 'Product created successfully with variants, inventory, and wholesale bulk tiers.');
        });
    }

    public function edit(Product $product)
    {
        $categories = Category::whereNull('parent_id')
            ->where('status', 'ACTIVE')
            ->with(['children' => fn($q) => $q->where('status', 'ACTIVE')])
            ->get();

        $units = Unit::all();

        $product->load([
            'category',
            'unit',
            'variants.stocks',
            'images',
            'priceTiers',
        ]);

        return response()
            ->view('admin.products.edit', compact('product', 'categories', 'units'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string|max:100|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'unit_id'     => 'required|exists:units,id',
            'weight'      => 'nullable|numeric|min:0',
            'base_price'  => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sku'] = strtoupper(trim($validated['sku']));

        $product->update([
            'name'        => $validated['name'],
            'sku'         => $validated['sku'],
            'category_id' => $validated['category_id'],
            'unit_id'     => $validated['unit_id'],
            'weight'      => $validated['weight'] ?? null,
            'base_price'  => $validated['base_price'],
            'description' => $validated['description'] ?? null,
            'is_active'   => $validated['is_active'],
        ]);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'General product specifications updated.');
    }

    public function destroy(Product $product)
    {
        // Delete related images from storage AND public/storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $publicFile = public_path('storage/' . $image->image_path);
            if (\Illuminate\Support\Facades\File::exists($publicFile)) {
                \Illuminate\Support\Facades\File::delete($publicFile);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    // ==========================================
    // SUB-ACTIONS FOR PRODUCT EDIT TAB MANAGERS
    // ==========================================

    public function storeImage(Request $request, Product $product)
    {
        $request->validate([
            'images'   => 'required|array',
            'images.*' => 'required|file|mimes:jpeg,png,jpg,webp,avif|max:5120',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $path = $this->saveAndSyncImage($file, 'products');
                    $product->images()->create([
                        'image_path' => $path,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Images uploaded successfully.');
    }

    public function destroyImage(Request $request, ...$args)
    {
        // When route is products/{product}/images/{image}, $args has [$product, $image].
        // When route is product-images/{image}, $args has [$image].
        // In both cases, the target image is ALWAYS the last argument!
        $imageArg = end($args);
        
        $image = null;
        if ($imageArg instanceof ProductImage) {
            $image = $imageArg;
        } elseif (is_numeric($imageArg)) {
            $image = ProductImage::find($imageArg);
        }

        if (!$image) {
            return back()->with('error', 'Image not found.');
        }

        $productId = $image->product_id;

        // Delete from BOTH storage/app/public and public/storage
        Storage::disk('public')->delete($image->image_path);
        $publicFile = public_path('storage/' . $image->image_path);
        if (\Illuminate\Support\Facades\File::exists($publicFile)) {
            \Illuminate\Support\Facades\File::delete($publicFile);
        }

        $image->delete();

        return redirect()->route('admin.products.edit', $productId)
            ->with('success', 'Image removed successfully.');
    }

    public function storeVariant(Request $request, Product $product)
    {
        $validated = $request->validate([
            'size'        => 'nullable|string|max:50',
            'color'       => 'nullable|string|max:50',
            'variant_sku' => 'required|string|max:100|unique:product_variants,variant_sku',
            'quantity'    => 'nullable|integer|min:0',
            'threshold'   => 'nullable|integer|min:0',
        ]);

        $defaultWarehouseId = $this->getDefaultWarehouseId();

        $variant = $product->variants()->create([
            'size'        => !empty($validated['size']) ? trim($validated['size']) : 'Standard',
            'color'       => !empty($validated['color']) ? trim($validated['color']) : null,
            'variant_sku' => strtoupper(trim($validated['variant_sku'])),
        ]);

        $variant->stocks()->create([
            'warehouse_id' => $defaultWarehouseId,
            'quantity'     => $validated['quantity'] ?? 0,
            'threshold'    => $validated['threshold'] ?? 5,
        ]);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'New variant added with inventory levels.');
    }

    public function destroyVariant(Request $request, ...$args)
    {
        $variantArg = end($args);
        $variant = null;

        if ($variantArg instanceof ProductVariant) {
            $variant = $variantArg;
        } elseif (is_numeric($variantArg)) {
            $variant = ProductVariant::find($variantArg);
        }

        if (!$variant) {
            return back()->with('error', 'Variant not found.');
        }

        $productId = $variant->product_id;
        $variant->delete();

        return redirect()->route('admin.products.edit', $productId)
            ->with('success', 'Variant removed successfully.');
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stocks'              => 'required|array',
            'stocks.*.stock_id'   => 'nullable|exists:stock,id',
            'stocks.*.variant_id' => 'required|exists:product_variants,id',
            'stocks.*.quantity'   => 'required|integer|min:0',
            'stocks.*.threshold'  => 'required|integer|min:0',
        ]);

        $defaultWarehouseId = $this->getDefaultWarehouseId();

        foreach ($request->stocks as $stockData) {
            if (!empty($stockData['stock_id'])) {
                $stock = Stock::find($stockData['stock_id']);
                if ($stock) {
                    $stock->update([
                        'quantity'  => $stockData['quantity'],
                        'threshold' => $stockData['threshold'],
                    ]);
                }
            } else {
                Stock::create([
                    'product_variant_id' => $stockData['variant_id'],
                    'warehouse_id'       => $defaultWarehouseId,
                    'quantity'           => $stockData['quantity'],
                    'threshold'          => $stockData['threshold'],
                ]);
            }
        }

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Stock inventory levels updated.');
    }

    public function storePriceTier(Request $request, Product $product)
    {
        $validated = $request->validate([
            'min_qty' => 'required|integer|min:1',
            'max_qty' => 'nullable|integer|gte:min_qty',
            'price'   => 'required|numeric|min:0',
        ]);

        $validated['product_id'] = $product->id;
        ProductPriceTier::create($validated);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Wholesale volume price tier added.');
    }

    public function destroyPriceTier(Request $request, ...$args)
    {
        $tierArg = end($args);
        $priceTier = null;

        if ($tierArg instanceof ProductPriceTier) {
            $priceTier = $tierArg;
        } elseif (is_numeric($tierArg)) {
            $priceTier = ProductPriceTier::find($tierArg);
        }

        if (!$priceTier) {
            return back()->with('error', 'Price tier not found.');
        }

        $productId = $priceTier->product_id;
        $priceTier->delete();

        return redirect()->route('admin.products.edit', $productId)
            ->with('success', 'Wholesale price tier removed.');
    }

    private function generateSku($categoryId, $name): string
    {
        $category = Category::find($categoryId);
        $categoryCode = $category ? strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $category->name), 0, 3)) : 'CAT';
        $nameCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 3));

        if (strlen($categoryCode) < 3) $categoryCode = str_pad($categoryCode, 3, 'X');
        if (strlen($nameCode) < 3) $nameCode = str_pad($nameCode, 3, 'X');

        $prefix = $categoryCode . '-' . $nameCode;
        $lastNumber = Product::where('sku', 'like', $prefix . '%')->count() + 1;

        return $prefix . '-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
    }
}
