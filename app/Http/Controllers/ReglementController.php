<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Hospitalisation;
use App\Models\Reglement;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ReglementController extends Controller
{
   public function journalCaisse()
{
    $reglements = Reglement::with(['consultation.patient', 'consultation.details.prestation', 'user'])
        ->orderBy('created_at', 'desc')
        ->get();

    $totalEntrees = $reglements->where('type', 'entrée')->sum('montant');
    $totalSorties = $reglements->where('type', 'sortie')->sum('montant');
    $users = User::all(); // Pour la liste des caissiers

    return view('dashboard.pages.comptabilites.journal_caisse', compact('reglements', 'totalEntrees', 'totalSorties', 'users'));
}
    public function index()
    {
        // Récupérer les consultations non soldées
        $consultations = Consultation::with(['patient', 'details.prestation'])
            ->where('reste_a_payer', '>', 0)
            ->get();

        // Récupérer les hospitalisations non soldées
        $hospitalisations = Hospitalisation::with(['patient', 'frais'])
            ->where('reste_a_payer', '>', 0)
            ->get();

        return view('dashboard.pages.comptabilites.reglement', compact('consultations', 'hospitalisations'));
    }

    public function showDetails($type, $id)
    {
        if ($type === 'consultation') {
            $item = Consultation::with(['patient', 'details.prestation', 'reglements.user'])->findOrFail($id);
        } else {
            $item = Hospitalisation::with(['patient', 'frais', 'reglements.user'])->findOrFail($id);
        }

        return view('dashboard.pages.comptabilites.modals.details', compact('item', 'type'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'type' => 'required|in:consultation,hospitalisation',
    //         'id' => 'required|integer',
    //         'montant' => 'required|numeric|min:1',
    //         'methode_paiement' => 'required|in:cash,mobile_money,virement'
    //     ]);

    //     // Trouver le modèle correspondant
    //     if ($request->type === 'consultation') {
    //         $model = Consultation::with(['patient', 'medecin', 'prestations'])->findOrFail($request->id);
    //     } else {
    //         $model = Hospitalisation::with(['patient', 'medecin', 'prestations'])->findOrFail($request->id);
    //     }

    //     // Vérification du montant
    //     if ($request->montant > $model->reste_a_payer) {
    //         return back()->with('error', 'Le montant payé ne peut pas dépasser le reste à payer');
    //     }

    //     // Création du règlement
    //     $reglement = Reglement::create([
    //         'user_id' => auth()->id(),
    //         'montant' => $request->montant,
    //         'methode_paiement' => $request->methode_paiement,
    //         $request->type . '_id' => $model->id
    //     ]);

    //     // Mise à jour du modèle
    //     $model->increment('montant_paye', $request->montant);
    //     $model->decrement('reste_a_payer', $request->montant);
    //     $model->save();

    //     // Génération du PDF
    //     $pdfData = [
    //         'numero' => 'RECU-' . strtoupper(substr($request->type, 0, 3)) . '-' . $reglement->id,
    //         'date' => now()->format('d/m/Y H:i'),
    //         'patient' => $model->patient,
    //         'type' => $request->type === 'consultation' ? 'Consultation' : 'Hospitalisation',
    //         'montant' => $request->montant,
    //         'methode_paiement' => $this->getPaymentMethodLabel($request->methode_paiement),
    //         'caissier' => auth()->user()->name,
    //         'details' => $model->prestations->map(function($prestation) {
    //             return [
    //                 'libelle' => $prestation->libelle,
    //                 'quantite' => $prestation->pivot->quantite,
    //                 'prix' => $prestation->pivot->montant,
    //                 'total' => $prestation->pivot->total
    //             ];
    //         })
    //     ];

    //     $pdf = PDF::loadView('dashboard.documents.recu_consultation', $pdfData);

    //     // Sauvegarde du PDF
    //     $pdfPath = 'reglements/recu-' . $reglement->id . '-' . now()->format('YmdHis') . '.pdf';
    //     Storage::disk('public')->put($pdfPath, $pdf->output());

    //     // Mise à jour du règlement avec le chemin du PDF
    //     $reglement->update(['pdf_path' => $pdfPath]);

    //     return redirect()->route('reglements.index')
    //         ->with([
    //             'success' => 'Paiement enregistré avec succès',
    //             'pdf_url' => Storage::url($pdfPath)
    //         ]);
    // }

    // private function getPaymentMethodLabel($method)
    // {
    //     return [
    //         'cash' => 'Espèces',
    //         'mobile_money' => 'Mobile Money',
    //         'virement' => 'Virement Bancaire'
    //     ][$method] ?? $method;
    // }
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:consultation,hospitalisation',
            'id' => 'required|integer',
            'montant' => 'required|numeric|min:1',
            'methode_paiement' => 'required|in:cash,mobile_money,virement'
        ]);

        if ($request->type === 'consultation') {
            $model = Consultation::findOrFail($request->id);
        } else {
            $model = Hospitalisation::findOrFail($request->id);
        }
        

        if ($request->montant > $model->reste_a_payer) {
            return back()->with('error', 'Le montant payé ne peut pas dépasser le reste à payer');
        }

        $reglement = new Reglement();
        $reglement->user_id = auth()->id();
        $reglement->montant = $request->montant;
        $reglement->methode_paiement = $request->methode_paiement;

        if ($request->type === 'consultation') {
            $reglement->consultation_id = $model->id;
        } else {
            $reglement->hospitalisation_id = $model->id;
        }

        $reglement->save();

        $model->reste_a_payer -= $request->montant;
        $model->save();

        return redirect()->route('reglements.index')
            ->with('success', 'Paiement enregistré avec succès');
    }
}
