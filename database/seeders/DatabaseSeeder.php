<?php

namespace Database\Seeders;

use App\Models\Livreur;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


       


        $this->call([
            //CompanySeeder::class,
            //GareSeeder::class,
            //CompanyGareSeeder::class,
            RolesSeeder::class,
            UsersWithRolesSeeder::class,
            //OrdersTableSeeder::class,
            LivreurSeeder::class,
            OrderSeeder::class
        ]);

       
    }
}
