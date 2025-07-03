<?php

namespace App\Http\Controllers;

use App\Models\Medecin;
use App\Models\Specialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedecinController extends Controller
{
    public function index()
    {
        $medecins = Medecin::with('specialite')->orderBy('name')->get();
        $specialites = Specialite::orderBy('nom', 'asc')->get();
        return view('dashboard.pages.parametrages.medecins', compact('medecins', 'specialites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricule' => 'nullable|unique:medecins',
            'nom_complet' => 'required',
            'telephone' => 'required',
            'specialite_id' => 'required|exists:specialites,id',
        ]);

        $medecin = Medecin::create($validated);
        

        return redirect()->route('medecins.index')->with('success', 'Médecin créé avec succès');
    }

    public function update(Request $request, Medecin $medecin)
    {
        $validated = $request->validate([
            'matricule' => 'nullable|unique:medecins,matricule,'.$medecin->id,
            'nom_complet' => 'required',
            'telephone' => 'required',
            'specialite_id' => 'required|exists:specialites,id',
        ]);

        $medecin->update($validated);

        return redirect()->route('medecins.index')->with('success', 'Médecin mis à jour');
    }
}
