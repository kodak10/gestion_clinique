<?php

namespace Database\Seeders;

use App\Models\CategoryPrestation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryPrestationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Consultations',
            'Chirurgie',
            'Imagerie médicale',
            'Analyses médicales / Laboratoire',
            'Maternité / Gynécologie',
            'Soins infirmiers',
            'Kinésithérapie / Rééducation',
            'Consultations spécialisées',
        ];

        foreach ($categories as $categorie) {
            CategoryPrestation::create([
                'nom' => $categorie,
            ]);
        }

    
    }
}
