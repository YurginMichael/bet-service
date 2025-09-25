<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;

class IdempotencyTest extends TestCase
{
    public function test_idempotency_returns_same_response(): void
    {
        $user = User::where('email', 'user1@test.com')->first();
        $event = Event::where('status', 'active')->first();
        
        if (!$user || !$event) {
            $this->markTestSkipped('Demo data not found. Please run: docker-compose exec app php artisan db:seed --force');
        }

        $headers = ['Idempotency-Key' => 'unique-key-123'];
        $data = [
            'event_id' => $event->id,
            'outcome' => 'Победа Спартака',
            'amount' => 100
        ];

        $response1 = $this->actingAs($user)
            ->withHeaders($headers)
            ->postJson('/api/bets', $data);

        $response2 = $this->actingAs($user)
            ->withHeaders($headers)
            ->postJson('/api/bets', $data);

        $response1->assertStatus(201);
        $response2->assertStatus(201);

        $this->assertEquals($response1->json(), $response2->json());

        $this->assertDatabaseHas('bets', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'amount' => 100
        ]);
    }
}