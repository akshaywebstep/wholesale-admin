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
        $query = Warehouse::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $warehouses = $query->withCount('stocks')->latest()->paginate(10)->withQueryString();

        return view('admin.warehouses.index', compact('warehouses'));
    }

    /**
     * Show Create Form
     */
    public function create()
    {
        return view('admin.warehouses.create');
    }

    /**
     * Store Warehouse
     */
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

    /**
     * Preview Single Warehouse
     */
    public function show(Warehouse $warehouse)
    {
        $warehouse->load(['stocks.productVariant.product']);
        return view('admin.warehouses.show', compact('warehouse'));
    }

    /**
     * Show Edit Form
     */
    public function edit(Warehouse $warehouse)
    {
        return view('admin.warehouses.edit', compact('warehouse'));
    }

    /**
     * Update Warehouse
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255|unique:warehouses,name,' . $warehouse->id,
            'location' => 'required|string|max:500',
            'status'   => 'required|in:active,inactive',
        ]);

        $warehouse->update($validated);

        return redirect()->route('admin.warehouses.index')
            ->with('success', 'Warehouse updated successfully.');
    }

    /**
     * Delete Warehouse
     */
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
}