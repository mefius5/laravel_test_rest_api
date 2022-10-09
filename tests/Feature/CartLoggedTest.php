<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\Cart\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Str;

class CartLoggedTest extends CartTest
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Sanctum::actingAs($this->user);
    }

    public function test_cart_store(): void
    {
        $response = $this->post(parent::CART_ENPOINT_PREFIX, $this->getCartStoreData());

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'token',
                    'user_id',
                    'product_id',
                    'position'
                ]
            ]
        ]);
        $this->assertNotEmpty($response['data'][0]['token']);
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'position' => 1
        ]);
    }

    public function test_cart_store_with_existed_cart(): void
    {
        $cart = Cart::factory()->create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'token' => Str::random(30),
            'position' => 1
        ]);

        $response = $this->post(parent::CART_ENPOINT_PREFIX, $this->getCartStoreData());

        $response->assertUnauthorized();
        $this->assertStringContainsString(CartService::UNAUTHORIZED_MESSAGE, $response['message']);
    }

    public function test_cart_store_with_same_token_of_another_user(): void
    {
        $cart = Cart::factory()->create([
            'user_id' => User::factory()->create(),
            'product_id' => $this->product->id,
            'token' => Str::random(30),
            'position' => 1
        ]);

        $response = $this->post(self::CART_ENPOINT_PREFIX, ['token' => $cart->token, 'product_id' => $this->product->id]);

        $response->assertUnauthorized();
        $this->assertStringContainsString(CartService::UNAUTHORIZED_MESSAGE, $response['message']);
    }
}
