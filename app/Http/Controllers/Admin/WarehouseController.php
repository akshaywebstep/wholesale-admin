<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Listing Page
     */
    public function index(Request $request)
    {
        $query = Warehouse::with(['stocks.productVariant.product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('manager_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', strtoupper($request->status));
        }

        $warehouses = $query->latest('id')->paginate(10)->withQueryString();

        $allWarehouses = Warehouse::with(['stocks.productVariant.product'])->get();
        $totalValuation = $allWarehouses->sum->total_valuation;
        $totalStockUnits = $allWarehouses->sum->total_stock_units;
        $totalLowStock = $allWarehouses->sum->low_stock_count;

        return view('admin.warehouses.index', compact('warehouses', 'totalValuation', 'totalStockUnits', 'totalLowStock'));
    }

    /*
    // [DISABLED] Show Create Form (Warehouse creation disabled by admin policy)
    public function create()
    {
        return view('admin.warehouses.create');
    }

    // [DISABLED] Store Warehouse
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255|unique:warehouses,name',
            'location' => 'required|string|max:500',
            'status'   => 'required|in:active,inactive',
        ]);

        Warehouse::create($validated);

        return redirect()->route('admin.warehouses.index')
            ->with('success', 'Warehouse created successfully.');
    }
    */

    /**
     * Preview Single Warehouse / Inventory Hub
     */
    public function show(Request $request, Warehouse $warehouse)
    {
        $warehouse->load(['stocks.productVariant.product.images', 'stocks.productVariant.product.category', 'stocks.productVariant.product.unit']);

        $stocks = $warehouse->stocks;

        if ($request->filled('search')) {
            $s = strtolower($request->search);
            $stocks = $stocks->filter(function ($st) use ($s) {
                $pName = strtolower($st->productVariant->product->name ?? '');
                $pSku = strtolower($st->productVariant->product->sku ?? '');
                $vSku = strtolower($st->productVariant->variant_sku ?? '');
                return str_contains($pName, $s) || str_contains($pSku, $s) || str_contains($vSku, $s);
            });
        }

        if ($request->filled('stock_filter')) {
            $sf = $request->stock_filter;
            if ($sf === 'low') {
                $stocks = $stocks->filter(fn($st) => $st->quantity > 0 && $st->quantity <= $st->threshold);
            } elseif ($sf === 'out') {
                $stocks = $stocks->filter(fn($st) => $st->quantity <= 0);
            } elseif ($sf === 'healthy') {
                $stocks = $stocks->filter(fn($st) => $st->quantity > $st->threshold);
            }
        }

        return view('admin.warehouses.show', compact('warehouse', 'stocks'));
    }

    /**
     * Show Edit Form
     */
    public function edit(Warehouse $warehouse)
    {
        return view('admin.warehouses.edit', compact('warehouse'));
    }

    /**
     * Update Warehouse Facility & Operational Details
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255|unique:warehouses,name,' . $warehouse->id,
            'code'            => 'nullable|string|max:50',
            'location'        => 'required|string|max:500',
            'status'          => 'required|in:ACTIVE,INACTIVE,active,inactive',
            'manager_name'    => 'nullable|string|max:255',
            'contact_phone'   => 'nullable|string|max:50',
            'contact_email'   => 'nullable|email|max:255',
            'tax_number'      => 'nullable|string|max:100',
            'operating_hours' => 'nullable|string|max:255',
            'dispatch_notes'  => 'nullable|string',
        ]);

        $validated['status'] = strtoupper($validated['status']);

        $warehouse->update($validated);

        return redirect()->route('admin.warehouses.show', $warehouse)
            ->with('success', 'Master facility and administrative profile updated successfully.');
    }

    /*
    // [DISABLED] Delete Warehouse (Warehouse deletion disabled by admin policy)
    public function destroy(Warehouse $warehouse)
    {
        // Check if stock exists before deleting
        if ($warehouse->stocks()->count() > 0) {
            return redirect()->route('admin.warehouses.index')
                ->with('error', 'Cannot delete warehouse because it has stocks assigned.');
        }

        $warehouse->delete();

        return redirect()->route('admin.warehouses.index')
            ->with('success', 'Warehouse deleted successfully.');
    }
    */
}