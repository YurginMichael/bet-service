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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Связь с пользователем
            $table->foreignId('bet_id')->nullable()->constrained()->onDelete('set null'); // Связь со ставкой (может быть null для депозитов)
            $table->enum('type', ['bet_placed', 'bet_won', 'bet_lost', 'deposit', 'withdrawal']); // Тип транзакции
            $table->decimal('amount', 10, 2); // Сумма транзакции (положительная для пополнений, отрицательная для списаний)
            $table->decimal('balance_before', 10, 2); // Баланс до транзакции
            $table->decimal('balance_after', 10, 2); // Баланс после транзакции
            $table->text('description')->nullable(); // Описание транзакции
            $table->string('reference_id')->nullable(); // Внешний ID для связи с платежными системами
            $table->timestamps();
            
            // Индексы
            $table->index(['user_id', 'created_at']);
            $table->index(['bet_id']);
            $table->index(['type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
