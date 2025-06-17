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
        // Définir les catégories
        $categories = [
            'Chambres' => [
                ['libelle' => 'Chambre Standard', 'montant' => 10000, 'description' => 'Chambre standard pour hospitalisation.'],
                ['libelle' => 'Chambre VIP', 'montant' => 25000, 'description' => 'Chambre de luxe avec services exclusifs.'],
            ],
            'Repas' => [
                ['libelle' => 'Repas Standard', 'montant' => 2000, 'description' => 'Repas standard pour les patients.'],
                ['libelle' => 'Repas Diététique', 'montant' => 3500, 'description' => 'Repas adaptés aux besoins diététiques.'],
            ],
            'Examen' => [
                ['libelle' => 'Examen de Sang', 'montant' => 3500, 'description' => 'Analyse complète de sang.'],
                ['libelle' => 'Examen Scanner', 'montant' => 10000, 'description' => 'Scanner pour un examen détaillé.'],
            ],
            'Ami' => [
                ['libelle' => 'Visite d\'un Ami', 'montant' => 1000, 'description' => 'Frais liés à la visite d\'un ami pendant l\'hospitalisation.'],
            ],
            'Pharmacie' => [
                ['libelle' => 'Paracétamol 500mg (boite)', 'montant' => 1500, 'description' => 'Antidouleur et antipyrétique'],
                ['libelle' => 'Ibuprofène 400mg (boite)', 'montant' => 2000, 'description' => 'Anti-inflammatoire non stéroïdien'],
                ['libelle' => 'Amoxicilline 500mg (boite)', 'montant' => 3500, 'description' => 'Antibiotique à large spectre'],
                ['libelle' => 'Doliprane 1000mg (boite)', 'montant' => 2500, 'description' => 'Antalgique et antipyrétique'],
                ['libelle' => 'Smecta (sachets)', 'montant' => 1800, 'description' => 'Traitement des diarrhées'],
                ['libelle' => 'Vitamine C 500mg (boite)', 'montant' => 3000, 'description' => 'Complément vitaminique'],
                ['libelle' => 'Dafalgan 1g (boite)', 'montant' => 2200, 'description' => 'Antidouleur et antipyrétique'],
            ],
        ];


        // Ajouter les catégories et les frais
        foreach ($categories as $categoryName => $frais) {
            // Créer la catégorie
            $category = CategoryFrais_Hospitalisation::create([
                'nom' => $categoryName,
            ]);

            // Ajouter les frais à cette catégorie
            foreach ($frais as $fraisData) {
                FraisHospitalisation::create([
                    'category_id' => $category->id,
                    'libelle' => $fraisData['libelle'],
                    'montant' => $fraisData['montant'],
                    'description' => $fraisData['description'],
                ]);
            }
        }
    }
}
