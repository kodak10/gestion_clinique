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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use NumberToWords\NumberToWords;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
class HospitalisationController extends Controller
{

   public function index()
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Receptionniste', 'Facturié', 'Pharmacien', 'Manager'])) {
            abort(403, 'Accès non autorisé.');
        }

        // Filtrer uniquement les hospitalisations dont le status est 'present'
        $hospitalisations = Hospitalisation::with('patient')
            ->where('status', 'present')
            ->get();

        return view('dashboard.pages.hospitalisations.index', compact('hospitalisations'));
    }


   public function storeSimple(Patient $patient)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Receptionniste', 'Caissière'])) {
            abort(403, 'Accès non autorisé.');
        }

        DB::beginTransaction();

        try {
            // Génération du numéro de facture unique
            $numeroFacture = $this->generateInvoiceNumber();

            // Création de l'hospitalisation
            $hospitalisation = Hospitalisation::create([
                'patient_id' => $patient->id,
                'user_id' => auth()->id(),
                'numero_facture' => $numeroFacture,
                'total' => 0,
                'ticket_moderateur' => 0,
                'reduction' => 0,
                'montant_a_paye' => 0,
                'reste_a_payer' => 0,
                'date_entree' => now(),
            ]);

            // Génération et stockage du QR code
            $qrContent = "HOSP-{$hospitalisation->id}-{$numeroFacture}";
            $qrCode = QrCode::format('png')->size(200)->generate($qrContent);
            
            $directory = 'qr-codes/hospitalisations';
            $fileName = "hosp-{$hospitalisation->id}-{$numeroFacture}.png";
            $path = "{$directory}/{$fileName}";
            
            Storage::disk('public')->put($path, $qrCode);
            
            // Mise à jour avec le chemin du QR code
            $hospitalisation->update(['qr_code_path' => $path]);

            // Ajout des deux lignes de détails
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
            
            return redirect()->back()->with([
                'success' => 'Le patient a été hospitalisé avec le numéro de facture: '.$numeroFacture,
                'qr_code_path' => $path // Optionnel: pour afficher directement
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    private function generateInvoiceNumber()
    {
        $lastInvoice = Hospitalisation::orderBy('id', 'desc')->first();
        $lastNumber = $lastInvoice ? intval(substr($lastInvoice->numero_facture, 1)) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        return 'H' . $newNumber;
    }

    public function sortir(Hospitalisation $hospitalisation)
    {
        if ($hospitalisation->status === 'sorti') {
            return redirect()->back()->with('warning', 'Le patient est déjà sorti.');
        }

        $hospitalisation->status = 'sorti';
        $hospitalisation->date_sortie = now();
        $hospitalisation->save();

        return redirect()->back()->with('success', 'Le patient a bien été déclaré sorti.');
    }

    public function rentrer(Hospitalisation $hospitalisation)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin'])) {
            abort(403, 'Accès non autorisé.');
        }
        if ($hospitalisation->status === 'present') {
            return redirect()->back()->with('warning', 'Le patient est déjà présent.');
        }

        $hospitalisation->status = 'present';
        $hospitalisation->save();

        return redirect()->back()->with('success', 'Le patient a bien été déclaré présent.');
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

    // avant de supprimer
    // public function storePharmacie(Request $request, Hospitalisation $hospitalisation)
    // {
    //     if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Pharmacien'])) {
    //         abort(403, 'Accès non autorisé.');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         // D'abord supprimer tous les médicaments existants pour cette hospitalisation
    //         DB::table('hospitalisation_medicament')
    //             ->where('hospitalisation_id', $hospitalisation->id)
    //             ->delete();

    //         $totalPharmacie = 0;
    //         $medicamentsTraites = []; // Pour éviter les doublons dans la même requête

    //         foreach ($request->medicaments ?? [] as $med) {
    //             $medicamentId = $med['medicament_id'];
    //             $quantite = $med['quantite'];
    //             $prix = $med['montant'];
    //             $total = $quantite * $prix;

    //             // Vérifier si ce médicament a déjà été traité dans cette requête
    //             if (in_array($medicamentId, $medicamentsTraites)) {
    //                 DB::rollBack();
    //                 return redirect()->back()->with('error', 
    //                     'Un médicament est en double.');
    //             }

    //             $medicamentsTraites[] = $medicamentId;

    //             // Insertion du nouveau médicament
    //             DB::table('hospitalisation_medicament')->insert([
    //                 'hospitalisation_id' => $hospitalisation->id,
    //                 'medicament_id' => $medicamentId,
    //                 'prix_unitaire' => $prix,
    //                 'quantite' => $quantite,
    //                 'total' => $total,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);

    //             $totalPharmacie += $total;
    //         }

    //         // Mettre à jour ou créer le détail pour les médicaments
    //         $hospitalisation->details()->updateOrCreate(
    //             ['frais_hospitalisation_id' => 2], // ID pour pharmacie
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
            // Récupérer les médicaments existants avant suppression
            $anciensMedicaments = DB::table('hospitalisation_medicament')
                ->where('hospitalisation_id', $hospitalisation->id)
                ->get();

            // D'abord supprimer tous les médicaments existants pour cette hospitalisation
            DB::table('hospitalisation_medicament')
                ->where('hospitalisation_id', $hospitalisation->id)
                ->delete();

            // Remettre les anciens médicaments dans le stock
            foreach ($anciensMedicaments as $ancienMed) {
                DB::table('medicaments')
                    ->where('id', $ancienMed->medicament_id)
                    ->increment('stock', $ancienMed->quantite);

                // Historique de la restitution
                DB::table('medicament_mouvements')->insert([
                    'medicament_id' => $ancienMed->medicament_id,
                    'quantite' => $ancienMed->quantite,
                    'type' => 'entree',
                    'user_id' => Auth::id(),
                    'hospitalisation_id' => $hospitalisation->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $totalPharmacie = 0;
            $medicamentsTraites = [];

            foreach ($request->medicaments ?? [] as $med) {
                $medicamentId = $med['medicament_id'];
                $quantite = $med['quantite'];
                $prix = $med['montant'];
                $total = $quantite * $prix;

                if (in_array($medicamentId, $medicamentsTraites)) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 
                        'Un médicament est en double.');
                }

                // Vérifier le stock disponible
                $stockDisponible = DB::table('medicaments')
                    ->where('id', $medicamentId)
                    ->value('stock');

                if ($stockDisponible < $quantite) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 
                        'Stock insuffisant pour le médicament: ' . $med['nom']);
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

                // Mettre à jour le stock
                DB::table('medicaments')
                    ->where('id', $medicamentId)
                    ->decrement('stock', $quantite);

                // Historique de la sortie
                DB::table('medicament_mouvements')->insert([
                    'medicament_id' => $medicamentId,
                    'quantite' => $quantite,
                    'type' => 'sortie',
                    // 'motif' => 'Prescription hospitalisation',
                    // 'utilisateur_nom' => Auth::user()->name,
                    'user_id' => Auth::id(),
                    'hospitalisation_id' => $hospitalisation->id,
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

    public function storeExamen(Request $request, Hospitalisation $hospitalisation)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Laboratin', 'Receptionniste', 'Facturié'])) {
            abort(403, 'Accès non autorisé.');
        }

        DB::beginTransaction();
        try {
            // D'abord supprimer tous les examens existants pour cette hospitalisation
            DB::table('hospitalisation_examen')
                ->where('hospitalisation_id', $hospitalisation->id)
                ->delete();

            $totalExamen = 0;
            $examensTraites = []; // Pour éviter les doublons dans la même requête

            foreach ($request->examens ?? [] as $examen) {
                $examenId = $examen['examen_id'];
                $quantite = $examen['quantite'];
                $prix = $examen['montant'];
                $taux = isset($examen['taux']) ? floatval($examen['taux']) : 0;

                // Vérifier si cet examen a déjà été traité dans cette requête
                if (in_array($examenId, $examensTraites)) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 
                        'L\'examen ID '.$examenId.' est en doublon dans le formulaire.');
                }

                $examensTraites[] = $examenId;

                // Calcul du total à la charge du patient
                $totalBrut = $prix * $quantite;
                $partAssurance = $totalBrut * ($taux / 100);
                $partPatient = $totalBrut - $partAssurance;

                // Insertion du nouvel examen
                DB::table('hospitalisation_examen')->insert([
                    'hospitalisation_id' => $hospitalisation->id,
                    'examen_id' => $examenId,
                    'prix' => $prix,
                    'taux' => $taux,
                    'quantite' => $quantite,
                    'total' => $partPatient,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $totalExamen += $partPatient;
            }

            // Mettre à jour ou créer le détail pour les examens
            $hospitalisation->details()->updateOrCreate(
                ['frais_hospitalisation_id' => 1], // ID pour laboratoire
                [
                    'hospitalisation_id' => $hospitalisation->id,
                    'quantite' => 1,
                    'prix_unitaire' => $totalExamen,
                    'taux' => 0,
                    'reduction' => 0,
                    'total' => $totalExamen,
                    'updated_at' => now()
                ]
            );

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
            // Mise à jour des informations de base
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
                'reste_a_payer' => $validatedData['montant_a_paye'] - ($validatedData['caution'] - $validatedData['caution'] ?? 0),
                'reduction' => $validatedData['reduction'] ?? 0,
                'reduction_par' => $validatedData['reduction_par'] ?? null,
            ]);

            // D'abord supprimer tous les détails existants pour cette hospitalisation
            $hospitalisation->details()->delete();

            $totalGeneral = 0;
            $fraisTraites = []; // Pour éviter les doublons dans la même requête

            // Combiner tous les frais (pharmacie + labo + autres)
            $allFrais = [
                $validatedData['frais_pharmacie'],
                $validatedData['frais_laboratoire'],
                ...$validatedData['frais']
            ];

            foreach ($allFrais as $fraisItem) {
                $fraisId = $fraisItem['frais_id'];

                // Vérifier si ce frais a déjà été traité dans cette requête
                if (in_array($fraisId, $fraisTraites)) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 
                        'Le frais ID '.$fraisId.' est en doublon dans le formulaire.');
                }

                $fraisTraites[] = $fraisId;

                // Créer le nouveau détail
                $detailData = [
                    'hospitalisation_id' => $hospitalisation->id,
                    'frais_hospitalisation_id' => $fraisId,
                    'quantite' => $fraisItem['quantite'],
                    'prix_unitaire' => $fraisItem['prix'],
                    'taux' => $fraisItem['taux'],
                    'total' => $fraisItem['total'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                HospitalisationDetail::create($detailData);

                $totalGeneral += $fraisItem['total'];
            }

            // Génération du PDF
            $facturePath = $this->generateFacturePdf($hospitalisation);
            $hospitalisation->update(['facture_path' => $facturePath]);

            DB::commit();
            return redirect()->back()
                ->with('swal_success', 'Facture enregistrée avec succès.')
                ->with('pdf_url', Storage::url($facturePath));

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
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('fr');
        $montantEnLettres = ucfirst($numberTransformer->toWords($hospitalisation->montant_a_paye));

        $pdf = Pdf::loadView('dashboard.documents.facture', [
            'hospitalisation' => $hospitalisation,
            'patient' => $hospitalisation->patient,
            'medecin' => $hospitalisation->medecin,
            'details' => $hospitalisation->details()->with('frais')->get(),
            'montantEnLettres' => $montantEnLettres
        ]);

        // Chemin relatif dans le storage public
        $directory = 'factures';
        $filename = 'facture_'.$hospitalisation->id.'_'.now()->format('YmdHis').'.pdf';
        $filepath = $directory.'/'.$filename;

        // Création du dossier si inexistant dans le storage public
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Suppression de l'ancien fichier
        if ($hospitalisation->facture_path) {
            Storage::disk('public')->delete($hospitalisation->facture_path);
        }

        // Sauvegarde du nouveau fichier dans le storage public
        Storage::disk('public')->put($filepath, $pdf->output());

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

public function printLaboratoire($id)
{
    $hospitalisation = Hospitalisation::with(['patient', 'medecin'])->findOrFail($id);
    
    // Récupération des détails laboratoire depuis hospitalisation_details
   $examens = DB::table('hospitalisation_examen')
        ->where('hospitalisation_id', $id)
        ->join('examens', 'hospitalisation_examen.examen_id', '=', 'examens.id')
        ->select('hospitalisation_examen.*', 'examens.nom as libelle')
        ->get();
    
        //dd($examens);

    $pdf = Pdf::loadView('dashboard.documents.print_laboratoire', [
        'hospitalisation' => $hospitalisation,
        'details' => $examens
    ]);

    return $pdf->stream('laboratoire_'.$id.'.pdf');
}


public function printPharmacie($id)
{
    $hospitalisation = Hospitalisation::with(['patient', 'medecin'])->findOrFail($id);
    
    // Utilisez la table hospitalisation_medicament
    $medicaments = DB::table('hospitalisation_medicament')
        ->where('hospitalisation_id', $id)
        ->join('medicaments', 'hospitalisation_medicament.medicament_id', '=', 'medicaments.id')
        ->select('hospitalisation_medicament.*', 'medicaments.nom as libelle')
        ->get();

    $pdf = Pdf::loadView('dashboard.documents.print_pharmacie', [
        'hospitalisation' => $hospitalisation,
        'medicaments' => $medicaments
    ]);

    return $pdf->stream('pharmacie_'.$id.'.pdf');
}

}
