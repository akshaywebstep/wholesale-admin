<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::with(['images', 'category', 'variants.stocks'])->latest()->paginate(12);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')
            ->where('status', 'ACTIVE')
            ->with(['children' => fn($q) => $q->where('status', 'ACTIVE')])
            ->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sku'] = $this->generateSku($validated['category_id'], $validated['name']);

        $product = Product::create($validated);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product created. Add variants, images, and pricing below.');
    }

    private function generateSku($categoryId, $name): string
    {
        $category = Category::find($categoryId);
        $categoryCode = strtoupper(substr($category->name, 0, 3));
        $nameCode = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3));

        $prefix = $categoryCode . '-' . $nameCode;
        $lastNumber = Product::where('sku', 'like', $prefix . '%')->count() + 1;

        return $prefix . '-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
    }

    public function edit(Product $product)
    {
        $categories = Category::whereNull('parent_id')
            ->where('status', 'ACTIVE')
            ->with(['children' => fn($q) => $q->where('status', 'ACTIVE')])
            ->get();

        $customerGroups = \App\Models\CustomerGroup::where('status', 'ACTIVE')->get();
        $product->load('variants', 'images', 'priceTiers.customerGroup');

        return view('admin.products.edit', compact('product', 'categories', 'customerGroups'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $product->update($validated);

        return redirect()->route('admin.products.edit', $product)->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    public function storeImage(Request $request, Product $product)
    {
        $validated = $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        foreach ($request->file('images') as $file) {
            $path = $file->store('products', 'public');
            $product->images()->create(['image_path' => $path]);
        }

        return back()->with('success', 'Images uploaded.');
    }

    public function destroyImage(\App\Models\ProductImage $image)
    {
        $productId = $image->product_id;
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return redirect()->route('admin.products.edit', $productId)->with('success', 'Image removed.');
    }

    public function storePriceTier(Request $request, Product $product)
    {
        $validated = $request->validate([
            'customer_group_id' => 'nullable|exists:customer_groups,id',
            'min_qty' => 'required|integer|min:1',
            'max_qty' => 'nullable|integer|gt:min_qty',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['product_id'] = $product->id;
        \App\Models\ProductPriceTier::create($validated);

        return back()->with('success', 'Price tier added.');
    }

    public function destroyPriceTier(\App\Models\ProductPriceTier $priceTier)
    {
        $productId = $priceTier->product_id;
        $priceTier->delete();
        return redirect()->route('admin.products.edit', $productId)->with('success', 'Price tier removed.');
    }
}
