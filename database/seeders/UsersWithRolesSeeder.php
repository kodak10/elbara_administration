<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersWithRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Récupérer tous les rôles disponibles
        $roles = Role::all();

        // Créer plusieurs utilisateurs avec des rôles spécifiques
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => 'Elbara2025',
                'phone_number' => '0123456789',
                'status' => 'actif',
                'role' => 'superAdmin',
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => 'Elbara2025',
                'phone_number' => '0123456789',
                'status' => 'actif',
                'role' => 'admin',
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'password' => 'Elbara2025',
                'phone_number' => '0123456789',
                'status' => 'actif',
                'role' => 'manager',
            ],
           
        ];

        // Créer les utilisateurs et leur assigner des rôles
        foreach ($users as $userData) {
            // Créer l'utilisateur
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'phone_number' => $userData['phone_number'],  // Ajouter le numéro de téléphone
                'status' => $userData['status'],  // Ajouter le status
            ]);

            // Assigner le rôle à l'utilisateur
            $user->assignRole($userData['role']);
        }
    }
}
