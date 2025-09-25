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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Название события
            $table->text('description')->nullable(); // Описание события
            $table->json('outcomes'); // JSON с возможными исходами и коэффициентами
            $table->timestamp('starts_at'); // Время начала события
            $table->timestamp('ends_at')->nullable(); // Время окончания события
            $table->enum('status', ['pending', 'active', 'finished', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
