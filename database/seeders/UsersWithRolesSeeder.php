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
            [
                'name' => 'Livreur User 1',
                'email' => 'livreur1@example.com',
                'password' => 'Elbara2025',
                'phone_number' => '0123456789',
                'status' => 'actif',
                'role' => 'livreur',
            ],
            [
                'name' => 'Livreur User 2',
                'email' => 'livreur2@example.com',
                'password' => 'Elbara2025',
                'phone_number' => '0123456789',
                'status' => 'actif',
                'role' => 'livreur',
            ],
            [
                'name' => 'Regular User 1',
                'email' => 'user1@example.com',
                'password' => 'Elbara2025',
                'phone_number' => '0123456789',
                'status' => 'inactif',
                'role' => 'user',
            ],
            [
                'name' => 'Regular User 2',
                'email' => 'user2@example.com',
                'password' => 'Elbara2025',
                'phone_number' => '0123456789',
                'status' => 'inactif',
                'role' => 'user',
            ]
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
