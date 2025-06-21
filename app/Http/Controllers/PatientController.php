<?php

namespace App\Http\Controllers;

use App\Models\Assurance;
use App\Models\Ethnie;
use App\Models\Patient;
use App\Models\Profession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // Générer le numéro de dossier provisoire pour l'affichage
        $provisionalNum = $this->generateProvisionalDossierNumber();
        
        return view('dashboard.pages.patients.create', compact('assurances', 'professions', 'ethnies', 'provisionalNum'));

        // return view('dashboard.pages.patients.create', compact('assurances', 'professions', 'ethnies'));    
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

    $validated['num_dossier'] = $this->generateFinalDossierNumber();

    //Patient::create($validated);
    $patient = Patient::create($validated);

    if ($request->hasFile('photo')) {
        // Récupérer l'extension
        $extension = $request->file('photo')->getClientOriginalExtension();
        
        // Créer le nom de fichier
        $filename = $patient->num_dossier . '.' . $extension;
        
        // Enregistrer la photo
        $path = $request->file('photo')->storeAs(
            'patients', 
            $filename, 
            'public'
        );
        
        // Mettre à jour le patient avec le chemin de la photo
        $patient->update(['photo' => $path]);
    }

    


    // Générer le PDF
    $pdf = PDF::loadView('dashboard.documents.dossier', [
        'patient' => $patient,
        'date_creation' => now()->format('d/m/Y')
    ]);

    // Sauvegarder le PDF
    $pdfPath = 'patients/fiches/' . $patient->num_dossier . '.pdf';
    Storage::disk('public')->put($pdfPath, $pdf->output());

    // Retourner la réponse avec le lien vers le PDF
    return redirect()
        ->route('patients.index')
        ->with([
            'success' => 'Patient créé avec succès',
            'pdf_url' => Storage::url($pdfPath)
        ]);

    // return redirect()->route('patients.index')->with('success', 'Patient créé avec succès');
}

private function generateProvisionalDossierNumber()
{
    $lastPatient = Patient::whereMonth('created_at', now()->month)
                         ->whereYear('created_at', now()->year)
                         ->orderBy('id', 'desc')
                         ->first();

    $sequence = $lastPatient ? (int)explode('/', $lastPatient->num_dossier)[0] + 1 : 1;
    
    return sprintf("%03d/%02d/%02d", 
        $sequence,
        now()->format('m'),
        now()->format('y'));
}

private function generateFinalDossierNumber()
{
    $lastPatient = Patient::whereMonth('created_at', now()->month)
                         ->whereYear('created_at', now()->year)
                         ->orderBy('id', 'desc')
                         ->first();

    $sequence = $lastPatient ? (int)explode('/', $lastPatient->num_dossier)[0] + 1 : 1;
    
    return sprintf("%03d/%02d/%02d", 
        $sequence,
        now()->format('m'),
        now()->format('y'));
}



    public function edit(Patient $patient)
    {
        $professions = Profession::orderBy('nom', 'asc')->get();
        $ethnies = Ethnie::orderBy('nom', 'asc')->get();
        $assurances = Assurance::all();
        return view('dashboard.pages.patients.edit', compact('patient', 'assurances', 'professions', 'ethnies'));
    }

   

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
        // Supprimer l'ancienne photo
        if ($patient->photo) {
            Storage::disk('public')->delete($patient->photo);
        }
        
        // Nouveau nom de fichier
        $extension = $request->file('photo')->getClientOriginalExtension();
        $filename = $patient->num_dossier . '.' . $extension;
        
        // Enregistrer la nouvelle photo
        $path = $request->file('photo')->storeAs(
            'patients', 
            $filename, 
            'public'
        );
        
        $validated['photo'] = $path;
    }

    $patient->update($validated);

    // Générer le nouveau PDF
    $pdf = PDF::loadView('dashboard.documents.dossier', [
        'patient' => $patient,
        'date_creation' => now()->format('d/m/Y')
    ]);

    // Regénérer le PDF après mise à jour
    $pdf = PDF::loadView('dashboard.documents.dossier', [
        'patient' => $patient,
        'date_creation' => now()->format('d/m/Y')
    ])->setPaper('A4', 'portrait');

    $pdfPath = 'patients/fiches/' . $patient->num_dossier . '.pdf';
    Storage::disk('public')->put($pdfPath, $pdf->output());
    $patient->update(['pdf_path' => $pdfPath]);

    return redirect()
        ->route('patients.index')
        ->with([
            'success' => 'Patient mis à jour avec succès',
            'pdf_url' => Storage::url($pdfPath)
        ]);
    }

    public function generatePatientPdf(Patient $patient)
{
    $pdf = PDF::loadView('dashboard.documents.dossier', [
        'patient' => $patient,
        'date_creation' => now()->format('d/m/Y')
    ]);

    // Configuration sans aucune marge
    $pdf->setPaper('A4', 'portrait')
        ->setOptions([
            'margin-top' => 0,
            'margin-right' => 0,
            'margin-bottom' => 0,
            'margin-left' => 0,
            'padding' => 0,
            'dpi' => 300,
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif'
        ]);

    // Pour sauvegarder :
    $pdfPath = 'patients/fiches/'.$patient->num_dossier.'.pdf';
    Storage::disk('public')->put($pdfPath, $pdf->output());
    
    // Pour afficher :
    return $pdf->stream('dossier_'.$patient->num_dossier.'.pdf');
}

public function viewPdf(Patient $patient)
{
    // Vérifier si le PDF existe
    if (!$patient->pdf_path || !Storage::disk('public')->exists($patient->pdf_path)) {
        abort(404, 'Le fichier PDF est introuvable');
    }

    // Récupérer le contenu du PDF
    $file = Storage::disk('public')->get($patient->pdf_path);
    
    return response($file, 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="'.$patient->num_dossier.'.pdf"');
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
        
        return redirect()->route('patients.create')->with('success', 'Ethnie créé avec succès');

    }

    public function storeProfession(Request $request)
    {
        $request->validate(['nom' => 'required|string|max:255|unique:professions']);
        
        $profession = Profession::create($request->only('nom'));
        
        return redirect()->route('patients.create')->with('success', 'Professio créé avec succès');

    }

    
}
