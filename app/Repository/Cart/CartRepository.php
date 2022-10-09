<?php

namespace App\Repository\Cart;

use App\Models\Cart;
use App\Models\User;

class CartRepository implements CartInterface
{
	public function getAllCartData(string $token): array
	{
		$cartItems = Cart::where('token', $token)->get()->toArray();

		return $cartItems;
	}
}