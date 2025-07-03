<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Hospitalisation;
use App\Models\Reglement;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class ReglementController extends Controller
{
    public function journalCaisse()
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Caissière', 'Respo Caissière', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

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
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Caissière', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

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
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Caissière', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

        if ($type === 'consultation') {
            $item = Consultation::with(['patient', 'details.prestation', 'reglements.user'])->findOrFail($id);
        } else {
            $item = Hospitalisation::with(['patient', 'frais', 'reglements.user'])->findOrFail($id);
        }

        return view('dashboard.pages.comptabilites.modals.details', compact('item', 'type'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Caissière', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

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
