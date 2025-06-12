<?php

namespace Database\Seeders;

use App\Models\Assurance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assurances = [
            [
                'name' => 'NSIA Assurance',
                'taux' => 80.00,
                'phone_number' => '0102030405',
                'email' => 'contact@nsiassurance.ci',
                'siege' => 'Abidjan Plateau',
                'image' => 'nsiassurance.png',
            ],
            [
                'name' => 'SAHAM Assurance',
                'taux' => 70.00,
                'phone_number' => '0708091011',
                'email' => 'info@saham.ci',
                'siege' => 'Abidjan Cocody',
                'image' => 'saham.png',
            ],
            [
                'name' => 'ALLIANZ Côte d’Ivoire',
                'taux' => 75.50,
                'phone_number' => '0506070809',
                'email' => 'support@allianz.ci',
                'siege' => 'Abidjan Marcory',
                'image' => 'allianz.png',
            ],
            [
                'name' => 'SUNU Assurance',
                'taux' => 65.00,
                'phone_number' => '0203040506',
                'email' => 'contact@sunu.ci',
                'siege' => 'Abidjan Treichville',
                'image' => 'sunu.png',
            ],
            [
                'name' => 'MCI Santé',
                'taux' => 60.00,
                'phone_number' => '0809091011',
                'email' => 'info@mci.ci',
                'siege' => 'Abidjan Yopougon',
                'image' => 'mci.png',
            ],
        ];

        foreach ($assurances as $assurance) {
            Assurance::create($assurance);
        }
    }
}
