<?php

namespace Database\Seeders;

use App\Models\Gare;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Créer quelques gares fictives
        Gare::create([
            'nom' => 'Gare de Abidjan',
            'contact_01' => '0102030405',
            'contact_02' => '0607080910',
            'localisation' => 'Abidjan, Côte d\'Ivoire',
            'informations_complementaires' => 'Gare principale de la ville.'
        ]);

        Gare::create([
            'nom' => 'Gare de Yamoussoukro',
            'contact_01' => '0203040506',
            'contact_02' => '0708091011',
            'localisation' => 'Yamoussoukro, Côte d\'Ivoire',
            'informations_complementaires' => 'Gare centrale de la capitale politique.'
        ]);

        Gare::create([
            'nom' => 'Gare de San Pedro',
            'contact_01' => '0304050607',
            'contact_02' => '0809101112',
            'localisation' => 'San Pedro, Côte d\'Ivoire',
            'informations_complementaires' => 'Gare régionale pour les voyages interurbains.'
        ]);
    }
}
