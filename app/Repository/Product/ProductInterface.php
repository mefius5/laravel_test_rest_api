<?php

namespace App\Repository\Product;

use App\Models\Product;

interface ProductInterface
{
	public function getFilteredProducts(array $params): array;
	public function getProductSavedInfo():array;
	public function getProductUpdatedInfo():array;
}