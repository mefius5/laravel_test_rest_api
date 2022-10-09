<?php

namespace App\Repository\User;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserRepository implements UserInterface
{
	public function getUserData(string $email, string $token): array
	{
		$user = User::where('email', $email)->first();
		return [
			'name' => $user->name,
			'email' => $email,
			'token' => $token
		];
	}
}