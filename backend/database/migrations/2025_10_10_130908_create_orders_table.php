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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // utilisateur qui a passé la commande
            $table->unsignedBigInteger('user_id');
            // montant total
            $table->decimal('montant_total', 10, 2)->default(0);
            // statut de la commande
            $table->enum('status', ['en_attente', 'en_cours', 'terminee','cancelled'])->default('en_attente');
            // adresse de livraison ou informations de retrait
            $table->text('addresse_commande')->nullable();
            // méthode de paiement
            $table->enum('payment_method', ['cash', 'mobile_money', 'carte'])->nullable();
            // référence éventuelle au parrainage
            $table->unsignedBigInteger('referral_id')->nullable();

            $table->timestamp('placed_at')->useCurrent();
            $table->timestamps();

            // indexes & foreign keys
            $table->index('user_id');
            $table->index('referral_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('referral_id')->references('referral_id')->on('referrals')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
