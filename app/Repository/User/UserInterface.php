<?php

namespace App\Repository\User;

use App\Models\User;
use Illuminate\Http\JsonResponse;

interface UserInterface
{
	public function getUserData(string $email, string $token): array;
}