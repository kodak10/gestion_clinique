@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Factures Non Soldées</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="factures-table" class="table table-vcenter table-mobile-md card-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>N° Reçu/Dossier</th>
                                <th>Patient</th>
                                <th>Téléphone</th>
                                <th>Montant Restant</th>
                                <th>Date</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($consultations as $consultation)
                            <tr>
                                <td><span class="badge bg-blue-lt">Consultation</span></td>
                                <td><span class="text-primary">{{ $consultation->numero_recu }}</span></td>
                                <td>{{ $consultation->patient->nom }} {{ $consultation->patient->prenoms }}</td>
                                <td>
                                    @if($consultation->patient->telephone)
                                        <a href="tel:{{ $consultation->patient->telephone }}">{{ $consultation->patient->telephone }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td><span class="text-danger fw-bold">{{ number_format($consultation->reste_a_payer, 0, ',', ' ') }} FCFA</span></td>
                                <td></td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <button class="btn btn-sm btn-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#details-modal"
                                            data-url="{{ route('reglements.details', ['type' => 'consultation', 'id' => $consultation->id]) }}">
                                            Détails
                                        </button>
                                        <button class="btn btn-sm btn-success" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#paiement-modal"
                                            data-type="consultation"
                                            data-id="{{ $consultation->id }}"
                                            data-montant="{{ $consultation->reste_a_payer }}">
                                            Payer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                            @foreach($hospitalisations as $hospitalisation)
                            <tr>
                                <td><span class="badge bg-orange-lt">Hospitalisation</span></td>
                                <td><span class="text-primary">{{ $hospitalisation->patient->numero_dossier }}</span></td>
                                <td>{{ $hospitalisation->patient->nom }} {{ $hospitalisation->patient->prenoms }}</td>
                                <td>
                                    @if($hospitalisation->patient->telephone)
                                        <a href="tel:{{ $hospitalisation->patient->telephone }}">{{ $hospitalisation->patient->telephone }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td><span class="text-danger fw-bold">{{ number_format($hospitalisation->reste_a_payer, 0, ',', ' ') }} FCFA</span></td>
                                <td></td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <button class="btn btn-sm btn-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#details-modal"
                                            data-url="{{ route('reglements.details', ['type' => 'hospitalisation', 'id' => $hospitalisation->id]) }}">
                                            Détails
                                        </button>
                                        <button class="btn btn-sm btn-success" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#paiement-modal"
                                            data-type="hospitalisation"
                                            data-id="{{ $hospitalisation->id }}"
                                            data-montant="{{ $hospitalisation->reste_a_payer }}">
                                            Payer
                                        </button>
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

<!-- Modal pour les détails -->
<div class="modal modal-blur fade" id="details-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Le contenu sera chargé via JavaScript -->
                <div class="text-center py-5">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour le paiement -->
<div class="modal modal-blur fade" id="paiement-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Effectuer un Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('reglements.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="type" id="paiement-type">
                    <input type="hidden" name="id" id="paiement-id">
                    
                    <div class="mb-3">
                        <label class="form-label">Montant à payer</label>
                        <input type="text" class="form-control" id="montant-restant" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Montant payé</label>
                        <input type="number" class="form-control" name="montant" id="montant-paye" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Méthode de paiement</label>
                        <select class="form-select" name="methode_paiement" required>
                            <option value="cash">Cash</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="virement">Virement</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Enregistrer le paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
$(document).ready(function() {
    // Gestion du modal de détails
    $('#details-modal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const url = button.data('url');
        const modal = $(this);
        
        modal.find('.modal-body').load(url, function() {
            // Contenu chargé
        });
    });

    // Gestion du modal de paiement
    $('#paiement-modal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const type = button.data('type');
        const id = button.data('id');
        const montant = button.data('montant');
        const modal = $(this);
        
        modal.find('#paiement-type').val(type);
        modal.find('#paiement-id').val(id);
        modal.find('#montant-restant').val(formatMoney(montant));
        modal.find('#montant-paye').val(montant);
    });

    // Fonction de formatage monétaire
    function formatMoney(amount) {
        return new Intl.NumberFormat('fr-FR', { 
            style: 'currency', 
            currency: 'XOF',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }
});
</script>
@endpush

@push('styles')
<style>
    .table-responsive {
        padding: 20px 0;
    }
    .btn-list {
        display: flex;
        gap: 5px;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.765625rem;
    }
    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush