<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Livreur;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class LivreurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {

            // 1. Créer d'abord un utilisateur
            $phoneNumber = '225' . substr(preg_replace('/\D/', '', $faker->phoneNumber), -8); // nettoie et garde les 8 derniers chiffres
            $user = User::create([
                'name' => $faker->firstName . ' ' . $faker->lastName,
                'phone_number' => $phoneNumber,
                'password' => Hash::make('password'), // un mot de passe par défaut
                'status' => 'Actif',
                'image' => 'storage/app/public/images/profile-default.webp',
            ]);

            // 2. Lui assigner le rôle "livreur"
            $user->assignRole('livreur');

            // 3. Créer ensuite le livreur rattaché à cet utilisateur
            Livreur::create([
                'user_id' => $user->id,
                'code' => 'LIV_' . strtoupper(substr(preg_replace('/\D/', '', uniqid()), -4)),
                'nom' => $faker->lastName,
                'prenoms' => $faker->firstName,
                'type' => $faker->randomElement(['Interne', 'Externe']),
                'numero_telephone' => $phoneNumber,
                'lieu_residence' => $faker->address,
                'informations_complementaires' => $faker->sentence,
                'status' => $faker->randomElement(['Actif', 'Inactif']),
            ]);
        }
    }
}
