<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\TestCase;

class LoginTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->artisan('passport:install');
    }

    /**
     * @test
     */
    public function login()
    {
        // ARRANGE
        $password = $this->faker->password;

        $user = factory(User::class)->create([
            'password' => Hash::make($password),
        ]);

        // ACT
        $response = $this->post('/api/v1/login', [
            'email' => $user->email,
            'grant_type' => 'password',
            'password' => $password,
            'username' => $user->email,
        ]);

        // ASSERT
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_at']);
    }

}
