<?php

namespace App\Http\Controllers;

use App\Models\CategoryPrestation;
use App\Models\Prestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrestationController extends Controller
{
    public function index()
    {
        $prestations = Prestation::with('categorie')->orderBy('libelle', 'asc')->get();
        $categories = CategoryPrestation::orderBy('nom', 'asc')->get();
        return view('dashboard.pages.parametrages.prestations', compact('prestations', 'categories'));
    }

    public function store(Request $request)
    {
        

        $validated = $request->validate([
            'categorie_id' => 'required|exists:category_prestations,id',
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
        ]);

        Prestation::create($validated);

        return redirect()->route('prestations.index')->with('success', 'Prestation créée avec succès');
    }

    public function update(Request $request, Prestation $prestation)
    {
       

        $validated = $request->validate([
            'categorie_id' => 'required|exists:category_prestations,id',
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
        ]);

        $prestation->update($validated);

        return redirect()->route('prestations.index')->with('success', 'Prestation mise à jour');
    }

    public function destroy(Prestation $prestation)
    {
        $prestation->delete();
        return redirect()->route('prestations.index')->with('success', 'Prestation supprimée');
    }
}
