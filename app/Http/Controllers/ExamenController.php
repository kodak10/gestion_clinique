<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExamenController extends Controller
{
    public function index()
    {
        // Logique pour afficher la liste des examens
        return view('dashboard.pages.hospitalisations.laboratoire');
    }

    public function store(Request $request)
{
    // Validation des données
    $validatedData = $request->validate([
        'code' => 'required|string|max:255|unique:examens',
        'nom' => 'required|string|max:255',
        'prix' => 'required|numeric|min:0',
        
    ]);

    // Création de l'examen
    $examen = \App\Models\Examen::create([
        'code' => $validatedData['code'],
        'nom' => $validatedData['nom'],
        'prix' => $validatedData['prix'],
        
    ]);

    // Redirection avec message de succès
    return redirect()->back()->with('success', 'Examen créé avec succès!');
}
}
