<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{

    public function run(): void
    {
        \App\Models\Event::create([
            'title' => 'Матч: Спартак vs ЦСКА',
            'description' => 'Футбольный матч чемпионата России',
            'outcomes' => [
                'Победа Спартака' => 2.10,
                'Ничья' => 3.20,
                'Победа ЦСКА' => 2.85,
            ],
            'starts_at' => now()->addHours(3),
            'ends_at' => now()->addHours(4),
            'status' => 'active',
        ]);

        \App\Models\Event::create([
            'title' => 'Матч: Зенит vs Краснодар',
            'description' => 'Футбольный матч чемпионата России',
            'outcomes' => [
                'Победа Зенита' => 1.85,
                'Ничья' => 3.50,
                'Победа Краснодара' => 3.80,
            ],
            'starts_at' => now()->addHours(7),
            'ends_at' => now()->addHours(8),
            'status' => 'active',
        ]);

        \App\Models\Event::create([
            'title' => 'Теннис: Медведев vs Джиокович',
            'description' => 'Финал турнира ATP',
            'outcomes' => [
                'Победа Медведева' => 2.50,
                'Победа Джиоковича' => 1.60,
            ],
            'starts_at' => now()->addHours(13),
            'ends_at' => now()->addHours(15),
            'status' => 'active',
        ]);

        \App\Models\Event::create([
            'title' => 'Баскетбол: Лейкерс vs Уорриорз',
            'description' => 'Матч NBA',
            'outcomes' => [
                'Победа Лейкерс' => 1.90,
                'Победа Уорриорз' => 1.95,
            ],
            'starts_at' => now()->addDays(1)->addHours(1),
            'ends_at' => now()->addDays(1)->addHours(3),
            'status' => 'active',
        ]);

        \App\Models\Event::create([
            'title' => 'Завершенный матч',
            'description' => 'Этот матч уже начался',
            'outcomes' => [
                'Исход 1' => 2.00,
                'Исход 2' => 2.00,
            ],
            'starts_at' => now()->subHours(1),
            'ends_at' => now()->addHours(1),
            'status' => 'active',
        ]);
    }
}
