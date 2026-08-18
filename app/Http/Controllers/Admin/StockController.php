<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with(['productVariant.product', 'warehouse'])->latest()->paginate(10);
        return view('admin.stocks.index', compact('stocks'));
    }

    public function create()
    {
        $variants = ProductVariant::with('product')->get();
        $warehouses = Warehouse::all();
        return view('admin.stocks.create', compact('variants', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:0',
            'threshold' => 'required|integer|min:0',
        ]);

        Stock::create($request->only(['product_variant_id', 'warehouse_id', 'quantity', 'threshold']));

        return redirect()->route('admin.stock.index')->with('success', 'Stock added successfully.');
    }

    public function edit(Stock $stock)
    {
        $variants = ProductVariant::with('product')->get();
        $warehouses = Warehouse::all();
        return view('admin.stocks.edit', compact('stock', 'variants', 'warehouses'));
    }

    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:0',
            'threshold' => 'required|integer|min:0',
        ]);

        $stock->update($request->only(['product_variant_id', 'warehouse_id', 'quantity', 'threshold']));

        return redirect()->route('admin.stock.index')->with('success', 'Stock updated successfully.');
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();
        return redirect()->route('admin.stock.index')->with('success', 'Stock entry deleted successfully.');
    }
}