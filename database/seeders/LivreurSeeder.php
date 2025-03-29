<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Livreur;
use Faker\Factory as Faker;

class LivreurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer une instance de Faker
        $faker = Faker::create();

        // Créer 10 livreurs avec des données aléatoires
        foreach (range(1, 10) as $index) {
            Livreur::create([
                'nom' => $faker->lastName,
                'prenoms' => $faker->firstName,
                'type' => $faker->randomElement(['Interne', 'Externe']),
                'numero_telephone' => $faker->phoneNumber,
                'lieu_residence' => $faker->address,
                'informations_complementaires' => $faker->sentence,
                'status' => $faker->randomElement(['Actif', 'Inactif']),
            ]);
        }
    }
}
