<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Créer les rôles
        Role::create(['name' => 'user']);
        Role::create(['name' => 'livreur']);
        Role::create(['name' => 'superAdmin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'manager']);
    }
}
