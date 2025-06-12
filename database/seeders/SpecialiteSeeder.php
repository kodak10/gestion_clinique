<?php

namespace Database\Seeders;

use App\Models\Specialite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialites = [
            'Cardiologie',
            'Dermatologie',
            'Gynécologie',
            'Pédiatrie',
            'Neurologie',
            'Radiologie',
            'Anesthésie',
            'Ophtalmologie',
            'Chirurgie générale',
            'Orthopédie',
            'Médecine Générale',
            'ORL (Oto-Rhino-Laryngologie)',
            'Gastro-entérologie',
            'Endocrinologie',
            'Néphrologie',
            'Hématologie',
            'Rhumatologie',
            'Urologie',
            'Allergologie',
            'Immunologie',
        ];

        
        foreach ($specialites as $nom) {
            Specialite::firstOrCreate(['nom' => $nom]);
        }

    }
}
