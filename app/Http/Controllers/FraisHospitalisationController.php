<?php

namespace App\Http\Controllers;

use App\Models\FraisHospitalisation;
use App\Models\CategoryFrais_Hospitalisation;
use Illuminate\Http\Request;

class FraisHospitalisationController extends Controller
{
     public function index()
    {
        $frais = FraisHospitalisation::with('category')->orderBy('libelle', 'asc')->get();
        $categories = CategoryFrais_Hospitalisation::all();
        return view('dashboard.pages.parametrages.frais_hospitalisations', compact('frais', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
        ]);

        FraisHospitalisation::create($validated);

        return redirect()->route('frais_hospitalisations.index')
            ->with('success', 'Frais d\'hospitalisation créé avec succès');
    }

    public function update(Request $request, FraisHospitalisation $fraisHospitalisation)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
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
            ->with('success', 'Frais d\'hospitalisation supprimé');
    }
}
