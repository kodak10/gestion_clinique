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
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Receptionniste'])) {
            abort(403, 'Accès non autorisé.');
        }

        $categories = CategoryPrestation::with('prestations')->get();
        $categorie_medecins = Specialite::with('medecins')->get();
        return view('dashboard.pages.consultation.create', compact('patient', 'categories', 'categorie_medecins'));
    }

    private function generateReceiptNumber()
    {
        $lastReceipt = Consultation::orderBy('id', 'desc')->first();
        $lastNumber = $lastReceipt ? intval(substr($lastReceipt->numero_recu, 1)) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        return 'R' . $newNumber;
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

     if (isset($validated['montant_paye']) && $validated['montant_paye'] > $validated['montant_a_paye']) {
        return back()->with('error', 'Le montant payé ne peut pas dépasser le montant à payer');
    }

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
        'methode_paiement' => $validated['methode_paiement'] ?? null,
        // 'numero_recu' => date('Ymd').'-'.Str::upper(Str::random(6)),
        'numero_recu' => $this->generateReceiptNumber(),
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
        $pdfPath = $this->enregistrerPaiement( // Capturez la valeur retournée
            consultation: $consultation,
            montant: $montantPaye,
            methode: $validated['methode_paiement'],
            type: 'entrée'
        );

        return redirect()
            ->route('patients.index', $patient)
            ->with([
                'success' => 'Acte Ambulatoire crée avec succès',
                'pdf_url' => Storage::url($pdfPath) // Utilisez la variable capturée
            ]);
    }

    

    return redirect()
        ->route('patients.index', $patient)
        ->with('success', 'Acte Ambulatoire crée avec succès');
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
    $pdfPath = $this->enregistrerPaiement( // Capturez la valeur retournée
        consultation: $consultation,
        montant: $validated['montant'],
        methode: $validated['methode_paiement'],
        type: 'entrée'
    );
    return back()->with([
        'success' => 'Paiement supplémentaire enregistré',
        'pdf_url' => Storage::url($pdfPath)
    ]);

}

private function enregistrerPaiement(Consultation $consultation, float $montant, string $methode, string $type)
{
    // Création du règlement
    $reglement = Reglement::create([
        'consultation_id' => $consultation->id,
        'user_id' => auth()->id(),
        'montant' => $montant,
        'methode_paiement' => $methode,
        'type' => $type,
        'date_reglement' => now(),
    ]);

    // Recharger la consultation avec toutes ses relations
    $consultation->refresh()->load(['patient', 'medecin', 'prestations', 'user']);

    // Génération du PDF
    $pdf = PDF::loadView('dashboard.documents.recu', [
        'consultation' => $consultation,
        'patient' => $consultation->patient,
        'medecin' => $consultation->medecin,
        'prestations' => $consultation->prestations,
        'date' => $reglement->created_at->format('d/m/Y H:i'),
        'numeroRecu' => $consultation->numero_recu,
        'user' => $consultation->user,
        'reglement' => $reglement, // Ajout du règlement spécifique
        'totalPaye' => $consultation->montant_paye + $montant, // Montant total payé après ce règlement
    ]);

    // Chemin de stockage du PDF
    $pdfPath = 'consultations/recu-' . $consultation->id . '-' . now()->format('YmdHis') . '.pdf';
    
    // Stockage du PDF
    Storage::disk('public')->put($pdfPath, $pdf->output());

   // dd([$montant,$consultation->reste_a_payer,$consultation->montant_paye,]);
    
    

    // Mise à jour de la consultation
    $consultation->update([
        'montant_paye' => $consultation->montant_paye,
        'reste_a_payer' => $consultation->reste_a_payer,
        'pdf_path' => $pdfPath // Sauvegarde du chemin du PDF
    ]);

    // Retourner le chemin du PDF pour un éventuel téléchargement
    return $pdfPath;
}



    public function edit(Consultation $consultation)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Respo Caissière'])) {
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
//     if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Respo Caissière', 'Caissière'])) {
//         abort(403, 'Accès non autorisé.');
//     }

//     $validated = $request->validate([
//         'medecin_id' => 'required|exists:medecins,id',
//         'specialite' => 'required',
//         'prestations' => 'required|array|min:1',
//         'prestations.*.prestation_id' => 'required|exists:prestations,id',
//         'prestations.*.montant' => 'required|numeric|min:500',
//         'prestations.*.quantite' => 'required|integer|min:1',
//         'prestations.*.taux' => 'nullable|numeric|min:0|max:100',
//         'reduction' => 'required|numeric|min:0',
//         'total' => 'required|numeric|min:0',
//         'ticket_moderateur' => 'required|numeric|min:0',

//         'montant_a_paye' => [
//             'required',
//             'numeric',
//             'min:0',
//             function ($attribute, $value, $fail) use ($consultation) {
//                 if ($value < $consultation->montant_paye) {
//                     $fail('Le montant à payer ('.$value.' FCFA) ne peut pas être inférieur au montant déjà payé ('.$consultation->montant_paye.' FCFA)');
//                 }
//             },
//         ],
//         'methode_paiement' => 'required_if:montant_paye,>0|in:cash,mobile_money,virement',
//         'montant_paye' => 'nullable|numeric|min:0|max:'.$request->montant_a_paye
//     ]);
//     // dd($validated);

//     if (isset($validated['montant_paye']) && $validated['montant_paye'] > $validated['montant_a_paye']) {
//         return back()->with('error', 'Le montant payé ne peut pas dépasser le montant à payer');
//     }

//     // Détermination des montants
//     $isCaissiere = Auth::user()->hasRole('Caissière');
//     $montantPaye = $isCaissiere ? ($validated['montant_paye'] ?? 0) : 0;
//     $resteAPayer = $validated['montant_a_paye'] - ($consultation->montant_paye + $montantPaye);

