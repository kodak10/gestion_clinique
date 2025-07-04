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
        $users = User::all();

        return view('dashboard.pages.comptabilites.journal_caisse', compact('reglements', 'totalEntrees', 'totalSorties', 'users'));
    }

    public function index()
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Caissière', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

        $consultations = Consultation::with(['patient', 'details.prestation'])
            ->where('reste_a_payer', '>', 0)
            ->get();

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

        // Génération du PDF
        $numeroRecu = 'RC-' . str_pad($reglement->id, 6, '0', STR_PAD_LEFT);
        $patient = $model->patient;

        $medecin = $request->type === 'consultation' ? $model->medecin : null;

        $prestations = $request->type === 'consultation' ? $model->details : [];

        
        $data = [
            'consultation' => $request->type === 'consultation' ? $model : null,
            'hospitalisation' => $request->type === 'hospitalisation' ? $model : null,
            'patient' => $patient,
                'prestations' => $prestations,

            'date' => now()->format('d/m/Y H:i'),
            'numeroRecu' => $numeroRecu,
            'user' => auth()->user(),
            'medecin' => $medecin,
            'reglement' => $reglement,
        ];

        $pdf = Pdf::loadView('dashboard.documents.recu_consultation', $data);
        $directory = 'reglements/'.$reglement->id;
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }
        $filename = 'recu-paiement-'.$numeroRecu.'.pdf';
        $path = $directory.'/'.$filename;
        Storage::disk('public')->put($path, $pdf->output());
        
        $reglement->update([
            'pdf_path' => $path
        ]);

        return redirect()->route('reglements.index')
            ->with([
                'success' => 'Paiement enregistré avec succès',
                'pdf_url' => Storage::url($path)
            ]);
    }

    public function edit($id)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Caissière', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

        $reglement = Reglement::with(['consultation.patient', 'hospitalisation.patient'])->findOrFail($id);
        
        return view('dashboard.pages.comptabilites.edit_reglement', compact('reglement'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Caissière', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'montant' => 'required|numeric|min:1',
            'methode_paiement' => 'required|in:cash,mobile_money,virement'
        ]);

        $reglement = Reglement::findOrFail($id);
        $oldAmount = $reglement->montant;
        
        // Récupérer le modèle associé (consultation ou hospitalisation)
        if ($reglement->consultation_id) {
            $model = Consultation::findOrFail($reglement->consultation_id);
        } else {
            $model = Hospitalisation::findOrFail($reglement->hospitalisation_id);
        }

        // Vérifier que le nouveau montant ne dépasse pas le reste à payer + ancien montant
        $maxAllowed = $model->reste_a_payer + $oldAmount;
        if ($request->montant > $maxAllowed) {
            return back()->with('error', 'Le montant ne peut pas dépasser ' . $maxAllowed);
        }

        // Mettre à jour le reste à payer
        $model->reste_a_payer += $oldAmount; // On remet l'ancien montant
        $model->reste_a_payer -= $request->montant; // On soustrait le nouveau montant
        $model->save();

        // Mettre à jour le règlement
        $reglement->update([
            'montant' => $request->montant,
            'methode_paiement' => $request->methode_paiement,
        ]);

        return redirect()->route('reglements.journal')
            ->with('success', 'Règlement mis à jour avec succès');
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Caissière', 'Comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

        $reglement = Reglement::findOrFail($id);
        
        // Récupérer le modèle associé
        if ($reglement->consultation_id) {
            $model = Consultation::findOrFail($reglement->consultation_id);
        } else {
            $model = Hospitalisation::findOrFail($reglement->hospitalisation_id);
        }

        // Remettre le montant dans le reste à payer
        $model->reste_a_payer += $reglement->montant;
        $model->save();

        // Supprimer le PDF associé s'il existe
        if ($reglement->pdf_path && Storage::exists($reglement->pdf_path)) {
            Storage::delete($reglement->pdf_path);
        }

        $reglement->delete();

        return redirect()->route('reglements.journal')
            ->with('success', 'Règlement supprimé avec succès');
    }

}
