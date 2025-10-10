<?php

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
        Schema::create('mini_jeu_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('mini_jeu_id');
            $table->integer('score')->default(0);
            $table->integer('duration_seconds')->nullable();
            $table->json('data')->nullable(); // stockage d'état ou replay
            $table->ipAddress('ip')->nullable();
            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['mini_jeu_id']);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('mini_jeu_id')->references('id')->on('mini_games')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mini_game_sessions');
    }
};
