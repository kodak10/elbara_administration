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
        Schema::table('demande_livreurs', function (Blueprint $table) {
            $table->boolean('a_moto')->nullable(); // Ajoute la colonne has_motorcycle avec une valeur true/false

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demande_livreur', function (Blueprint $table) {
            //
        });
    }
};
