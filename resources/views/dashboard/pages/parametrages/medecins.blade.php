@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Medecins</h2>
            </div>
            <div class="col">
                <a href="#" class="btn btn-2 float-end" data-bs-toggle="modal" data-bs-target="#modal-report">Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            @foreach($medecins as $medecin)
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body p-4 text-center">
                            <span class="avatar avatar-xl mb-3 rounded" style="background-image: url({{ $medecin->image_url }})"></span>
                            <h3 class="m-0 mb-1">{{ $medecin->nom_complet }}</h3>
                            <div class="mt-3">
                               @if($medecin->specialite)
                                    <span class="badge bg-purple-lt">{{ $medecin->specialite->nom }}</span>
                                @endif

                            </div>
                        </div>
                        <div class="d-flex">
                            <a href="#" class="card-btn" data-bs-toggle="modal" data-bs-target="#edit-modal-{{ $medecin->id }}">
                                <i class="fas fa-edit me-1 text-primary"></i> Modifier
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Modal d'édition -->
                <div class="modal modal-blur fade" id="edit-modal-{{ $medecin->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modifier médecin</h5>
                            </div>
                            <form method="POST" action="{{ route('medecins.update', $medecin->id) }}" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label">Matricule</label>
                                                <input type="text" class="form-control" name="matricule" value="{{ $medecin->matricule }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="mb-3">
                                                <label class="form-label">Spécialités</label>
                                                <select class="form-select" name="specialites" required>
                                                    @foreach($specialites as $specialite)
                                                        <option value="{{ $specialite->id }}" 
                                                            {{ $specialite->id == $medecin->specialite_id ? 'selected' : '' }}>
                                                            {{ $specialite->nom }}
                                                        </option>

                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="mb-3">
                                                <label class="form-label">Nom complet</label>
                                                <input type="text" class="form-control" name="nom_complet" value="{{ $medecin->nom_complet }}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label">Téléphone</label>
                                                <input type="text" class="form-control" name="telephone" value="{{ $medecin->telephone }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal de création -->
<div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un médecin</h5>
            </div>
            <form method="POST" action="{{ route('medecins.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Matricule</label>
                                <input type="text" class="form-control" name="matricule">
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label class="form-label">Spécialités</label>
                                <select class="form-select" name="specialite_id" required>
                                    @foreach($specialites as $specialite)
                                        <option value="{{ $specialite->id }}" 
                                            {{ $medecin->specialite_id == $specialite->id ? 'selected' : '' }}>
                                            {{ $specialite->nom }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label class="form-label">Nom complet</label>
                                <input type="text" class="form-control" name="nom_complet" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Téléphone</label>
                                <input type="text" class="form-control" name="telephone" required>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection