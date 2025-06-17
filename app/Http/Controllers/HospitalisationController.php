<?php

namespace App\Http\Controllers;

use App\Models\CategoryPrestation;
use App\Models\DetailsFraisPharmacie;
use App\Models\FraisHospitalisation;
use App\Models\Hospitalisation;
use App\Models\HospitalisationDetail;
use App\Models\Patient;
use App\Models\Prestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HospitalisationController extends Controller
{
    public function storeSimple(Patient $patient)
    {
        // Vérifier qu'il n'est pas déjà hospitalisé
        $existe = Hospitalisation::where('patient_id', $patient->id)->whereNull('date_sortie')->exists();

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
    $patient = $hospitalisation->patient;

    // 1. Récupérer le frais de pharmacie existant pour cette hospitalisation
    $fraisPharmacie = FraisHospitalisation::where([
        'hospitalisation_id' => $hospitalisation->id,
        'category_id' => 5
    ])->first();

    // 2. Récupérer les détails existants (médicaments déjà enregistrés)
    $medicamentsExistants = $fraisPharmacie 
        ? $fraisPharmacie->details()
            ->select('id', 'libelle', 'prix_unitaire', 'quantite', 'total')
            ->get()
            ->map(function($detail) {
                return [
                    'id' => $detail->id,
                    'libelle' => $detail->libelle,
                    'quantite' => $detail->quantite,
                    'montant' => $detail->prix_unitaire,
                    'total' => $detail->total
                ];
            })->toArray()
        : [];

    return view('dashboard.pages.hospitalisations.pharmacie', compact(
        'hospitalisation',
        'patient',
        'medicamentsExistants'
    ));
}

public function storePharmacie(Request $request, Hospitalisation $hospitalisation)
{
    $validated = $request->validate([
        'medicaments' => 'required|array|min:1',
        'medicaments.*.libelle' => 'required|string|max:255',
        'medicaments.*.quantite' => 'required|integer|min:1',
        'medicaments.*.montant' => 'required|numeric|min:0',
        'medicaments.*.total' => 'required|numeric|min:0',
    ]);

    DB::transaction(function () use ($validated, $hospitalisation) {
        // 1. Créer ou récupérer le frais de pharmacie
        $fraisPharmacie = HospitalisationDetail::firstOrCreate([
            'hospitalisation_id' => $hospitalisation->id,
            'category_id' => 5
        ], [
            'libelle' => 'Frais Pharmacie',
            'montant' => 0,
            'description' => 'Détails pharmacie pour hospitalisation #' . $hospitalisation->id
        ]);

        // 2. Enregistrer chaque médicament directement dans hospitalisation_details
        foreach ($validated['medicaments'] as $medicament) {
            HospitalisationDetail::create([
                'hospitalisation_id' => $hospitalisation->id,
                'frais_hospitalisation_id' => $fraisPharmacie->id,
                'libelle' => $medicament['libelle'],
                'quantite' => $medicament['quantite'],
                'prix_unitaire' => $medicament['montant'],
                'total' => $medicament['total'],
                'reduction' => 0
            ]);
        }

        // 3. Mettre à jour le total du frais
        $fraisPharmacie->update([
            'montant' => $fraisPharmacie->details()->sum('total')
        ]);

        // 4. Mettre à jour le total de l'hospitalisation
        $hospitalisation->update([
            'total' => $hospitalisation->fraisHospitalisations()->sum('montant')
        ]);
    });

    return redirect()->route('hospitalisations.show', $hospitalisation)
        ->with('success', 'Médicaments enregistrés avec succès');
}
  
    // public function storePharmacie(Request $request, Hospitalisation $hospitalisation)
    // {

    //     $request->validate([
    //         'prestations' => 'required|array|min:1',
    //         'prestations.*.prestation_id' => 'required|exists:prestations,id',
    //         'prestations.*.quantite' => 'required|integer|min:1',
    //         'prestations.*.montant' => 'required|numeric|min:0',
    //         'prestations.*.total' => 'required|numeric|min:0',
    //     ]);

    //     // Total général
    //     $total = collect($request->prestations)->sum('total');

    //     // Création du frais d’hospitalisation (catégorie pharmacie, ID 5)
    //     $frais = FraisHospitalisation::create([
    //         'category_id' => 5, // ID fixe pour la catégorie "Pharmacie"
    //         'libelle' => 'Pharmacie',
    //         'montant' => $total,
    //         'description' => 'Frais pharmacie pour hospitalisation ID ' . $hospitalisation->id,
    //     ]);


    //     // Insertion dans hospitalisation_details
    //     foreach ($request->prestations as $prestation) {
    //     HospitalisationDetail::create([
    //         'hospitalisation_id' => $hospitalisation->id,
    //         'frais_hospitalisation_id' => $frais->id,
    //         //'prestation_id' => $prestation['prestation_id'], // ← Ajouté
    //         'quantite' => $prestation['quantite'],
    //         'prix_unitaire' => $prestation['montant'],
    //         'reduction' => 0, // ← Obligatoire car présent dans la table
    //         'total' => $prestation['total'],
    //     ]);

    //     }

    //     return redirect()->route('hospitalisations.index', $hospitalisation)
    //         ->with('success', 'Les frais de pharmacie ont été enregistrés avec succès.');
    // }

}
