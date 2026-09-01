<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Executive Summary KPIs
        $totalRevenue = Order::where('status', '!=', 'CANCELLED')->sum('total_amount');
        $totalOrders = Order::count();
        $pendingOrdersCount = Order::whereIn('status', ['PENDING', 'PROCESSING'])->count();
        $completedOrdersCount = Order::where('status', 'DELIVERED')->count();
        $avgOrderValue = $totalOrders > 0 ? (Order::where('status', '!=', 'CANCELLED')->avg('total_amount') ?? 0) : 0;

        $totalProducts = Product::where('is_active', true)->count();
        $totalCustomers = User::where('user_type', 'CUSTOMER')->count();
        $activeWarehousesCount = Warehouse::where('status', 'ACTIVE')->count();

        // 2. Low Stock Alerts
        $lowStockProducts = Product::with(['images', 'category', 'unit', 'variants.stocks.warehouse'])
            ->get()
            ->filter(function ($product) {
                return $product->total_stock <= 20;
            })
            ->sortBy('total_stock')
            ->take(6);

        $lowStockCount = $lowStockProducts->count();

        // 3. Recent Wholesale Orders
        $recentOrders = Order::with(['user', 'items.variant.product.images'])
            ->latest()
            ->take(6)
            ->get();

        // 4. Recent Verified Trade Customers
        $recentCustomers = User::where('user_type', 'CUSTOMER')
            ->latest()
            ->take(5)
            ->get();

        // 5. Top Catalog Products
        $topProducts = Product::with(['images', 'category', 'unit', 'variants.stocks'])
            ->where('is_active', true)
            ->latest()
            ->take(5)
            ->get();

        // 6. Last 7 Days Revenue & Orders Trend
        $revenueTrend = [];
        $ordersTrend = [];
        $chartLabels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('D, M j');

            $dayRevenue = Order::whereDate('created_at', $date->toDateString())
                ->where('status', '!=', 'CANCELLED')
                ->sum('total_amount');

            $dayOrders = Order::whereDate('created_at', $date->toDateString())->count();

            $revenueTrend[] = (float) $dayRevenue;
            $ordersTrend[] = (int) $dayOrders;
        }

        // If today is empty, fallback gracefully with recent figures
        if (array_sum($revenueTrend) == 0 && $totalRevenue > 0) {
            $revenueTrend[6] = (float) $totalRevenue;
            $ordersTrend[6] = (int) $totalOrders;
        }

        // 7. Order Status Breakdown
        $ordersByStatus = [
            'PENDING'    => Order::where('status', 'PENDING')->count(),
            'PROCESSING' => Order::where('status', 'PROCESSING')->count(),
            'SHIPPED'    => Order::where('status', 'SHIPPED')->count(),
            'DELIVERED'  => Order::where('status', 'DELIVERED')->count(),
            'CANCELLED'  => Order::where('status', 'CANCELLED')->count(),
        ];

        // 8. Categories Overview
        $topCategories = Category::whereNull('parent_id')
            ->withCount('products')
            ->orderByDesc('products_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'pendingOrdersCount',
            'completedOrdersCount',
            'avgOrderValue',
            'totalProducts',
            'totalCustomers',
            'activeWarehousesCount',
            'lowStockCount',
            'lowStockProducts',
            'recentOrders',
            'recentCustomers',
            'topProducts',
            'chartLabels',
            'revenueTrend',
            'ordersTrend',
            'ordersByStatus',
            'topCategories'
        ));
    }
}