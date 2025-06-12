<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $patients = [
            [
                'nom' => 'Kouassi',
                'prenoms' => 'Jean',
                'date_naissance' => '1985-06-15',
                'domicile' => 'Abidjan, Cocody',
                'sexe' => 'M',
                'profession' => 'Ingénieur',
                'ethnie' => 'Akan',
                'religion' => 'Chrétien',
                'groupe_rhesus' => 'A+',
                'contact_urgence' => 'Kouadio Paul',
                'contact_patient' => '0708091011',
                'contact_urgence' => 'Kouassi Jean',
            ],
            [
                'nom' => 'Yao',
                'prenoms' => 'Amina',
                'date_naissance' => '1992-03-22',
                'domicile' => 'Abidjan, Yopougon',
                'sexe' => 'F',
                'profession' => 'Enseignante',
                'ethnie' => 'Baoulé',
                'religion' => 'Musulmane',
                'groupe_rhesus' => 'B+',
                'assurance_id' => 2,
                'taux_couverture' => 80,
                'matricule_assurance' => 'CNPS12345',
                'contact_patient' => '0708091011',
                'contact_urgence' => 'Kouassi Jean',
            ],
            [
                'nom' => 'Bamba',
                'prenoms' => 'Moussa',
                'date_naissance' => '1978-11-05',
                'domicile' => 'Bouaké',
                'sexe' => 'M',
                'profession' => 'Commerçant',
                'ethnie' => 'Malinké',
                'religion' => 'Musulman',
                'groupe_rhesus' => 'O+',
                'envoye_par' => 'Dr. Koné',
                'contact_patient' => '0708091011',
                'contact_urgence' => 'Kouassi Jean',
            ],
            [
                'nom' => 'Dosso',
                'prenoms' => 'Fatou',
                'date_naissance' => '1995-08-30',
                'domicile' => 'Abidjan, Treichville',
                'sexe' => 'F',
                'profession' => 'Infirmière',
                'ethnie' => 'Sénoufo',
                'religion' => 'Animiste',
                'groupe_rhesus' => 'AB+',
                'assurance_id' => 4,
                'taux_couverture' => 70,
                'matricule_assurance' => 'ARACI67890',
                'contact_patient' => '0708091011',
                'contact_urgence' => 'Kouassi Jean',
            ],
            [
                'nom' => 'Konan',
                'prenoms' => 'Affoué',
                'date_naissance' => '1988-04-17',
                'domicile' => 'San Pedro',
                'sexe' => 'F',
                'profession' => 'Fonitrice',
                'ethnie' => 'Bété',
                'religion' => 'Chrétienne',
                'groupe_rhesus' => 'A-',
                'contact_urgence' => 'Konan Koffi',
                'contact_patient' => '0708091011',
                'contact_urgence' => 'Kouassi Jean',
            ],
        ];

        foreach ($patients as $patient) {
            Patient::create($patient);
        }
    }
}
