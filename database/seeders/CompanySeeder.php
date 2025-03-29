<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Créer quelques compagnies fictives
        Company::create([
            'name' => 'Thatcher Keel',
            'email' => 'thatcher.keel@example.com',
            'logo' => 'path/to/logo1.png',
            'additional_info' => 'Compagnie de transport de marchandises.'
        ]);

        Company::create([
            'name' => 'Global Express',
            'email' => 'global.express@example.com',
            'logo' => 'path/to/logo2.png',
            'additional_info' => 'Service express pour le transport rapide.'
        ]);

        Company::create([
            'name' => 'Speedy Logistics',
            'email' => 'speedy.logistics@example.com',
            'logo' => 'path/to/logo3.png',
            'additional_info' => 'Logistique rapide et fiable.'
        ]);
    }
}
