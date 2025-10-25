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
    Schema::create('games', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->string('folder_name'); // nom du dossier du jeu
        $table->string('thumbnail')->nullable(); // image de prévisualisation
        $table->integer('points')->default(1);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
