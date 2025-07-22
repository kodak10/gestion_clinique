<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActeAmbulatoire extends Controller
{
    public function index()
    {
        $actes = ActeAmbulatoire::with(['patient', 'medecin'])
            ->orderBy('date_realisation', 'desc')
            ->paginate(20);

        return view('actes-ambulatoires.index', compact('actes'));
    }

    public function create(Patient $patient, Consultation $consultation)
    {
        $medecins = User::where('role', 'medecin')->get();
        $categoriesActes = CategorieActe::with('actes')->get();

        return view('actes-ambulatoires.create', compact('patient', 'consultation', 'medecins', 'categoriesActes'));
    }

    public function store(Request $request, Patient $patient, Consultation $consultation)
    {
        $validated = $request->validate([
            'acte_id' => 'required|exists:actes,id',
            'medecin_id' => 'required|exists:users,id',
            'date_realisation' => 'required|date',
            'observations' => 'nullable|string',
        ]);

        $acte = Acte::findOrFail($validated['acte_id']);

        $acteAmbulatoire = ActeAmbulatoire::create([
            'consultation_id' => $consultation->id,
            'patient_id' => $patient->id,
            'medecin_id' => $validated['medecin_id'],
            'code_acte' => 'ACT-' . strtoupper(uniqid()),
            'libelle' => $acte->libelle,
            'description' => $acte->description,
            'cout' => $acte->cout,
            'taux_couverture' => $patient->taux_couverture ?? 0,
            'montant_patient' => $acte->cout * (1 - ($patient->taux_couverture ?? 0) / 100),
            'montant_rembourse' => $acte->cout * (($patient->taux_couverture ?? 0) / 100),
            'statut' => 'en_attente',
            'date_realisation' => $validated['date_realisation'],
            'observations' => $validated['observations'],
        ]);

        return redirect()
            ->route('consultations.show', [$patient, $consultation])
            ->with('success', 'Acte ambulatoire créé avec succès.');
    }

    public function show(Patient $patient, Consultation $consultation, ActeAmbulatoire $acte)
    {
        return view('actes-ambulatoires.show', compact('patient', 'consultation', 'acte'));
    }

    public function updateStatus(Request $request, ActeAmbulatoire $acte)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,realise,annule,reporte',
            'date_realisation' => 'nullable|date|required_if:statut,reporte',
            'observations' => 'nullable|string',
        ]);

        $acte->update([
            'statut' => $request->statut,
            'date_realisation' => $request->statut === 'reporte' 
                ? $request->date_realisation 
                : $acte->date_realisation,
            'observations' => $request->observations ?? $acte->observations,
        ]);

        return back()->with('success', 'Statut de l\'acte mis à jour.');
    }
}
