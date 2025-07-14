@extends('dashboard.layouts.master')
@section('content')
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <h2 class="page-title">Tableau de Bord de la Clinique</h2>
        </div>
        <div class="col-auto ms-auto d-print-none">
          <div class="btn-list">
            <span class="d-none d-sm-inline">
              <a href="#" class="btn btn-white">
                Nouveau rapport
              </a>
            </span>
            <button class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#createRdvModal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Nouveau RDV
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="page-body">
    <div class="container-xl">
      <div class="row row-deck row-cards">
        <!-- Statistiques principales -->
        {{-- <div class="col-sm-6 col-lg-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">Patients aujourd'hui</div>
                <div class="ms-auto lh-1">
                  <div class="dropdown">
                    <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">7 derniers jours</a>
                    <div class="dropdown-menu dropdown-menu-end">
                      <a class="dropdown-item active" href="#">7 derniers jours</a>
                      <a class="dropdown-item" href="#">30 derniers jours</a>
                      <a class="dropdown-item" href="#">3 derniers mois</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="h1 mb-3">24</div>
              <div class="d-flex mb-2">
                <div>Evolution</div>
                <div class="ms-auto">
                  <span class="text-green d-inline-flex align-items-center lh-1">
                    8% <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                  </span>
                </div>
              </div>
              <div class="progress progress-sm">
                <div class="progress-bar bg-primary" style="width: 75%" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" aria-label="75% Complete">
                  <span class="visually-hidden">75% Complete</span>
                </div>
              </div>
            </div>
          </div>
        </div> 
        
        <div class="col-sm-6 col-lg-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">RDVs programmés</div>
                <div class="ms-auto lh-1">
                  <div class="dropdown">
                    <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aujourd'hui</a>
                    <div class="dropdown-menu dropdown-menu-end">
                      <a class="dropdown-item active" href="#">Aujourd'hui</a>
                      <a class="dropdown-item" href="#">Demain</a>
                      <a class="dropdown-item" href="#">Cette semaine</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="h1 mb-3">18</div>
              <div class="d-flex mb-2">
                <div>En attente</div>
                <div class="ms-auto">
                  <span class="text-green d-inline-flex align-items-center lh-1">
                    4 <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /></svg>
                  </span>
                </div>
              </div>
              <div class="progress progress-sm">
                <div class="progress-bar bg-warning" style="width: 45%" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" aria-label="45% Complete">
                  <span class="visually-hidden">45% Complete</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-sm-6 col-lg-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">Hospitalisations</div>
                <div class="ms-auto lh-1">
                  <div class="dropdown">
                    <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">En cours</a>
                    <div class="dropdown-menu dropdown-menu-end">
                      <a class="dropdown-item active" href="#">En cours</a>
                      <a class="dropdown-item" href="#">Sorties aujourd'hui</a>
                      <a class="dropdown-item" href="#">Cette semaine</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="h1 mb-3">9</div>
              <div class="d-flex mb-2">
                <div>Lits occupés</div>
                <div class="ms-auto">
                  <span class="text-red d-inline-flex align-items-center lh-1">
                    75% <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7l6 -6l6 6l6 -6l6 6" /><path d="M3 17l6 -6l6 6l6 -6l6 6" /></svg>
                  </span>
                </div>
              </div>
              <div class="progress progress-sm">
                <div class="progress-bar bg-danger" style="width: 75%" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" aria-label="75% Complete">
                  <span class="visually-hidden">75% Complete</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-sm-6 col-lg-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">Recettes journalières</div>
                <div class="ms-auto lh-1">
                  <div class="dropdown">
                    <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aujourd'hui</a>
                    <div class="dropdown-menu dropdown-menu-end">
                      <a class="dropdown-item active" href="#">Aujourd'hui</a>
                      <a class="dropdown-item" href="#">Hier</a>
                      <a class="dropdown-item" href="#">Cette semaine</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="h1 mb-3">1,245€</div>
              <div class="d-flex mb-2">
                <div>Objectif</div>
                <div class="ms-auto">
                  <span class="text-green d-inline-flex align-items-center lh-1">
                    92% <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                  </span>
                </div>
              </div>
              <div class="progress progress-sm">
                <div class="progress-bar bg-success" style="width: 92%" role="progressbar" aria-valuenow="92" aria-valuemin="0" aria-valuemax="100" aria-label="92% Complete">
                  <span class="visually-hidden">92% Complete</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        --}}
        {{-- <!-- Graphique des RDVs -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Rendez-vous par spécialité</h3>
            </div>
            <div class="card-body">
              <div id="chart-specialites"></div>
            </div>
          </div>
        </div>
        
        <!-- Graphique des recettes -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Recettes des 30 derniers jours</h3>
            </div>
            <div class="card-body">
              <div id="chart-revenus"></div>
            </div>
          </div>
        </div> --}}
        
         @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Des erreurs ont été détectées :</strong>
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

        <!-- Tableau des RDVs à venir -->
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Rendez-vous à venir</h3>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-vcenter table-mobile-md card-table">
                            <thead>
                                <tr>
                                  <th class="w-1"></th>
                                    <th>Patient</th>
                                    <th>Date/Heure</th>
                                    <th>Spécialité</th>
                                    <th>Médecin</th>
                                    <th>Statut</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rendezVous as $rdv)
                                <tr>
                                    <td data-label="Patient">
                                        <div class="d-flex py-1 align-items-center">
                                            <div class="flex-fill">
                                                <div class="font-weight-medium">{{ $rdv->patient->nom }} {{ $rdv->prenoms }}</div>
                                                <div class="text-muted">{{ $rdv->patient->contact_patient }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Date/Heure">
                                        <div>{{ $rdv->date_heure->format('H:i') }}</div>
                                        <div class="text-muted">{{ $rdv->date_heure->format('d/m/Y') }}</div>
                                    </td>
                                    <td data-label="Spécialité">
                                        {{ $rdv->specialite->nom }}
                                    </td>
                                    <td data-label="Médecin">
                                        {{ $rdv->medecin->nom_complet }}
                                    </td>
                                    <td data-label="Statut">
                                        @php
                                            $badgeClass = [
                                                'confirmé' => 'bg-success',
                                                'en_attente' => 'bg-warning',
                                                'annulé' => 'bg-danger',
                                                'terminé' => 'bg-secondary'
                                            ][$rdv->statut];
                                        @endphp
                                        <span class="badge {{ $badgeClass }} me-1"></span>
                                        {{ ucfirst($rdv->statut) }}
                                    </td>
                                    
                                    <td>
                                      <div class="btn-group">
                                          <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                                          <div class="dropdown-menu dropdown-menu-end">
                                              <button class="dropdown-item edit-rdv" 
                                                      data-bs-toggle="modal" 
                                                      data-bs-target="#editRdvModal"
                                                      data-rdv="{{ json_encode($rdv) }}">
                                                  Modifier
                                              </button>
                                              <button class="dropdown-item delete-rdv" 
                                                      data-bs-toggle="modal" 
                                                      data-bs-target="#deleteRdvModal"
                                                      data-rdv-id="{{ $rdv->id }}">
                                                  Supprimer
                                              </button>
                                              <a class="dropdown-item" href="#" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailRdvModal"
                                                data-patient="{{ $rdv->patient->nom_complet }}"
                                                data-date="{{ $rdv->date_heure->format('d/m/Y H:i') }}"
                                                data-medecin="{{ $rdv->medecin->nom_complet }}"
                                                data-specialite="{{ $rdv->specialite->nom }}"
                                                data-statut="{{ ucfirst($rdv->statut) }}"
                                                data-motif="{{ $rdv->motif ?? 'Non spécifié' }}">
                                                  Détails
                                              </a>
                                          </div>
                                      </div>
                                  </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
              </div>
            </div>
          </div>
        </div>
        
        
      </div>
    </div>
  </div>

<div class="modal fade" id="createRdvModal" tabindex="-1" aria-labelledby="createRdvModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRdvModalLabel">Nouveau Rendez-vous</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('store.rdv') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="patient_id" class="form-label">Patient</label>
                        <select class="form-select select2" id="patient_id" name="patient_id" required>
                            <option value="">Sélectionner un patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->nom }} {{ $patient->prenoms }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="medecin_id" class="form-label">Médecin</label>
                        <select class="form-select select2" id="medecin_id" name="medecin_id" required>
                          <option value="">Sélectionner un médecin</option>
                          @foreach($medecins as $medecin)
                              <option value="{{ $medecin->id }}"
                                  data-specialite="{{ $medecin->specialite->id ?? '' }}"
                                  data-specialite-nom="{{ $medecin->specialite->nom ?? '' }}">
                                  {{ $medecin->nom_complet }}
                              </option>
                          @endforeach
                      </select>

                    </div>
                    <div class="mb-3">
                        <label for="specialite_id" class="form-label">Spécialité</label>
                        <input type="text" class="form-control" id="specialite_id" name="specialite_nom" readonly>
                        <input type="hidden" id="specialite_id_value" name="specialite_id">
                    </div>
                    <div class="mb-3">
                        <label for="date_heure" class="form-label">Date et Heure</label>
                        <input type="datetime-local" class="form-control" id="date_heure" name="date_heure" required>
                    </div>
                    <div class="mb-3">
                        <label for="motif" class="form-label">Motif (optionnel)</label>
                        <textarea class="form-control" id="motif" name="motif" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editRdvModal" tabindex="-1" aria-labelledby="editRdvModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRdvModalLabel">Modifier Rendez-vous</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_patient_id" class="form-label">Patient</label>
                        <select class="form-select" id="edit_patient_id" name="patient_id" required>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->nom }} {{ $patient->prenoms }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_specialite_id" class="form-label">Spécialité</label>
                        {{-- <select class="form-select" id="edit_specialite_id" name="specialite_id" required>
                            @foreach($specialites as $specialite)
                                <option value="{{ $specialite->id }}">{{ $specialite->nom }}</option>
                            @endforeach
                        </select> --}}
                    </div>
                    <div class="mb-3">
                        <label for="edit_medecin_id" class="form-label">Médecin</label>
                        <select class="form-select" id="edit_medecin_id" name="medecin_id" required>
                            @foreach($medecins as $medecin)
                                <option value="{{ $medecin->id }}">{{ $medecin->nom_complet }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_date_heure" class="form-label">Date et Heure</label>
                        <input type="datetime-local" class="form-control" id="edit_date_heure" name="date_heure" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_statut" class="form-label">Statut</label>
                        <select class="form-select" id="edit_statut" name="statut" required>
                            <option value="confirmé">Confirmé</option>
                            <option value="en_attente">En attente</option>
                            <option value="annulé">Annulé</option>
                            <option value="terminé">Terminé</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_motif" class="form-label">Motif (optionnel)</label>
                        <textarea class="form-control" id="edit_motif" name="motif" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteRdvModal" tabindex="-1" aria-labelledby="deleteRdvModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRdvModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer ce rendez-vous ? Cette action est irréversible.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')

<script>
$(document).ready(function() {
    // Initialiser Select2
    

    $('.select2').select2({
        dropdownParent: $('#createRdvModal')
    });

    // Quand on change de médecin, on remplit la spécialité
    $('#medecin_id').on('change', function() {
        var selectedMedecin = $(this).find('option:selected');
        var specialiteNom = selectedMedecin.data('specialite-nom') || '';
        var specialiteId = selectedMedecin.data('specialite') || '';
        $('#specialite_id').val(specialiteNom);
        $('#specialite_id_value').val(specialiteId);
    });

    // Réinitialiser la spécialité à la fermeture du modal
    $('#createRdvModal').on('hidden.bs.modal', function () {
        $('#specialite_id').val('');
        $('#specialite_id_value').val('');
        $('#medecin_id').val('').trigger('change');
    });
});
</script>
@endpush
@endsection