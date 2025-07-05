<?php

namespace App\Http\Controllers;

use App\Models\CategoryPrestation;
use App\Models\Consultation;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\Prestation;
use App\Models\Reglement;
use App\Models\Specialite;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConsultationController extends Controller
{
    public function create(Patient $patient)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Respo Caissière', 'Caissière', 'Facturié', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

        $categories = CategoryPrestation::with('prestations')->get();
        $categorie_medecins = Specialite::with('medecins')->get();
        return view('dashboard.pages.consultation.create', compact('patient', 'categories', 'categorie_medecins'));
    }


public function store(Request $request, Patient $patient)
{
    // Vérification des permissions
    if (!Auth::user()->hasAnyRole(['Receptionniste', 'Caissière', 'Developpeur'])) {
        abort(403, 'Accès non autorisé.');
    }

    // Validation du formulaire
    $validated = $request->validate([
        'medecin_id' => 'required|exists:medecins,id',
        'specialite' => 'required',
        'prestations' => 'required|array',
        'prestations.*.prestation_id' => 'required|exists:prestations,id',
        'prestations.*.montant' => 'required|numeric|min:500',
        'prestations.*.quantite' => 'required|integer|min:1',
        'prestations.*.taux' => 'required|numeric|min:0|max:100',
        'reduction' => 'required|numeric|min:0',
        'total' => 'required|numeric|min:0',
        'ticket_moderateur' => 'required|numeric|min:0',
        'montant_a_paye' => 'required|numeric|min:0',
        'methode_paiement' => 'required_if:montant_paye,>0|in:cash,mobile_money,virement',
        'montant_paye' => 'nullable|numeric|min:0|max:'.$request->montant_a_paye
    ]);

    // Détermination des montants
    $isCaissiere = Auth::user()->hasRole('Caissière');
    $montantPaye = $isCaissiere ? ($validated['montant_paye'] ?? 0) : 0;
    $resteAPayer = $validated['montant_a_paye'] - $montantPaye;

    // Création de la consultation
    $consultation = Consultation::create([
        'user_id' => auth()->id(),
        'patient_id' => $patient->id,
        'medecin_id' => $validated['medecin_id'],
        'total' => $validated['total'],
        'ticket_moderateur' => $validated['ticket_moderateur'],
        'reduction' => $validated['reduction'],
        'montant_a_paye' => $validated['montant_a_paye'],
        'montant_paye' => $montantPaye,
        'reste_a_payer' => $resteAPayer,
        'date_consultation' => now(),
        'numero_recu' => date('Ymd').'-'.Str::upper(Str::random(6)),
    ]);

    // Enregistrement des prestations
    foreach ($validated['prestations'] as $prestation) {
        $consultation->prestations()->attach($prestation['prestation_id'], [
            'quantite' => $prestation['quantite'],
            'montant' => $prestation['montant'],
            'taux' => $prestation['taux'],
            'total' => $prestation['montant'] * $prestation['quantite']
        ]);
    }

    // Enregistrement du paiement initial si montant payé > 0
    if ($montantPaye > 0) {
        $this->enregistrerPaiement(
            consultation: $consultation,
            montant: $montantPaye,
            methode: $validated['methode_paiement'],
            type: 'initial'
        );
    }

    return redirect()
        ->route('patients.index', $patient)
        ->with('success', 'Consultation créée avec succès');
}

public function ajouterPaiement(Request $request, Consultation $consultation)
{
    // Vérification des permissions
    if (!Auth::user()->hasRole('Caissière')) {
        abort(403, 'Accès non autorisé.');
    }

    // Validation
    $validated = $request->validate([
        'montant' => 'required|numeric|min:0.01|max:'.$consultation->reste_a_payer,
        'methode_paiement' => 'required|in:cash,mobile_money,virement'
    ]);

    // Enregistrement du paiement
    $this->enregistrerPaiement(
        consultation: $consultation,
        montant: $validated['montant'],
        methode: $validated['methode_paiement'],
        type: 'complémentaire'
    );

    return back()->with('success', 'Paiement supplémentaire enregistré');
}

