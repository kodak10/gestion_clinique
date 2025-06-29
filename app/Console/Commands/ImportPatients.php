<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportPatients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:import-patients';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    protected $signature = 'import:patients';
    protected $description = 'Importer les patients depuis tbl_patients vers patients';

    public function handle()
    {
        $oldPatients = DB::table('tbl_patients')->get();
        $this->info("Import de {$oldPatients->count()} patients...");

        foreach ($oldPatients as $old) {
            try {
                $nomComplet = explode(' ', $old->nom_patient, 2);
                $nom = $nomComplet[0] ?? '';
                $prenoms = $nomComplet[1] ?? '';

                $profession_id = Profession::firstOrCreate(['libelle' => $old->profession ?: 'Aucun'])->id;
                $ethnie_id = Ethnie::firstOrCreate(['libelle' => $old->ethnie ?: 'Aucun'])->id;
                $assurance_id = $old->id_assurance ?: null;

                $sexe = strtolower($old->sexe) === 'masculin' ? 'M' : 'F';

                Patient::create([
                    'num_dossier' => $old->compte_patient,
                    'nom' => strtoupper($nom),
                    'prenoms' => ucwords(strtolower($prenoms)),
                    'date_naissance' => $old->date_naiss_patient,
                    'domicile' => $old->domicile ?? 'Inconnu',
                    'sexe' => $sexe,
                    'profession_id' => $profession_id,
                    'ethnie_id' => $ethnie_id,
                    'religion' => ucfirst(strtolower($old->religion ?? '')),
                    'groupe_rhesus' => $old->rhesus,
                    'electrophorese' => $old->electrophorese,
                    'assurance_id' => $assurance_id,
                    'taux_couverture' => $old->taux_reduction,
                    'matricule_assurance' => $old->num_carte ?? null,
                    'contact_urgence' => $old->adresse_parent,
                    'contact_patient' => $old->adresse,
                    'photo' => 'patients/patient.png', // facultatif : tu peux aussi traiter le binaire
                    'envoye_par' => $old->nom_envoyer,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                $this->error("Erreur pour patient {$old->id_patient} : " . $e->getMessage());
            }
        }

        $this->info("Import terminé !");
        return 0;
    }
}
