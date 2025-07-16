<?php

namespace App\Http\Controllers;

use App\Models\CategoryDepense;
use App\Models\Depense;
use App\Models\Reglement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DepenseController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable', 'Respo Caissière','Caissière'])) {
            abort(403, 'Accès non autorisé.');
        }

        $depenses = Depense::with('category', 'user')
            ->orderBy('date', 'desc')
            ->get();
            
        $categories = CategoryDepense::all();
        
        return view('dashboard.pages.comptabilites.depenses.index', compact('depenses', 'categories'));
    }

    public function create()
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable','Caissière'])) {
            abort(403, 'Accès non autorisé.');
        }

        $categories = CategoryDepense::all();
        return view('dashboard.pages.comptabilites.depenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable','Caissière'])) {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'category_depense_id' => 'nullable|exists:category_depenses,id',
            'numero_recu' => 'required|string|unique:depenses',
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'date' => 'required|date',
            'numero_cheque' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'libelle' => 'required|string|max:255',
        ]);

        DB::transaction(function() use ($validated) {
            $validated['user_id'] = auth()->id();
            $depense = Depense::create($validated);

            // Création du règlement de sortie
            Reglement::create([
                'depense_id' => $depense->id,
                'user_id' => auth()->id(),
                'montant' => $validated['montant'],
                'type' => 'sortie',
                'date_reglement' => $validated['date'],
                'numero_recu' => $validated['numero_recu']
            ]);
        });

        return redirect()->route('depenses.index')->with('success', 'Dépense enregistrée avec succès.');
    }

    public function edit(Depense $depense)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable','Caissière'])) {
            abort(403, 'Accès non autorisé.');
        }

        $categories = CategoryDepense::all();
        return view('dashboard.pages.comptabilites.depenses.edit', compact('depense', 'categories'));
    }

   
    public function update(Request $request, Depense $depense)
{
    // dd($request->all());
    if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable','Caissière'])) {
        abort(403, 'Accès non autorisé.');
    }

    $validated = $request->validate([
        'category_depense_id' => 'nullable|exists:category_depenses,id',
        'numero_recu' => 'required|string|unique:depenses,numero_recu,' . $depense->id,
        'libelle' => 'required|string|max:255',
        'montant' => 'required|numeric|min:0',
        'date' => 'required|date',
        'numero_cheque' => 'nullable|string|max:50',
        'description' => 'nullable|string',
    ]);

    DB::transaction(function () use ($depense, $validated) {
        // Mise à jour de la dépense
        $depense->update($validated);

        // Mise à jour ou création du règlement lié
        $depense->reglement()->update([
            'montant' => $validated['montant'],
            'updated_at' => $validated['date'],
            //'user_id' => auth()->id(),
            'type' => 'sortie'
        ]);

    });

    return redirect()->route('depenses.index')->with('success', 'Dépense mise à jour avec succès.');
}


    // public function destroy(Depense $depense)
    // {
    //     if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable'])) {
    //         abort(403, 'Accès non autorisé.');
    //     }

    //     $depense->delete();

    //     return redirect()->route('depenses.index')->with('success', 'Dépense supprimée avec succès.');
    // }
    public function destroy(Depense $depense)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

        DB::transaction(function() use ($depense) {
            // Supprimer d'abord le règlement associé
            $depense->reglement()->delete();
            // Puis supprimer la dépense
            $depense->delete();
        });

        return redirect()->route('depenses.index')->with('success', 'Dépense supprimée avec succès.');
    }


    public function storeCategory(Request $request)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable','Caissière'])) {
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
