@extends('dashboard.layouts.master')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="page-title m-0">Suivi du Patient</h2>
            <a href="{{ route('patients.index') }}" class="btn bg-gray-500 ">Retour</a>
        </div>

    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Carte d'information du patient -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Informations du Patient</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nom:</strong> {{ $patient->nom }}</p>
                        <p><strong>Prénoms:</strong> {{ $patient->prenoms }}</p>
                        <p><strong>Numéro dossier:</strong> {{ $patient->num_dossier }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Date de naissance:</strong> {{ $patient->date_naissance->format('d/m/Y') }}</p>
                        <p><strong>Contact:</strong> {{ $patient->contact_patient }}</p>
                        <p><strong>Domicile:</strong> {{ $patient->domicile }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Onglets pour consultations et hospitalisations -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="#consultations" class="nav-link active" data-bs-toggle="tab">Consultations</a>
                    </li>
                    <li class="nav-item">
                        <a href="#hospitalisations" class="nav-link" data-bs-toggle="tab">Hospitalisations</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Onglet Consultations -->
                    <div class="tab-pane active show" id="consultations">
                        <div class="d-flex justify-content-between mb-3">
                            <h4>Historique des Consultations</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#consultationModal">
                                Nouvelle Consultation
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter" id="consultations-table">
                                <thead>
                                    <tr>
                                        <th class="w-1"></th>

                                        <th>Numéro reçu</th>
                                        <th>Date</th>
                                        <th>Médecin</th>
                                        <th>Total</th>
                                        <th>Payé</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($consultations as $consultation)
                                    <tr>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item detail-mouvement" href="#" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#modal-detail"
                                                            data-patient="{{ $consultation->patient->nom }} {{ $consultation->patient->prenoms }}"
                                                            data-date="{{ $consultation->date_consultation->format('d/m/Y H:i') }}"
                                                            data-recus="{{ $consultation->numero_recu }}"
                                                            data-total="{{ number_format($consultation->total, 0, ',', ' ') }}"
                                                            data-reduction="{{ number_format($consultation->reduction, 0, ',', ' ') }}"
                                                            data-ticket="{{ number_format($consultation->montant_a_paye, 0, ',', ' ') }}"
                                                            data-encaisser="{{ number_format($consultation->reglements->sum('montant'), 0, ',', ' ') }}"
                                                            data-prestations="{{ json_encode($consultation->details->map(function($item) {
                                                                return [
                                                                    'libelle' => $item->prestation->libelle,
                                                                    'quantite' => $item->quantite,
                                                                    'montant' => number_format($item->montant, 0, ',', ' '),
                                                                    'total' => number_format($item->total, 0, ',', ' ')
                                                                ];
                                                            })) }}"
                                                            data-caissier="{{ $consultation->reglements->first()->user->name ?? 'N/A' }}">
                                                            Détail du mouvement
                                                        </a>
                                                        <a class="dropdown-item" href="{{ route('consultations.edit', $consultation->id) }}">Modifier</a>
                                                        @if($consultation->pdf_path)
                                                        <a class="dropdown-item" href="{{ Storage::url($consultation->pdf_path) }}" target="_blank">
                                                            Imprimer le reçu
                                                        </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $consultation->numero_recu }}</td>
                                        <td>{{ $consultation->date_consultation->format('d/m/Y H:i') }}</td>
                                        <td>{{ $consultation->medecin->nom_complet }}</td>
                                        <td>{{ number_format($consultation->montant_a_paye, 0, ',', ' ') }} FCFA</td>
                                        <td>
                                            {{ number_format($consultation->reglements->sum('montant'), 0, ',', ' ') }} FCFA
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Onglet Hospitalisations -->
                    <div class="tab-pane" id="hospitalisations">
                        <div class="d-flex justify-content-between mb-3">
                            <h4>Historique des Hospitalisations</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#hospitalisationModal">
                                Nouvelle Hospitalisation
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter" id="hospitalisations-table">
                                <thead>
                                    <tr>
                                        <th>Numéro dossier</th>
                                        <th>Date admission</th>
                                        <th>Médecin</th>
                                        <th>Total</th>
                                        <th>Payé</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hospitalisations as $hospitalisation)
                                    <tr>
                                        <td>{{ $hospitalisation->patient->num_dossier }}</td>
                                        <td>{{ $hospitalisation->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $hospitalisation->medecin->nom_complet ?? '' }}</td>
                                        <td>{{ number_format($hospitalisation->total, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ number_format($hospitalisation->montant_paye, 0, ',', ' ') }} FCFA</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editHospitalisationModal{{ $hospitalisation->id }}">
                                                Modifier
                                            </a>
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

<div class="modal modal-blur fade" id="modal-detail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du mouvement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Patient</label>
                        <input type="text" class="form-control" id="detail-patient" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date & Heure</label>
                        <input type="text" class="form-control" id="detail-date" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Numéro de reçu</label>
                        <input type="text" class="form-control" id="detail-recus" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Caissier</label>
                        <input type="text" class="form-control" id="detail-caissier" readonly>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Prestations effectuées</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Prestation</th>
                                    <th>Quantité</th>
                                    <th>Prix unitaire</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="detail-prestations">
                                <!-- Les prestations seront ajoutées ici par JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-3">
                        <label class="form-label">Montant Total</label>
                        <input type="text" class="form-control" id="detail-montant" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ticket modérateur</label>
                        <input type="text" class="form-control" id="detail-ticket" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Réduction</label>
                        <input type="text" class="form-control" id="detail-reduction" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Montant Encaissé</label>
                        <input type="text" class="form-control" id="detail-encaisser" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
    $(document).ready(function() {
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

        // Gestion du modal de détail
        $('.detail-mouvement').on('click', function() {
            const patient = $(this).data('patient');
            const date = $(this).data('date');
            const recus = $(this).data('recus');
            const total = $(this).data('total'); // J'ai changé 'montant' en 'total'
            const reduction = $(this).data('reduction');
            const ticket = $(this).data('ticket');
            const encaisser = $(this).data('encaisser');
            const prestations = $(this).data('prestations');
            const caissier = $(this).data('caissier');

            $('#detail-patient').val(patient);
            $('#detail-date').val(date);
            $('#detail-recus').val(recus);
            $('#detail-montant').val(total); // J'utilise 'total' ici
            $('#detail-reduction').val(reduction);
            $('#detail-ticket').val(ticket);
            $('#detail-caissier').val(caissier);
            $('#detail-encaisser').val(encaisser);

            // Remplir le tableau des prestations
            let prestationsHtml = '';
            prestations.forEach(prestation => {
                prestationsHtml += `
                    <tr>
                        <td>${prestation.libelle}</td>
                        <td>${prestation.quantite}</td>
                        <td>${prestation.montant}</td>
                        <td>${prestation.total} </td>
                    </tr>
                `;
            });
            $('#detail-prestations').html(prestationsHtml);
        });
    });
</script>
@endpush