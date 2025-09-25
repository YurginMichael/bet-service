<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Связь с пользователем
            $table->foreignId('event_id')->constrained()->onDelete('cascade'); // Связь с событием
            $table->string('outcome'); // Выбранный исход ставки
            $table->decimal('amount', 10, 2); // Сумма ставки
            $table->decimal('coefficient', 8, 4); // Коэффициент на момент ставки
            $table->decimal('potential_win', 10, 2); // Потенциальный выигрыш
            $table->enum('status', ['pending', 'won', 'lost', 'cancelled'])->default('pending');
            $table->string('idempotency_key')->unique(); // Ключ идемпотентности
            $table->timestamps();
            
            // Индексы для оптимизации запросов
            $table->index(['user_id', 'created_at']);
            $table->index(['event_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bets');
    }
};
