<?php

namespace Database\Seeders;

use App\Models\Medicament;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MedicamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicaments = [
            [
                'code' => 'MED001',
                'nom' => 'Paracétamol 500mg',
                'unite_mesure' => 'Comprimé',
                'prix_achat' => 100,
                'prix_vente' => 150,
                'stock' => 200,
                'stock_alerte' => 20,
                'date_peremption' => Carbon::now()->addMonths(6),
            ],
            [
                'code' => 'MED002',
                'nom' => 'Amoxicilline 1g',
                'unite_mesure' => 'Capsule',
                'prix_achat' => 300,
                'prix_vente' => 400,
                'stock' => 150,
                'stock_alerte' => 15,
                'date_peremption' => Carbon::now()->addMonths(8),
            ],
            [
                'code' => 'MED003',
                'nom' => 'Ibuprofène 200mg',
                'unite_mesure' => 'Comprimé',
                'prix_achat' => 80,
                'prix_vente' => 120,
                'stock' => 300,
                'stock_alerte' => 30,
                'date_peremption' => Carbon::now()->addMonths(5),
            ],
            [
                'code' => 'MED004',
                'nom' => 'Metformine 500mg',
                'unite_mesure' => 'Comprimé',
                'prix_achat' => 150,
                'prix_vente' => 200,
                'stock' => 100,
                'stock_alerte' => 10,
                'date_peremption' => Carbon::now()->addYear(),
            ],
            [
                'code' => 'MED005',
                'nom' => 'Salbutamol 100mcg',
                'unite_mesure' => 'Inhalateur',
                'prix_achat' => 600,
                'prix_vente' => 750,
                'stock' => 50,
                'stock_alerte' => 5,
                'date_peremption' => Carbon::now()->addMonths(9),
            ],
        ];

        foreach ($medicaments as $medicament) {
            Medicament::create($medicament);
        }
    }
}
