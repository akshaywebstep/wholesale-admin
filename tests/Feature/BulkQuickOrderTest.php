<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPriceTier;
use App\Models\ProductVariant;
use App\Models\Stock;
use App\Models\Unit;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BulkQuickOrderTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected Product $product1;
    protected Product $product2;

    protected function setUp(): void
    {
        parent::setUp();

        $unit = Unit::create(['name' => 'Pack', 'short_code' => 'pk', 'status' => 'ACTIVE']);
        $category = Category::create(['name' => 'Vapes', 'slug' => 'vapes', 'status' => 'ACTIVE']);
        $warehouse = Warehouse::create(['name' => 'Main Warehouse', 'location' => 'Raleigh', 'status' => 'ACTIVE']);

        $this->product1 = Product::create([
            'name'        => 'Elf Bar BC5000 Blue Razz',
            'sku'         => 'ELF-BC5000-BLU',
            'base_price'  => 500,
            'is_active'   => 1,
            'category_id' => $category->id,
            'unit_id'     => $unit->id,
        ]);

        $v1 = ProductVariant::create([
            'product_id'  => $this->product1->id,
            'variant_sku' => 'ELF-BC5000-BLU-V1',
        ]);
        Stock::create([
            'product_variant_id' => $v1->id,
            'warehouse_id'       => $warehouse->id,
            'quantity'           => 100,
        ]);

        // Volume Tier: 50+ units for $450
        ProductPriceTier::create([
            'product_id' => $this->product1->id,
            'min_qty'    => 50,
            'max_qty'    => null,
            'price'      => 450,
        ]);

        $this->product2 = Product::create([
            'name'        => 'Smok Nord 5 Pod Kit',
            'sku'         => 'SMOK-NORD-5',
            'base_price'  => 1200,
            'is_active'   => 1,
            'category_id' => $category->id,
            'unit_id'     => $unit->id,
        ]);

        $v2 = ProductVariant::create([
            'product_id'  => $this->product2->id,
            'variant_sku' => 'SMOK-NORD-5-V1',
        ]);
        Stock::create([
            'product_variant_id' => $v2->id,
            'warehouse_id'       => $warehouse->id,
            'quantity'           => 40,
        ]);

        $this->customer = User::create([
            'name'          => 'Retail Store Owner',
            'email'         => 'retailer@store.com',
            'password'      => Hash::make('Retail123!'),
            'user_type'     => 'CUSTOMER',
            'business_name' => 'Apex Smoke Shop',
            'status'        => 'ACTIVE',
        ]);
    }

    public function test_quick_order_page_renders_successfully(): void
    {
        $response = $this->get(route('shop.quick-order.index'));
        $response->assertStatus(200);
        $response->assertSee('Multi-Item Quick Order Table');
        $response->assertSee('Order Items Matrix');
    }

    public function test_quick_order_sku_search_returns_products(): void
    {
        $response = $this->getJson(route('shop.quick-order.search', ['q' => 'ELF']));
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'sku'  => 'ELF-BC5000-BLU',
            'name' => 'Elf Bar BC5000 Blue Razz',
        ]);
    }

    public function test_calculate_tier_price_for_product(): void
    {
        // 10 units -> Base price 500
        $res1 = $this->getJson(route('shop.quick-order.calculatePrice', [
            'product_id' => $this->product1->id,
            'quantity'   => 10,
        ]));
        $res1->assertStatus(200);
        $res1->assertJson([
            'unit_price' => 500,
            'line_total' => 5000,
        ]);

        // 50 units -> Tier price 450
        $res2 = $this->getJson(route('shop.quick-order.calculatePrice', [
            'product_id' => $this->product1->id,
            'quantity'   => 50,
        ]));
        $res2->assertStatus(200);
        $res2->assertJson([
            'unit_price'    => 450,
            'line_total'    => 22500,
            'tier_discount' => true,
        ]);
    }

    public function test_sample_csv_template_download(): void
    {
        $response = $this->get(route('shop.quick-order.downloadTemplate'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="wholesale_b2b_purchase_order.csv"');
    }

    public function test_upload_csv_with_valid_and_invalid_items(): void
    {
        $csvContent = "SKU,Quantity\n" .
                      "ELF-BC5000-BLU,50\n" .
                      "SMOK-NORD-5,10\n" .
                      "NON-EXISTENT-SKU-999,5\n";

        $file = UploadedFile::fake()->createWithContent('order_test.csv', $csvContent);

        $response = $this->postJson(route('shop.quick-order.uploadCsv'), [
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success'       => true,
            'valid_count'   => 2,
            'invalid_count' => 1,
        ]);

        $response->assertJsonFragment([
            'sku' => 'ELF-BC5000-BLU',
            'requested_qty' => 50,
            'unit_price' => 450,
        ]);

        $response->assertJsonFragment([
            'sku'    => 'NON-EXISTENT-SKU-999',
            'reason' => 'SKU not found in active catalog',
        ]);
    }

    public function test_bulk_add_to_cart_for_authenticated_customer(): void
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->postJson(route('shop.quick-order.addBulk'), [
                'items' => [
                    [
                        'product_id' => $this->product1->id,
                        'quantity'   => 50,
                    ],
                    [
                        'product_id' => $this->product2->id,
                        'quantity'   => 10,
                    ],
                ]
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('carts', [
            'user_id'    => $this->customer->id,
            'product_id' => $this->product1->id,
            'quantity'   => 50,
        ]);

        $this->assertDatabaseHas('carts', [
            'user_id'    => $this->customer->id,
            'product_id' => $this->product2->id,
            'quantity'   => 10,
        ]);
    }

    public function test_bulk_add_to_cart_requires_login(): void
    {
        $response = $this->postJson(route('shop.quick-order.addBulk'), [
            'items' => [
                ['product_id' => $this->product1->id, 'quantity' => 10],
            ]
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
        ]);
    }
}
