<?php

namespace Database\Seeders;

use App\Models\Examen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExamenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $examens = [
            ['nom' => 'Glycémie à jeun',         'prix' => 2000],
            ['nom' => 'NFS',                     'prix' => 3500],
            ['nom' => 'Créatinine',              'prix' => 2500],
            ['nom' => 'Bilan hépatique',         'prix' => 7000],
            ['nom' => 'Ionogramme sanguin',      'prix' => 6000],
            ['nom' => 'Test VIH',                'prix' => 1500],
            ['nom' => 'Groupage sanguin',        'prix' => 3000],
            ['nom' => 'Bilan lipidique',         'prix' => 5000],
            ['nom' => 'ECG',                     'prix' => 8000],
            ['nom' => 'Radiographie thoracique', 'prix' => 10000],
        ];

        foreach ($examens as $examen) {
            Examen::create([
                'code' => strtoupper(Str::random(6)),
                'nom' => $examen['nom'],
                'prix' => $examen['prix'],
            ]);
        }
    }
}
