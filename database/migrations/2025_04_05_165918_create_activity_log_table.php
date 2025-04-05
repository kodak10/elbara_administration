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
        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable();
            $table->text('description')->nullable();
            $table->text('event')->nullable(); // Le type d'événement (created, updated, deleted, etc.)
            $table->text('properties')->nullable(); // Informations détaillées sur le changement
            $table->uuid('batch_uuid')->nullable(); // Pour grouper des logs
        
            // Clé étrangère vers l'entité qui a causé l'activité (ex: User)
            $table->nullableMorphs('causer');
        
            // Clé étrangère vers l'entité concernée (ex: User, Order)
            $table->nullableMorphs('subject');
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_log');
    }
};
