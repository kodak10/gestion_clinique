<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Depense;
use App\Models\Hospitalisation;
use App\Models\Reglement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoriqueController extends Controller
{
   public function index()
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin'])) {
            abort(403, 'Accès non autorisé.');
        }

        // Récupérer les données de chaque section
        $reglements = Reglement::with(['consultation'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(1);

        $consultations = Consultation::with(['user', 'patient'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        $hospitalisations = Hospitalisation::with(['user', 'patient'])
                        ->orderBy('date_entree', 'desc')
                        ->paginate(10);

        $depenses = Depense::with('user')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return view('dashboard.pages.historiques.index', compact(
            'reglements',
            'consultations',
            'hospitalisations',
            'depenses'
        ));
    }
}
