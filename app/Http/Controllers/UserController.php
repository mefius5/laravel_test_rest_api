<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use App\Repository\User\UserInterface;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private UserInterface $userRepository,
        private UserService $userService) {
    }

    public function register(UserRegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = $this->userService->createUser($validated);

        $access_token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'data' => $this->userRepository->getUserData($validated['email'], $access_token)
        ], JsonResponse::HTTP_CREATED);
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = $this->userService->verifyUserLogin($validated['email'], $validated['password']);
        if($user){
            $access_token = $user->createToken('access_token')->plainTextToken;

            return response()->json([
                'data' => $this->userRepository->getUserData($validated['email'], $access_token)
            ], JsonResponse::HTTP_OK);
        }

        return response()->json([
            'message' => 'Incorrect email or password'
        ], JsonResponse::HTTP_UNAUTHORIZED);
    }
}
