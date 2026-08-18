<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Request $request)
    {
        $variants = ProductVariant::with('product')
            ->when($request->product_id, fn($q) => $q->where('product_id', $request->product_id))
            ->when($request->search, function ($q) use ($request) {
                $q->where('variant_sku', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $products = Product::orderBy('name')->get(['id', 'name']);

        return view('admin.productVariants.index', compact('variants', 'products'));
    }

    public function create(Request $request)
    {
        $products = Product::orderBy('name')->get(['id', 'name']);
        $selectedProductId = $request->query('product_id');

        return view('admin.productVariants.create', compact('products', 'selectedProductId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'variant_sku' => 'required|string|max:100|unique:product_variants,variant_sku',
        ]);

        ProductVariant::create($validated);

        return redirect()
            ->route('admin.productVariants.index', ['product_id' => $validated['product_id']])
            ->with('success', 'Variant added.');
    }

    public function show(ProductVariant $product_variant)
    {
        $product_variant->load('product', 'stock');
        return view('admin.productVariants.show', ['variant' => $product_variant]);
    }

    public function edit(ProductVariant $product_variant)
    {
        $products = Product::orderBy('name')->get(['id', 'name']);
        return view('admin.productVariants.edit', [
            'variant' => $product_variant,
            'products' => $products,
        ]);
    }

    public function update(Request $request, ProductVariant $product_variant)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'variant_sku' => 'required|string|max:100|unique:product_variants,variant_sku,' . $product_variant->id,
        ]);

        $product_variant->update($validated);

        return redirect()
            ->route('admin.productVariants.index', ['product_id' => $validated['product_id']])
            ->with('success', 'Variant updated.');
    }

    public function destroy(ProductVariant $product_variant)
    {
        $productId = $product_variant->product_id;
        $product_variant->delete();

        return redirect()
            ->route('admin.productVariants.index', ['product_id' => $productId])
            ->with('success', 'Variant deleted.');
    }
}