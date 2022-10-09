<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Str;

class CartService 
{
	const UNAUTHORIZED_MESSAGE = 'Adding item to this cart is unauthorized';

	public function addItemToCart(array $data, ?User $user): Cart|string
	{
		$existedItem = $this->checkExistingCartItems($data['token'], $user);

		if($user){
			if($existedItem && ($existedItem->user_id !== $user->id || $existedItem->token !== $data['token'])){
				return self::UNAUTHORIZED_MESSAGE;
			}

			return $this->createCart($data, $user);
        }else{
			if($existedItem && ($existedItem->token !== $data['token'] || $existedItem->user_id)){
				return self::UNAUTHORIZED_MESSAGE;
			}

			return $this->createCart($data);
		}
	}

	protected function createCart(array $data, ?User $user = null): Cart
	{
		return Cart::create([
			'token' => $data['token'] ?? Str::random(30),
			'product_id' => $data['product_id'],
			'user_id' => $user ? $user->id : null,
			'position' => Cart::where('token', $data['token'])->get()->count() + 1
		]);
	}

	public function checkExistingCartItems(?string $token, ?User $user): ?Cart
	{
		if($token){
			return Cart::where('token', $token)->first() ?? null;
		}

		if($user){
			return Cart::where('user_id', $user->id)->first() ?? null;
		}

		return null;
	}

}

