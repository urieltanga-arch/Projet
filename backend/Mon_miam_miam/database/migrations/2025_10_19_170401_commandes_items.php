<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
    {
        Schema::create('commande_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained()->onDelete('cascade');
            $table->foreignId('plat_id')->nullable()->constrained();
            $table->string('nom'); // Nom du plat au moment de la commande
            $table->integer('quantite');
            $table->decimal('prix_unitaire', 10, 2);
            $table->text('instructions')->nullable();
            $table->timestamps();
            
            $table->index('commande_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('commande_items');
    }
};
