<?php

namespace Database\Seeders;

use App\Models\Medecin;
use App\Models\Specialite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MedecinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $noms = [
            'Dr Kouadio Jean-Baptiste',
            'Dr Traoré Awa',
            'Dr Koné Ibrahim',
            'Dr N’Guessan Marie',
            'Dr Bamba Souleymane',
            'Dr Diomandé Serge',
            'Dr Aka Blanche',
            'Dr Yao Fernand',
            'Dr Koffi Nadège',
            'Dr Soro Mamadou'
        ];

        foreach ($noms as $nom) {
            Medecin::create([
                'matricule' => strtoupper(Str::random(6)),
                'nom_complet' => $nom,
                'telephone' => '07' . rand(10000000, 99999999),
                'specialite_id' => Specialite::inRandomOrder()->first()->id,
            ]);
        }
    }
}
