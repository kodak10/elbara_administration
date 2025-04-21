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
            $table->string('code'); 
            $table->string('nom');
            $table->string('prenoms');
            $table->string('numero_telephone');
            $table->string('type')->nullable(); 
            $table->string('lieu_residence')->nullable();
            $table->string('photo')->nullable(); 
            $table->string('status')->default('actif'); 
            $table->text('informations_complementaires')->nullable(); 
            $table->boolean('approuve')->default(false); 
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

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
