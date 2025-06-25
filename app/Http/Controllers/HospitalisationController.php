<?php

namespace App\Http\Controllers;

use \Exception;
use \Log;
use App\Models\CategoryFrais_Hospitalisation;
use App\Models\CategoryPrestation;
use App\Models\DetailsFraisPharmacie;
use App\Models\FraisHospitalisation;
use App\Models\Hospitalisation;
use App\Models\HospitalisationDetail;
use App\Models\Medicament;
use App\Models\Patient;
use App\Models\Prestation;
use App\Models\Reglement;
use App\Models\Specialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HospitalisationController extends Controller
{
    public function storeSimple(Patient $patient)
    {
        // Vérifier qu'il n'est pas déjà hospitalisé
        $existe = Hospitalisation::where('patient_id', $patient->id)->whereNull('date_sortie')->exists();

        if ($existe) {
            return redirect()->back()->with('error', 'Ce patient est déjà hospitalisé.');
        }

        Hospitalisation::create([
            'patient_id' => $patient->id,
            'user_id' => auth()->id(),
            'total' => "0",
            'ticket_moderateur' => "0",
            'reduction' => "0",
            'montant_a_paye' => "0",
            'reste_a_payer' => "0",
            'date_entree' => now(),
        ]);

        return redirect()->back()->with('success', 'Le patient a été hospitalisé.');
    }

    public function index()
    {
        $hospitalisations = Hospitalisation::with('patient')->get();
        return view('dashboard.pages.hospitalisations.index', compact('hospitalisations'));
    }


public function createPharmacie(Hospitalisation $hospitalisation)
{
    $patient = $hospitalisation->patient;

    // Récupérer les médicaments déjà associés à l'hospitalisation
    $medicamentsExistants = $hospitalisation->medicaments()
        ->withPivot('quantite', 'prix_unitaire',  'total', )
        ->get();

    // Tous les médicaments disponibles
    $allMedicaments = Medicament::orderBy('nom')->get();
    
    return view('dashboard.pages.hospitalisations.pharmacie', compact(
        'hospitalisation', 
        'medicamentsExistants', 
        'allMedicaments', 
        'patient'
    ));
}

// public function storePharmacie(Request $request, Hospitalisation $hospitalisation)
// {
//     // ID du frais hospitalisation pour la pharmacie
//     $fraisPharmacieId = 2;

//     // Validation des données
//     $validated = $request->validate([
//         'medicaments' => 'nullable|array',
//         'medicaments.*.id' => 'sometimes|nullable|exists:hospitalisation_medicament,id',
//         'medicaments.*.medicament_id' => 'required|exists:medicaments,id',
//         'medicaments.*.prix_unitaire' => 'required|numeric|min:0',
//         'medicaments.*.quantite' => 'required|integer|min:1',
        
//         'nouveaux_medicaments' => 'nullable|array',
//         'nouveaux_medicaments.*.medicament_id' => 'required|exists:medicaments,id',
//         'nouveaux_medicaments.*.prix_unitaire' => 'required|numeric|min:0',
//         'nouveaux_medicaments.*.quantite' => 'required|integer|min:1',
        
//         'medicaments_supprimes' => 'nullable|array',
//         'medicaments_supprimes.*' => 'exists:hospitalisation_medicament,id'
//     ]);

//     try {
//         DB::beginTransaction();

//         $submittedIds = [];
        
//         // Traitement des médicaments existants
//         foreach ($validated['medicaments'] ?? [] as $medicament) {
//             $total = $medicament['prix_unitaire'] * $medicament['quantite'];

//             // Mise à jour dans hospitalisation_medicament
//             DB::table('hospitalisation_medicament')
//                 ->where('id', $medicament['id'])
//                 ->update([
//                     'prix_unitaire' => $medicament['prix_unitaire'],
//                     'quantite' => $medicament['quantite'],
//                     'total' => $total,
//                     'updated_at' => now()
//                 ]);

//             $submittedIds[] = $medicament['id'];
//         }

//         // Ajout des nouveaux médicaments
//         foreach ($validated['nouveaux_medicaments'] ?? [] as $newMedicament) {
//             $total = $newMedicament['prix_unitaire'] * $newMedicament['quantite'];

//             $newId = DB::table('hospitalisation_medicament')->insertGetId([
//                 'hospitalisation_id' => $hospitalisation->id,
//                 'medicament_id' => $newMedicament['medicament_id'],
//                 'prix_unitaire' => $newMedicament['prix_unitaire'],
//                 'quantite' => $newMedicament['quantite'],
//                 'total' => $total,
//                 'created_at' => now(),
//                 'updated_at' => now()
//             ]);

//             $submittedIds[] = $newId;
//         }

//         // Suppression des médicaments retirés
//         if (!empty($validated['medicaments_supprimes'])) {
//             DB::table('hospitalisation_medicament')
//                 ->whereIn('id', $validated['medicaments_supprimes'])
//                 ->delete();
//         }

//         // Enregistrement du total dans hospitalisation_details
//         $totalPharmacie = DB::table('hospitalisation_medicament')
//             ->where('hospitalisation_id', $hospitalisation->id)
//             ->sum('total');

//         HospitalisationDetail::updateOrCreate(
//             [
//                 'hospitalisation_id' => $hospitalisation->id,
//                 'frais_hospitalisation_id' => $fraisPharmacieId
//             ],
//             [
//                 'prix_unitaire' => $totalPharmacie,
//                 'quantite' => 1,
//                 'taux' => 0,
//                 'reduction' => 0,
//                 'total' => $totalPharmacie,
//                 'updated_at' => now()
//             ]
//         );

//         DB::commit();
//         return redirect()->back()->with('success', 'Médicaments enregistrés avec succès.');

//     } catch (\Exception $e) {
//         DB::rollBack();
//         return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
//     }
// }

public function storePharmacie(Request $request, Hospitalisation $hospitalisation)
{
    DB::beginTransaction();
    try {
        // Supprimer tous les médicaments existants pour cette hospitalisation
        $hospitalisation->medicaments()->detach();

        // Ajouter les médicaments mis à jour
        $medicamentsData = [];
        $totalPharmacie = 0;

        // Traiter les médicaments existants modifiés
        foreach ($request->medicaments ?? [] as $medicament) {
            $total = $medicament['prix_unitaire'] * $medicament['quantite'];
            $medicamentsData[$medicament['medicament_id']] = [
                'prix_unitaire' => $medicament['prix_unitaire'],
                'quantite' => $medicament['quantite'],
                'total' => $total
            ];
            $totalPharmacie += $total;
        }

        // Traiter les nouveaux médicaments
        foreach ($request->nouveaux_medicaments ?? [] as $medicament) {
            $total = $medicament['prix_unitaire'] * $medicament['quantite'];
            $medicamentsData[$medicament['medicament_id']] = [
                'prix_unitaire' => $medicament['prix_unitaire'],
                'quantite' => $medicament['quantite'],
                'total' => $total
            ];
            $totalPharmacie += $total;
        }

        // Attacher tous les médicaments en une seule opération
        $hospitalisation->medicaments()->attach($medicamentsData);

        // Mettre à jour hospitalisation_details
        $hospitalisation->details()->update([
            'hospitalisation_id' => $hospitalisation->id,
            'frais_hospitalisation_id' => 2,
            'quantite' => 1,
            'prix_unitaire' => $totalPharmacie,
            'taux' => 0,
            'reduction' => 0,
            'total' => $totalPharmacie,
           
            'updated_at' => now()
        ]);

        

        DB::commit();

        return redirect()->back()->with('success', 'Médicaments mis à jour avec succès');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
    }
}


public function createLaboratoire(Hospitalisation $hospitalisation)
{
    $patient = $hospitalisation->patient;

    // Récupérer les frais de la catégorie "Examens" uniquement (category_id = 3)
    $examens = FraisHospitalisation::where('category_id', 3)->orderBy('libelle')->get();

    // Récupérer les détails déjà enregistrés pour cette hospitalisation et de cette catégorie uniquement
    $details = HospitalisationDetail::with('frais')
        ->where('hospitalisation_id', $hospitalisation->id)
        ->whereHas('frais', function ($query) {
            $query->where('category_id', 3);
        })
        ->get();

    return view('dashboard.pages.hospitalisations.laboratoire', 
        compact('hospitalisation', 'patient', 'examens', 'details'));
}


    public function storeExamen(Request $request, Hospitalisation $hospitalisation)
    {
        $request->validate([
            'examens' => 'required|array|min:1',
            'examens.*.frais_id' => 'required|exists:frais_hospitalisations,id',
            'examens.*.prix' => 'required|numeric|min:0',
            'examens.*.quantite' => 'required|integer|min:1',
            'examens.*.total' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $submittedIds = [];
            
            foreach ($request->examens as $med) {
                $data = [
                    'hospitalisation_id' => $hospitalisation->id,
                    'frais_hospitalisation_id' => $med['frais_id'],
                    'quantite' => $med['quantite'],
                    'prix_unitaire' => $med['prix'],
                    'total' => $med['total'],
                    'updated_at' => now()
                ];

                if (isset($med['id'])) {
                    // Mise à jour de l'enregistrement existant
                    HospitalisationDetail::where('id', $med['id'])
                        ->update($data);
                    $submittedIds[] = $med['id'];
                } else {
                    // Création d'un nouvel enregistrement
                    $newDetail = HospitalisationDetail::create(array_merge($data, [
                        'created_at' => now()
                    ]));
                    $submittedIds[] = $newDetail->id;
                }
            }

            // Supprimer les enregistrements qui ne sont plus dans le formulaire
            HospitalisationDetail::where('hospitalisation_id', $hospitalisation->id)
                ->whereNotIn('id', $submittedIds)
                ->delete();

            DB::commit();
            return redirect()->back()->with('swal_success', 'Examen enregistrée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    public function createFacture(Hospitalisation $hospitalisation)
{
    $patient = $hospitalisation->patient;
        $categorie_medecins = Specialite::with('medecins')->get();

    

   $detailsLaboratoire = HospitalisationDetail::with('fraisHospitalisation')
    ->where('hospitalisation_id', $hospitalisation->id)
    ->where('frais_hospitalisation_id', 1)
    ->get();


    $detailsPharmacie = HospitalisationDetail::with('fraisHospitalisation')
    ->where('hospitalisation_id', $hospitalisation->id)
    ->where('frais_hospitalisation_id', 2)
    ->get();

    $autresDetails = HospitalisationDetail::with('fraisHospitalisation')
    ->where('hospitalisation_id', $hospitalisation->id)
    ->whereNotIn('frais_hospitalisation_id', [1, 2])
    ->get();

// Récupérer les frais disponibles non encore utilisés
$utilises = $autresDetails->pluck('frais_hospitalisation_id')->toArray();
$autresFrais = FraisHospitalisation::whereNotIn('id', array_merge([1, 2], $utilises))
    ->orderBy('libelle')
    ->get();

    $taux_assurance = $hospitalisation->patient->taux_assurance ?? 0; // ou 100 par défaut si nécessaire



    return view('dashboard.pages.hospitalisations.create', 
        compact('hospitalisation', 'patient', 'categorie_medecins', 'detailsLaboratoire', 'detailsPharmacie', 'autresFrais', 'taux_assurance'));
}


public function storeFacture(Request $request, Hospitalisation $hospitalisation)
{
    // Activation des logs détaillés
    \Log::info('Début de storeFacture', ['request' => $request->all(), 'hospitalisation_id' => $hospitalisation->id]);

    $request->validate([
        'frais' => 'required|array|min:1',
        'frais.*.frais_id' => 'required|exists:frais_hospitalisations,id',
        'frais.*.prix' => 'required|numeric|min:0',
        'frais.*.quantite' => 'required|integer|min:1',
        'frais.*.total' => 'required|numeric|min:0',
        'frais.*.medecin_id' => 'nullable|exists:medecins,id',
        'date_sortie' => 'required|date',
        'date_entree' => 'required|date',
        'caution' => 'nullable|numeric|min:0',
        'payeur' => 'nullable|string|max:255',
    ]);

    try {
        DB::beginTransaction();
        \Log::debug('Transaction DB commencée');

        // Mise à jour des dates de l'hospitalisation
        $hospitalisation->update([
            'date_entree' => $request->date_entree,
            'date_sortie' => $request->date_sortie,
        ]);
        \Log::info('Dates hospitalisation mises à jour', ['date_entree' => $request->date_entree, 'date_sortie' => $request->date_sortie]);

        $submittedIds = [];
        $totalGeneral = 0;

        \Log::debug('Traitement des frais', ['count_frais' => count($request->frais ?? [])]);

        // Correction: utiliser $request->frais au lieu de $request->autres
        foreach ($request->frais as $index => $fraisItem) {
            $totalItem = $fraisItem['prix'] * $fraisItem['quantite'];
            $totalGeneral += $totalItem;

            $data = [
                'hospitalisation_id' => $hospitalisation->id,
                'frais_hospitalisation_id' => $fraisItem['frais_id'],
                'quantite' => $fraisItem['quantite'],
                'prix_unitaire' => $fraisItem['prix'],
                'total' => $totalItem,
                'updated_at' => now()
            ];

            \Log::debug("Traitement frais #$index", ['data' => $data]);

            if (isset($fraisItem['id'])) {
                HospitalisationDetail::where('id', $fraisItem['id'])
                    ->update($data);
                $submittedIds[] = $fraisItem['id'];
                \Log::debug("Frais existant mis à jour", ['id' => $fraisItem['id']]);
            } else {
                $newDetail = HospitalisationDetail::create(array_merge($data, [
                    'created_at' => now()
                ]));
                $submittedIds[] = $newDetail->id;
                \Log::debug("Nouveau frais créé", ['new_id' => $newDetail->id]);
            }
        }

        \Log::info('Total général calculé', ['total' => $totalGeneral]);

        // Suppression des frais non soumis
        $deletedCount = HospitalisationDetail::where('hospitalisation_id', $hospitalisation->id)
            ->whereNotIn('id', $submittedIds)
            ->delete();
        \Log::debug('Frais supprimés', ['count' => $deletedCount]);

        // Calcul des montants
        $ticketModerateur = $totalGeneral * 0.2;
        $montantAPaye = $totalGeneral - $ticketModerateur - $hospitalisation->reduction;

        \Log::debug('Calculs financiers', [
            'ticketModerateur' => $ticketModerateur,
            'reduction' => $hospitalisation->reduction,
            'montantAPaye' => $montantAPaye
        ]);

        // Mise à jour de l'hospitalisation
        $hospitalisation->update([
            'total' => $totalGeneral,
            'ticket_moderateur' => $ticketModerateur,
            'montant_a_paye' => $montantAPaye,
        ]);
        \Log::info('Hospitalisation mise à jour', $hospitalisation->toArray());

        DB::commit();
        \Log::info('Transaction DB commitée avec succès');
        
        return redirect()->back()->with('swal_success', 'Facture enregistrée avec succès.');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Erreur dans storeFacture', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request' => $request->all()
        ]);
        return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
    }
}

}
