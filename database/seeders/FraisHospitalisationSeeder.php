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
            ['libelle' => 'Examen', 'montant' => 3500, 'description' => 'Analyse complète de sang.'],
            
            // Pharmacie
            ['libelle' => 'Pharmacie', 'montant' => 1500, 'description' => 'Antidouleur et antipyrétique'],

            // Chambres
            ['libelle' => 'Chambre Standard', 'montant' => 10000, 'description' => 'Chambre standard pour hospitalisation.'],
            ['libelle' => 'Chambre VIP', 'montant' => 25000, 'description' => 'Chambre de luxe avec services exclusifs.'],

            // Repas
            ['libelle' => 'Repas Standard', 'montant' => 2000, 'description' => 'Repas standard pour les patients.'],
            ['libelle' => 'Repas Diététique', 'montant' => 3500, 'description' => 'Repas adaptés aux besoins diététiques.'],

            

            // Visite
            ['libelle' => 'Visite d\'un Ami', 'montant' => 1000, 'description' => 'Frais liés à la visite d\'un ami pendant l\'hospitalisation.'],

            
        ];

        foreach ($frais as $fraisData) {
            FraisHospitalisation::create($fraisData);
        }
    }
}
