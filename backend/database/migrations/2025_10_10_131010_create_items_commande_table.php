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
        Schema::create('item_commande', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_commande');
            $table->unsignedBigInteger('id_plat');
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire', 8, 2);
            $table->decimal('prix_total', 10, 2);
            $table->timestamps();

            $table->index(['id_commande']);
            $table->index(['id_plat']);

            $table->foreign('id_commande')->references('id')->on('commande')->onDelete('cascade');
            $table->foreign('id_plat')->references('id')->on('plats')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_commande');
    }
};
