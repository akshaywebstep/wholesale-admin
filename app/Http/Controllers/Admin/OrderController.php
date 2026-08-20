<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Orders List View with Analytics Summary
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.variant.product', 'items.product'])->latest();

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search Filter (Order Number, Customer Name/Email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Quick Stats for Dashboard Header
        $stats = [
            'total'     => Order::count(),
            'pending'   => Order::where('status', 'PENDING')->count(),
            'confirmed' => Order::where('status', 'CONFIRMED')->count(),
            'revenue'   => Order::whereNotIn('status', ['CANCELLED'])->sum('total_amount'),
        ];

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Professional Single Order Details View
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.variant']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update Status, Generate Invoice & Deduct Stock
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:PENDING,CONFIRMED,SHIPPED,DELIVERED,CANCELLED',
        ]);

        $oldStatus = strtoupper($order->status);
        $newStatus = strtoupper($request->status);

        if ($oldStatus === $newStatus) {
            return back()->with('info', 'Order status is already set to ' . $newStatus);
        }

        DB::transaction(function () use ($order, $oldStatus, $newStatus) {

            // Trigger Workflow on Order Confirmation
            if ($newStatus === 'CONFIRMED' && $oldStatus !== 'CONFIRMED') {

                // 1. Invoice Record
                if (!$order->invoice) {
                    Invoice::create([
                        'order_id'       => $order->id,
                        'invoice_number' => 'INV-' . date('Ymd') . '-' . sprintf('%04d', $order->id),
                        'pdf_path'       => 'invoices/INV-' . $order->id . '.pdf',
                    ]);
                }

                // 2. Stock Deduction
                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        Stock::where('product_variant_id', $item->product_variant_id)
                            ->decrement('quantity', $item->quantity);
                    } else {
                        Stock::where('product_id', $item->product_id)
                            ->whereNull('product_variant_id')
                            ->decrement('quantity', $item->quantity);
                    }
                }
            }

            // Update Main Order Status
            $order->update(['status' => $newStatus]);
        });

        return back()->with('success', 'Order status updated to ' . $newStatus . ' successfully.');
    }



    public function downloadInvoice(Order $order)
    {
        $order->load(['user', 'warehouse', 'items.product', 'items.variant', 'invoice']);

        if (!$order->invoice) {
            return back()->with('error', 'Invoice not generated for this order yet.');
        }

        $pdf = Pdf::loadView('admin.orders.invoice-pdf', compact('order'));

        return $pdf->download($order->invoice->invoice_number . '.pdf');
    }
}
