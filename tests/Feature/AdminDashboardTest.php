<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_admin_can_view_dashboard_with_kpis(): void
    {
        $admin = User::create([
            'name'      => 'Admin User',
            'email'     => 'admin@wholesale.com',
            'password'  => Hash::make('Admin123!'),
            'user_type' => 'ADMIN',
            'status'    => 'ACTIVE',
        ]);

        $category = Category::create([
            'name'   => 'Beverages',
            'slug'   => 'beverages',
            'status' => 'ACTIVE',
        ]);

        $product = Product::create([
            'name'        => 'Red Bull 250ml',
            'sku'         => 'BEV-RED-001',
            'category_id' => $category->id,
            'base_price'  => 36.00,
            'is_active'   => 1,
        ]);

        $customer = User::create([
            'name'          => 'Retail Store',
            'email'         => 'buyer@store.com',
            'password'      => Hash::make('Buyer123!'),
            'user_type'     => 'CUSTOMER',
            'business_name' => 'Apex Retailers',
            'status'        => 'ACTIVE',
        ]);

        $order = Order::create([
            'order_number'     => 'ORD-2026-9999',
            'user_id'          => $customer->id,
            'total_amount'     => 450.00,
            'status'           => 'PENDING',
            'shipping_address' => '123 Market St',
        ]);

        $response = $this->actingAs($admin, 'web')->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Gross Revenue');
        $response->assertSee('Total Orders');
        $response->assertSee('Active SKUs');
        $response->assertSee('Trade Retailers');
        $response->assertSee('Stock Alerts');
        $response->assertSee('Revenue & Sales Trends', false);
        $response->assertSee('Order Fulfillment Pipeline');
        $response->assertSee('Recent Wholesale Orders');
        $response->assertSee('ORD-2026-9999');
        $response->assertSee('Apex Retailers');
    }
}
