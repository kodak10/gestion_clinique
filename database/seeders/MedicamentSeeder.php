<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicament;
use Illuminate\Support\Str;

class MedicamentSeeder extends Seeder
{
    public function run(): void
    {
        $noms = [
            'ABAISSE LANGUE',
            'ACUPAN',
            'ADRENALINE',
            'AIGUILLE PL',
            'AMOXICILLINE',
            'ARTEMETHER 20MG',
            'ARTEMETHER 80MG',
            'ARTESUN 30MG',
            'ARTESUN 60MG',
            'ATROPINE',
            'CURAM',
            'BANDE N°10',
            'BANDE N°15',
            'BANDE N°5',
            'BANDE N°7',
            'BETADINE JAUNE',
            'BETADINE ROUGE',
            'BRASSA',
            'CEFTRIAXONE',
            'CLAMP DE BARR',
            'COMPRESSE NON STERIL',
            'COMPRESSE STERIL',
            'COTON',
            'CUTICELLE',
            'DETAIL GANT',
            'DEXAMETAXONE',
            'DICLOFENAC',
            'DRAIN',
            'EAU OXYGENE GRAND',
            'EAU OXYGENE PETIT',
            'EPHEDRINE',
            'EPICRANIEN BLEU',
            'EXACYL',
            'FIL NYLON N°0',
            'FIL NYLON N°01',
            'FIL NYLON N°2',
            'FIL NYLON N°2/0',
            'FIL NYLON N°3/0',
            'G10',
            'GANT STERIL N°7/5',
            'GANT STERIL N°8',
            'GELOPLASMAT',
            'GENTAMYCINE',
            'GLUCOSE',
            'HALOTANE',
            'HPV',
            'INTRANULE BLEU',
            'INTRANULE VERT',
            'LAME BISTORIE N°23',
            'LAROXYL',
            'LASILIX',
            'LOXEN',
            'MARCAÏNE',
            'METRO PERF',
            'MIDAZOLAM',
            'NOVALGIN',
            'PERFUSEUR',
            'POCHE URINAIRE',
            'QUININE',
            'SALE (SSI)',
            'SERINGUE 10CC',
            'SERINGUE 20CC',
            'SERINGUE 5CC',
            'SERINGUE 60 CC',
            'SERINGUE INSULINE',
            'SONDE URINAIRE N°14',
            'SONDE URINAIRE N°16',
            'SONDE URINAIRE N°18',
            'SPASFON INJ',
            'SYNTHOCINONE',
            'TETANOS',
            'TRABAR',
            'VALIUM',
            'VITAMINE C',
            'VITAMINE K1',
            'VOGALENE',
            'XYLOCAÏNE 2 %',
            'XYLOCAÏNE 50ML',
            'XYLOCAÏNE GEL',
            'CIMETIDINE',
            'CASAQUE JETTABLE',
            'SONDE A 2 VOIE',
            'DICACILLINE',
            'BANDE 30 CM',
            'IMIPENEN 500MG',
            'SALE 250',
            'GLUCOSE 250',
            'NOVALGIN 500',
            'FINE ECHO',
            'GLUCOSE 30',
            'CHAMP JETABLBE',
            'SPECUIM',
        ];

        foreach ($noms as $nom) {
            Medicament::create([
                'code'          => strtoupper(Str::slug($nom, '_')) . '_' . rand(100, 999), // Code unique
                'nom'           => $nom,
                'unite_mesure'  => 'pièce', // Valeur par défaut
                'prix_achat'    => 0,       // Valeur par défaut
                'prix_vente'    => 0,       // Valeur par défaut
                'stock'         => 0,
                'stock_alerte'  => 10,
                'date_peremption' => null,
            ]);
        }
    }
}
