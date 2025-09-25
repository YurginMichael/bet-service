<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class SecurityTest extends TestCase
{
    public function test_hmac_signature_validation(): void
    {
        $user = User::where('email', 'user1@test.com')->first();
        
        if (!$user) {
            $this->markTestSkipped('Demo data not found. Please run: docker-compose exec app php artisan db:seed --force');
        }
        
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'X-Signature' => 'valid-signature'
        ])->postJson('/api/bets/external', [
            'event_id' => 1,
            'outcome' => 'test',
            'amount' => 100
        ]);

        $response->assertStatus(401);
    }

    public function test_invalid_hmac_signature_rejects_request(): void
    {
        $user = User::where('email', 'user1@test.com')->first();
        
        if (!$user) {
            $this->markTestSkipped('Demo data not found. Please run: docker-compose exec app php artisan db:seed --force');
        }
        
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'X-Signature' => 'completely-wrong-signature'
        ])->postJson('/api/bets/external', [
            'event_id' => 1,
            'outcome' => 'test',
            'amount' => 100
        ]);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Invalid signature']);
    }

    public function test_missing_hmac_signature_rejects_request(): void
    {
        $user = User::where('email', 'user1@test.com')->first();
        
        if (!$user) {
            $this->markTestSkipped('Demo data not found. Please run: docker-compose exec app php artisan db:seed --force');
        }
        
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/bets/external', [
            'event_id' => 1,
            'outcome' => 'test',
            'amount' => 100
        ]);

        $response->assertStatus(401);
    }
}