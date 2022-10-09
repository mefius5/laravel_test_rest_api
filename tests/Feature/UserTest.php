<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    const USER_REGISTER_ENDPOINT = 'api/user/register';
    const USER_LOGIN_ENDPOINT = 'api/user/login';

    public function test_user_register(): void
    {
        $response = $this->post(self::USER_REGISTER_ENDPOINT, $this->getUserRegisterData());

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'name',
                'email',
                'token'
            ]
        ]);

        $this->assertEquals($this->getUserRegisterData()['email'], $response['data']['email']);
        $this->assertEquals($this->getUserRegisterData()['name'], $response['data']['name']);
        $this->assertNotEmpty($response['data']['token']);
       
        $this->assertDatabaseHas('users', [
            'email' => $this->getUserRegisterData()['email'],
        ]);
    }

    public function test_user_login(): void
    {
        $register = $this->post(self::USER_REGISTER_ENDPOINT, $this->getUserRegisterData());

        $response = $this->post(self::USER_LOGIN_ENDPOINT, [
            'email' => $this->getUserRegisterData()['email'],
            'password' => $this->getUserRegisterData()['password']
        ]);

        $response->assertOk();

        $this->assertEquals($this->getUserRegisterData()['email'], $response['data']['email']);
        $this->assertEquals($this->getUserRegisterData()['name'], $response['data']['name']);
        $this->assertNotEmpty($response['data']['token']);
    }

    private function getUserRegisterData(): array
    {
        return [
            'name' => 'test',
            'email' => 'test@test.pl',
            'password' => 'pass123',
            'password_confirmation' => 'pass123',
        ];
    }

    public function test_user_incorrect_login(): void
    {
        $register = $this->post(self::USER_REGISTER_ENDPOINT, $this->getUserRegisterData());

        $response = $this->post(self::USER_LOGIN_ENDPOINT, [
            'email' => 'test2@test.pl',
            'password' => 'pass1234'
        ]);

        $response->assertUnauthorized();

        $this->assertSame('Incorrect email or password', $response['message']);
    }
}
