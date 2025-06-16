<?php

use App\Http\Controllers\AssuranceController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\FraisHospitalisationController;
use App\Http\Controllers\HospitalisationController;
use App\Http\Controllers\MedecinController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrestationController;
use App\Http\Controllers\UtilisateurController;
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
    Route::delete('/patients/{patient}/remove-photo', [PatientController::class, 'removePhoto'])->name('patients.remove-photo');
    Route::get('/patients/{patient}/consultations/create', [ConsultationController::class, 'create'])->name('consultations.create');
    Route::post('/patients/{patient}/consultations', [ConsultationController::class, 'store'])->name('consultations.store');

    Route::post('/hospitalisations/simple/{patient}', [HospitalisationController::class, 'storeSimple'])->name('hospitalisations.store.simple');
    Route::resource('hospitalisations', HospitalisationController::class);

    Route::get('/hospitalisations/{patient}/facture/create', [HospitalisationController::class, 'createFacture'])->name('hospitalisations.facture.create');
    Route::post('/hospitalisations/{patient}/facture', [HospitalisationController::class, 'storeFacture'])->name('hospitalisations.facture.store');

Route::get('/hospitalisations/{hospitalisation}/pharmacie/create', [HospitalisationController::class, 'createPharmacie'])->name('hospitalisations.pharmacie.create');
Route::post('/hospitalisations/{hospitalisation}/pharmacie', [HospitalisationController::class, 'storePharmacie'])->name('hospitalisations.pharmacie.store');

    Route::get('/hospitalisations/{patient}/laboratoire/create', [HospitalisationController::class, 'createLaboratoire'])->name('hospitalisations.laboratoire.create');
    Route::post('/hospitalisations/{patient}/laboratoire', [HospitalisationController::class, 'storeLaboratoire'])->name('hospitalisations.laboratoire.store');

});

