<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $users = User::pluck('id')->toArray(); // Liste des utilisateurs existants

        for ($i = 0; $i < 10; $i++) {
            Order::create([
                'user_id' => $faker->randomElement($users),
                'depart_long' => $faker->longitude,
                'depart_lat' => $faker->latitude,
                'destination_long' => $faker->longitude,
                'destination_lat' => $faker->latitude,
                'depart_adresse' => $faker->address,
                'destination_adresse' => $faker->address,
                'numero_destinateur' => $faker->phoneNumber,
                'numero_destinataire' => $faker->phoneNumber,
                'libelle' => $faker->sentence,
                'montant' => $faker->randomFloat(2, 1000, 5000),
                'distance_km' => $faker->randomFloat(2, 1, 50),
                'duree_minutes' => $faker->numberBetween(10, 120),
                'reference_commande' => strtoupper(Str::random(10)),
                'date' => $faker->date,
                'engin' => $faker->randomElement(['Moto', 'Camion', 'Tricycle']),
                'type_course' => $faker->randomElement(['Course', 'Livraison']),
                'status_orders' => $faker->randomElement(['En attente', 'Acceptée', 'En cours', 'Livrée', 'Annulée', 'Échouée']),
                'status_payment' => $faker->randomElement(['Non payé', 'Payé']),
                'mode_payment' => $faker->randomElement(['Espèces', 'Mobile Money', 'Carte Bancaire']),
                'transaction_id' => strtoupper(Str::random(15)),
                'date_paiement' => $faker->dateTime,
                'instructions' => $faker->sentence,
                'status_livreur' => $faker->randomElement(['En route', 'Arrivé', 'Livré', 'En attente']),
                'livreur_id' => $faker->randomElement($users),
                'admin_id' => $faker->randomElement($users),
                'notation' => $faker->numberBetween(1, 5),
                'historique_statut' => json_encode([
                    ['status' => 'En attente', 'date' => $faker->dateTime],
                    ['status' => 'En cours', 'date' => $faker->dateTime],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
