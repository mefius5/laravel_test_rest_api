<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
	public function createUser(array $data): User
	{
		return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => User::ROLE_CUSTOMER
        ]);
	}

	public function verifyUserLogin(string $email, string $password): User|bool
	{
		$user = User::where('email', $email)->first();

		return ($user && Hash::check($password, $user->password)) ? $user : false;
	}
}