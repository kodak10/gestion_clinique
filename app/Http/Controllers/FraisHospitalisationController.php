<?php

namespace App\Http\Controllers;

use App\Models\CategoryFrais_Hospitalisation;
use App\Models\FraisHospitalisation;
use App\Models\Hospitalisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FraisHospitalisationController extends Controller
{
     public function index()
    {
        $frais = FraisHospitalisation::orderBy('libelle', 'asc')->get();
        //$categories = CategoryFrais__Hospitalisation::orderBy('nom', 'asc')->get();;
        return view('dashboard.pages.parametrages.frais_hospitalisations', compact('frais'));
    }

    public function storeCategory(Request $request)
    {
         $validator = Validator::make($request->all(), [
        'nom' => 'required|string|unique:category_frais__hospitalisations,nom',
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
            //'category_id' => 'required|exists:category_frais__hospitalisations,id',
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
            // 'category_id' => 'required|exists:category_frais__hospitalisations,id',
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
