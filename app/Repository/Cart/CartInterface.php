<?php

namespace App\Repository\Cart;

use App\Models\Cart;

interface CartInterface
{
	public function getAllCartData(string $token): array;
}