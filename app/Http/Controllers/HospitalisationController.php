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

        // Récupérer les médicaments (category_id = 5 pour pharmacie)
        $medicaments = CategoryFrais_Hospitalisation::with(['fraisHospitalisations' => function($query) {
            $query->where('category_id', 5)->orderBy('libelle');
        }])->where('id', 5)->get();

        // Récupérer les détails existants
        $details = HospitalisationDetail::with('fraisHospitalisation')
                    ->where('hospitalisation_id', $hospitalisation->id)
                    ->get();

        return view('dashboard.pages.hospitalisations.pharmacie', 
            compact('hospitalisation', 'patient', 'medicaments', 'details'));
    }

// public function createLaboratoire(Hospitalisation $hospitalisation)
//     {
//         $patient = $hospitalisation->patient;

//         // Récupérer les médicaments (category_id = 3 pour examens)
//         $examens = CategoryFrais_Hospitalisation::with(['fraisHospitalisations' => function($query) {
//             $query->where('category_id', 3)->orderBy('libelle');
//         }])->where('id', 3)->get();

//         // Récupérer les détails existants
//         $details = HospitalisationDetail::with('fraisHospitalisation')
//                     ->where('hospitalisation_id', $hospitalisation->id)
                    
//                     ->get();

//         return view('dashboard.pages.hospitalisations.laboratoire', 
//             compact('hospitalisation', 'patient', 'examens', 'details'));
//     }

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




   public function storePharmacie(Request $request, Hospitalisation $hospitalisation)
    {
        $request->validate([
            'medicaments' => 'required|array|min:1',
            'medicaments.*.frais_id' => 'required|exists:frais_hospitalisations,id',
            'medicaments.*.prix' => 'required|numeric|min:0',
            'medicaments.*.quantite' => 'required|integer|min:1',
            'medicaments.*.total' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $submittedIds = [];
            
            foreach ($request->medicaments as $med) {
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
            return redirect()->back()->with('swal_success', 'Ordonnance enregistrée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
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

    

    // Récupérer les détails existants pour laboratoire
    $detailsLaboratoire = HospitalisationDetail::with('fraisHospitalisation')
                    ->where('hospitalisation_id', $hospitalisation->id)
                    ->whereHas('fraisHospitalisation', function($query) {
                        $query->where('category_id', 3);
                    })
                    ->get();

    $detailsPharmacie = HospitalisationDetail::with('frais') // pas 'fraisHospitalisation'
    ->where('hospitalisation_id', $hospitalisation->id)
    ->whereHas('frais', function($query) {
        $query->where('category_id', 5); // 5 = ID de la catégorie Pharmacie
    })
    ->get();

    $autresFrais = CategoryFrais_Hospitalisation::with(['fraisHospitalisations' => function($query) {
        $query->whereNotIn('category_id', [3, 5])->orderBy('libelle');
    }])->whereNotIn('id', [3, 5])->get();


    return view('dashboard.pages.hospitalisations.create', 
        compact('hospitalisation', 'patient', 'categorie_medecins', 'detailsLaboratoire', 'detailsPharmacie', 'autresFrais'));
}


// public function storeFacture(Request $request, Hospitalisation $hospitalisation)
// {
//     // Activation des logs détaillés
//     \Log::info('Début de storeFacture', ['request' => $request->all(), 'hospitalisation_id' => $hospitalisation->id]);

//     $request->validate([
//         'frais' => 'required|array|min:1',
//         'frais.*.frais_id' => 'required|exists:frais_hospitalisations,id',
//         'frais.*.prix' => 'required|numeric|min:0',
//         'frais.*.quantite' => 'required|integer|min:1',
//         'frais.*.total' => 'required|numeric|min:0',
//         'frais.*.medecin_id' => 'nullable|exists:medecins,id',
//         'date_sortie' => 'required|date',
//         'date_entree' => 'required|date',
//         'caution' => 'nullable|numeric|min:0',
//         'payeur' => 'nullable|string|max:255',
//     ]);

//     try {
//         DB::beginTransaction();
//         \Log::debug('Transaction DB commencée');

//         // Mise à jour des dates de l'hospitalisation
//         $hospitalisation->update([
//             'date_entree' => $request->date_entree,
//             'date_sortie' => $request->date_sortie,
//         ]);
//         \Log::info('Dates hospitalisation mises à jour', ['date_entree' => $request->date_entree, 'date_sortie' => $request->date_sortie]);

//         $submittedIds = [];
//         $totalGeneral = 0;

//         \Log::debug('Traitement des frais', ['count_frais' => count($request->frais ?? [])]);

//         // Correction: utiliser $request->frais au lieu de $request->autres
//         foreach ($request->frais as $index => $fraisItem) {
//             $totalItem = $fraisItem['prix'] * $fraisItem['quantite'];
//             $totalGeneral += $totalItem;

//             $data = [
//                 'hospitalisation_id' => $hospitalisation->id,
//                 'frais_hospitalisation_id' => $fraisItem['frais_id'],
//                 'quantite' => $fraisItem['quantite'],
//                 'prix_unitaire' => $fraisItem['prix'],
//                 'total' => $totalItem,
//                 'updated_at' => now()
//             ];

//             \Log::debug("Traitement frais #$index", ['data' => $data]);

//             if (isset($fraisItem['id'])) {
//                 HospitalisationDetail::where('id', $fraisItem['id'])
//                     ->update($data);
//                 $submittedIds[] = $fraisItem['id'];
//                 \Log::debug("Frais existant mis à jour", ['id' => $fraisItem['id']]);
//             } else {
//                 $newDetail = HospitalisationDetail::create(array_merge($data, [
//                     'created_at' => now()
//                 ]));
//                 $submittedIds[] = $newDetail->id;
//                 \Log::debug("Nouveau frais créé", ['new_id' => $newDetail->id]);
//             }
//         }

//         \Log::info('Total général calculé', ['total' => $totalGeneral]);

//         // Suppression des frais non soumis
//         $deletedCount = HospitalisationDetail::where('hospitalisation_id', $hospitalisation->id)
//             ->whereNotIn('id', $submittedIds)
//             ->delete();
//         \Log::debug('Frais supprimés', ['count' => $deletedCount]);

//         // Calcul des montants
//         $ticketModerateur = $totalGeneral * 0.2;
//         $montantAPaye = $totalGeneral - $ticketModerateur - $hospitalisation->reduction;

//         \Log::debug('Calculs financiers', [
//             'ticketModerateur' => $ticketModerateur,
//             'reduction' => $hospitalisation->reduction,
//             'montantAPaye' => $montantAPaye
//         ]);

//         // Mise à jour de l'hospitalisation
//         $hospitalisation->update([
//             'total' => $totalGeneral,
//             'ticket_moderateur' => $ticketModerateur,
//             'montant_a_paye' => $montantAPaye,
//         ]);
//         \Log::info('Hospitalisation mise à jour', $hospitalisation->toArray());

//         DB::commit();
//         \Log::info('Transaction DB commitée avec succès');
        
//         return redirect()->back()->with('swal_success', 'Facture enregistrée avec succès.');
//     } catch (\Exception $e) {
//         DB::rollBack();
//         \Log::error('Erreur dans storeFacture', [
//             'error' => $e->getMessage(),
//             'trace' => $e->getTraceAsString(),
//             'request' => $request->all()
//         ]);
//         return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
//     }
// }
public function storeFacture(Request $request, Hospitalisation $hospitalisation)
{
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
        'montant_paye' => 'nullable|numeric|min:0', // Nouveau champ pour le règlement
        'mode_paiement' => 'nullable|string|max:255', // Nouveau champ pour le règlement
    ]);

    try {
        DB::beginTransaction();
        \Log::debug('Transaction DB commencée');

        // 1. Mise à jour des dates de l'hospitalisation
        $hospitalisation->update([
            'date_entree' => $request->date_entree,
            'date_sortie' => $request->date_sortie,
        ]);
        \Log::info('Dates hospitalisation mises à jour');

        // 2. Traitement des frais
        $submittedIds = [];
        $totalGeneral = 0;

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

            if (isset($fraisItem['id'])) {
                HospitalisationDetail::where('id', $fraisItem['id'])->update($data);
                $submittedIds[] = $fraisItem['id'];
            } else {
                $newDetail = HospitalisationDetail::create(array_merge($data, ['created_at' => now()]));
                $submittedIds[] = $newDetail->id;
            }
        }

        // 3. Suppression des frais non soumis
        HospitalisationDetail::where('hospitalisation_id', $hospitalisation->id)
            ->whereNotIn('id', $submittedIds)
            ->delete();

        // 4. Calcul des montants
        $ticketModerateur = $totalGeneral * 0.2;
        $montantAPaye = $totalGeneral - $ticketModerateur - $hospitalisation->reduction;

        // 5. Mise à jour de l'hospitalisation
        $hospitalisation->update([
            'total' => $totalGeneral,
            'ticket_moderateur' => $ticketModerateur,
            'montant_a_paye' => $montantAPaye,
            'statut_paiement' => $request->montant_paye >= $montantAPaye ? 'payé' : 'partiel'
        ]);

        // 6. Enregistrement du règlement
        $reglement = Reglement::create([
            'hospitalisation_id' => $hospitalisation->id,
            'patient_id' => $hospitalisation->patient_id,
            //'montant' => $request->montant_paye,
            'montant' => 0,
            'mode_paiement' => "Cash",
            'user_id' => auth()->id(),
        ]);

        \Log::info('Règlement créé', ['reglement_id' => $reglement->id]);

        // 7. Si caution payée, l'enregistrer
        if ($request->caution) {
            Caution::updateOrCreate(
                ['hospitalisation_id' => $hospitalisation->id],
                ['montant' => $request->caution, 'date_versement' => now()]
            );
        }

        DB::commit();
        \Log::info('Transaction complétée avec succès');

        return redirect()->back()->with([
            'swal_success' => 'Facture et règlement enregistrés avec succès',
            'print_url' => route('facture.print', $hospitalisation->id) // Optionnel: URL pour impression
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Erreur dans storeFacture', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
    }
}
}
