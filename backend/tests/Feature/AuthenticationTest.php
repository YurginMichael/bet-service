<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class AuthenticationTest extends TestCase
{
    public function test_user_can_register(): void
    {
        $email = 'test' . time() . '@example.com';

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email'],
                'token',
                'token_type'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'balance' => 1000.00
        ]);
    }

    public function test_user_can_login(): void
    {
        $user = User::where('email', 'user1@test.com')->first();
        
        if (!$user) {
            $this->markTestSkipped('Demo data not found. Please run: docker-compose exec app php artisan db:seed --force');
        }

        $response = $this->postJson('/api/login', [
            'email' => 'user1@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email'],
                'token',
                'token_type'
            ]);
    }

    public function test_user_can_logout(): void
    {
        $user = User::where('email', 'user1@test.com')->first();
        
        if (!$user) {
            $this->markTestSkipped('Demo data not found. Please run: docker-compose exec app php artisan db:seed --force');
        }

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/logout');

        $response->assertStatus(200);
    }

    public function test_protected_routes_require_authentication(): void
    {
        $response = $this->getJson('/api/bets');

        $response->assertStatus(401);
    }
}