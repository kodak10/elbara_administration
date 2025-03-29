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
        Schema::create('livreurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenoms');
            $table->string('numero_telephone');
            $table->string('type')->nullable(); // Externe ou Interne
            $table->string('lieu_residence')->nullable();
            $table->string('photo')->nullable(); // Photo du livreur
            $table->string('status')->default('actif'); // Status du livreur (actif, suspendu, etc.)
            $table->text('informations_complementaires')->nullable(); // Informations supplémentaires
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livreurs');
    }
};
