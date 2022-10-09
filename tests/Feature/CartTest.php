<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class CartTest extends TestCase
{
    const CART_ENPOINT_PREFIX = 'api/carts';

    protected Product $product;

    public function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory()->create([
            'category_id' => Category::factory()->create()->id
        ]);
    }

    protected function getCartStoreData(): array
    {
        return [
            'token' => '',
            'product_id' => $this->product->id
        ];
    }

    protected function getCartStoreDataRandomToken(): array
    {
        return [
            'token' => Str::random(30),
            'product_id' => $this->product->id
        ];
    }
}
