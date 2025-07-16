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

        $reglements = Reglement::with(['consultation', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'reglements_page');

        $consultations = Consultation::with(['user', 'patient'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'consultations_page');

        $hospitalisations = Hospitalisation::with(['user', 'patient'])
            ->orderBy('date_entree', 'desc')
            ->paginate(5, ['*'], 'hospitalisations_page');

        $depenses = Depense::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'depenses_page');


        return view('dashboard.pages.historiques.index', compact(
            'reglements',
            'consultations',
            'hospitalisations',
            'depenses'
        ));
    }
}
