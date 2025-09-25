<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;

class BetCreationTest extends TestCase
{
    public function test_user_can_create_bet(): void
    {
        $user = User::where('email', 'user1@test.com')->first();
        $event = Event::where('status', 'active')->first();
        
        if (!$user || !$event) {
            $this->markTestSkipped('Demo data not found. Please run: docker-compose exec app php artisan db:seed --force');
        }

        $response = $this->actingAs($user)
            ->withHeaders(['Idempotency-Key' => 'test-key-1-' . time()])
            ->postJson('/api/bets', [
                'event_id' => $event->id,
                'outcome' => 'Победа Спартака',
                'amount' => 100
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'bet' => ['id', 'event_title', 'outcome', 'amount', 'coefficient', 'potential_win', 'status'],
                'balance'
            ]);

        $this->assertDatabaseHas('bets', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'amount' => 100,
            'status' => 'pending'
        ]);

        $user->refresh();
        $this->assertLessThan(5000, $user->balance);
    }

    public function test_user_cannot_bet_with_invalid_outcome(): void
    {
        $user = User::where('email', 'user1@test.com')->first();
        $event = Event::where('status', 'active')->first();
        
        if (!$user || !$event) {
            $this->markTestSkipped('Demo data not found. Please run: docker-compose exec app php artisan db:seed --force');
        }

        $response = $this->actingAs($user)
            ->withHeaders(['Idempotency-Key' => 'test-invalid-outcome-' . time()])
            ->postJson('/api/bets', [
                'event_id' => $event->id,
                'outcome' => 'Несуществующий исход',
                'amount' => 100
            ]);

        $response->assertStatus(400)
            ->assertJson(['error' => 'Invalid outcome for this event.']);
    }

    public function test_user_cannot_bet_with_negative_amount(): void
    {
        $user = User::where('email', 'user1@test.com')->first();
        $event = Event::where('status', 'active')->first();
        
        if (!$user || !$event) {
            $this->markTestSkipped('Demo data not found. Please run: docker-compose exec app php artisan db:seed --force');
        }

        $response = $this->actingAs($user)
            ->withHeaders(['Idempotency-Key' => 'test-negative-amount-' . time()])
            ->postJson('/api/bets', [
                'event_id' => $event->id,
                'outcome' => 'Победа Спартака',
                'amount' => -100
            ]);

        $response->assertStatus(422);
    }

    public function test_user_cannot_bet_with_zero_amount(): void
    {
        $user = User::where('email', 'user1@test.com')->first();
        $event = Event::where('status', 'active')->first();
        
        if (!$user || !$event) {
            $this->markTestSkipped('Demo data not found. Please run: docker-compose exec app php artisan db:seed --force');
        }

        $response = $this->actingAs($user)
            ->withHeaders(['Idempotency-Key' => 'test-zero-amount-' . time()])
            ->postJson('/api/bets', [
                'event_id' => $event->id,
                'outcome' => 'Победа Спартака',
                'amount' => 0
            ]);

        $response->assertStatus(422);
    }

    public function test_user_cannot_bet_on_nonexistent_event(): void
    {
        $user = User::where('email', 'user1@test.com')->first();
        
        if (!$user) {
            $this->markTestSkipped('Demo data not found. Please run: docker-compose exec app php artisan db:seed --force');
        }

        $response = $this->actingAs($user)
            ->withHeaders(['Idempotency-Key' => 'test-nonexistent-event-' . time()])
            ->postJson('/api/bets', [
                'event_id' => 99999,
                'outcome' => 'Победа Спартака',
                'amount' => 100
            ]);

        $response->assertStatus(422);
    }

    public function test_user_cannot_bet_without_authentication(): void
    {
        $event = Event::where('status', 'active')->first();
        
        if (!$event) {
            $this->markTestSkipped('Demo data not found. Please run: docker-compose exec app php artisan db:seed --force');
        }

        $response = $this->withHeaders(['Idempotency-Key' => 'test-no-auth-' . time()])
            ->postJson('/api/bets', [
                'event_id' => $event->id,
                'outcome' => 'Победа Спартака',
                'amount' => 100
            ]);

        $response->assertStatus(401);
    }

}