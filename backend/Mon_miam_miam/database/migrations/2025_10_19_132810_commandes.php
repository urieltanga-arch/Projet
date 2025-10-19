<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_commande')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('statut', ['en_attente', 'en_preparation', 'prete', 'en_livraison', 'livree', 'annulee'])->default('en_attente');
            $table->decimal('montant_total', 10, 2);
            $table->text('items'); // JSON des items commandés
            $table->text('adresse_livraison')->nullable();
            $table->string('telephone_contact');
            $table->text('notes')->nullable();
            $table->timestamp('preparee_a')->nullable();
            $table->timestamp('livree_a')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};