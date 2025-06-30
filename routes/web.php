<?php

use App\Http\Controllers\AssuranceController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\FraisHospitalisationController;
use App\Http\Controllers\HospitalisationController;
use App\Http\Controllers\MedecinController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrestationController;
use App\Http\Controllers\ReglementController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\TracabiliteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;






Route::get('/', function () {
    return view('login');
});


Auth::routes(['register' => false]); // Désactive l'inscription si nécessaire

//Route::middleware(['auth', 'user.status'])->group(function () {
Route::middleware(['auth',])->group(function () {

    Route::get('/home', function () {
        return view('dashboard.pages.index');
    });

    Route::get('/utilisateurs', [UtilisateurController::class, 'index'])->name('utilisateurs.index');
    Route::post('/utilisateurs', [UtilisateurController::class, 'store'])->name('utilisateurs.store');
    Route::post('/utilisateurs/{id}/toggle-status', [UtilisateurController::class, 'toggleStatus'])->name('utilisateurs.toggleStatus');

    Route::resource('assurances', AssuranceController::class);
    Route::resource('medecins', MedecinController::class);

    Route::post('/category_hospitalisation', [FraisHospitalisationController::class, 'storeCategory'])->name('category.hospitalisation.store');

    Route::resource('frais_hospitalisations', FraisHospitalisationController::class);
    Route::resource('prestations', PrestationController::class);

    Route::resource('patients', PatientController::class);
    Route::get('/patients-data', [PatientController::class, 'patientsData'])->name('patients.data');

    Route::post('/patients/create/professions', [PatientController::class, 'storeProfession'])->name('professions.store');
    Route::post('/patients/create/ethnies', [PatientController::class, 'storeEthnie'])->name('ethnies.store');

    Route::delete('/patients/{patient}/remove-photo', [PatientController::class, 'removePhoto'])->name('patients.remove-photo');
    Route::get('/patients/{patient}/consultations/create', [ConsultationController::class, 'create'])->name('consultations.create');
    Route::post('/patients/{patient}/consultations', [ConsultationController::class, 'store'])->name('consultations.store');

    Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations.index');
    Route::get('/consultations/{consultation}/edit', [ConsultationController::class, 'edit'])->name('consultations.edit');
    Route::put('/consultations/{consultation}', [ConsultationController::class, 'update'])->name('consultations.update');



   Route::get('/patients/{patient}/view-pdf', [PatientController::class, 'viewPdf'])->name('patients.view-pdf');


    Route::post('/hospitalisations/simple/{patient}', [HospitalisationController::class, 'storeSimple'])->name('hospitalisations.store.simple');
    Route::resource('hospitalisations', HospitalisationController::class);

    Route::get('/hospitalisations/{hospitalisation}/facture/create', [HospitalisationController::class, 'createFacture'])->name('hospitalisations.facture.create');
    Route::post('/hospitalisations/{hospitalisation}/facture', [HospitalisationController::class, 'storeFacture'])->name('hospitalisations.facture.store');

    Route::get('/hospitalisations/{hospitalisation}/pharmacie/create', [HospitalisationController::class, 'createPharmacie'])->name('hospitalisations.pharmacie.create');
    Route::post('/hospitalisations/{hospitalisation}/pharmacie', [HospitalisationController::class, 'storePharmacie'])->name('hospitalisations.pharmacie.store');
    Route::delete('/hospitalisations/{hospitalisation}/pharmacie/{pivot}', [HospitalisationController::class, 'destroyMedicament'])->name('hospitalisations.pharmacie.destroy');

    Route::get('/hospitalisations/{hospitalisation}/laboratoire/create', [HospitalisationController::class, 'createExamen'])->name('hospitalisations.laboratoire.create');
    Route::post('/hospitalisations/{hospitalisation}/laboratoire', [HospitalisationController::class, 'storeExamen'])->name('hospitalisations.laboratoire.store');
    Route::delete('/hospitalisations/{hospitalisation}/laboratoire/{pivot}', [HospitalisationController::class, 'destroyMedicament'])->name('hospitalisations.laboratoire.destroy');

    Route::get('/comptabilite/journalcaisse', [ReglementController::class, 'journalCaisse'])->name('comptabilite.journalcaisse');

    Route::resource('reglements', ReglementController::class);

    Route::get('/reglements/{type}/{id}/details', [ReglementController::class, 'showDetails'])
        ->name('reglements.details');

    Route::resource('depenses', DepenseController::class);
    Route::post('category-depenses', [DepenseController::class, 'storeCategory'])->name('category-depenses.store');


    Route::get('/profil', [UtilisateurController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profil', [UtilisateurController::class, 'updateProfile'])->name('profile.update');

    Route::get('/tracabilite', [TracabiliteController::class, 'index'])->name('tracabilite.index');

});

