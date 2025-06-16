<?php

namespace App\Http\Controllers;

use App\Models\CategoryPrestation;
use App\Models\DetailsFraisPharmacie;
use App\Models\FraisHospitalisation;
use App\Models\Hospitalisation;
use App\Models\HospitalisationDetail;
use App\Models\Patient;
use Illuminate\Http\Request;

class HospitalisationController extends Controller
{
    public function storeSimple(Patient $patient)
{
    // Vérifier qu'il n'est pas déjà hospitalisé
    $existe = Hospitalisation::where('patient_id', $patient->id)
                ->whereNull('date_sortie')
                ->exists();

    if ($existe) {
        return redirect()->back()->with('error', 'Ce patient est déjà hospitalisé.');
    }

    Hospitalisation::create([
        'patient_id' => $patient->id,
        'user_id' => auth()->id(),
        'total' => "0",
        'ticket_moderateur' => "0",
        'reduction' => "0",
        'montant_a_paye' => "0",
        'date_entree' => now(),
    ]);

    return redirect()->back()->with('success', 'Le patient a été hospitalisé.');
}
    public function index()
    {
        $hospitalisations = Hospitalisation::with('patient')->get();
        return view('dashboard.pages.hospitalisations.index', compact('hospitalisations'));
    }

    
   public function createPharmacie(Hospitalisation $hospitalisation)
    {
        $category = CategoryPrestation::where('nom', 'Pharmacie')->first();
        $prestations = $category ? $category->prestations : collect();

        $patient = $hospitalisation->patient; // Relation à définir dans ton modèle Hospitalisation

        return view('dashboard.pages.hospitalisations.pharmacie', compact('hospitalisation', 'prestations', 'patient'));
    }


    // public function storePharmacie(Request $request, Patient $patient)
    // {
    //     $request->validate([
    //         'prestations' => 'required|array|min:1',
    //         'prestations.*.prestation_id' => 'required|exists:prestations,id',
    //         'prestations.*.quantite' => 'required|integer|min:1',
    //         'prestations.*.montant' => 'required|numeric|min:0',
    //         'prestations.*.total' => 'required|numeric|min:0',
    //     ]);

    //     // Calcul du total général
    //     $total = collect($request->prestations)->sum('total');

    //     // Création du frais d'hospitalisation
    //     $frais = FraisHospitalisation::create([
    //         'category_id' => $patient->id,
    //         'montant' => $total,
    //         'libelle' => "Pharmacie",
    //         'date_enregistrement' => now(),
    //     ]);

    //     // Enregistrement des détails
    //     foreach ($request->prestations as $prestation) {
    //         DetailsFraisPharmacie::create([
    //             'frais_hospitalisation_id' => $frais->id,
    //             'prestation_id' => $prestation['prestation_id'],
    //             'quantite' => $prestation['quantite'],
    //             'prix_unitaire' => $prestation['montant'],
    //             'total' => $prestation['total'],
    //         ]);
    //     }

    //     return redirect()->route('patients.show', $patient)
    //         ->with('success', 'Les médicaments ont été enregistrés avec succès.');
    // }

    public function storePharmacie(Request $request, Hospitalisation $hospitalisation)
{
       

    $request->validate([
        'prestations' => 'required|array|min:1',
        'prestations.*.prestation_id' => 'required|exists:prestations,id',
        'prestations.*.quantite' => 'required|integer|min:1',
        'prestations.*.montant' => 'required|numeric|min:0',
        'prestations.*.total' => 'required|numeric|min:0',
    ]);

    // Total général
    $total = collect($request->prestations)->sum('total');

    // Création du frais d’hospitalisation (catégorie pharmacie, ID 5)
    $frais = FraisHospitalisation::create([
        'category_id' => 5, // ID fixe pour la catégorie "Pharmacie"
        'libelle' => 'Pharmacie',
        'montant' => $total,
        'description' => 'Frais pharmacie pour hospitalisation ID ' . $hospitalisation->id,
    ]);


    // Insertion dans hospitalisation_details
    foreach ($request->prestations as $prestation) {
    HospitalisationDetail::create([
        'hospitalisation_id' => $hospitalisation->id,
        'frais_hospitalisation_id' => $frais->id,
        //'prestation_id' => $prestation['prestation_id'], // ← Ajouté
        'quantite' => $prestation['quantite'],
        'prix_unitaire' => $prestation['montant'],
        'reduction' => 0, // ← Obligatoire car présent dans la table
        'total' => $prestation['total'],
    ]);


    }

    return redirect()->route('hospitalisations.show', $hospitalisation)
        ->with('success', 'Les frais de pharmacie ont été enregistrés avec succès.');
}

}
