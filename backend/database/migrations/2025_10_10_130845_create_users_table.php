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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // informations basiques
            $table->string('nom');
            $table->string('prenom')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('mot_de_passe');

            // contact & adresse
            $table->string('telephone')->nullable();
            

            // parrainage
            $table->string('referral_code')->nullable()->unique();
            $table->foreignId('referred_by')->nullable()->constrained('users')->nullOnDelete();

            // rôle / état
            $table->enum('role', ['customer', 'staff', 'admin'])->default('customer');
            $table->boolean('is_active')->default(true);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
