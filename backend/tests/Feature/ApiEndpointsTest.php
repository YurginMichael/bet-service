<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;

class ApiEndpointsTest extends TestCase
{
    public function test_get_events_endpoint_returns_list(): void
    {
        $response = $this->getJson('/api/events');

        $response->assertStatus(200);

        $events = $response->json();
        $this->assertNotEmpty($events);
    }

    public function test_get_user_bets_endpoint_requires_authentication(): void
    {
        $response = $this->getJson('/api/bets');

        $response->assertStatus(401);
    }

    public function test_get_user_bets_endpoint_returns_user_bets(): void
    {
        $user = User::where('email', 'user1@test.com')->first();
        
        if (!$user) {
            $this->markTestSkipped('Demo data not found. Please run: docker-compose exec app php artisan db:seed --force');
        }

        $response = $this->actingAs($user)
            ->getJson('/api/bets');

        $response->assertStatus(200);
    }

    public function test_get_user_info_endpoint_returns_user_data(): void
    {
        $user = User::where('email', 'user1@test.com')->first();
        
        if (!$user) {
            $this->markTestSkipped('Demo data not found. Please run: docker-compose exec app php artisan db:seed --force');
        }

        $response = $this->actingAs($user)
            ->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJson([
                'email' => 'user1@test.com'
            ]);
    }

    public function test_login_with_invalid_credentials_fails(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }

    public function test_login_with_wrong_password_fails(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'user1@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }

    public function test_register_with_invalid_data_fails(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '456',
        ]);

        $response->assertStatus(422);
    }

    public function test_register_with_existing_email_fails(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'user1@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
    }

    public function test_bet_creation_updates_user_balance(): void
    {
        $user = User::where('email', 'user1@test.com')->first();
        $event = Event::where('status', 'active')->first();
        
        if (!$user || !$event) {
            $this->markTestSkipped('Demo data not found. Please run: docker-compose exec app php artisan db:seed --force');
        }

        $initialBalance = $user->balance;
        $betAmount = 200;

        $response = $this->actingAs($user)
            ->withHeaders(['Idempotency-Key' => 'balance-test-' . time()])
            ->postJson('/api/bets', [
                'event_id' => $event->id,
                'outcome' => 'Победа Спартака',
                'amount' => $betAmount
            ]);

        $response->assertStatus(201);

        $user->refresh();
        $this->assertLessThan($initialBalance, $user->balance);

        $response->assertJson([
            'balance' => $user->balance
        ]);
    }

    public function test_bet_creation_calculates_correct_potential_win(): void
    {
        $user = User::where('email', 'user1@test.com')->first();
        $event = Event::where('status', 'active')->first();
        
        if (!$user || !$event) {
            $this->markTestSkipped('Demo data not found. Please run: docker-compose exec app php artisan db:seed --force');
        }

        $betAmount = 100;
        $expectedCoefficient = 2.1;
        $expectedPotentialWin = $betAmount * $expectedCoefficient;

        $response = $this->actingAs($user)
            ->withHeaders(['Idempotency-Key' => 'potential-win-test'])
            ->postJson('/api/bets', [
                'event_id' => $event->id,
                'outcome' => 'Победа Спартака',
                'amount' => $betAmount
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'bet' => [
                    'amount' => $betAmount,
                    'coefficient' => $expectedCoefficient,
                    'potential_win' => $expectedPotentialWin,
                    'status' => 'pending'
                ]
            ]);
    }
}