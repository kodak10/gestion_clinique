<?php

namespace App\Http\Controllers;

use App\Models\CategorieMedicament;
use App\Models\Fournisseur;
use App\Models\HospitalisationMedicament;
use App\Models\Medicament;
use App\Models\StockMouvement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
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

        $medicaments = Medicament::orderBy('nom')->get();

        return view('dashboard.pages.pharmacie.index', compact('medicaments'));
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
            //'stock' => 'required|integer|min:0',
            'stock_alerte' => 'required|integer|min:0',
            'date_peremption' => 'nullable|date',
            
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
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Comptable'])) {
                abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'operation_type' => 'required|in:entree,sortie',
            'quantite' => 'required|integer|min:1',
            'motif' => 'nullable|string|max:255'
        ]);

        try {
            DB::transaction(function () use ($request, $medicament) {
                $stockAvant = $medicament->stock;
                $quantite = $request->quantite;
                $type = $request->operation_type;
                $typeLibelle = $type === 'entree' ? 'entrée' : 'sortie';

                // Vérification du stock pour les sorties
                if ($type === 'sortie' && $quantite > $medicament->stock) {
                    throw new \Exception('Stock insuffisant');
                }

                // Mise à jour du stock
                $medicament->{$type === 'entree' ? 'increment' : 'decrement'}('stock', $quantite);

                // Enregistrement du mouvement
                // StockMouvement::create([
                //     'medicament_id' => $medicament->id,
                //     'user_id' => auth()->id(),
                //     'type' => $type,
                //     'quantite' => $quantite,
                //     'stock_avant' => $stockAvant,
                //     'stock_apres' => $medicament->fresh()->stock,
                // ]);
            });

            return redirect()->route('medicaments.index')
                ->with('success', 'Stock mis à jour avec succès');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    public function destroy(Medicament $medicament)
    {
        try {
            DB::transaction(function () use ($medicament) {
                // Vérification plus complète des utilisations
                $isUsed = HospitalisationMedicament::where('medicament_id', $medicament->id)
                    ->orWhereHas('stockMouvements', function($q) use ($medicament) {
                        $q->where('medicament_id', $medicament->id);
                    })
                    ->exists();

                if ($isUsed) {
                    throw new \Exception('Ce médicament ne peut être supprimé car il a des mouvements associés');
                }

                // Archivage avant suppression
                StockMouvement::create([
                    'medicament_id' => $medicament->id,
                    'user_id' => auth()->id(),
                    'type' => 'suppression',
                    'quantite' => $medicament->stock,
                    'stock_avant' => $medicament->stock,
                    'stock_apres' => 0,
                ]);

                $medicament->delete();
            });

            return redirect()->route('medicaments.index')
                ->with('success', 'Médicament supprimé avec succès');

        } catch (\Exception $e) {
            return redirect()->route('medicaments.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Affiche l'historique des mouvements de stock
     */

    public function historiqueGlobalPDF()
    {
        // Récupérer tous les médicaments avec leur historique
        $medicaments = Medicament::with(['hospitalisations.patient', 'hospitalisations.medecin'])
            ->withCount(['hospitalisations as total_prescriptions' => function($query) {
                $query->select(DB::raw('COALESCE(SUM(quantite), 0)'));
            }])
            ->orderBy('nom')
            ->get();

        // Données pour le PDF
        $data = [
            'medicaments' => $medicaments,
            'date' => now()->format('d/m/Y H:i'),
            'user' => auth()->user()
        ];

        // Générer le PDF
        $pdf = PDF::loadView('dashboard.documents.rapport_pharmacie', $data)
                ->setPaper('a4', 'landscape');

        return $pdf->stream('historique-global-medicaments-'.now()->format('Y-m-d').'.pdf');
    }
}