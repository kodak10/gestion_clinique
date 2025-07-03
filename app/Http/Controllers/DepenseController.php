<?php

namespace App\Http\Controllers;

use App\Models\CategoryDepense;
use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepenseController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

        $depenses = Depense::with('category')
            ->orderBy('date', 'desc')
            ->get();
            
        $categories = CategoryDepense::all();
        
        return view('dashboard.pages.comptabilites.depenses.index', compact('depenses', 'categories'));
    }

    public function create()
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

        $categories = CategoryDepense::all();
        return view('dashboard.pages.comptabilites.depenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'category_depense_id' => 'nullable|exists:category_depenses,id',
            'numero_recu' => 'required|string|unique:depenses',
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'date' => 'required|date',
            'numero_cheque' => 'nullable|string|max:50',
            'description' => 'nullable|string'
        ]);

        Depense::create($validated);

        return redirect()->route('depenses.index')->with('success', 'Dépense enregistrée avec succès.');
    }

    public function edit(Depense $depense)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

        $categories = CategoryDepense::all();
        return view('dashboard.pages.comptabilites.depenses.edit', compact('depense', 'categories'));
    }

    public function update(Request $request, Depense $depense)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'category_depense_id' => 'nullable|exists:category_depenses,id',
            'numero_recu' => 'required|string|unique:depenses,numero_recu,'.$depense->id,
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'date' => 'required|date',
            'numero_cheque' => 'nullable|string|max:50',
            'description' => 'nullable|string'
        ]);

        $depense->update($validated);

        return redirect()->route('depenses.index')->with('success', 'Dépense mise à jour avec succès.');
    }

    public function destroy(Depense $depense)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

        $depense->delete();

        return redirect()->route('depenses.index')->with('success', 'Dépense supprimée avec succès.');
    }


    public function storeCategory(Request $request)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:category_depenses',
            'description' => 'nullable|string'
        ]);

        CategoryDepense::create($validated);

        return redirect()->route('depenses.index')->with('success', 'Catégorie créée avec succès.');
    }
}
