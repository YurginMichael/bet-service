<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        \App\Models\User::firstOrCreate(
            ['email' => 'user1@test.com'],
            [
                'name' => 'Тестовый Пользователь 1',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'balance' => 5000.00,
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'user2@test.com'],
            [
                'name' => 'Тестовый Пользователь 2',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'balance' => 1000.00,
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'poor@test.com'],
            [
                'name' => 'Бедный Пользователь',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'balance' => 10.00,
            ]
        );
    }
}
