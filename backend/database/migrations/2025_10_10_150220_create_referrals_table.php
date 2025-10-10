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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id(); // Clé primaire standard 'id'
            // parrain (referrer) and filleul (referred)
            $table->unsignedBigInteger('id_parrain');
            $table->unsignedBigInteger('id_filleul');
            // date of referral
            $table->timestamp('date_parrainage')->useCurrent();
            // status of the referral
            $table->enum('etat', ['en_attente', 'recompense_obtenue'])->default('en_attente');

            // indexes & foreign keys
            $table->index(['id_parrain']);
            $table->index(['id_filleul']);
            $table->foreign('id_parrain')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_filleul')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
