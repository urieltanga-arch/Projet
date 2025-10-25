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
        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->text('description');
            $table->text('reponse_employee')->nullable();
            $table->enum('type_probleme', [
                'Problème de qualité',
                'Quantité incorrecte',
                'Retard de livraison',
                'Mauvaise commande',
                'Service client',
                'Autre'
            ]);
            $table->enum('statut', [
                'non_traitee',
                'en_cours',
                'resolue',
                'fermee'
            ])->default('non_traitee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclamations');
    }
};