<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ShopController extends Controller
{
    /**
     * Dedicated Search Results Page
     */
    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));

        $productsQuery = Product::with(['images', 'category', 'priceTiers'])
            ->where('is_active', true);

        if (!empty($query)) {
            $productsQuery->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhereHas('category', function ($catQ) use ($query) {
                      $catQ->where('name', 'like', "%{$query}%");
                  });
            });
        }

        $products = $productsQuery->latest()->paginate(20)->withQueryString();

        return view('frontend.shop.search', compact('products', 'query'));
    }

    /**
     * Instant Live Autocomplete (JSON API)
     */
    public function autocomplete(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'total'   => 0,
                'items'   => [],
            ]);
        }

        $custUser = Auth::guard('customer')->user();
        $webUser = Auth::guard('web')->user();
        $customer = ($custUser && $custUser->user_type === 'CUSTOMER') ? $custUser : (($webUser && $webUser->user_type === 'CUSTOMER') ? $webUser : null);

        $productsQuery = Product::with(['images', 'category', 'priceTiers'])
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhereHas('category', function ($catQ) use ($query) {
                      $catQ->where('name', 'like', "%{$query}%");
                  });
            });

        $total = $productsQuery->count();
        $products = $productsQuery->latest()->take(6)->get();

        $items = $products->map(function ($product) use ($customer) {
            $price = $customer ? $product->priceForUser($customer) : null;

            return [
                'id'           => $product->id,
                'name'         => $product->name,
                'sku'          => $product->sku,
                'category'     => $product->category->name ?? '',
                'image_url'    => $product->featured_image_url,
                'url'          => route('shop.product', $product->id),
                'price'        => $price !== null ? '$' . number_format($price, 2) : null,
                'is_logged_in' => (bool) $customer,
                'is_active'    => (bool) $product->is_active,
            ];
        });

        return response()->json([
            'success'  => true,
            'total'    => $total,
            'view_all' => route('shop.search', ['q' => $query]),
            'items'    => $items,
        ]);
    }

    public function category(Category $category)
    {
        $categoryIds = $category->children()->pluck('id')->push($category->id);

        $products = Product::whereIn('category_id', $categoryIds)
            ->with('images')
            ->where('is_active', true)
            ->paginate(20);

        return view('frontend.shop.category', compact('category', 'products'));
    }

    public function show($id)
    {
        $customer = Auth::guard('customer')->user();

        $product = Product::with([
            'images',
            'category',
            'variants',
            'priceTiers' => fn($q) => $q->orderBy('min_qty', 'asc'),
        ])->where('is_active', 1)->findOrFail($id);

        return view('frontend.shop.show', compact('product'));
    }

    public function quickView($id)
    {
        $custUser = Auth::guard('customer')->user();
        $webUser = Auth::guard('web')->user();
        $customer = ($custUser && $custUser->user_type === 'CUSTOMER') ? $custUser : (($webUser && $webUser->user_type === 'CUSTOMER') ? $webUser : null);

        $product = Product::with([
            'images',
            'category',
            'variants',
            'priceTiers' => fn($q) => $q->orderBy('min_qty', 'asc'),
        ])->where('is_active', 1)->findOrFail($id);

        $price = $customer ? $product->priceForUser($customer) : null;
        $isLoggedIn = (bool) $customer;

        return response()->json([
            'success'      => true,
            'product'      => $product,
            'price'        => $price ? number_format($price, 2) : null,
            'is_logged_in' => $isLoggedIn,
            'image_url'    => $product->featured_image_url,
            'product_url'  => route('shop.product', $product->id),
            'login_url'    => route('login')
        ]);
    }

    /**
     * Customer Orders List
     */
    public function orders()
    {
        $customer = Auth::guard('customer')->user();

        $orders = Order::where('user_id', $customer->id)
            ->with(['items.variant.product.images'])
            ->latest()
            ->paginate(10);

        return view('frontend.orders.index', compact('orders'));
    }

    /**
     * Customer Single Order Details
     */
    public function orderDetails($id)
    {
        $customer = Auth::guard('customer')->user();

        $order = Order::where('id', $id)
            ->where('user_id', $customer->id)
            ->with(['items.variant.product.images'])
            ->firstOrFail();

        return view('frontend.orders.show', compact('order'));
    }

    /**
     * Download Invoice for Customer Order
     */
    public function downloadInvoice($id)
    {
        $customer = Auth::guard('customer')->user();

        $order = Order::where('id', $id)
            ->where('user_id', $customer->id)
            ->with(['user', 'warehouse', 'items.product', 'items.variant.product', 'invoice'])
            ->firstOrFail();

        // Ensure invoice record exists
        if (!$order->invoice) {
            $invoice = Invoice::create([
                'order_id'       => $order->id,
                'invoice_number' => 'INV-' . $order->created_at->format('Ymd') . '-' . sprintf('%04d', $order->id),
                'pdf_path'       => 'invoices/INV-' . $order->id . '.pdf',
            ]);
            $order->setRelation('invoice', $invoice);
        }

        $pdf = Pdf::loadView('admin.orders.invoice-pdf', compact('order'));

        return $pdf->download($order->invoice->invoice_number . '.pdf');
    }
}
