@extends('dashboard.layouts.master')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Création de Patient</h2>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE HEADER -->
<!-- BEGIN PAGE BODY -->
<div class="page-body">
    <div class="container-xl">
        <div class="card p-2">
            <form action="{{ route('patients.store') }}" method="POST" enctype="multipart/form-data" id="create-patient-form" >                
                @csrf
                <input type="hidden" class="form-control" value="{{ $provisionalNum }}" readonly>
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" value="{{ old('nom') }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Prénoms <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('prenoms') is-invalid @enderror" name="prenoms" value="{{ old('prenoms') }}" required>
                                @error('prenoms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" name="date_naissance" value="{{ old('date_naissance') }}" required>
                                @error('date_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Domicile <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('domicile') is-invalid @enderror" name="domicile" value="{{ old('domicile') }}" required>
                                @error('domicile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Contact <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('contact_patient') is-invalid @enderror" name="contact_patient" value="{{ old('contact_patient') }}" required>
                                @error('contact_patient')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label class="form-label">Sexe <span class="text-danger">*</span></label>
                                <select class="form-control @error('sexe') is-invalid @enderror" name="sexe" required>
                                    <option value="">Sélectionner</option>
                                    <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>
                                @error('sexe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Profession</label>
                                <div class="input-group">
                                    <select class="form-select @error('profession_id') is-invalid @enderror" name="profession_id" id="profession-select">
                                        <option value="">Sélectionner</option>
                                        @foreach($professions as $profession)
                                            <option value="{{ $profession->id }}" {{ old('profession_id') == $profession->id ? 'selected' : '' }}>
                                                {{ $profession->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modal-profession">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 5l0 14" />
                                            <path d="M5 12l14 0" />
                                        </svg>
                                    </button>
                                </div>
                                @error('profession_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Ethnie</label>
                                <div class="input-group">
                                    <select class="form-select @error('ethnie_id') is-invalid @enderror" name="ethnie_id" id="ethnie-select">
                                        <option value="">Sélectionner</option>
                                        @foreach($ethnies as $ethnie)
                                            <option value="{{ $ethnie->id }}" {{ old('ethnie_id') == $ethnie->id ? 'selected' : '' }}>
                                                {{ $ethnie->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modal-ethnie">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 5l0 14" />
                                            <path d="M5 12l14 0" />
                                        </svg>
                                    </button>
                                </div>
                                @error('ethnie_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Religion</label>
                                <select class="form-control" name="" id="">
                                    <option value="">Sélectionner</option>
                                    <option value="Catholique" {{ old('religion') == 'Catholique' ? 'selected' : '' }}>Catholique</option>
                                    <option value="Protestant" {{ old('religion') == 'Protestant' ? 'selected' : '' }}>Protestant</option>
                                    <option value="Musulman" {{ old('religion') == 'Musulman' ? 'selected' : '' }}>Musulman</option>
                                    <option value="Autre" {{ old('religion') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('religion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Personne en cas d'urgence</label>
                                <input type="text" class="form-control @error('contact_urgence') is-invalid @enderror" name="contact_urgence" value="{{ old('contact_urgence') }}">
                                @error('contact_urgence')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Photo</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" name="photo">
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Groupe Rhesus</label>
                                <input type="text" class="form-control @error('groupe_rhesus') is-invalid @enderror" name="groupe_rhesus" value="{{ old('groupe_rhesus') }}">
                                @error('groupe_rhesus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Electrophorese</label>
                                <input type="text" class="form-control @error('electrophorese') is-invalid @enderror" name="electrophorese" value="{{ old('electrophorese') }}">
                                @error('electrophorese')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Assurance</label>
                                <select class="form-control @error('assurance_id') is-invalid @enderror" name="assurance_id">
                                    <option value="" >Aucune</option>
                                    @foreach($assurances as $assurance)
                                        <option value="{{ $assurance->id }}" {{ old('assurance_id') == $assurance->id ? 'selected' : '' }}>
                                            {{ $assurance->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assurance_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Taux Couverture</label>
                                <input type="number" class="form-control @error('taux_couverture') is-invalid @enderror" name="taux_couverture" value="{{ old('taux_couverture') }}" min="0" max="100">
                                @error('taux_couverture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Matricule Assurance</label>
                                <input type="text" class="form-control @error('matricule_assurance') is-invalid @enderror" name="matricule_assurance" value="{{ old('matricule_assurance') }}">
                                @error('matricule_assurance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        
                        
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <a href="{{ route('patients.index') }}" class="btn btn-link link-secondary btn-3">Annuler</a>
                    <button type="submit" class="btn btn-primary btn-5 ms-auto">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Profession -->
<div class="modal modal-blur fade" id="modal-profession" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle Profession</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('professions.store') }}" method="POST" id="profession-form" class="form-loader">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom de la profession</label>
                        <input type="text" class="form-control" name="nom" id="profession-nom" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Annuler</a>
                    <button type="submit" class="btn btn-primary ms-auto">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ethnie -->
<div class="modal modal-blur fade" id="modal-ethnie" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle Ethnie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('ethnies.store') }}" method="POST" id="ethnie-form" class="form-loader">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom de l'ethnie</label>
                        <input type="text" class="form-control" name="nom" id="ethnie-nom" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Annuler</a>
                    <button type="submit" class="btn btn-primary ms-auto">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Utilisez le même ID que dans votre formulaire
        const form = document.getElementById('create-patient-form');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Empêche la soumission immédiate
                
                Swal.fire({
                    title: 'Confirmer la création',
                    text: "Voulez-vous enregistrer ce nouveau patient ?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, enregistrer',
                    cancelButtonText: 'Annuler',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    backdrop: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Réactive le bouton de soumission
                        const submitBtn = form.querySelector('button[type="submit"]');
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enregistrement...';
                        
                        // Soumet le formulaire
                        form.submit();
                    }
                });
            });
        }
        // Affichage des messages flash avec SweetAlert
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: '{{ session('error') }}'
            });
        @endif

        
    });
</script>
@endpush