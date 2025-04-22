<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Livreur;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $user = User::find(5); // Utilisateur avec ID 5
        $livreur = Livreur::find(3); // Livreur avec ID 3

        // Vérifier si l'utilisateur et le livreur existent
        if ($user && $livreur) {
            // Statuts de commande
            $statuses = [
                'En attente', 
                'Assignée', 
                'Acceptée', 
                'En cours', 
                'Terminée', 
                'Annulée', 
                'Échouée'
            ];

            // Créer 3 commandes pour chaque statut
            foreach ($statuses as $status) {
                for ($i = 0; $i < 3; $i++) {
                    Order::create([
                        'user_id' => $user->id,
                        'depart_long' => 5.123456,
                        'depart_lat' => -4.123456,
                        'destination_long' => 5.654321,
                        'destination_lat' => -4.654321,
                        'depart_adresse' => 'Adresse de départ',
                        'destination_adresse' => 'Adresse de destination',
                        'numero_destinateur' => '123456789',
                        'numero_destinataire' => '987654321',
                        'libelle' => 'Libellé de la commande',
                        'montant' => 100.50,
                        'distance_km' => 5.5,
                        'duree_minutes' => 15,
                        'reference_commande' => 'REF-' . strtoupper(uniqid()),
                        'date' => Carbon::now(),
                        'engin' => 'Moto', // Ou 'Camion' ou 'Tricycle'
                        'type_course' => 'Course', // Ou 'Livraison' ou 'Expédition'
                        'status_orders' => $status,
                        'status_payment' => 'Non payé',
                        'mode_payment' => 'Espèces',
                        'transaction_id' => null,
                        'date_paiement' => null,
                        'instructions' => 'Aucune instruction',
                        'status_livreur' => 'En attente',
                        'livreur_id' => $livreur->id,
                        'admin_id' => $user->id,
                        'notation' => null,
                        'historique_statut' => json_encode([
                            'status' => $status,
                            'timestamp' => Carbon::now()
                        ]),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        } else {
            $this->command->error("Utilisateur ou livreur introuvable.");
        }
    }
}
