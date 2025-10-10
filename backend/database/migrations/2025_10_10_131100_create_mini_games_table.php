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
        Schema::create('mini_jeux', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('type')->nullable(); // ex: puzzle, memory, trivia
            $table->json('meta')->nullable(); // configuration spécifique au mini-jeu
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mini_jeux');
    }
};
