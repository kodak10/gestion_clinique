<?php

namespace App\Http\Controllers;

use App\Models\Medicament;
use App\Models\CategorieMedicament;
use App\Models\Fournisseur;
use App\Models\HospitalisationMedicament;
use App\Models\StockMouvement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MedicamentController extends Controller
{
    /**
     * Affiche la liste des médicaments
     */
    public function index()
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable','Pharmacien'])) {
            abort(403, 'Accès non autorisé.');
        }

        $medicaments = Medicament::with(['categorie'])
            ->orderBy('nom')
            ->get();

        $categories = CategorieMedicament::orderBy('nom')->get();
        // $fournisseurs = Fournisseur::orderBy('nom')->get();

        return view('dashboard.pages.pharmacie.index', compact('medicaments', 'categories'));
    }

    /**
     * Enregistre un nouveau médicament
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:medicaments|max:50',
            'nom' => 'required|max:100',
            'unite_mesure' => 'required|max:20',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_alerte' => 'required|integer|min:0',
            'date_peremption' => 'nullable|date',
            'categorie_id' => 'required|exists:categorie_medicaments,id',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function () use ($request) {
            $medicament = Medicament::create($request->all());

            // Enregistrer le mouvement de stock initial
            if ($request->stock > 0) {
                StockMouvement::create([
                    'medicament_id' => $medicament->id,
                    'user_id' => auth()->id(),
                    'type' => 'initial',
                    'quantite' => $request->stock,
                    'stock_avant' => 0,
                    'stock_apres' => $request->stock,
                    'motif' => 'Stock initial'
                ]);
            }
        });

        return redirect()->route('medicaments.index')
            ->with('success', 'Médicament créé avec succès');
    }

    /**
     * Met à jour un médicament existant
     */
    public function update(Request $request, Medicament $medicament)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:50|unique:medicaments,code,'.$medicament->id,
            'nom' => 'required|max:100',
            'unite_mesure' => 'required|max:20',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_alerte' => 'required|integer|min:0',
            'date_peremption' => 'nullable|date',
            'categorie_id' => 'required|exists:categorie_medicaments,id',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $medicament->update($request->all());

        return redirect()->route('medicaments.index')
            ->with('success', 'Médicament mis à jour avec succès');
    }

    /**
     * Gère les mouvements de stock
     */
    public function updateStock(Request $request, Medicament $medicament)
    {
        $request->validate([
            'operation_type' => 'required|in:entree,sortie,ajustement',
            'quantite' => 'required|integer|min:1',
            'motif' => 'nullable|string|max:255'
        ]);

        DB::transaction(function () use ($request, $medicament) {
            $stockAvant = $medicament->stock;
            $quantite = $request->quantite;

            switch ($request->operation_type) {
                case 'entree':
                    $medicament->increment('stock', $quantite);
                    $type = 'entrée';
                    break;
                
                case 'sortie':
                    if ($quantite > $medicament->stock) {
                        return back()->with('error', 'Stock insuffisant');
                    }
                    $medicament->decrement('stock', $quantite);
                    $type = 'sortie';
                    break;
                
                case 'ajustement':
                    $medicament->stock = $quantite;
                    $medicament->save();
                    $type = 'ajustement';
                    break;
            }

            // Enregistrer le mouvement de stock
            StockMouvement::create([
                'medicament_id' => $medicament->id,
                'user_id' => auth()->id(),
                'type' => $type,
                'quantite' => $quantite,
                'stock_avant' => $stockAvant,
                'stock_apres' => $medicament->stock,
                'motif' => $request->motif ?? 'Opération de stock'
            ]);
        });

        return redirect()->route('medicaments.index')
            ->with('success', 'Stock mis à jour avec succès');
    }

    /**
     * Supprime un médicament
     */
    public function destroy(Medicament $medicament)
    {
        // Vérifier si le médicament est utilisé dans des hospitalisations
        $usedInHospitalisations = HospitalisationMedicament::where('medicament_id', $medicament->id)->exists();

        if ($usedInHospitalisations) {
            return redirect()->route('medicaments.index')
                ->with('error', 'Ce médicament ne peut pas être supprimé car il est utilisé dans des hospitalisations');
        }

        $medicament->delete();

        return redirect()->route('medicaments.index')
            ->with('success', 'Médicament supprimé avec succès');
    }

    /**
     * Affiche l'historique des mouvements de stock
     */
    public function stockHistory(Medicament $medicament)
    {
        $mouvements = StockMouvement::where('medicament_id', $medicament->id)
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        return view('medicaments.history', compact('medicament', 'mouvements'));
    }
}