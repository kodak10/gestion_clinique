<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Depense;
use App\Models\Hospitalisation;
use App\Models\Reglement;
use Illuminate\Http\Request;

class HistoriqueController extends Controller
{
   public function index()
    {
        // Récupérer les données de chaque section
        $reglements = Reglement::with(['consultation'])
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();

        $consultations = Consultation::with(['user', 'patient'])
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();

        $hospitalisations = Hospitalisation::with(['user', 'patient'])
                        ->orderBy('date_entree', 'desc')
                        ->limit(5)
                        ->get();

        $depenses = Depense::with('user')
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();

        return view('dashboard.pages.historiques.index', compact(
            'reglements',
            'consultations',
            'hospitalisations',
            'depenses'
        ));
    }
}
