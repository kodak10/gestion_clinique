<?php

namespace App\Http\Controllers;

use App\Models\CategoryPrestation;
use App\Models\Consultation;
use App\Models\Patient;
use App\Models\Prestation;
use App\Models\Reglement;
use App\Models\Specialite;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function create(Patient $patient)
    {
        $categories = CategoryPrestation::with('prestations')->get();
        $categorie_medecins = Specialite::with('medecins')->get();
        return view('dashboard.pages.consultation.create', compact('patient', 'categories', 'categorie_medecins'));
    }

    public function store(Request $request, Patient $patient)
{
    $request->validate([
        'medecin_id' => 'required|exists:medecins,id',
        'prestations' => 'required|array',
        'prestations.*.prestation_id' => 'required|exists:prestations,id',
        'prestations.*.montant' => 'required|numeric|min:500',
        'prestations.*.quantite' => 'required|integer|min:1',
        'reduction' => 'required|numeric|min:0',
        'montant_paye' => 'required|numeric|min:0',
        'methode_paiement' => 'required|in:cash,mobile_money,virement'
    ]);

    // 1. Calcul du total
    $totalPrestations = collect($request->prestations)->sum(function($item) {
        return $item['montant'] * $item['quantite'];
    });

    // 2. Ticket modérateur (assurance)
    $tauxAssurance = $patient->assurance->taux ?? 0;
    $ticketModerateur = $totalPrestations * (1 - $tauxAssurance / 100);

    // 3. Montant à payer = ticket - réduction
    $montantAPayer = max($ticketModerateur - $request->reduction, 0);

    // 4. Vérification
    if ($request->montant_paye > $montantAPayer) {
        return back()->withErrors(['montant_paye' => 'Le montant payé ne peut pas dépasser le montant à payer.'])->withInput();
    }

    // 5. Enregistrement
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
    ]);

    // 6. Lier les prestations
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


    return redirect()->route('patients.index', $consultation)
        ->with('success', 'Consultation enregistrée avec succès');
}


   
}
