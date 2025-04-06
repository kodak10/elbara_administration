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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('depart_long', 10, 7);
            $table->decimal('depart_lat', 10, 7);
            $table->decimal('destination_long', 10, 7);
            $table->decimal('destination_lat', 10, 7);
            $table->string('depart_adresse')->nullable();
            $table->string('destination_adresse')->nullable();
            $table->string('numero_destinateur')->nullable();
            $table->string('numero_destinataire')->nullable();
            $table->string('libelle')->nullable();
            $table->decimal('montant', 10, 2);
            $table->decimal('distance_km', 5, 2)->nullable();
            $table->integer('duree_minutes')->nullable();
            $table->string('reference_commande')->unique();
            $table->date('date');
            $table->enum('engin', ['Moto', 'Camion', 'Tricycle']);
            $table->enum('type_course', ['Course', 'Livraison']);
            $table->enum('status_orders', ['En attente', 'Acceptée', 'En cours', 'Livrée', 'Annulée', 'Échouée']);
            $table->enum('status_payment', ['Non payé', 'Payé']);
            $table->enum('mode_payment', ['Espèces', 'Mobile Money', 'Carte Bancaire']);
            $table->string('transaction_id')->nullable();
            $table->timestamp('date_paiement')->nullable();
            $table->text('instructions')->nullable();
            $table->enum('status_livreur', ['En route', 'Arrivé', 'Livré', 'En attente'])->nullable();
            $table->foreignId('livreur_id')->nullable()->constrained('livreurs')->onDelete('set null');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->tinyInteger('notation')->nullable();
            $table->json('historique_statut')->nullable();
            $table->timestamps();
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
