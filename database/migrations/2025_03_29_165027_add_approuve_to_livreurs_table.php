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
        Schema::table('livreurs', function (Blueprint $table) {
            $table->boolean('approuve')->default(false); // Ajout de la colonne 'approuve'

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livreurs', function (Blueprint $table) {
            //
        });
    }
};
