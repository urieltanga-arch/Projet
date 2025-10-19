<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('referred_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->integer('points_earned')->default(0);
            $table->timestamps();
        });

        // Ajouter une colonne pour savoir qui a parrainé l'utilisateur
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('referred_by')->nullable()->after('referral_code')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('referred_by');
        });
    }
};