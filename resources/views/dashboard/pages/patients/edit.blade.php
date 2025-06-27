@extends('dashboard.layouts.master')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Modification de Patient</h2>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE HEADER -->
<!-- BEGIN PAGE BODY -->
<div class="page-body">
    <div class="container-xl">
        <div class="card p-2">
            <form action="{{ route('patients.update', $patient->id) }}" method="POST"  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" class="form-control" value="{{ old('num_dossier', $patient->num_dossier) }}" readonly>
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
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" value="{{ old('nom', $patient->nom) }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Prénoms <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('prenoms') is-invalid @enderror" name="prenoms" value="{{ old('prenoms', $patient->prenoms) }}" required>
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
                                <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" name="date_naissance" value="{{ old('date_naissance', $patient->date_naissance->format('Y-m-d')) }}" required>
                                @error('date_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Domicile <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('domicile') is-invalid @enderror" name="domicile" value="{{ old('domicile', $patient->domicile) }}" required>
                                @error('domicile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Contact <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('contact_patient') is-invalid @enderror" name="contact_patient" value="{{ old('contact_patient', $patient->contact_patient) }}" required>
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
                                    <option value="M" {{ old('sexe', $patient->sexe) == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ old('sexe', $patient->sexe) == 'F' ? 'selected' : '' }}>Féminin</option>
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
                                    <select class="form-select @error('profession_id') is-invalid @enderror" name="profession_id" id="profession-select-edit">
                                        <option value="">Sélectionner</option>
                                        @foreach($professions as $profession)
                                            <option value="{{ $profession->id }}" 
                                                {{ old('profession_id', $patient->profession_id) == $profession->id ? 'selected' : '' }}>
                                                {{ $profession->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modal-profession-edit">
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
                                    <select class="form-select @error('ethnie_id') is-invalid @enderror" name="ethnie_id" id="ethnie-select-edit">
                                        <option value="">Sélectionner</option>
                                        @foreach($ethnies as $ethnie)
                                            <option value="{{ $ethnie->id }}" 
                                                {{ old('ethnie_id', $patient->ethnie_id) == $ethnie->id ? 'selected' : '' }}>
                                                {{ $ethnie->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modal-ethnie-edit">
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
                                <select class="form-control @error('religion') is-invalid @enderror" name="religion">
                                    <option value="">Sélectionner</option>
                                    <option value="Catholique" {{ old('religion', $patient->religion) == 'Catholique' ? 'selected' : '' }}>Catholique</option>
                                    <option value="Protestant" {{ old('religion', $patient->religion) == 'Protestant' ? 'selected' : '' }}>Protestant</option>
                                    <option value="Musulman" {{ old('religion', $patient->religion) == 'Musulman' ? 'selected' : '' }}>Musulman</option>
                                    <option value="Autre" {{ old('religion', $patient->religion) == 'Autre' ? 'selected' : '' }}>Autre</option>
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
                                <input type="text" class="form-control @error('contact_urgence') is-invalid @enderror" name="contact_urgence" value="{{ old('contact_urgence', $patient->contact_urgence) }}">
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
                                @if($patient->photo)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $patient->photo) }}" alt="Photo patient" style="max-width: 100px;">
                                        <a href="#" class="text-danger ms-2" onclick="event.preventDefault(); document.getElementById('remove-photo').submit();">Supprimer</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Groupe Rhesus</label>
                                <input type="text" class="form-control @error('groupe_rhesus') is-invalid @enderror" name="groupe_rhesus" value="{{ old('groupe_rhesus', $patient->groupe_rhesus) }}">
                                @error('groupe_rhesus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Electrophorese</label>
                                <input type="text" class="form-control @error('electrophorese') is-invalid @enderror" name="electrophorese" value="{{ old('electrophorese', $patient->electrophorese) }}">
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
                                    <option value="">Aucune</option>
                                    @foreach($assurances as $assurance)
                                        <option value="{{ $assurance->id }}" {{ old('assurance_id', $patient->assurance_id) == $assurance->id ? 'selected' : '' }}>
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
                                <input type="number" class="form-control @error('taux_couverture') is-invalid @enderror" name="taux_couverture" value="{{ old('taux_couverture', $patient->taux_couverture) }}" min="0" max="100">
                                @error('taux_couverture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Matricule Assurance</label>
                                <input type="text" class="form-control @error('matricule_assurance') is-invalid @enderror" name="matricule_assurance" value="{{ old('matricule_assurance', $patient->matricule_assurance) }}">
                                @error('matricule_assurance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            
                        </div>
                        
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <a href="{{ route('patients.index') }}" class="btn btn-link link-secondary btn-3">Annuler</a>
                    <button type="submit" class="btn btn-primary btn-5 ms-auto" >Mettre à jour</button>
                    
                </div>
            </form>

            <!-- Formulaire caché pour supprimer la photo -->
            @if($patient->photo)
                <form id="remove-photo" action="{{ route('patients.remove-photo', $patient->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>
    </div>
</div>

<!-- Modal Profession -->
<div class="modal modal-blur fade" id="modal-profession-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle Profession</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('professions.store') }}" method="POST" id="profession-form">
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
<div class="modal modal-blur fade" id="modal-ethnie-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle Ethnie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('ethnies.store') }}" method="POST" id="ethnie-form">
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
        // Sélectionnez le formulaire spécifique par son action
        const form = document.querySelector('form[action="{{ route('patients.update', $patient->id) }}"]');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Confirmation',
                    text: "Voulez-vous vraiment modifier ce patient ?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, Modifier',
                    cancelButtonText: 'Annuler',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Désactivez le bouton pour éviter les doubles clics
                        const submitBtn = form.querySelector('button[type="submit"]');
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> En cours...';
                        
                        // Soumettez le formulaire
                        form.submit();
                    }
                });
            });
        }

        // Initialisation DataTable si nécessaire
        if (jQuery().DataTable && $('.table').length) {
            $('.table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/French.json"
                },
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "responsive": true
            });
        }
    });
</script>
@endpush