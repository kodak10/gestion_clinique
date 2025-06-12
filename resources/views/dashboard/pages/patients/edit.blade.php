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
            <form action="{{ route('patients.update', $patient->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
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
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Domicile <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('domicile') is-invalid @enderror" name="domicile" value="{{ old('domicile', $patient->domicile) }}" required>
                                @error('domicile')
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
                                <input type="text" class="form-control @error('profession') is-invalid @enderror" name="profession" value="{{ old('profession', $patient->profession) }}">
                                @error('profession')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Ethnie</label>
                                <input type="text" class="form-control @error('ethnie') is-invalid @enderror" name="ethnie" value="{{ old('ethnie', $patient->ethnie) }}">
                                @error('ethnie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Religion</label>
                                <input type="text" class="form-control @error('religion') is-invalid @enderror" name="religion" value="{{ old('religion', $patient->religion) }}">
                                @error('religion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Groupe Rhesus</label>
                                <input type="text" class="form-control @error('groupe_rhesus') is-invalid @enderror" name="groupe_rhesus" value="{{ old('groupe_rhesus', $patient->groupe_rhesus) }}">
                                @error('groupe_rhesus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
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
                            <div class="mb-3">
                                <label class="form-label">Personne en cas d'urgence</label>
                                <input type="text" class="form-control @error('contact_urgence') is-invalid @enderror" name="contact_urgence" value="{{ old('contact_urgence', $patient->contact_urgence) }}">
                                @error('contact_urgence')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Contact</label>
                                <input type="text" class="form-control @error('contact_patient') is-invalid @enderror" name="contact_patient" value="{{ old('contact_patient', $patient->contact_patient) }}">
                                @error('contact_patient')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
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
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Envoyé Par</label>
                                <input type="text" class="form-control @error('envoye_par') is-invalid @enderror" name="envoye_par" value="{{ old('envoye_par', $patient->envoye_par) }}">
                                @error('envoye_par')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('patients.index') }}" class="btn btn-link link-secondary btn-3">Annuler</a>
                    <button type="submit" class="btn btn-primary btn-5 ms-auto">Mettre à jour</button>
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
@endsection