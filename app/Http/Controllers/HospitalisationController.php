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
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class HospitalisationController extends Controller
{

    public function index()
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Receptionniste', 'Facturié', 'Pharmacien'])) {
            abort(403, 'Accès non autorisé.');
        }

        $hospitalisations = Hospitalisation::with('patient')->get();
        return view('dashboard.pages.hospitalisations.index', compact('hospitalisations'));
    }

    public function storeSimple(Patient $patient)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Receptionniste'])) {
            abort(403, 'Accès non autorisé.');
        }

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
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Laboratin', 'Receptionniste', 'Facturié', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

        $patient = $hospitalisation->patient;        
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
    
    // public function storePharmacie(Request $request, Hospitalisation $hospitalisation)
    // {
    //     if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Pharmacien'])) {
    //         abort(403, 'Accès non autorisé.');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $totalPharmacie = 0;

    //         foreach ($request->medicaments ?? [] as $med) {
    //             $medicamentId = $med['medicament_id'];
    //             $quantite = $med['quantite'];
    //             $prix = $med['montant'];
    //             $total = $quantite * $prix;

    //             // Chercher une ligne existante avec le même médicament ET le même prix
    //             $ligneExistante = DB::table('hospitalisation_medicament')
    //                 ->where('hospitalisation_id', $hospitalisation->id)
    //                 ->where('medicament_id', $medicamentId)
    //                 ->where('prix_unitaire', $prix)
    //                 ->first();

    //             if ($ligneExistante) {
    //                 // Ajouter la quantité à la ligne existante
    //                 $nouvelleQuantite = $ligneExistante->quantite + $quantite;
    //                 $nouveauTotal = $nouvelleQuantite * $prix;

    //                 DB::table('hospitalisation_medicament')
    //                     ->where('id', $ligneExistante->id)
    //                     ->update([
    //                         'quantite' => $nouvelleQuantite,
    //                         'total' => $nouveauTotal,
    //                         'updated_at' => now(),
    //                     ]);
    //             } else {
    //                 // Créer une nouvelle ligne car prix différent
    //                 DB::table('hospitalisation_medicament')->insert([
    //                     'hospitalisation_id' => $hospitalisation->id,
    //                     'medicament_id' => $medicamentId,
    //                     'prix_unitaire' => $prix,
    //                     'quantite' => $quantite,
    //                     'total' => $total,
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ]);
    //             }

    //             $totalPharmacie += $total;
    //         }

    //         // Mettre à jour ou créer le détail pour les médicaments
    //         $hospitalisation->details()->updateOrCreate(
    //             ['frais_hospitalisation_id' => 2],
    //             [
    //                 'hospitalisation_id' => $hospitalisation->id,
    //                 'quantite' => 1,
    //                 'prix_unitaire' => $totalPharmacie,
    //                 'taux' => 0,
    //                 'reduction' => 0,
    //                 'total' => $totalPharmacie,
    //                 'updated_at' => now()
    //             ]
    //         );

    //         DB::commit();
    //         return redirect()->back()->with('success', 'Médicaments mis à jour avec succès');

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
    //     }
    // }
