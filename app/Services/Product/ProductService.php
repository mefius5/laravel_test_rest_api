<?php

namespace App\Services\Product;

use App\Jobs\ProductSaveJob;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService {
	
	public function createOrUpdateProduct(array $data, ?Product $product = null): void
	{
		DB::transaction(function () use($data, $product) {
			$category_name = $data['category_name'];
			unset($data['category_name']);

			$category = Category::firstOrcreate([
				'name' => $category_name
			]);

			$data['category_id'] = $category->id;

			$storeOrUpdateProduct = $product ?? new Product();
			
			$storeOrUpdateProduct->category_id = $category->id;
			$storeOrUpdateProduct->name = $data['name'];
			$storeOrUpdateProduct->description = $data['description'];
			$storeOrUpdateProduct->price = $data['price'];
			$storeOrUpdateProduct->active = $data['active'];

			ProductSaveJob::dispatch($storeOrUpdateProduct);
		});
	}
}