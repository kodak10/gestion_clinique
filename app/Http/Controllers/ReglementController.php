<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Hospitalisation;
use App\Models\Reglement;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ReglementController extends Controller
{
    // public function journalCaisse(Request $request)
    // {
    //     // Récupération des paramètres de filtrage
    //     $user_id = $request->input('user_id');
    //     $date_debut = $request->input('date_debut', date('Y-m-d', strtotime('-1 day')));
    //     $date_fin = $request->input('date_fin', date('Y-m-d'));
    //     $type = $request->input('type', 'all');
        
    //     // Construction de la requête
    //     $query = Reglement::with(['user', 'consultation.patient', 'hospitalisation.patient'])
    //         ->whereBetween('created_at', [$date_debut . ' 00:00:00', $date_fin . ' 23:59:59'])
    //         ->orderBy('created_at', 'desc');
        
    //     // Filtre par utilisateur
    //     if ($user_id) {
    //         $query->where('user_id', $user_id);
    //     }
        
    //     // Filtre par type
    //     if ($type != 'all') {
    //         $query->where('type', $type);
    //     }
        
    //     // Récupération des règlements
    //     $reglements = $query->get();
        
    //     // Calcul des totaux
    //     $totalEntrees = $reglements->where('type', 'entrée')->sum('montant');
    //     $totalSorties = $reglements->where('type', 'sortie')->sum('montant');
        
    //     // Liste des utilisateurs pour le filtre
    //     $users = User::whereHas('roles', function($q) {
    //         $q->whereIn('name', ['Caissière', 'Comptable']);
    //     })->get();
        
    //     return view('dashboard.pages.comptabilites.journal_caisse', compact(
    //         'reglements',
    //         'totalEntrees',
    //         'totalSorties',
    //         'users',
    //         'user_id',
    //         'date_debut',
    //         'date_fin',
    //         'type'
    //     ));
    // }
    public function journalCaisse(Request $request)
{
    // Récupération des paramètres de filtrage
    $user_id = $request->input('user_id');
    $date_debut = $request->input('date_debut', date('Y-m-d', strtotime('-1 day')));
    $date_fin = $request->input('date_fin', date('Y-m-d'));
    $type = $request->input('type', 'all');
    
    // Construction de la requête
    $query = Reglement::with(['user', 'consultation.patient', 'hospitalisation.patient'])
        ->whereBetween('created_at', [$date_debut . ' 00:00:00', $date_fin . ' 23:59:59'])
        ->orderBy('created_at', 'desc');
    
    // Filtre par utilisateur
    if (auth()->user()->hasAnyRole(['Caissière', 'Comptable'])) {
        // Si l'utilisateur est une caissière/comptable, on filtre par défaut sur ses transactions
        $query->where('user_id', auth()->id());
        
        // Sauf si un filtre utilisateur explicite est demandé
        if ($user_id && $user_id != auth()->id()) {
            // Vérifier si l'utilisateur a le droit de voir les autres caissiers
            if (auth()->user()->hasRole('Admin')) {
                $query->where('user_id', $user_id);
            }
        }
    } elseif ($user_id) {
        // Pour les autres rôles (admin, etc.), appliquer le filtre si spécifié
        $query->where('user_id', $user_id);
    }
    
    // Filtre par type
    if ($type != 'all') {
        $query->where('type', $type);
    }
    
    // Récupération des règlements
    $reglements = $query->get();
    
    // Calcul des totaux
    $totalEntrees = $reglements->where('type', 'entrée')->sum('montant');
    $totalSorties = $reglements->where('type', 'sortie')->sum('montant');
    
    // Liste des utilisateurs pour le filtre
    $users = User::whereHas('roles', function($q) {
        $q->whereIn('name', ['Caissière', 'Comptable']);
    })->get();
    
    // Si c'est une caissière/comptable, on pré-sélectionne son propre ID
    $selectedUserId = auth()->user()->hasAnyRole(['Caissière', 'Comptable']) 
        ? auth()->id() 
        : $user_id;
    
    return view('dashboard.pages.comptabilites.journal_caisse', compact(
        'reglements',
        'totalEntrees',
        'totalSorties',
        'users',
        'selectedUserId', // Utilisé pour pré-sélectionner dans le select
        'date_debut',
        'date_fin',
        'type'
    ));
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

    $validated = $request->validate([
        'type' => 'required|in:consultation,hospitalisation',
        'id' => 'required|integer',
        'montant' => 'required|numeric|min:1',
        'methode_paiement' => 'required|in:cash,mobile_money,virement'
    ]);

    $model = $validated['type'] === 'consultation'
        ? Consultation::findOrFail($validated['id'])
        : Hospitalisation::findOrFail($validated['id']);

    if ($validated['montant'] > $model->reste_a_payer) {
        return back()->with('error', 'Le montant payé ne peut pas dépasser le reste à payer');
    }

    $pdfPath = null;

    DB::transaction(function() use ($validated, $model) {
        // Création du règlement
        $reglement = Reglement::create([
            'user_id' => auth()->id(),
            'montant' => $validated['montant'],
            'methode_paiement' => $validated['methode_paiement'],
            $validated['type'].'_id' => $model->id
        ]);

        // Mise à jour du modèle
        $model->reste_a_payer -= $validated['montant'];
        $model->save();

        // Génération du PDF
        $numeroRecu = 'RC-'.str_pad($reglement->id, 6, '0', STR_PAD_LEFT);
        $pdfData = [
            'patient' => $model->patient,
            'date' => now()->format('d/m/Y H:i'),
            'numeroRecu' => $numeroRecu,
            'user' => auth()->user(),
            'reglement' => $reglement
        ];

        if ($validated['type'] === 'consultation') {
            $pdfData['consultation'] = $model;
            $pdfData['medecin'] = $model->medecin;
            $pdfData['prestations'] = $model->prestations;
        } else {
            $pdfData['hospitalisation'] = $model;
        }

        $pdf = Pdf::loadView('dashboard.documents.recu_consultation', $pdfData);
        $pdfPath = 'reglements/'.$reglement->id.'/recu-'.$numeroRecu.'.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());
        $reglement->update(['pdf_path' => $pdfPath]);
    });

    return redirect()->route('reglements.index')
        ->with([
            'success' => 'Paiement enregistré avec succès',
            'pdf_url' => Storage::url($pdfPath)
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

    public function updateReglement(Request $request, $id)
{
    if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin', 'Caissière', 'Comptable'])) {
        abort(403, 'Accès non autorisé.');
    }

    $validated = $request->validate([
        'montant' => 'required|numeric|min:1',
        'methode_paiement' => 'required|in:cash,mobile_money,virement'
    ]);

    $reglement = Reglement::findOrFail($id);
    $model = $reglement->consultation_id 
        ? Consultation::findOrFail($reglement->consultation_id)
        : Hospitalisation::findOrFail($reglement->hospitalisation_id);

    DB::transaction(function() use ($validated, $reglement, $model) {
        // Réajustement du reste à payer
        $model->reste_a_payer += $reglement->montant;
        $model->reste_a_payer -= $validated['montant'];
        $model->save();

        // Mise à jour du règlement
        $reglement->update([
            'montant' => $validated['montant'],
            'methode_paiement' => $validated['methode_paiement']
        ]);
    });

    return redirect()->route('reglements.journal')
        ->with('success', 'Règlement mis à jour avec succès');
}

public function destroy($id)
    {
        if (!Auth::user()->hasAnyRole(['Admin', 'Développeur', 'Comptable', 'Respo Caissière'])) {
        abort(403, 'Accès non autorisé.');
    }

        $reglement = Reglement::findOrFail($id);
        
      
        
        DB::transaction(function() use ($reglement) {
            // Récupérer le modèle associé (consultation ou hospitalisation)
            if ($reglement->consultation_id) {
                $model = Consultation::findOrFail($reglement->consultation_id);
            } else {
                $model = Hospitalisation::findOrFail($reglement->hospitalisation_id);
            }
            
            // Mise à jour des montants
            $model->montant_paye -= $reglement->montant;
            $model->reste_a_payer += $reglement->montant;
            $model->save();
            
            // Suppression du règlement
            $reglement->delete();
        });
        
        return redirect()->route('comptabilite.journalcaisse')
            ->with('success', 'Règlement supprimé avec succès');
    }

public function printJournal(Request $request)
{
    // Récupération des paramètres de filtrage
    $user_id = $request->input('user_id');
    $date_debut = $request->input('date_debut', date('Y-m-d', strtotime('-1 day')));
    $date_fin = $request->input('date_fin', date('Y-m-d'));
    $type = $request->input('type', 'all');
    
    // Construction de la requête
    $query = Reglement::with(['user', 'consultation.patient', 'hospitalisation.patient'])
        ->whereBetween('created_at', [$date_debut . ' 00:00:00', $date_fin . ' 23:59:59'])
        ->orderBy('created_at', 'desc');

    // Gestion du filtre utilisateur
    if (auth()->user()->hasAnyRole(['Caissière', 'Comptable'])) {
        // Par défaut, on filtre sur l'utilisateur connecté
        $query->where('user_id', auth()->id());
        
        // Sauf si un autre utilisateur est explicitement demandé (pour les admins)
        if ($user_id && $user_id != auth()->id() && auth()->user()->hasRole('Admin')) {
            $query->where('user_id', $user_id);
        }
    } elseif ($user_id) {
        // Pour les autres rôles avec filtre explicite
        $query->where('user_id', $user_id);
    }
    
    // Filtre par type
    if ($type != 'all') {
        $query->where('type', $type);
    }
    
    $reglements = $query->get();
    $totalEntrees = $reglements->where('type', 'entrée')->sum('montant');
    $totalSorties = $reglements->where('type', 'sortie')->sum('montant');

    // Récupération du nom de la caissière pour l'en-tête
    $caissiereName = $user_id 
        ? User::find($user_id)->name 
        : (auth()->user()->hasAnyRole(['Caissière', 'Comptable']) 
            ? auth()->user()->name 
            : 'Toutes les caissières');

    $pdf = Pdf::loadView('dashboard.documents.journal_caisse', [
        'reglements' => $reglements,
        'totalEntrees' => $totalEntrees,
        'totalSorties' => $totalSorties,
        'date_debut' => $date_debut,
        'date_fin' => $date_fin,
        'caissiereName' => $caissiereName,
        'typeFilter' => $type,
        'printedAt' => now()->format('d/m/Y H:i')
    ]);
    
    return $pdf->stream('journal-caisse-'.now()->format('Ymd-His').'.pdf');
}

}
