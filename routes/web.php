<?php

use App\Http\Controllers\AccueilController;
use App\Http\Controllers\AssuranceController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\FraisHospitalisationController;
use App\Http\Controllers\HistoriqueController;
use App\Http\Controllers\HospitalisationController;
use App\Http\Controllers\MedecinController;
use App\Http\Controllers\MedicamentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrestationController;
use App\Http\Controllers\ReglementController;
use App\Http\Controllers\TracabiliteController;
use App\Http\Controllers\UtilisateurController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('login');
});


Auth::routes(['register' => false]); // Désactive l'inscription si nécessaire

Route::get('/hospitalisations/{id}', [HospitalisationController::class, 'show'])->name('hospitalisations.show');

Route::middleware(['auth', 'user.status'])->group(function () {

    
    Route::get('/home', [AccueilController::class, 'home'])->name('home');
    Route::post('/', [AccueilController::class, 'storeRdv'])->name('store.rdv');

    Route::resource('utilisateurs', UtilisateurController::class);
    Route::post('/utilisateurs/{id}/toggle-status', [UtilisateurController::class, 'toggleStatus'])->name('utilisateurs.toggleStatus');

    Route::resource('assurances', AssuranceController::class);

    Route::resource('medecins', MedecinController::class);

    Route::post('/category_hospitalisation', [FraisHospitalisationController::class, 'storeCategory'])->name('category.hospitalisation.store');

    Route::resource('frais_hospitalisations', FraisHospitalisationController::class);

    Route::resource('prestations', PrestationController::class);

    Route::resource('patients', PatientController::class);
    Route::get('/patients-data', [PatientController::class, 'getPatientsData'])->name('patients.data');
    Route::post('/patients/create/professions', [PatientController::class, 'storeProfession'])->name('professions.store');
    Route::post('/patients/create/ethnies', [PatientController::class, 'storeEthnie'])->name('ethnies.store');
    Route::delete('/patients/{patient}/remove-photo', [PatientController::class, 'removePhoto'])->name('patients.remove-photo');
    Route::get('/patients/{patient}/consultations/create', [ConsultationController::class, 'create'])->name('consultations.create');
    Route::post('/patients/{patient}/consultations', [ConsultationController::class, 'store'])->name('consultations.store');
    Route::get('/patients/{patient}/view-pdf', [PatientController::class, 'viewPdf'])->name('patients.view-pdf');

    Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations.index');
    Route::get('/consultations/{consultation}/edit', [ConsultationController::class, 'edit'])->name('consultations.edit');
    Route::put('/consultations/{consultation}', [ConsultationController::class, 'update'])->name('consultations.update');

    Route::post('/hospitalisations/simple/{patient}', [HospitalisationController::class, 'storeSimple'])->name('hospitalisations.store.simple');
    Route::resource('hospitalisations', HospitalisationController::class)->except(['show'])->middleware('auth');
    Route::post('/hospitalisations/{hospitalisation}/sortir', [HospitalisationController::class, 'sortir'])->name('hospitalisations.sortir');
    Route::post('/hospitalisations/{hospitalisation}/rentrer', [HospitalisationController::class, 'rentrer'])->name('hospitalisations.rentrer');
    Route::get('/hospitalisations/{hospitalisation}/facture/create', [HospitalisationController::class, 'createFacture'])->name('hospitalisations.facture.create');
    Route::post('/hospitalisations/{hospitalisation}/facture', [HospitalisationController::class, 'storeFacture'])->name('hospitalisations.facture.store');
    Route::get('/hospitalisations/{hospitalisation}/pharmacie/create', [HospitalisationController::class, 'createPharmacie'])->name('hospitalisations.pharmacie.create');
    Route::post('/hospitalisations/{hospitalisation}/pharmacie', [HospitalisationController::class, 'storePharmacie'])->name('hospitalisations.pharmacie.store');
    Route::delete('/hospitalisations/{hospitalisation}/pharmacie/{pivot}', [HospitalisationController::class, 'destroyMedicament'])->name('hospitalisations.pharmacie.destroy');
    Route::get('/hospitalisations/{hospitalisation}/laboratoire/create', [HospitalisationController::class, 'createExamen'])->name('hospitalisations.laboratoire.create');
    Route::post('/hospitalisations/{hospitalisation}/laboratoire', [HospitalisationController::class, 'storeExamen'])->name('hospitalisations.laboratoire.store');
    Route::delete('/hospitalisations/{hospitalisation}/laboratoire/{pivot}', [HospitalisationController::class, 'destroyMedicament'])->name('hospitalisations.laboratoire.destroy');

    Route::get('/print/laboratoire/{id}', [HospitalisationController::class, 'printLaboratoire'])->name('print.laboratoire');
    Route::get('/print/pharmacie/{id}', [HospitalisationController::class, 'printPharmacie'])->name('print.pharmacie');

    Route::get('/comptabilite/journalcaisse', [ReglementController::class, 'journalCaisse'])->name('comptabilite.journalcaisse');

    Route::get('/caisse/print', [ReglementController::class, 'printJournal'])
    ->name('caisse.print');
    
    Route::resource('reglements', ReglementController::class);
    Route::get('/reglements/{type}/{id}/details', [ReglementController::class, 'showDetails'])->name('reglements.details');

    Route::resource('depenses', DepenseController::class);
    Route::post('category-depenses', [DepenseController::class, 'storeCategory'])->name('category-depenses.store');

    Route::get('/profil', [UtilisateurController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profil', [UtilisateurController::class, 'updateProfile'])->name('profile.update');

    Route::get('/tracabilite', [TracabiliteController::class, 'index'])->name('tracabilite.index');
    Route::get('/tracabilite/data', [TracabiliteController::class, 'getTracabiliteData'])->name('tracabilite.data');

    Route::resource('historique', HistoriqueController::class);

    Route::resource('medicaments', MedicamentController::class)->except(['show']);

    Route::resource('examens', ExamenController::class)->except(['show']);

    Route::post('/{medicament}/update-stock', [MedicamentController::class, 'updateStock'])->name('medicaments.update-stock');

    Route::get('/medicaments/historique-global-pdf', [MedicamentController::class, 'historiqueGlobalPDF'])->name('medicaments.historique.global.pdf');

    Route::get('/medicaments/inventaire-pdf', [MedicamentController::class, 'inventaireMedicamentsPDF'])
    ->name('medicaments.inventaire.pdf');

    Route::get('/aides', function () {
        return view('dashboard.pages.help');
    })->name('aide');

    

});

