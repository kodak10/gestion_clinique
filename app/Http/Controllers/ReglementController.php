<?php

namespace App\Http\Controllers;

use App\Models\Reglement;
use App\Models\User;
use Illuminate\Http\Request;

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
}
