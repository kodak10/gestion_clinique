<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\ExamenSeeder;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    

    public function run()
    {
        // Création des rôles
        $roles = ['Developpeur', 'Admin','Manager', 'Comptable','Facturié', 'Respo Caissière', 'Caissière', 'Receptionniste', 'Pharmacien'];
        
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Création d'un admin par défaut
        $admin = \App\Models\User::create([
            'name' => 'Admin Parfait',
            'pseudo' => 'Admin',
            'email' => 'admin@example.com',
            'phone_number' => '123456789',
            'password' => bcrypt('password'),
            'status' => 'Actif'
        ]);

        $admin->assignRole('Admin');

        $this->call([
            //SpecialiteSeeder::class,
            //MedecinSeeder::class,
            //AssuranceSeeder::class,
            //PatientSeeder::class,
            //SpecialiteSeeder::class,
            //CategoryPrestationsSeeder::class,
            //PrestationSeeder::class,
            FraisHospitalisationSeeder::class,
            MedicamentSeeder::class,
            ExamenSeeder::class,
            
            
        ]);
    }
}
