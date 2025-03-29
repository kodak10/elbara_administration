<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Gare;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyGareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Récupérer toutes les gares et compagnies
        $gares = Gare::all();
        $compagnies = Company::all();

        // Associer les gares et compagnies
        // Par exemple, chaque gare aura 2 compagnies associées
        foreach ($gares as $gare) {
            $gare->compagnies()->sync(
                $compagnies->random(2)->pluck('id')
            );
        }
    }
}
