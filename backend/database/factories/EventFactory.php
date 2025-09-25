<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'outcomes' => [
                'Победа команды A' => $this->faker->randomFloat(2, 1.5, 3.0),
                'Победа команды B' => $this->faker->randomFloat(2, 1.5, 3.0),
                'Ничья' => $this->faker->randomFloat(2, 2.0, 4.0),
            ],
            'starts_at' => Carbon::now()->addHours($this->faker->numberBetween(1, 48)),
            'ends_at' => null,
            'status' => $this->faker->randomElement(['pending', 'active', 'finished']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'starts_at' => Carbon::now()->addHours(2),
        ]);
    }

    public function finished(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'finished',
            'starts_at' => Carbon::now()->subHours(2),
        ]);
    }
}
