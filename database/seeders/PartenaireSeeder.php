<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PartenaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('partenaires')->insert([
            [
                'name' => 'Advice Solutions',
                'short_description' => 'Experts en IT et services digitaux',
                'description' => 'Advice propose des solutions IT adaptées à vos besoins, incluant développement web, mobile, et conseils en stratégie digitale.',
                'logo_url' => 'partners/advice_logo.png', // Emplacement du logo
                'phone' => '+225 0707070707',
                'email' => 'contact@advice.ci',
                'address' => 'Abidjan, Côte d\'Ivoire',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'GreenTech CI',
                'short_description' => 'Innovation environnementale',
                'description' => 'GreenTech développe des solutions écologiques pour un avenir durable.',
                'logo_url' => 'partners/greentech_logo.png',
                'phone' => '+225 0101010101',
                'email' => 'info@greentech.ci',
                'address' => 'Bouaké, Côte d\'Ivoire',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'TransExpress',
                'short_description' => 'Livraison rapide',
                'description' => 'TransExpress est votre partenaire pour la livraison rapide et fiable partout en Côte d\'Ivoire.',
                'logo_url' => 'partners/transexpress_logo.png',
                'phone' => '+225 0505050505',
                'email' => 'support@transexpress.ci',
                'address' => 'Yamoussoukro, Côte d\'Ivoire',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
