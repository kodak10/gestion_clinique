<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryPrestation;
use App\Models\Prestation;

class PrestationSeeder extends Seeder
{
    public function run(): void
    {
        $prestations = [
            ['Médecine générale', 0],
            ['Pédiatrie-Néonatalogie', 0],
            ['Gynécologie Obstétrique', 0],
            ['Chirurgie générale et digestive', 0],
            ['Chirurgie pédiatrique', 0],
            ['Nutrition diététique', 0],
            ['Infectiologie', 0],
            ['O.R.L', 0],
            ['Stomatologie', 0],
            ['Rhumatologie', 0],
            ['Diabétologie', 0],
            ['Neurologie', 0],
            ['Gastro-entérologie', 0],
            ['Analyses Médicales', 6000],
            ['Dermatologie', 0],
            ['Traumatolgie', 0],
            ['Consultation Générale', 0],
            ['POCHE DE SANG', 0],
            ['CERTIFICAT DE DECES', 0],
            ['PNEUMOLOGIE', 0],
            ['PLASTICIEN', 0],
            ['AVIS GASTRO', 0],
            ['PANSEMENT', 0],
            ['RADIO PULMONAIRE DE FACE', 0],
            ['ECHODOPPLER CARDIAQUE', 0],
            ['HOLTER ECG', 0],
            ['INJECTION', 0],
            ['ECHOGRAPHIE ABDO PELVIENNE', 0],
            ['ECHOGRAPHIE ABDOMINALE', 0],
            ['ECHOGRAPHIE PELVIENNE', 0],
            ['UROLOGIE', 0],
            ['CONSULTATION SPECIALISTE', 0],
            ['ECG', 0],
            ['ECHO TSA', 0],
            ['HERNIE', 0],
            ['APPENDICITE', 0],
            ['PHARMACIE', 0],
            ['CHIRURGIE', 0],
            ['ECHODOPPLER DES MEMBE INFERIEUR', 0],
            ['LOCATION BLOC', 0],
            ['AVIS NEURO', 0],
            ['AVIS CARDIO', 0],
            ['HYDRO', 0],
            ['AVIS NEUPHRO', 0],
            ['PONCTION EVACUATOIRE', 0],
            ['BILAN PREOPERATOIRE', 0],
            ['BILAN PREOPERATOIRE0', 0],
            ['ECHOGRAPHIE TESTICULAIRE', 0],
            ['EVACUATION UTERUSE', 0],
            ['CONSULTATION GASTROLOGIQUE', 0],
            ['AVIS UROLOGUE', 0],
            ['ECHODOPPLER DES VAISSEAUX DU COU', 0],
            ['CONSULTATION CARDIO', 0],
            ['ECHOGRAPHIE PROSTATIQUE', 0],
            ['CONSUTATION DENTAIRE', 0],
            ['CONSULTATION PRENATALE', 0],
            ['MISE EN OBSERVATION', 0],
            ['MISE EN OBERVATION', 0],
            ['AVIS', 0],
            ['PRISE DE TENSION', 0],
            ['RADIO DU GENOU DROIT ET GAUCHE', 0],
            ['RADIO CRANE', 0],
            ['RADIO RACHIS', 0],
            ['RADIO LOMBO SACRE', 0],
            ['AVIS DERMATO', 0],
            ['AVIS INFECTIO', 0],
            ['CONSULTATION RHUMATO', 0],
            ['AVIS RHUMATO', 0],
            ['AVIS DIABETO', 0],
            ['AVIS KINE', 0],
            ['seance kine', 0],
            ['RADIO FACE DEBOUT', 0],
            ['ECHO OBSTETRICALE', 0],
            ['echo endovaginale', 0],
            ['AVIS ORL', 0],
            ['PONCTION D`ASCITE', 0],
            ['AVANCE HOSPITALISATION', 0],
            ['POINT DE SUTURE', 0],
            ['TEXT DE DINGUE', 0],
            ['SEANCE DE KINE', 0],
            ['RADIO', 0],
            ['RADIO GENOU DROIT F/P', 0],
            ['RADIO DES MAINS', 0],
            ['SOIN DENTAIRE', 0],
            ['TURBAGE', 0],
            ['RESTA A PAYER', 0],
            ['AMI', 0],
            ['cesarienne', 0],
            ['CONTROLE DE LA TENSION', 0],
            ['RETOUR EN CAISSE', 0],
            ['RECOUVREMENT', 0],
            ['AVANCE CHIRURGIE', 0],
            ['CONSULTATION OPHTAMOLOGIE', 0],
            ['BIOPSIE', 0],
            ['ww', 0],
            ['HOSPITALISATION', 0],
            ['fond d`oeil', 0],
            ['DR YAO', 0],
            ['DR YAO CHIRURGIEN', 0],
            ['OSE DE STERILET', 0],
            ['POSE DE STERILET', 0],
            ['CHIRURGIEN', 0],
            ['SOINS OPHTALMO', 0],
            ['ACTES DE TRANSFUSION', 0],
            ['AMBULANCE', 0],
            ['REFRACTOMETRIE', 0],
            ['SONDE URINAIRE', 0],
            ['EVACUATION', 0],
            ['GLYCEMIE CAP', 0],
            ['RADIO PULMONAIRE F/P', 0],
            ['ECHOGRAPHIE VESICO RENALE', 0],
            ['AVIS GYNECO', 0],
            ['AVIS TRAUMATO', 0],
            ['PROTHESE', 0],
            ['ACCOUCHEMENT NORMAL', 0],
            ['oxygene', 0],
        ];

        foreach ($prestations as [$libelle, $montant]) {
            $categoryName = $this->resolveCategory($libelle);
            $category = CategoryPrestation::firstOrCreate(['nom' => $categoryName]);

            Prestation::create([
                'categorie_id' => $category->id,
                'libelle' => $libelle,
                'montant' => $montant,
                'description' => null,
            ]);
        }
    }

    private function resolveCategory(string $libelle): string
    {
        $keywords = [
            'radio' => 'Radiologie',
            'echo' => 'Échographie',
            'ecg' => 'Cardiologie',
            'doppler' => 'Cardiologie',
            'kine' => 'Kinésithérapie',
            'consultation' => 'Consultations',
            'chirurgie' => 'Chirurgie',
            'ambulance' => 'Transport',
            'pharmacie' => 'Pharmacie',
            'avis' => 'Avis spécialisés',
            'observation' => 'Observation',
            'analyse' => 'Biologie',
            'soin' => 'Soins',
            'certificat' => 'Administratif',
            'accouchement' => 'Gynécologie',
            'ophtalmo' => 'Ophtalmologie',
            'pédiatrie' => 'Pédiatrie',
            'dermato' => 'Dermatologie',
            'infectio' => 'Infectiologie',
            'cardio' => 'Cardiologie',
            'urologue' => 'Urologie',
            'dentaire' => 'Odontologie',
            'diabéto' => 'Diabétologie',
            'gynéco' => 'Gynécologie',
        ];

        $libelleLower = strtolower($libelle);
        foreach ($keywords as $keyword => $category) {
            if (str_contains($libelleLower, $keyword)) {
                return $category;
            }
        }

        return 'Autres';
    }
}