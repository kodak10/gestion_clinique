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

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConsultationController extends Controller
{
    public function create(Patient $patient)
    {
        $categories = CategoryPrestation::with('prestations')->get();
        $categorie_medecins = Specialite::with('medecins')->get();
        return view('dashboard.pages.consultation.create', compact('patient', 'categories', 'categorie_medecins'));
    }

//     public function store(Request $request, Patient $patient)
// {
//     $request->validate([
//         'medecin_id' => 'required|exists:medecins,id',
//         'prestations' => 'required|array',
//         'prestations.*.prestation_id' => 'required|exists:prestations,id',
//         'prestations.*.montant' => 'required|numeric|min:500',
//         'prestations.*.quantite' => 'required|integer|min:1',
//         'reduction' => 'required|numeric|min:0',
//         'montant_paye' => 'required|numeric|min:0',
//         'methode_paiement' => 'required|in:cash,mobile_money,virement'
//     ]);

//     // 1. Calcul du total
//     $totalPrestations = collect($request->prestations)->sum(function($item) {
//         return $item['montant'] * $item['quantite'];
//     });

//     // 2. Ticket modérateur (assurance)
//     $tauxAssurance = $patient->assurance->taux ?? 0;
//     $ticketModerateur = $totalPrestations * (1 - $tauxAssurance / 100);

//     // 3. Montant à payer = ticket - réduction
//     $montantAPayer = max($ticketModerateur - $request->reduction, 0);

//     // 4. Vérification
//     if ($request->montant_paye > $montantAPayer) {
//         return back()->withErrors(['montant_paye' => 'Le montant payé ne peut pas dépasser le montant à payer.'])->withInput();
//     }

//     // 5. Enregistrement
//     $consultation = Consultation::create([
//         'user_id' => auth()->id(),
//         'patient_id' => $patient->id,
//         'medecin_id' => $request->medecin_id,
//         'total' => $totalPrestations,
//         'ticket_moderateur' => $ticketModerateur,
//         'reduction' => $request->reduction,
//         'montant_a_paye' => $montantAPayer,
//         'montant_paye' => $request->montant_paye,
//         'reste_a_payer' => $montantAPayer - $request->montant_paye,
//         'methode_paiement' => $request->methode_paiement,
//         'date_consultation' => now(),
//     ]);

//     // 6. Lier les prestations
//     foreach ($request->prestations as $prestation) {
//         $consultation->prestations()->attach($prestation['prestation_id'], [
//             'quantite' => $prestation['quantite'],
//             'montant' => $prestation['montant'],
//             'total' => $prestation['montant'] * $prestation['quantite']
//         ]);
//     }

//     Reglement::create([
//         'consultation_id' => $consultation->id,
//         'user_id' => auth()->id(),
//         'montant' => $request->montant_paye,
//         'methode_paiement' => $request->methode_paiement,
//         'type' => 'entrée',
//     ]);


//     return redirect()->route('patients.index', $consultation)
//         ->with('success', 'Consultation enregistrée avec succès');
// }


public function store(Request $request, Patient $patient)
{
    $request->validate([
        'medecin_id' => 'required|exists:medecins,id',
        'specialite' => 'required',
        'prestations' => 'required|array',
        'prestations.*.prestation_id' => 'required|exists:prestations,id',
        'prestations.*.montant' => 'required|numeric|min:500',
        'prestations.*.quantite' => 'required|integer|min:1',
        'reduction' => 'required|numeric|min:0',
        'montant_paye' => 'required|numeric|min:0',
        'methode_paiement' => 'required|in:cash,mobile_money,virement'
    ]);

    // Génération du numéro de reçu
    $numeroRecu = date('Ymd') . '-' . Str::upper(Str::random(6));

    // Calcul du total
    $totalPrestations = collect($request->prestations)->sum(function($item) {
        return $item['montant'] * $item['quantite'];
    });

    // Ticket modérateur (assurance)
    $tauxAssurance = $patient->assurance->taux ?? 0;
    $ticketModerateur = $totalPrestations * (1 - $tauxAssurance / 100);

    // Montant à payer = ticket - réduction
    $montantAPayer = max($ticketModerateur - $request->reduction, 0);

    // Vérification
    if ($request->montant_paye > $montantAPayer) {
        return back()->withErrors(['montant_paye' => 'Le montant payé ne peut pas dépasser le montant à payer.'])->withInput();
    }

    // Enregistrement
    $consultation = Consultation::create([
        'user_id' => auth()->id(),
        'patient_id' => $patient->id,
        'medecin_id' => $request->medecin_id,
        'total' => $totalPrestations,
        'ticket_moderateur' => $ticketModerateur,
        'reduction' => $request->reduction,
        'montant_a_paye' => $montantAPayer,
        'montant_paye' => $request->montant_paye,
        'reste_a_payer' => $montantAPayer - $request->montant_paye,
        'methode_paiement' => $request->methode_paiement,
        'date_consultation' => now(),
        'numero_recu' => $numeroRecu,

    ]);

    // Lier les prestations
    foreach ($request->prestations as $prestation) {
        $consultation->prestations()->attach($prestation['prestation_id'], [
            'quantite' => $prestation['quantite'],
            'montant' => $prestation['montant'],
            'total' => $prestation['montant'] * $prestation['quantite']
        ]);
    }

    Reglement::create([
        'consultation_id' => $consultation->id,
        'user_id' => auth()->id(),
        'montant' => $request->montant_paye,
        'methode_paiement' => $request->methode_paiement,
        'type' => 'entrée',
    ]);

    // Génération du PDF
    $medecin = Medecin::find($request->medecin_id);
    $prestations = $consultation->prestations;

    $data = [
        'consultation' => $consultation,
        'patient' => $patient,
        'medecin' => $medecin,
        'prestations' => $prestations,
        'date' => $consultation->date_consultation->format('d/m/Y H:i'),
        'numeroRecu' => $numeroRecu,
        'user' => auth()->user(),
    ];

    $pdf = Pdf::loadView('dashboard.documents.recu_consultation', $data);

    // Création du dossier si inexistant
    $directory = 'consultations/'.$consultation->id;
    if (!Storage::exists($directory)) {
        Storage::makeDirectory($directory);
    }

    // Chemin du fichier PDF
    $filename = 'recu-consultation-'.$numeroRecu.'.pdf';
    $path = $directory.'/'.$filename;

    // Enregistrement du PDF
    
    Storage::disk('public')->put($path, $pdf->output());

    $consultation->update([
        'pdf_path' => $path
    ]);

    return redirect()
        ->route('patients.index', $patient)
        ->with([
            'success' => 'Consultation créée avec succès',
            'pdf_url' => Storage::url($path)
        ]);
}

    public function index()
{
    $consultations = Consultation::with(['patient', 'medecin'])->latest()->get(); // <- AJOUTE get()
    return view('dashboard.pages.consultation.index', compact('consultations'));
}

public function edit(Consultation $consultation)
{
    $patient = $consultation->patient;
    $categories = CategoryPrestation::with('prestations')->get();
    $categorie_medecins = Specialite::with('medecins')->get();
    
    // Récupérer les prestations existantes pour les passer à la vue
    $prestationsExistantes = $consultation->prestations->map(function($prestation) {
        return [
            'prestation_id' => $prestation->id,
            'montant' => $prestation->pivot->montant,
            'quantite' => $prestation->pivot->quantite,
            'total' => $prestation->pivot->total
        ];
    })->toArray();

    return view('dashboard.pages.consultation.edit', compact(
        'consultation',
        'patient',
        'categories',
        'categorie_medecins',
        'prestationsExistantes'
    ));
}

public function update(Request $request, Consultation $consultation)
{
    // Validation des données
    $validatedData = $request->validate([
        'medecin_id' => 'required|exists:medecins,id',
        'specialite' => 'required',
        'prestations' => 'required|array|min:1',
        'prestations.*.prestation_id' => 'required|exists:prestations,id',
        'prestations.*.montant' => 'required|numeric|min:500',
        'prestations.*.quantite' => 'required|integer|min:1',
        'reduction' => 'required|numeric|min:0',
        'montant_paye' => 'required|numeric|min:0',
        'methode_paiement' => 'required|in:cash,mobile_money,virement'
    ]);

    // Calcul des montants
    $totalPrestations = collect($request->prestations)->sum(function($item) {
        return $item['montant'] * $item['quantite'];
    });

    $tauxAssurance = $consultation->patient->assurance->taux ?? 0;
    $ticketModerateur = $totalPrestations * (1 - $tauxAssurance / 100);
    $montantAPayer = max($ticketModerateur - $request->reduction, 0);

    // Vérification de cohérence
    if ($request->montant_paye > $montantAPayer) {
        return back()->withErrors([
            'montant_paye' => 'Le montant payé ne peut pas dépasser le montant à payer.'
        ])->withInput();
    }

    // Calcul de la différence avec les anciens règlements
    $ancienTotalPaye = $consultation->reglements->sum('montant');
    $difference = $request->montant_paye - $ancienTotalPaye;

    // Transaction pour garantir l'intégrité des données
    DB::transaction(function() use (
        $request, 
        $consultation, 
        $totalPrestations,
        $ticketModerateur,
        $montantAPayer,
        $difference
    ) {
        // Mise à jour de la consultation
        $consultation->update([
            'medecin_id' => $request->medecin_id,
            'specialite' => $request->specialite,
            'total' => $totalPrestations,
            'ticket_moderateur' => $ticketModerateur,
            'reduction' => $request->reduction,
            'montant_a_paye' => $montantAPayer,
            'montant_paye' => $request->montant_paye,
            'reste_a_payer' => $montantAPayer - $request->montant_paye,
            'methode_paiement' => $request->methode_paiement,
        ]);

        // Mise à jour des prestations
        $consultation->prestations()->detach();
        foreach ($request->prestations as $prestation) {
            $consultation->prestations()->attach($prestation['prestation_id'], [
                'quantite' => $prestation['quantite'],
                'montant' => $prestation['montant'],
                'total' => $prestation['montant'] * $prestation['quantite']
            ]);
        }

        // Gestion des règlements
        // if (abs($difference) > 0) {
        //     $consultation->reglements()->create([
        //         'montant' => $difference,
        //         'methode_paiement' => $request->methode_paiement,
        //         'user_id' => auth()->id(),
        //         'notes' => $difference > 0 
        //             ? 'Ajustement positif après modification' 
        //             : 'Ajustement négatif après modification'
        //     ]);
        // } else {
        //     // Mise à jour du dernier règlement si pas de différence
        //     if ($consultation->reglements->isNotEmpty()) {
        //         $consultation->reglements->last()->update([
        //             'methode_paiement' => $request->methode_paiement
        //         ]);
        //     }
        // }
        $lastReglement = $consultation->reglements()->latest()->first();

        if ($lastReglement) {
            $lastReglement->update([
                'montant' => $request->montant_paye,
                'methode_paiement' => $request->methode_paiement,
                'notes' => 'Montant ajusté après modification',
                'user_id' => auth()->id(),
            ]);
        } else {
            $consultation->reglements()->create([
                'montant' => $request->montant_paye,
                'methode_paiement' => $request->methode_paiement,
                //'user_id' => auth()->id(),
                'notes' => 'Premier règlement (ajusté)'
            ]);
        }


    });

    // Régénération du PDF
    $pdf = Pdf::loadView('dashboard.documents.recu_consultation', [
        'consultation' => $consultation->fresh(), // Recharger les relations
        'patient' => $consultation->patient,
        'medecin' => $consultation->medecin,
        'prestations' => $consultation->prestations,
        'date' => $consultation->date_consultation->format('d/m/Y H:i'),
        'numeroRecu' => $consultation->numero_recu,
        'user' => $consultation->user,
    ]);

    
    

    $pdfPath = 'consultations/recu-' . $consultation->id . '-' . now()->format('YmdHis') . '.pdf';
    Storage::disk('public')->put($pdfPath, $pdf->output());

    // Mise à jour du chemin du PDF
    $consultation->update(['pdf_path' => $pdfPath]);

    
    return back()->with([
    'success' => 'Consultation mise à jour avec succès',
    'pdf_url' => Storage::url($pdfPath)
]);


}

}
