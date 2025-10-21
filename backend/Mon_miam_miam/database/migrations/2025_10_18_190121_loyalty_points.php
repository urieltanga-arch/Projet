<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table des points
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->integer('points');
            $table->enum('type', ['earned', 'spent'])->default('earned');
            $table->timestamps();
            $table->index(['user_id', 'type']);
        });

        // Ajouter le code de parrainage aux utilisateurs
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code')->unique()->nullable()->after('email');
            $table->integer('total_points')->default(0)->after('referral_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_points');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['referral_code', 'total_points']);
        });
    }
};