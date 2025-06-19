<?php

namespace App\Http\Controllers;

use App\Models\Assurance;
use App\Models\Ethnie;
use App\Models\Patient;
use App\Models\Profession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('assurance')->orderBy('nom', 'asc')->get();
        return view('dashboard.pages.patients.index', compact('patients'));
    }

    public function create()
    {
        $assurances = Assurance::all();
        $professions = Profession::orderBy('nom', 'asc')->get();
        $ethnies = Ethnie::orderBy('nom', 'asc')->get();

        return view('dashboard.pages.patients.create', compact('assurances', 'professions', 'ethnies'));    
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'prenoms' => 'required|string|max:255',
        'date_naissance' => 'required|date',
        'domicile' => 'required|string|max:255',
        'sexe' => 'required|in:M,F',
        'profession_id' => 'nullable|exists:professions,id',
        'ethnie_id' => 'nullable|exists:ethnies,id',
        'religion' => 'nullable|string|max:255',
        'groupe_rhesus' => 'nullable|string|max:10',
        'electrophorese' => 'nullable|string|max:255',
        'assurance_id' => 'nullable|exists:assurances,id',
        'taux_couverture' => [
            'nullable',
            'integer',
            'min:0',
            'max:100',
            function ($attribute, $value, $fail) use ($request) {
                if (!empty($request->assurance_id) && is_null($value)) {
                    $fail('Le taux de couverture est requis si une assurance est sélectionnée.');
                }
            },
        ],
        'matricule_assurance' => [
            'nullable',
            'string',
            'max:50',
            function ($attribute, $value, $fail) use ($request) {
                if (!empty($request->assurance_id) && empty($value)) {
                    $fail("Le matricule d'assurance est requis si une assurance est sélectionnée.");
                }
            },
        ],
        'contact_urgence' => 'nullable|string|max:255',
        'contact_patient' => 'required|string|max:20',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'envoye_par' => 'nullable|string|max:255',
    ]);

    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('patients', 'public');
        $validated['photo'] = $path;
    }

    Patient::create($validated);

    return redirect()->route('patients.index')->with('success', 'Patient créé avec succès');
}


    public function show(Patient $patient)
    {
        return view('dashboard.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $professions = Profession::orderBy('nom', 'asc')->get();
        $ethnies = Ethnie::orderBy('nom', 'asc')->get();
        $assurances = Assurance::all();
        return view('dashboard.pages.patients.edit', compact('patient', 'assurances', 'professions', 'ethnies'));
    }

    // public function update(Request $request, Patient $patient)
    // {
    //     $validated = $request->validate(Patient::rules($patient->id));

    //     // Gestion de la photo
    //     if ($request->hasFile('photo')) {
    //         // Supprimer l'ancienne photo si elle existe
    //         if ($patient->photo) {
    //             Storage::disk('public')->delete($patient->photo);
    //         }
    //         $path = $request->file('photo')->store('patients', 'public');
    //         $validated['photo'] = $path;
    //     }

    //     $patient->update($validated);

    //     return redirect()->route('patients.index')->with('success', 'Patient mis à jour avec succès');
    // }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'domicile' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'profession_id' => 'nullable|exists:professions,id',
            'ethnie_id' => 'nullable|exists:ethnies,id',
            'religion' => 'nullable|string|max:255',
            'groupe_rhesus' => 'nullable|string|max:10',
            'electrophorese' => 'nullable|string|max:255',
            'assurance_id' => 'nullable|exists:assurances,id',
            'taux_couverture' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
                function ($attribute, $value, $fail) use ($request) {
                    if (!empty($request->assurance_id) && is_null($value)) {
                        $fail('Le taux de couverture est requis si une assurance est sélectionnée.');
                    }
                },
            ],
            'matricule_assurance' => [
                'nullable',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($request) {
                    if (!empty($request->assurance_id) && empty($value)) {
                        $fail("Le matricule d'assurance est requis si une assurance est sélectionnée.");
                    }
                },
            ],
            'contact_urgence' => 'nullable|string|max:255',
            'contact_patient' => 'required|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'envoye_par' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($patient->photo) {
                Storage::disk('public')->delete($patient->photo);
            }
            $path = $request->file('photo')->store('patients', 'public');
            $validated['photo'] = $path;
        }

        $patient->update($validated);

        return redirect()->route('patients.index')->with('success', 'Patient mis à jour avec succès');
    }

    public function removePhoto(Patient $patient)
    {
        if ($patient->photo) {
            Storage::disk('public')->delete($patient->photo);
            $patient->update(['photo' => null]);
        }

        return back()->with('success', 'Photo supprimée avec succès');
    }


    public function storeEthnie(Request $request)
    {
        $request->validate(['nom' => 'required|string|max:255|unique:ethnies']);
        
        $ethnie = Ethnie::create($request->only('nom'));
        
        return response()->json([
            'success' => true,
            'id' => $ethnie->id,
            'nom' => $ethnie->nom
        ]);
    }

    public function storeProfession(Request $request)
    {
        $request->validate(['nom' => 'required|string|max:255|unique:professions']);
        
        $profession = Profession::create($request->only('nom'));
        
        return response()->json([
            'success' => true,
            'id' => $profession->id,
            'nom' => $profession->nom
        ]);
    }

    
}
