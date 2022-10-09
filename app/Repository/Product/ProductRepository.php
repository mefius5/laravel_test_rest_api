<?php

namespace App\Repository\Product;

use App\Models\Product;

class ProductRepository implements ProductInterface
{
	public function getAllProducts()
	{
		return Product::where('active', true)->get()->toArray();
	}

	public function getFilteredProducts(array $params): array
	{
		if(isset($params['category_id'])){
			return Product::where('category_id', $params['category_id'])->get()->toArray();
		}

		return $this->getAllProducts();
	}

	public function getProductSavedInfo(): array
	{
		return [
			'message' => "Product Saved"
		];
	}

	public function getProductUpdatedInfo(): array
	{
		return [
			'message' => "Product Updated"
		];
	}
}