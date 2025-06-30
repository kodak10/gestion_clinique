<?php

namespace App\Http\Controllers;

use App\Models\CategoryFrais_Hospitalisation;
use App\Models\FraisHospitalisation;
use App\Models\Hospitalisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class FraisHospitalisationController extends Controller
{
    public function index()
    {
        $frais = FraisHospitalisation::orderBy('libelle', 'asc')->get();
        return view('dashboard.pages.parametrages.frais_hospitalisations', compact('frais'));
    }

    public function storeCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|nu|unique:category_frais__hospitalisations,nom',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        CategoryFrais_Hospitalisation::create([
            'nom' => $request->nom,
        ]);

        return redirect()->back()->with('success', 'Catégorie ajoutée avec succès.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
        ]);

        FraisHospitalisation::create($validated);

        return redirect()->route('frais_hospitalisations.index')
            ->with('success', "Frais d'hospitalisation créé avec succès");
    }

    public function update(Request $request, FraisHospitalisation $fraisHospitalisation)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $fraisHospitalisation->update($validated);

        return redirect()->route('frais_hospitalisations.index')
            ->with('success', 'Frais d\'hospitalisation mis à jour');
    }

    public function destroy(FraisHospitalisation $fraisHospitalisation)
    {
        $fraisHospitalisation->delete();
        return redirect()->route('frais_hospitalisations.index')
            ->with('success', "Frais d'hospitalisation supprimé");
    }
}
