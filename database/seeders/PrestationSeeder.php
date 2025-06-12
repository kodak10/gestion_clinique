<?php

namespace Database\Seeders;

use App\Models\CategoryPrestation;
use App\Models\Prestation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrestationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Liste des prestations à ajouter avec des catégories spécifiques
        $prestations = [
            // Catégorie: Consultations
            [
                'categorie' => 'Consultations',
                'prestations' => [
                    ['libelle' => 'Consultation Générale', 'montant' => 5000, 'description' => 'Consultation avec un médecin généraliste.'],
                    ['libelle' => 'Consultation Spécialisée', 'montant' => 7000, 'description' => 'Consultation spécialisée par un médecin expert.'],
                    ['libelle' => 'Consultation Pédiatrique', 'montant' => 6000, 'description' => 'Consultation avec un pédiatre.'],
                    ['libelle' => 'Consultation Dermatologique', 'montant' => 8000, 'description' => 'Consultation avec un dermatologue.'],
                ],
            ],

            // Catégorie: Chirurgie
            [
                'categorie' => 'Chirurgie',
                'prestations' => [
                    ['libelle' => 'Chirurgie Orthopédique', 'montant' => 25000, 'description' => 'Intervention chirurgicale pour correction orthopédique.'],
                    ['libelle' => 'Chirurgie Cardiaque', 'montant' => 35000, 'description' => 'Intervention chirurgicale du cœur.'],
                    ['libelle' => 'Chirurgie Esthétique', 'montant' => 18000, 'description' => 'Chirurgie pour des fins esthétiques.'],
                    ['libelle' => 'Chirurgie Viscerale', 'montant' => 30000, 'description' => 'Chirurgie des organes internes.'],
                ],
            ],

            // Catégorie: Imagerie Médicale
            [
                'categorie' => 'Imagerie médicale',
                'prestations' => [
                    ['libelle' => 'Radiographie', 'montant' => 4000, 'description' => 'Examen radiologique standard.'],
                    ['libelle' => 'Échographie', 'montant' => 6000, 'description' => 'Examen échographique pour visualisation des organes.'],
                    ['libelle' => 'Scanner', 'montant' => 10000, 'description' => 'Examen par scanner pour analyse détaillée.'],
                    ['libelle' => 'IRM', 'montant' => 12000, 'description' => 'Imagerie par résonance magnétique.'],
                ],
            ],

            // Catégorie: Analyses médicales
            [
                'categorie' => 'Analyses médicales / Laboratoire',
                'prestations' => [
                    ['libelle' => 'Analyse de Sang', 'montant' => 2500, 'description' => 'Analyse complète du sang.'],
                    ['libelle' => 'Analyse Urinaire', 'montant' => 2000, 'description' => 'Analyse des urines pour vérifier les paramètres.'],
                    ['libelle' => 'Test de Grossesse', 'montant' => 1500, 'description' => 'Test sanguin pour détecter une grossesse.'],
                    ['libelle' => 'Analyse de Cholestérol', 'montant' => 3000, 'description' => 'Analyse du taux de cholestérol dans le sang.'],
                ],
            ],
        ];

        // Insertion des prestations
        foreach ($prestations as $categorieData) {
            // Récupérer la catégorie par son nom
            $categorie = CategoryPrestation::where('nom', $categorieData['categorie'])->first();

            // Si la catégorie existe, ajouter les prestations
            if ($categorie) {
                foreach ($categorieData['prestations'] as $prestationData) {
                    Prestation::create([
                        'libelle' => $prestationData['libelle'],
                        'montant' => $prestationData['montant'],
                        'description' => $prestationData['description'],
                        'categorie_id' => $categorie->id,
                    ]);
                }
            }
        }
    }
}
