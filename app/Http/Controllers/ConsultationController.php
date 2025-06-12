<?php

namespace App\Http\Controllers;

use App\Models\CategoryPrestation;
use App\Models\Consultation;
use App\Models\Patient;
use App\Models\Prestation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function create(Patient $patient)
    {
        $categories = CategoryPrestation::with('prestations')->get();
        return view('dashboard.pages.consultation.create', compact('patient', 'categories'));
    }

    public function store(Request $request, Patient $patient)
    {
        $request->validate([
            'prestations' => 'required|array',
            'prestations.*.id' => 'required|exists:prestations,id',
            'prestations.*.quantite' => 'required|integer|min:1',
            'reduction' => 'nullable|numeric|min:0',
        ]);

        // Calcul des montants
        $total = 0;
        $prestationsData = [];

        foreach ($request->prestations as $prestation) {
            $prestationModel = Prestation::find($prestation['id']);
            $montant = $prestationModel->montant * $prestation['quantite'];
            $total += $montant;

            $prestationsData[$prestation['id']] = [
                'quantite' => $prestation['quantite'],
                'montant' => $prestationModel->montant
            ];
        }

        $ticketModerateur = $patient->assurance ? $total * ($patient->assurance->taux_couverture / 100) : 0;
        $montantAPayer = $total - $ticketModerateur - $request->reduction;

        $consultation = Consultation::create([
            'patient_id' => $patient->id,
            'user_id' => auth()->id(),
            'date_consultation' => now(),
            'total' => $total,
            'ticket_moderateur' => $ticketModerateur,
            'reduction' => $request->reduction ?? 0,
            'montant_paye' => $montantAPayer
        ]);

        $consultation->prestations()->sync($prestationsData);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Consultation enregistrée avec succès');
    }
}
