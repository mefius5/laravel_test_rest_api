<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    const PRODUCT_ENDPOINT_PREFIX = 'api/products';

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        Sanctum::actingAs($user);
    }

    public function test_product_index(): void
    {
        $category = Category::factory()->has(Product::factory()->count(30), 'products')->count(5)->create();

        $response = $this->get(self::PRODUCT_ENDPOINT_PREFIX);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'category_id',
                    'name',
                    'price',
                    'description',
                    'active'
                ]
            ]
        ]);
    }

    public function test_add_product(): void
    {   
        $response = $this->post(self::PRODUCT_ENDPOINT_PREFIX, $this->getStoreProductData());

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'message'
            ]
        ]);

        $this->assertStringContainsString('Product Saved', $response['data']['message']);

        $this->assertDatabaseHas('products', [
            'name' => $this->getStoreProductData()['name'],
            'price' => $this->getStoreProductData()['price'],
            'description' => $this->getStoreProductData()['description']
        ]);
    }

    public function test_edit_product_as_admin(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN
        ]);

        Sanctum::actingAs($user);

        $product = Product::factory()->create([
            'category_id' => Category::factory()->create()->id
        ]);

        $response = $this->put(self::PRODUCT_ENDPOINT_PREFIX . '/' . $product->id,  $this->getStoreProductData());

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'message'
            ]
        ]);

        $this->assertStringContainsString('Product Updated', $response['data']['message']);

        $this->assertDatabaseHas('products', [
            'name' => $this->getStoreProductData()['name'],
            'price' => $this->getStoreProductData()['price'],
            'description' => $this->getStoreProductData()['description']
        ]);

    }

    public function test_edit_product(): void
    {
        $product = Product::factory()->create([
            'category_id' => Category::factory()->create()->id
        ]);

        $response = $this->put(self::PRODUCT_ENDPOINT_PREFIX . '/' . $product->id,  $this->getStoreProductData());

        $response->assertForbidden();
    }

    private function getStoreProductData(): array
    {
        return [
            'name' => 'Product test name',
            'price' => 39.99,
            'description' => 'Test description',
            'category_name' => 'Test category',
            'active' => 1
        ];
    }
}
