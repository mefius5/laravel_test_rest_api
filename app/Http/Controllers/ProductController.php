<?php

namespace App\Http\Controllers;

use App\Http\Integrations\DummyJson\Requests\GetProductsRequest;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Requests\StoreUpdateProductRequest;
use App\Models\Product;
use App\Repository\Product\ProductInterface;
use App\Services\Product\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{

    public function __construct(
        private ProductInterface $productRepository,
        private ProductService $productService) 
    {
    }

    public function index(ProductIndexRequest $request): JsonResponse
    {
        // $request = new GetProductsRequest();
        // $response = $request->send();
        // dd($response);

        $validated = $request->validated();
    
        return response()->json([
            'data' => $this->productRepository->getFilteredProducts($validated)
        ], JsonResponse::HTTP_OK);
    }

    public function store(StoreUpdateProductRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->productService->createOrUpdateProduct($validated);
        
        return response()->json([
            'data' => $this->productRepository->getProductSavedInfo()
        ], JsonResponse::HTTP_CREATED);
    }

    public function update(StoreUpdateProductRequest $request, Product $product): JsonResponse
    {
        $this->authorize('update', $product);
        $validated = $request->validated();

        $this->productService->createOrUpdateProduct($validated, $product);

        return response()->json([
            'data' => $this->productRepository->getProductUpdatedInfo()
        ], JsonResponse::HTTP_OK);
    }
}