//     DB::transaction(function() use ($consultation, $validated, $montantPaye, $resteAPayer) {
//         // Mise à jour de la consultation
//         $consultation->update([
//             'medecin_id' => $validated['medecin_id'],
//             'specialite' => $validated['specialite'],
//             'total' => $validated['total'],
//             'ticket_moderateur' => $validated['ticket_moderateur'],
//             'reduction' => $validated['reduction'],
//             'montant_a_paye' => $validated['montant_a_paye'],
//             'montant_paye' => $consultation->montant_paye + $montantPaye,
//             'reste_a_payer' => $resteAPayer
//         ]);

//         // Mise à jour des prestations
//         $consultation->prestations()->detach();
//         foreach ($validated['prestations'] as $prestation) {
//             $consultation->prestations()->attach($prestation['prestation_id'], [
//                 'quantite' => $prestation['quantite'],
//                 'montant' => $prestation['montant'],
//                 'taux' => $prestation['taux'] ?? 0,
//                 'total' => $prestation['montant'] * $prestation['quantite']
//             ]);
//         }

//         // Enregistrement du paiement si montant payé > 0
//         if ($montantPaye > 0) {
//             $this->enregistrerPaiement(
//                 consultation: $consultation,
//                 montant: $montantPaye,
                
//                 methode: $validated['methode_paiement'],
//                 type: 'entrée'
//             );
//         }
//     });

//     $dernierReglement = $consultation->reglements()->latest()->first();

//     // Régénération du PDF
//     $pdf = Pdf::loadView('dashboard.documents.recu', [
//         'consultation' => $consultation->fresh(),
//         'patient' => $consultation->patient,
//         'medecin' => $consultation->medecin,
//         'prestations' => $consultation->prestations,
//         'date' => $consultation->date_consultation->format('d/m/Y H:i'),
//         'numeroRecu' => $consultation->numero_recu,
//         'user' => $consultation->user,
//         'reglement' => $dernierReglement,
        
//     ]);

//     $pdfPath = 'consultations/recu-'.$consultation->id.'-'.now()->format('YmdHis').'.pdf';
//     Storage::disk('public')->put($pdfPath, $pdf->output());
//     $consultation->update(['pdf_path' => $pdfPath]);

//     return back()->with([
//         'success' => 'Consultation mise à jour avec succès',
//         'pdf_url' => Storage::url($pdfPath)
//     ]);
// }

public function update(Request $request, Consultation $consultation)
{
    if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Respo Caissière', 'Caissière'])) {
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
        'montant_a_paye' => [
            'required',
            'numeric',
            'min:0',
            function ($attribute, $value, $fail) use ($consultation) {
                if ($value < $consultation->montant_paye) {
                    $fail('Le montant à payer ('.$value.' FCFA) ne peut pas être inférieur au montant déjà payé ('.$consultation->montant_paye.' FCFA)');
                }
            },
        ],
        'methode_paiement' => 'required_if:montant_paye,>0|in:cash,mobile_money,virement',
        'montant_paye' => 'nullable|numeric|min:0|max:'.$request->montant_a_paye
    ]);

    if (isset($validated['montant_paye']) && $validated['montant_paye'] > $validated['montant_a_paye']) {
        return back()->with('error', 'Le montant payé ne peut pas dépasser le montant à payer');
    }

    // Détermination des montants
    $isCaissiere = Auth::user()->hasRole('Caissière');
    $montantPaye = $isCaissiere ? ($validated['montant_paye'] ?? 0) : 0;
    $resteAPayer = $validated['montant_a_paye'] - ($consultation->montant_paye + $montantPaye);

    $reglement = null;

    DB::transaction(function() use ($consultation, $validated, $montantPaye, $resteAPayer, &$reglement) {
        // Mise à jour de la consultation
        $consultation->update([
            'medecin_id' => $validated['medecin_id'],
            'specialite' => $validated['specialite'],
            'total' => $validated['total'],
            'ticket_moderateur' => $validated['ticket_moderateur'],
            'reduction' => $validated['reduction'],
            'montant_a_paye' => $validated['montant_a_paye'],
            'montant_paye' => $consultation->montant_paye + $montantPaye,
            'reste_a_payer' => $resteAPayer
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

        // Enregistrement du paiement si montant payé > 0
        if ($montantPaye > 0) {
            $reglement = $this->enregistrerPaiement(
                consultation: $consultation,
                montant: $montantPaye,
                methode: $validated['methode_paiement'],
                type: 'entrée'
            );
        }
    });

    // Get the latest reglement if none was created in this update
    if (!$reglement) {
        $reglement = $consultation->reglements()->latest()->first();
    }

    // Régénération du PDF
    $pdf = Pdf::loadView('dashboard.documents.recu', [
        'consultation' => $consultation->fresh(),
        'patient' => $consultation->patient,
        'medecin' => $consultation->medecin,
        'prestations' => $consultation->prestations,
        'date' => $consultation->date_consultation->format('d/m/Y H:i'),
        'numeroRecu' => $consultation->numero_recu,
        'user' => $consultation->user,
        'reglement' => $reglement,
        'totalPaye' => $consultation->montant_paye,
    ]);

    $pdfPath = 'consultations/recu-'.$consultation->id.'-'.now()->format('YmdHis').'.pdf';
    Storage::disk('public')->put($pdfPath, $pdf->output());
    $consultation->update(['pdf_path' => $pdfPath]);

    return back()->with([
        'success' => 'Acte Ambulatoire mis à jour avec succès',
        'pdf_url' => Storage::url($pdfPath)
    ]);
}

}
