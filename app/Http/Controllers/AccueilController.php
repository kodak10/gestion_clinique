<?php

namespace App\Http\Controllers;

use App\Models\Medecin;
use App\Models\Patient;
use App\Models\Rdv;
use App\Models\Specialite;
use Illuminate\Http\Request;

class AccueilController extends Controller
{
    public function home()
    {
        $rendezVous = Rdv::with(['patient', 'medecin', 'specialite'])
            ->where('date_heure', '>=', now())
            ->orderBy('date_heure')
            ->get();

        $patients = Patient::all();
        $medecins = Medecin::all();
        $specialites = Specialite::all();

        return view('dashboard.pages.index', compact('rendezVous', 'patients', 'medecins', 'specialites'));
    }

    public function storeRdv(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medecin_id' => 'required|exists:medecins,id',
            'specialite_id' => 'required|exists:specialites,id',
            'date_heure' => 'required|date|after:now',
            'motif' => 'nullable|string|max:500'
        ]);

        Rdv::create($validated);

        return redirect()->route('home')
            ->with('success', 'Rendez-vous créé avec succès');
    }

    public function editRdv(Rdv $rendezVou)
    {
        return response()->json([
            'rendezVous' => $rendezVou,
            'patients' => Patient::all(),
            'medecins' => Medecin::all(),
            'specialites' => Specialite::all()
        ]);
    }

    public function updateRdv(Request $request, RendezVous $rendezVou)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medecin_id' => 'required|exists:medecins,id',
            'specialite_id' => 'required|exists:specialites,id',
            'date_heure' => 'required|date',
            'statut' => 'required|in:confirmé,en_attente,annulé,terminé',
            'motif' => 'nullable|string|max:500'
        ]);

        $rendezVou->update($validated);

        return redirect()->route('rendez-vous.index')
            ->with('success', 'Rendez-vous mis à jour avec succès');
    }

    public function destroyRdv(Rdv $rendezVou)
    {
        $rendezVou->delete();

        return redirect()->route('rendez-vous.index')
            ->with('success', 'Rendez-vous supprimé avec succès');
    }
}
