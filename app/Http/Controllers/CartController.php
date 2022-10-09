<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Models\Cart;
use App\Repository\Cart\CartInterface;
use App\Services\Cart\CartService;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{

    public function __construct(
        private CartInterface $cartRepository,
        private CartService $cartService) 
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCartRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = auth('sanctum')->check() ? auth('sanctum')->user() : null;

        $result = $this->cartService->addItemToCart($validated, $user);

        if(! $result instanceof Cart){
            return response()->json([
                'message' => $result
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'data' => $this->cartRepository->getAllCartData($result->token)
        ], JsonResponse::HTTP_CREATED);
    }
}
