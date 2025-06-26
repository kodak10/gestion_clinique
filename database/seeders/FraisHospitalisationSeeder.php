<?php

namespace Database\Seeders;

use App\Models\FraisHospitalisation;
use App\Models\CategoryFrais_Hospitalisation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FraisHospitalisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $frais = [

            // Examens
            ['libelle' => 'Examen', 'montant' => 0],
            
            // Pharmacie
            ['libelle' => 'Pharmacie', 'montant' => 0],

            // Chambres
            ['libelle' => 'Chambre Standard', 'montant' => 10000],
            ['libelle' => 'Chambre VIP', 'montant' => 25000],

            // Repas
            ['libelle' => 'Repas Standard', 'montant' => 2000],
            ['libelle' => 'Repas Diététique', 'montant' => 3500],

            

            // Visite
            ['libelle' => 'Visite d\'un Ami', 'montant' => 1000],

            
        ];

        foreach ($frais as $fraisData) {
            FraisHospitalisation::create($fraisData);
        }
    }
}
