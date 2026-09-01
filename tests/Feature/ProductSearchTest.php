<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;
    protected Product $product1;
    protected Product $product2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name'   => 'Vape & E-Liquid',
            'slug'   => 'vape-e-liquid',
            'status' => 'ACTIVE',
        ]);

        $this->product1 = Product::create([
            'name'        => 'Elf Bar BC5000 Disposable Pod',
            'sku'         => 'VAP-ELF-0001',
            'description' => 'Rechargeable 5000 puffs premium wholesale vape',
            'category_id' => $this->category->id,
            'base_price'  => 12.50,
            'is_active'   => true,
        ]);

        $this->product2 = Product::create([
            'name'        => 'Al Fakher Shisha Molasses',
            'sku'         => 'SHI-ALF-250G',
            'description' => 'Authentic Middle Eastern double apple hookah flavor',
            'category_id' => $this->category->id,
            'base_price'  => 8.00,
            'is_active'   => true,
        ]);
    }

    public function test_search_page_renders_successfully(): void
    {
        $response = $this->get(route('shop.search'));

        $response->assertStatus(200);
        $response->assertSee('Wholesale Catalog Search');
        $response->assertSee('Elf Bar BC5000');
        $response->assertSee('Al Fakher Shisha');
    }

    public function test_search_page_filters_by_product_name(): void
    {
        $response = $this->get(route('shop.search', ['q' => 'Elf Bar']));

        $response->assertStatus(200);
        $response->assertSee('Elf Bar BC5000 Disposable Pod');
        $response->assertDontSee('Al Fakher Shisha Molasses');
    }

    public function test_search_page_filters_by_sku(): void
    {
        $response = $this->get(route('shop.search', ['q' => 'SHI-ALF']));

        $response->assertStatus(200);
        $response->assertSee('Al Fakher Shisha Molasses');
        $response->assertDontSee('Elf Bar BC5000 Disposable Pod');
    }

    public function test_autocomplete_returns_matching_products_json(): void
    {
        $response = $this->getJson(route('shop.search.autocomplete', ['q' => 'Elf']));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'total'   => 1,
        ]);
        $response->assertJsonFragment([
            'sku'  => 'VAP-ELF-0001',
            'name' => 'Elf Bar BC5000 Disposable Pod',
        ]);
    }

    public function test_autocomplete_returns_empty_when_query_is_too_short(): void
    {
        $response = $this->getJson(route('shop.search.autocomplete', ['q' => 'E']));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'total'   => 0,
            'items'   => [],
        ]);
    }
}