private function enregistrerPaiement(Consultation $consultation, float $montant, string $methode, string $type)
{
    // Création du règlement
    Reglement::create([
        'consultation_id' => $consultation->id,
        'user_id' => auth()->id(),
        'montant' => $montant,
        'methode_paiement' => $methode,
        'type' => $type,
        'date_reglement' => now(),
    ]);

    // Mise à jour de la consultation
    $consultation->update([
        'montant_paye' => $consultation->montant_paye + $montant,
        'reste_a_payer' => $consultation->reste_a_payer - $montant
    ]);
}


    public function index()
{
    // Vérification des permissions
    if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Respo Caissière', 'Caissière', 'Facturié', 'Comptable'])) {
        abort(403, 'Accès non autorisé.');
    }

    // Récupération des règlements avec les relations nécessaires
    $reglements = Reglement::with([
            'consultation.patient', 
            'consultation.medecin',
            'hospitalisation.patient',
            'user'
        ])
        ->latest()
        ->get(); 

    return view('dashboard.pages.comptabilites.reglement', compact('reglements'));
}

    public function edit(Consultation $consultation)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Respo Caissière', 'Caissière', 'Facturié'])) {
            abort(403, 'Accès non autorisé.');
        }

        $patient = $consultation->patient;
        $categories = CategoryPrestation::with('prestations')->get();
        $categorie_medecins = Specialite::with('medecins')->get();
        
        // Récupérer les prestations existantes pour les passer à la vue
        $prestationsExistantes = $consultation->prestations->map(function($prestation) {
            return [
                'prestation_id' => $prestation->id,
                'montant' => $prestation->pivot->montant,
                'taux' => $prestation->pivot->taux,
                'quantite' => $prestation->pivot->quantite,
                'total' => $prestation->pivot->total
            ];
        })->toArray();

        // dd($prestationsExistantes);

        return view('dashboard.pages.consultation.edit', compact(
            'consultation',
            'patient',
            'categories',
            'categorie_medecins',
            'prestationsExistantes'
        ));
    }

    // public function update(Request $request, Consultation $consultation)
    // {
    //     if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Respo Caissière'])) {
    //         abort(403, 'Accès non autorisé.');
    //     }

    //     // Validation des données
    //     $validatedData = $request->validate([
    //         'medecin_id' => 'required|exists:medecins,id',
    //         'specialite' => 'required',
    //         'prestations' => 'required|array|min:1',
    //         'prestations.*.prestation_id' => 'required|exists:prestations,id',
    //         'prestations.*.montant' => 'required|numeric|min:500',
    //         'prestations.*.quantite' => 'required|integer|min:1',
    //         'reduction' => 'required|numeric|min:0',
    //         'montant_paye' => 'required|numeric|min:0',
    //         'methode_paiement' => 'required|in:cash,mobile_money,virement'
    //     ]);

    //     // Calcul des montants
    //     $totalPrestations = collect($request->prestations)->sum(function($item) {
    //         return $item['montant'] * $item['quantite'];
    //     });

    //     $tauxAssurance = $consultation->patient->assurance->taux ?? 0;
    //     $ticketModerateur = $totalPrestations * (1 - $tauxAssurance / 100);
    //     $montantAPayer = max($ticketModerateur - $request->reduction, 0);

    //     dd([
    //         'ticket moderateur' => $ticketModerateur,
    //         'montant_a_paye' => $montantAPayer,
    //         'paye' => $request->montant_paye,
            
    //     ]);


    //     // Vérification de cohérence
    //     if (round($request->montant_paye, 2) > round($montantAPayer, 2)) {
    //         return back()->withErrors(['montant_paye' => 'Le montant payé ne peut pas dépasser le montant à payer.'])->withInput();
    //     }


    //     // Calcul de la différence avec les anciens règlements
    //     $ancienTotalPaye = $consultation->reglements->sum('montant');
    //     $difference = $request->montant_paye - $ancienTotalPaye;

    //     // Transaction pour garantir l'intégrité des données
    //     DB::transaction(function() use (
    //         $request, 
    //         $consultation, 
    //         $totalPrestations,
    //         $ticketModerateur,
    //         $montantAPayer,
    //         $difference
    //     ) {
    //         // Mise à jour de la consultation
    //         $consultation->update([
    //             'medecin_id' => $request->medecin_id,
    //             'specialite' => $request->specialite,
    //             'total' => $totalPrestations,
    //             'ticket_moderateur' => $ticketModerateur,
    //             'reduction' => $request->reduction,
    //             'montant_a_paye' => $montantAPayer,
    //             'montant_paye' => $request->montant_paye,
    //             'reste_a_payer' => $montantAPayer - $request->montant_paye,
    //             'methode_paiement' => $request->methode_paiement,
    //         ]);

    //         // Mise à jour des prestations
    //         $consultation->prestations()->detach();
    //         foreach ($request->prestations as $prestation) {
    //             $consultation->prestations()->attach($prestation['prestation_id'], [
    //                 'quantite' => $prestation['quantite'],
    //                 'montant' => $prestation['montant'],
    //                 'total' => $prestation['montant'] * $prestation['quantite']
    //             ]);
    //         }

    //         $lastReglement = $consultation->reglements()->latest()->first();

    //         if ($lastReglement) {
    //             $lastReglement->update([
    //                 'montant' => $request->montant_paye,
    //                 'methode_paiement' => $request->methode_paiement,
    //                 'notes' => 'Montant ajusté après modification',
    //             ]);
    //         } else {
    //             $consultation->reglements()->create([
    //                 'montant' => $request->montant_paye,
    //                 'methode_paiement' => $request->methode_paiement,
    //                 'notes' => 'Premier règlement (ajusté)'
    //             ]);
    //         }
    //     });

    //     // Régénération du PDF
    //     $pdf = Pdf::loadView('dashboard.documents.recu_consultation', [
    //         'consultation' => $consultation->fresh(), // Recharger les relations
    //         'patient' => $consultation->patient,
    //         'medecin' => $consultation->medecin,
    //         'prestations' => $consultation->prestations,
    //         'date' => $consultation->date_consultation->format('d/m/Y H:i'),
    //         'numeroRecu' => $consultation->numero_recu,
    //         'user' => $consultation->user,
    //     ]);

    //     $pdfPath = 'consultations/recu-' . $consultation->id . '-' . now()->format('YmdHis') . '.pdf';
    //     Storage::disk('public')->put($pdfPath, $pdf->output());

    //     // Mise à jour du chemin du PDF
    //     $consultation->update(['pdf_path' => $pdfPath]);

    //     return back()->with([
    //         'success' => 'Consultation mise à jour avec succès',
    //         'pdf_url' => Storage::url($pdfPath)
    //     ]);

    // }

    public function update(Request $request, Consultation $consultation)
{
    if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Respo Caissière'])) {
        abort(403, 'Accès non autorisé.');
    }

    $validated = $request->validate([
        'medecin_id' => 'required|exists:medecins,id',
        'specialite' => 'required',
        'prestations' => 'required|array|min:1',
        'prestations.*.prestation_id' => 'required|exists:prestations,id',
        'prestations.*.montant' => 'required|numeric|min:500',
        'prestations.*.quantite' => 'required|integer|min:1',
        'prestations.*.taux' => 'nullable|numeric|min:0|max:100',
        'reduction' => 'required|numeric|min:0',
        'total' => 'required|numeric|min:0',
        'ticket_moderateur' => 'required|numeric|min:0',
        'montant_a_paye' => 'required|numeric|min:0'
    ]);

    DB::transaction(function() use ($request, $consultation, $validated) {
        // Mise à jour de la consultation
        $consultation->update([
            'medecin_id' => $validated['medecin_id'],
            'specialite' => $validated['specialite'],
            'total' => $validated['total'],
            'ticket_moderateur' => $validated['ticket_moderateur'],
            'reduction' => $validated['reduction'],
            'montant_a_paye' => $validated['montant_a_paye'],
            'reste_a_payer' => $validated['montant_a_paye'] - $consultation->montant_paye
        ]);

        // Mise à jour des prestations
        $consultation->prestations()->detach();
        foreach ($validated['prestations'] as $prestation) {
            $consultation->prestations()->attach($prestation['prestation_id'], [
                'quantite' => $prestation['quantite'],
                'montant' => $prestation['montant'],
                'taux' => $prestation['taux'] ?? 0,
                'total' => $prestation['montant'] * $prestation['quantite']
            ]);
        }
    });

    // Régénération du PDF
    $pdf = Pdf::loadView('dashboard.documents.recu_consultation', [
        'consultation' => $consultation->fresh(),
        'patient' => $consultation->patient,
        'medecin' => $consultation->medecin,
        'prestations' => $consultation->prestations,
        'date' => $consultation->date_consultation->format('d/m/Y H:i'),
        'numeroRecu' => $consultation->numero_recu,
        'user' => $consultation->user,
    ]);

    $pdfPath = 'consultations/recu-'.$consultation->id.'-'.now()->format('YmdHis').'.pdf';
    Storage::disk('public')->put($pdfPath, $pdf->output());
    $consultation->update(['pdf_path' => $pdfPath]);

    return back()->with([
        'success' => 'Consultation mise à jour avec succès',
        'pdf_url' => Storage::url($pdfPath)
    ]);
}


}