public function storePharmacie(Request $request, Hospitalisation $hospitalisation)
{
    if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Pharmacien'])) {
        abort(403, 'Accès non autorisé.');
    }

    DB::beginTransaction();
    try {
        // D'abord supprimer tous les médicaments existants pour cette hospitalisation
        DB::table('hospitalisation_medicament')
            ->where('hospitalisation_id', $hospitalisation->id)
            ->delete();

        $totalPharmacie = 0;
        $medicamentsTraites = []; // Pour éviter les doublons dans la même requête

        foreach ($request->medicaments ?? [] as $med) {
            $medicamentId = $med['medicament_id'];
            $quantite = $med['quantite'];
            $prix = $med['montant'];
            $total = $quantite * $prix;

            // Vérifier si ce médicament a déjà été traité dans cette requête
            if (in_array($medicamentId, $medicamentsTraites)) {
                DB::rollBack();
                return redirect()->back()->with('error', 
                    'Le médicament ID '.$medicamentId.' est en doublon dans le formulaire.');
            }

            $medicamentsTraites[] = $medicamentId;

            // Insertion du nouveau médicament
            DB::table('hospitalisation_medicament')->insert([
                'hospitalisation_id' => $hospitalisation->id,
                'medicament_id' => $medicamentId,
                'prix_unitaire' => $prix,
                'quantite' => $quantite,
                'total' => $total,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $totalPharmacie += $total;
        }

        // Mettre à jour ou créer le détail pour les médicaments
        $hospitalisation->details()->updateOrCreate(
            ['frais_hospitalisation_id' => 2], // ID pour pharmacie
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
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Laboratin', 'Receptionniste', 'Facturié', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

        $patient = $hospitalisation->patient;
        $examensPrescrits = $hospitalisation->examens()->orderBy('created_at')->get();

        // dd($examensPrescrits);
        // Tous les médicaments disponibles
        $allexamens = Examen::orderBy('nom')->get();
        
        return view('dashboard.pages.hospitalisations.laboratoire', compact(
            'hospitalisation', 
            'examensPrescrits', 
            'allexamens', 
            'patient'
        ));
    }

    
    // public function storeExamen(Request $request, Hospitalisation $hospitalisation)
    // {
    //     if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Laboratin', 'Receptionniste'])) {
    //         abort(403, 'Accès non autorisé.');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         // Supprimer tous les examens existants pour cette hospitalisation
    //         $hospitalisation->examens()->detach();

    //         // Ajouter les examens mis à jour
    //         $examensData = [];
    //         $totalExamen = 0;

    //         // Traiter les examens existants modifiés
    //         foreach ($request->examens ?? [] as $examen) {
    //             $totalBrut = $examen['montant'] * $examen['quantite'];
    //             $taux = isset($examen['taux']) ? floatval($examen['taux']) : 0;
    //             $partAssurance = $totalBrut * ($taux / 100);
    //             $partPatient = $totalBrut - $partAssurance;

    //             $examensData[$examen['examen_id']] = [
    //                 'prix' => $examen['montant'],
    //                 'taux' => $taux,
    //                 'quantite' => $examen['quantite'],
    //                 'total' => $partPatient, // On stocke la part à la charge du patient
    //             ];
    //             $totalExamen += $partPatient;
    //         }

    //         // Traiter les nouveaux examens (si applicable)
    //         foreach ($request->nouveaux_examens ?? [] as $examen) {
    //             $totalBrut = $examen['montant'] * $examen['quantite'];
    //             $taux = isset($examen['taux']) ? floatval($examen['taux']) : 0;
    //             $partAssurance = $totalBrut * ($taux / 100);
    //             $partPatient = $totalBrut - $partAssurance;

    //             $examensData[$examen['examen_id']] = [
    //                 'prix' => $examen['montant'],
    //                 'taux' => $taux,
    //                 'quantite' => $examen['quantite'],
    //                 'total' => $partPatient,
    //             ];
    //             $totalExamen += $partPatient;
    //         }

    //         // Attacher tous les examens en une seule opération
    //         $hospitalisation->examens()->attach($examensData);

    //         // Mettre à jour hospitalisation_details
    //         $hospitalisation->details()
    //             ->where('frais_hospitalisation_id', 1)
    //             ->update([
    //                 'hospitalisation_id' => $hospitalisation->id,
    //                 'frais_hospitalisation_id' => 1,
    //                 'quantite' => 1,
    //                 'prix_unitaire' => $totalExamen,
    //                 'taux' => 0,
    //                 'reduction' => 0,
    //                 'total' => $totalExamen,
    //                 'updated_at' => now()
    //             ]);

    //         DB::commit();

    //         return redirect()->back()->with('success', 'Examen mis à jour avec succès');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
    //     }
    // }
    public function storeExamen(Request $request, Hospitalisation $hospitalisation)
{
    if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Laboratin', 'Receptionniste'])) {
        abort(403, 'Accès non autorisé.');
    }

    DB::beginTransaction();
    try {
        $examensData = [];
        $totalExamen = 0;

        foreach (($request->examens ?? []) as $examen) {
            $examenId = $examen['examen_id'];
            $quantite = $examen['quantite'];
            $prix = $examen['montant'];
            $taux = isset($examen['taux']) ? floatval($examen['taux']) : 0;

            // Vérifier s'il existe déjà cet examen pour cette hospitalisation avec un prix ou taux différent
            $doublon = DB::table('hospitalisation_examen')
                ->where('hospitalisation_id', $hospitalisation->id)
                ->where('examen_id', $examenId)
                ->where(function($query) use ($prix, $taux) {
                    $query->where('prix', '!=', $prix)
                          ->orWhere('taux', '!=', $taux);
                })
                ->first();

            if ($doublon) {
                DB::rollBack();
                return redirect()->back()->with('error',
                    'Cet examen existe déjà pour cette hospitalisation avec un prix ou un taux différent ('
                    . 'Prix : ' . $doublon->prix . ' XOF, Taux : ' . $doublon->taux . '%). '
                    . 'Veuillez corriger ou supprimer la ligne existante avant de continuer.');
            }

            // Calcul du total à la charge du patient
            $totalBrut = $prix * $quantite;
            $partAssurance = $totalBrut * ($taux / 100);
            $partPatient = $totalBrut - $partAssurance;

            // Préparer les données pour l'attach
            $examensData[$examenId] = [
                'prix' => $prix,
                'taux' => $taux,
                'quantite' => $quantite,
                'total' => $partPatient,
            ];
            $totalExamen += $partPatient;
        }

        // Supprimer tous les examens existants pour cette hospitalisation (si tu veux écraser tout)
        $hospitalisation->examens()->detach();

        // Attacher tous les examens en une seule opération
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

        return redirect()->back()->with('success', 'Examens mis à jour avec succès');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
    }
}



    
    public function createFacture(Hospitalisation $hospitalisation)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Receptionniste', 'Facturié', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

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


public function storeFacture(Request $request, Hospitalisation $hospitalisation)
{
    if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Receptionniste', 'Facturié', 'Comptable'])) {
        abort(403, 'Accès non autorisé.');
    }

    // Validation des données avec les champs fixes
    $validatedData = $request->validate([
        // Champs fixes pour pharmacie et labo
        'frais_pharmacie.frais_id' => 'required|exists:frais_hospitalisations,id',
        'frais_pharmacie.prix' => 'required|numeric|min:0',
        'frais_pharmacie.quantite' => 'required|integer|min:1',
        'frais_pharmacie.taux' => 'required|numeric|min:0',
        'frais_pharmacie.total' => 'required|numeric|min:0',
        
        'frais_laboratoire.frais_id' => 'required|exists:frais_hospitalisations,id',
        'frais_laboratoire.prix' => 'required|numeric|min:0',
        'frais_laboratoire.quantite' => 'required|integer|min:1',
        'frais_laboratoire.taux' => 'required|numeric|min:0',
        'frais_laboratoire.total' => 'required|numeric|min:0',
        
        // Autres frais dynamiques
        'frais' => 'required|array|min:1',
        'frais.*.frais_id' => 'required|exists:frais_hospitalisations,id',
        'frais.*.prix' => 'required|numeric|min:0',
        'frais.*.quantite' => 'required|integer|min:1',
        'frais.*.taux' => 'required|numeric|min:0',
        'frais.*.total' => 'required|numeric|min:0',
        
        // Autres champs
        'medecin_id' => 'required|exists:medecins,id',
        'specialite_id' => 'required|exists:specialites,id',
        'date_sortie' => 'required|date',
        'date_entree' => 'required|date',
        'caution' => 'nullable|numeric|min:0',
        'payeur' => 'nullable|string|max:255',
        'total' => 'required|numeric|min:0',
        'ticket_moderateur' => 'required|numeric|min:0',
        'montant_a_paye' => 'required|numeric|min:0',
        'reduction' => 'nullable|numeric|min:0',
        'reduction_par' => 'required_if:reduction,>,1|nullable|string|max:255',
    ]);

    DB::beginTransaction();
    try {
        // Mise à jour des informations de base (inchangé)
        $hospitalisation->update([
            'date_entree' => $validatedData['date_entree'],
            'date_sortie' => $validatedData['date_sortie'],
            'medecin_id' => $validatedData['medecin_id'],
            'specialite_id' => $validatedData['specialite_id'],
            'caution' => $validatedData['caution'] ?? 0,
            'payeur' => $validatedData['payeur'] ?? null,
            'total' => $validatedData['total'],
            'ticket_moderateur' => $validatedData['ticket_moderateur'],
            'montant_a_paye' => $validatedData['montant_a_paye'],
            'reste_a_payer' => $validatedData['montant_a_paye'] - ($validatedData['caution'] ?? 0),
            'reduction' => $validatedData['reduction'] ?? 0,
            'reduction_par' => $validatedData['reduction_par'] ?? null,
        ]);

        // Combiner tous les frais (pharmacie + labo + autres)
        $allFrais = [
            $validatedData['frais_pharmacie'],
            $validatedData['frais_laboratoire'],
            ...$validatedData['frais']
        ];

        // Récupérer les IDs des frais soumis
        $submittedFraisIds = collect($allFrais)->pluck('frais_id')->toArray();

        // Supprimer les frais qui ne sont plus dans la requête
        $hospitalisation->details()
            ->whereNotIn('frais_hospitalisation_id', $submittedFraisIds)
            ->delete();

        $totalGeneral = 0;

        // Traitement de tous les frais
        foreach ($allFrais as $fraisItem) {
            $fraisId = $fraisItem['frais_id'];
            $prix = $fraisItem['prix'];
            $taux = $fraisItem['taux'];

            // Vérification des doublons (inchangé)
            $doublon = \App\Models\HospitalisationDetail::where('hospitalisation_id', $hospitalisation->id)
                ->where('frais_hospitalisation_id', $fraisId)
                ->where(function($query) use ($prix, $taux) {
                    $query->where('prix_unitaire', '!=', $prix)
                          ->orWhere('taux', '!=', $taux);
                })
                ->first();

            if ($doublon) {
                DB::rollBack();
                return redirect()->back()->with('error',
                    'Un doublon a été détecté pour le frais "' . ($doublon->frais->libelle ?? 'Inconnu') . '" : '
                    . 'Prix existant : ' . $doublon->prix_unitaire . ' XOF, Taux existant : ' . $doublon->taux . '%.<br>'
                    . 'Veuillez corriger ou supprimer la ligne existante avant de continuer.');
            }

            $detailData = [
                'hospitalisation_id' => $hospitalisation->id,
                'frais_hospitalisation_id' => $fraisId,
                'quantite' => $fraisItem['quantite'],
                'prix_unitaire' => $prix,
                'taux' => $taux,
                'total' => $fraisItem['total'],
                'updated_at' => now()
            ];

            \App\Models\HospitalisationDetail::updateOrCreate(
                [
                    'hospitalisation_id' => $hospitalisation->id,
                    'frais_hospitalisation_id' => $fraisId
                ],
                $detailData
            );

            $totalGeneral += $fraisItem['total'];
        }

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



    private function generateFacturePdf(Hospitalisation $hospitalisation)
    {
        $pdf = Pdf::loadView('dashboard.documents.facture', [
            'hospitalisation' => $hospitalisation,
            'patient' => $hospitalisation->patient,
            'medecin' => $hospitalisation->medecin,
            'details' => $hospitalisation->details
        ]);

        // Création du dossier si inexistant
        $directory = storage_path('app/public/factures');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Nom du fichier
        $filename = 'facture_' . $hospitalisation->id . '_' . time() . '.pdf';
        $filepath = 'factures/' . $filename;
        $fullPath = storage_path('app/public/' . $filepath);

        // Suppression de l'ancien fichier s'il existe
        if ($hospitalisation->facture_path) {
            $oldPath = storage_path('app/public/' . $hospitalisation->facture_path);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // Sauvegarde du nouveau fichier
        $pdf->save($fullPath);

        return $filepath;
    }   

    private function createOrUpdateReglement(Hospitalisation $hospitalisation, array $data)
    {
        // Calcul du montant à enregistrer (montant payé = montant total - caution)
        $montantPaye = $data['montant_a_paye'] - ($hospitalisation->caution ?? 0);
        
        // Si le montant est positif, on enregistre le règlement
        if ($montantPaye > 0) {
            Reglement::updateOrCreate(
                [
                    'hospitalisation_id' => $hospitalisation->id,
                    'type' => 'entrée'
                ],
                [
                    'user_id' => auth()->id(),
                    'montant' => $montantPaye,
                    'methode_paiement' => $data['methode_paiement'] ?? 'cash',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }

}
