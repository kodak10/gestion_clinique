<?php

namespace App\Http\Controllers;

use \Exception;
use \Log;
use App\Models\CategoryFrais_Hospitalisation;
use App\Models\CategoryPrestation;
use App\Models\DetailsFraisPharmacie;
use App\Models\Examen;
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

    public function index()
    {
        $hospitalisations = Hospitalisation::with('patient')->get();
        return view('dashboard.pages.hospitalisations.index', compact('hospitalisations'));
    }

    public function storeSimple(Patient $patient)
    {
        // Vérifier qu'il n'est pas déjà hospitalisé
        $existe = Hospitalisation::where('patient_id', $patient->id)->whereNull('date_sortie')->exists();

        if ($existe) {
            return redirect()->back()->with('error', 'Ce patient est déjà hospitalisé.');
        }

        DB::beginTransaction();

        try {
            // Création de l'hospitalisation
            $hospitalisation = Hospitalisation::create([
                'patient_id' => $patient->id,
                'user_id' => auth()->id(),
                'total' => 0,
                'ticket_moderateur' => 0,
                'reduction' => 0,
                'montant_a_paye' => 0,
                'reste_a_payer' => 0,
                'date_entree' => now(),
            ]);

            // Ajout des deux lignes de détails avec frais_hospitalisation_id 1 et 2
            $hospitalisation->details()->createMany([
                [
                    'frais_hospitalisation_id' => 1,
                    'quantite' => 1,
                    'prix_unitaire' => 0,
                    'reduction' => 0,
                    'taux' => 0,
                    'total' => 0,
                ],
                [
                    'frais_hospitalisation_id' => 2,
                    'quantite' => 1,
                    'prix_unitaire' => 0,
                    'reduction' => 0,
                    'taux' => 0,
                    'total' => 0,
                ]
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Le patient a été hospitalisé.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function createPharmacie(Hospitalisation $hospitalisation)
    {
        $patient = $hospitalisation->patient;

        // Récupérer les médicaments déjà associés à l'hospitalisation
        // $medicamentsExistants = $hospitalisation->medicaments()
        //     ->withPivot('quantite', 'prix_unitaire',  'total', )
        //     ->get();
        $medicamentsPrescrits = $hospitalisation->medicaments()->get();


        // Tous les médicaments disponibles
        $allMedicaments = Medicament::orderBy('nom')->get();
        
        return view('dashboard.pages.hospitalisations.pharmacie', compact(
            'hospitalisation', 
            'medicamentsPrescrits', 
            'allMedicaments', 
            'patient'
        ));
    }

    // public function storePharmacie(Request $request, Hospitalisation $hospitalisation, HospitalisationService $service)
    // {
    //     $request->validate([
    //             'frais' => 'required|array|min:1',
    //             'frais.*.frais_id' => 'required|exists:frais_hospitalisations,id',
    //             'frais.*.prix' => 'required|numeric|min:0',
    //             'frais.*.quantite' => 'required|integer|min:1',
    //             'frais.*.taux' => 'nullable|integer|min:0',
    //             'frais.*.total' => 'required|numeric|min:0',
    //             'frais.*.medecin_id' => 'nullable|exists:medecins,id',
    //             'date_sortie' => 'required|date',
    //             'date_entree' => 'required|date',
    //             'caution' => 'nullable|numeric|min:0',
    //             'payeur' => 'nullable|string|max:255',
    //         ]);    
    //     $medicamentsData = [];
    //     foreach ($request->medicaments as $med) {
    //         $medicamentsData[$med['medicament_id'] = [
    //             'quantite' => $med['quantite'],
    //             'prix_unitaire' => $med['montant'],
    //             'total' => $med['montant'] * $med['quantite']
    //         ]];
    //     }
        
    //     $service->updatePharmacie($hospitalisation, $medicamentsData);
        
    //     return back()->withSuccess('Pharmacie mise à jour');
    // }
    
public function storePharmacie(Request $request, Hospitalisation $hospitalisation)
{
    DB::beginTransaction();
    try {
        $totalPharmacie = 0;

        foreach ($request->medicaments ?? [] as $med) {
            $medicamentId = $med['medicament_id'];
            $quantite = $med['quantite'];
            $prix = $med['montant'];
            $total = $quantite * $prix;

            // Chercher une ligne existante avec le même médicament ET le même prix
            $ligneExistante = DB::table('hospitalisation_medicament')
                ->where('hospitalisation_id', $hospitalisation->id)
                ->where('medicament_id', $medicamentId)
                ->where('prix_unitaire', $prix)
                ->first();

            if ($ligneExistante) {
                // Ajouter la quantité à la ligne existante
                $nouvelleQuantite = $ligneExistante->quantite + $quantite;
                $nouveauTotal = $nouvelleQuantite * $prix;

                DB::table('hospitalisation_medicament')
                    ->where('id', $ligneExistante->id)
                    ->update([
                        'quantite' => $nouvelleQuantite,
                        'total' => $nouveauTotal,
                        'updated_at' => now(),
                    ]);
            } else {
                // Créer une nouvelle ligne car prix différent
                DB::table('hospitalisation_medicament')->insert([
                    'hospitalisation_id' => $hospitalisation->id,
                    'medicament_id' => $medicamentId,
                    'prix_unitaire' => $prix,
                    'quantite' => $quantite,
                    'total' => $total,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $totalPharmacie += $total;
        }

        // Mettre à jour ou créer le détail pour les médicaments
        $hospitalisation->details()->updateOrCreate(
            ['frais_hospitalisation_id' => 2],
            [
                'hospitalisation_id' => $hospitalisation->id,
                'quantite' => 1,
                'prix_unitaire' => $totalPharmacie,
                'taux' => 0,
                'reduction' => 0,
                'total' => $totalPharmacie,
                'updated_at' => now()
            ]
        );

        DB::commit();
        return redirect()->back()->with('success', 'Médicaments mis à jour avec succès');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
    }
}
    

    public function createExamen(Hospitalisation $hospitalisation)
    {
        $patient = $hospitalisation->patient;

        
        $examensPrescrits = $hospitalisation->examens()->get();


        // Tous les médicaments disponibles
        $allexamens = Examen::orderBy('nom')->get();
        
        return view('dashboard.pages.hospitalisations.laboratoire', compact(
            'hospitalisation', 
            'examensPrescrits', 
            'allexamens', 
            'patient'
        ));
    }

    public function storeExamen(Request $request, Hospitalisation $hospitalisation)
    {
        DB::beginTransaction();
        try {
            // Supprimer tous les médicaments existants pour cette hospitalisation
            $hospitalisation->examens()->detach();

            // Ajouter les médicaments mis à jour
            $examensData = [];
            $totalExamen = 0;

            // Traiter les médicaments existants modifiés
            foreach ($request->examens ?? [] as $examen) {
                $total = $examen['montant'] * $examen['quantite'];
                $examensData[$examen['examen_id']] = [
                    'prix' => $examen['montant'],
                    'quantite' => $examen['quantite'],
                    'total' => $total
                ];
                $totalExamen += $total;
            }

            // Traiter les nouveaux médicaments
            foreach ($request->nouveaux_examens ?? [] as $examen) {
                $total = $examen['montant'] * $examen['quantite'];
                $examensData[$examen['examen_id']] = [
                    'prix' => $examen['montant'],
                    'quantite' => $examen['quantite'],
                    'total' => $total
                ];
                $totalExamen += $total;
            }

            // Attacher tous les médicaments en une seule opération
            $hospitalisation->examens()->attach($examensData);

            // Mettre à jour hospitalisation_details
            $hospitalisation->details()
            ->where('frais_hospitalisation_id', 1)
            ->update([
                'hospitalisation_id' => $hospitalisation->id,
                'frais_hospitalisation_id' => 1,
                'quantite' => 1,
                'prix_unitaire' => $totalExamen,
                'taux' => 0,
                'reduction' => 0,
                'total' => $totalExamen,
            
                'updated_at' => now()
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Examen mis à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    // public function createFacture(Hospitalisation $hospitalisation)
    // {
    //     $patient = $hospitalisation->patient;
    //         $categorie_medecins = Specialite::with('medecins')->get();

        

    //     $detailsLaboratoire = HospitalisationDetail::with('fraisHospitalisation')
    //         ->where('hospitalisation_id', $hospitalisation->id)
    //         ->where('frais_hospitalisation_id', 1)
    //         ->get();


    //         $detailsPharmacie = HospitalisationDetail::with('fraisHospitalisation')
    //         ->where('hospitalisation_id', $hospitalisation->id)
    //         ->where('frais_hospitalisation_id', 2)
    //         ->get();

    //         $autresDetails = HospitalisationDetail::with('fraisHospitalisation')
    //         ->where('hospitalisation_id', $hospitalisation->id)
    //         ->whereNotIn('frais_hospitalisation_id', [1, 2])
    //         ->get();

    //     // Récupérer les frais disponibles non encore utilisés
    //     $utilises = $autresDetails->pluck('frais_hospitalisation_id')->toArray();
    //     $autresFrais = FraisHospitalisation::whereNotIn('id', array_merge([1, 2], $utilises))
    //     ->orderBy('libelle')
    //     ->get();

    //     $taux_assurance = $hospitalisation->patient->taux_assurance ?? 0; // ou 100 par défaut si nécessaire



    //     return view('dashboard.pages.hospitalisations.create', 
    //         compact('hospitalisation', 'patient', 'categorie_medecins', 'detailsLaboratoire', 'detailsPharmacie', 'autresFrais', 'taux_assurance'));
    // }
    public function createFacture(Hospitalisation $hospitalisation)
{
    $patient = $hospitalisation->patient;
    $categorie_medecins = Specialite::with('medecins')->get();

    // Formatage des dates pour la vue
    $dateEntree = $hospitalisation->date_entree 
        ? \Carbon\Carbon::parse($hospitalisation->date_entree)->format('Y-m-d\TH:i')
        : now()->format('Y-m-d\TH:i');
    
    $dateSortie = $hospitalisation->date_sortie
        ? \Carbon\Carbon::parse($hospitalisation->date_sortie)->format('Y-m-d\TH:i')
        : null;

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

    // Récupérer tous les frais existants (sauf 1 et 2)
    $tousFrais = FraisHospitalisation::whereNotIn('id', [1, 2])
        ->orderBy('libelle')
        ->get();

    // Récupérer les IDs des frais déjà utilisés
    $utilises = $autresDetails->pluck('frais_hospitalisation_id')->unique()->toArray();

    // Filtrer les frais disponibles (ceux qui ne sont pas encore utilisés)
    $autresFrais = $tousFrais->reject(function ($frais) use ($utilises) {
        return in_array($frais->id, $utilises);
    });

    $taux_assurance = $patient->taux_couverture ?? 0;

    return view('dashboard.pages.hospitalisations.create', compact(
        'hospitalisation', 
        'patient', 
        'categorie_medecins', 
        'detailsLaboratoire', 
        'detailsPharmacie', 
        'autresFrais',
        'autresDetails',
        'taux_assurance',
        'dateEntree',
        'dateSortie',
        'tousFrais'
    ));
}


    // public function storeFacture(Request $request, Hospitalisation $hospitalisation)
    // {
    //     // dd($request);
    //     // Activation des logs détaillés
    //     \Log::info('Début de storeFacture', ['request' => $request->all(), 'hospitalisation_id' => $hospitalisation->id]);

    //     $request->validate([
    //         'frais' => 'required|array|min:1',
    //         'frais.*.frais_id' => 'required|exists:frais_hospitalisations,id',
    //         'frais.*.prix' => 'required|numeric|min:0',
    //         'frais.*.quantite' => 'required|integer|min:1',
    //         'frais.*.taux' => 'required|integer|min:0',
    //         'frais.*.total' => 'required|numeric|min:0',
    //         'medecin_id' => 'required|exists:medecins,id',
    //         'date_sortie' => 'required|date',
    //         'date_entree' => 'required|date',
    //         'caution' => 'nullable|numeric|min:0',
    //         'payeur' => 'nullable|string|max:255',
    //     ]);
    //     // dd($request);

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
    //                 'taux' => $fraisItem['taux'],
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
    // Validation des données
    $validatedData = $request->validate([
        'frais' => 'required|array|min:1',
        'frais.*.frais_id' => 'required|exists:frais_hospitalisations,id',
        'frais.*.prix' => 'required|numeric|min:0',
        'frais.*.quantite' => 'required|integer|min:1',
        'frais.*.taux' => 'required|numeric|min:0',
        'frais.*.total' => 'required|numeric|min:0',
        'medecin_id' => 'required|exists:medecins,id',
        'date_sortie' => 'required|date',
        'date_entree' => 'required|date',
        'caution' => 'nullable|numeric|min:0',
        'payeur' => 'nullable|string|max:255',
    ]);

    DB::beginTransaction();
    try {
        // Mise à jour des informations de base
        $hospitalisation->update([
            'date_entree' => $validatedData['date_entree'],
            'date_sortie' => $validatedData['date_sortie'],
            'medecin_id' => $validatedData['medecin_id'],
        ]);

        $submittedIds = [];
        $totalGeneral = 0;

        // Récupérer les totaux existants pour pharmacie (2) et examens (1)
        $totalPharmacie = $hospitalisation->details()
            ->where('frais_hospitalisation_id', 2)
            ->sum('total');
        
        $totalExamen = $hospitalisation->details()
            ->where('frais_hospitalisation_id', 1)
            ->sum('total');

        // Traitement des frais (en excluant pharmacie et examens)
        foreach ($validatedData['frais'] as $fraisItem) {
            $fraisId = $fraisItem['frais_id'];
            
            // On saute les frais de pharmacie et examens qui sont gérés ailleurs
            if (in_array($fraisId, [1, 2])) {
                continue;
            }

            $totalItem = $fraisItem['prix'] * $fraisItem['quantite'];
            $totalGeneral += $totalItem;

            $detailData = [
                'hospitalisation_id' => $hospitalisation->id,
                'frais_hospitalisation_id' => $fraisId,
                'quantite' => $fraisItem['quantite'],
                'prix_unitaire' => $fraisItem['prix'],
                'taux' => $fraisItem['taux'],
                'total' => $totalItem,
                'updated_at' => now()
            ];

            // UpdateOrCreate pour éviter les doublons
            $detail = HospitalisationDetail::updateOrCreate(
                [
                    'hospitalisation_id' => $hospitalisation->id,
                    'frais_hospitalisation_id' => $fraisId
                ],
                $detailData
            );

            $submittedIds[] = $detail->id;
        }

        // Ajouter les totaux de pharmacie et examens au total général
        $totalGeneral += $totalPharmacie + $totalExamen;

        // Suppression des frais non soumis (sauf pharmacie et examens)
        // HospitalisationDetail::where('hospitalisation_id', $hospitalisation->id)
        //     ->whereNotIn('frais_hospitalisation_id', [1, 2]) // Exclure pharmacie et examens
        //     ->whereNotIn('id', $submittedIds)
        //     ->delete();

        // Calcul des montants finaux
        $ticketModerateur = $totalGeneral * 0.2;
        $montantAPaye = $totalGeneral - $ticketModerateur - ($hospitalisation->reduction ?? 0);

        // Mise à jour des totaux
        $hospitalisation->update([
            'total' => $totalGeneral,
            'ticket_moderateur' => $ticketModerateur,
            'montant_a_paye' => $montantAPaye,
        ]);

        DB::commit();
        
        return redirect()->back()
            ->with('swal_success', 'Facture enregistrée avec succès.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Erreur storeFacture', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
    }
}

   


}
