<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehiculeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vehicule_types')->insert([
            [
                'title' => 'Moto',
                'price_per_km' => 100.00,
                'additional_price' => 0,
                'risk_price' => 50.00,
                'image_path' => 'images/vehicules/moto.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Moto Taxi',
                'price_per_km' => 120.00,
                'additional_price' => 10.00,
                'risk_price' => 60.00,
                'image_path' => 'images/vehicules/moto_taxi.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Tricycle',
                'price_per_km' => 150.00,
                'additional_price' => 20.00,
                'risk_price' => 80.00,
                'image_path' => 'images/vehicules/tricycle.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Camion',
                'price_per_km' => 300.00,
                'additional_price' => 50.00,
                'risk_price' => 150.00,
                'image_path' => 'images/vehicules/camion.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
