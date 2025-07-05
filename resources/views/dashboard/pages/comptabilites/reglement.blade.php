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
                    <table class="table" id="table-default">
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
                                    @if($consultation->patient->contact_patient)
                                        <a href="tel:{{ $consultation->patient->contact_patient }}">{{ $consultation->patient->contact_patient }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td><span class="text-danger fw-bold">{{ number_format($consultation->reste_a_payer, 0, ',', ' ') }} FCFA</span></td>
                                <td>{{ $consultation->created_at }}</td>
                                
                              
                                {{-- <td>
                                    <div class="btn-list flex-nowrap">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                @if($consultation->pdf_path)
                                                    <a class="dropdown-item" href="{{ Storage::url($consultation->pdf_path) }}" target="_blank">
                                                        Réimprimer le reçu
                                                    </a>
                                                @endif
                                                
                                                <!-- Bouton Détails -->
                                                <button class="dropdown-item" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#details-modal"
                                                    data-url="{{ route('reglements.details', [
                                                        'type' => $reglement->consultation_id ? 'consultation' : 'hospitalisation',
                                                        'id' => $reglement->consultation_id ?? $reglement->hospitalisation_id
                                                    ]) }}">
                                                    Détails
                                                </button>

                                                <!-- Bouton Payer (si reste à payer) -->
                                                @if(($reglement->consultation && $reglement->consultation->reste_a_payer > 0) || 
                                                    ($reglement->hospitalisation && $reglement->hospitalisation->reste_a_payer > 0))
                                                    <button class="dropdown-item" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#paiement-modal"
                                                        data-type="{{ $reglement->consultation_id ? 'consultation' : 'hospitalisation' }}"
                                                        data-id="{{ $reglement->consultation_id ?? $reglement->hospitalisation_id }}"
                                                        data-montant="{{ $reglement->consultation->reste_a_payer ?? $reglement->hospitalisation->reste_a_payer }}">
                                                        Payer
                                                    </button>
                                                @endif

                                               
                                            </div>
                                        </div>
                                    </div>
                                </td> --}}
                                 <td>
                                    <div class="btn-list flex-nowrap">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                @if($consultation->pdf_path)
                                                    <a class="dropdown-item" href="{{ Storage::url($consultation->pdf_path) }}" target="_blank">
                                                        Réimprimer le reçu
                                                    </a>
                                                @endif

                                                <button class="dropdown-item detail-mouvement"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modal-detail"
                                                    data-patient="{{ $consultation->patient->nom }} {{ $consultation->patient->prenoms }}"
                                                    data-date="{{ $consultation->created_at->format('d/m/Y H:i') }}"
                                                    data-recus="{{ $consultation->numero_recu }}"
                                                    data-total="{{ number_format($consultation->total, 0, ',', ' ') }}"
                                                    data-reduction="{{ number_format($consultation->reduction, 0, ',', ' ') }}"
                                                    data-ticket="{{ number_format($consultation->ticket_moderateur, 0, ',', ' ') }}"
                                                    data-encaisser="{{ number_format($consultation->reglements->montant, 0, ',', ' ') }}"
                                                    data-prestations="{{ json_encode($consultation->prestations->map(function($item) {
                                                        return [
                                                            'libelle' => $item->libelle,
                                                            'quantite' => $item->pivot->quantite,
                                                            'montant' => number_format($item->pivot->montant, 0, ',', ' '),
                                                            'total' => number_format($item->pivot->total, 0, ',', ' ')
                                                        ];
                                                    })) }}"
                                                    data-caissier="{{ $consultation->user->name }}">
                                                    Détails
                                                </button>

                                                @if($consultation->reste_a_payer > 0)
                                                    <button class="dropdown-item"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#paiement-modal"
                                                        data-type="consultation"
                                                        data-id="{{ $consultation->id }}"
                                                        data-montant="{{ $consultation->reste_a_payer }}">
                                                        Payer
                                                    </button>
                                                @endif

                                               
                                            </div>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                            @endforeach

                            @foreach($hospitalisations as $hospitalisation)
                            <tr>
                                <td><span class="badge bg-orange-lt">Hospitalisation</span></td>
                                <td><span class="text-primary">{{ $hospitalisation->patient->num_dossier }}</span></td>
                                <td>{{ $hospitalisation->patient->nom }} {{ $hospitalisation->patient->prenoms }}</td>
                                <td>
                                    @if($hospitalisation->patient->telephone)
                                        <a href="tel:{{ $hospitalisation->patient->telephone }}">{{ $hospitalisation->patient->telephone }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td><span class="text-danger fw-bold">{{ number_format($hospitalisation->reste_a_payer, 0, ',', ' ') }} FCFA</span></td>
                                <td>{{ $hospitalisation->created_at }}</td>
                                {{-- <td>
                                    <div class="btn-list flex-nowrap">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                @if($reglement->consultation && $reglement->consultation->pdf_path)
                                                    <a class="dropdown-item" href="{{ Storage::url($reglement->consultation->pdf_path) }}" target="_blank">
                                                        Réimprimer le reçu
                                                    </a>
                                                @endif
                                                
                                                <!-- Bouton Détails -->
                                                <button class="dropdown-item detail-mouvement" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modal-detail"
                                                    data-patient="{{ $reglement->consultation->patient->nom ?? $reglement->hospitalisation->patient->nom }} {{ $reglement->consultation->patient->prenoms ?? $reglement->hospitalisation->patient->prenoms }}"
                                                    data-date="{{ $reglement->created_at->format('d/m/Y H:i') }}"
                                                    data-recus="{{ $reglement->consultation->numero_recu ?? 'HOSP-'.$reglement->hospitalisation->id }}"
                                                    data-total="{{ number_format($reglement->consultation->total ?? $reglement->hospitalisation->total, 0, ',', ' ') }}"
                                                    data-reduction="{{ number_format($reglement->consultation->reduction ?? $reglement->hospitalisation->reduction, 0, ',', ' ') }}"
                                                    data-ticket="{{ number_format($reglement->consultation->ticket_moderateur ?? $reglement->hospitalisation->ticket_moderateur, 0, ',', ' ') }}"
                                                    data-encaisser="{{ number_format($reglement->montant, 0, ',', ' ') }}"
                                                    data-prestations="{{ json_encode($reglement->consultation ? $reglement->consultation->prestations->map(function($item) {
                                                        return [
                                                            'libelle' => $item->libelle,
                                                            'quantite' => $item->pivot->quantite,
                                                            'montant' => number_format($item->pivot->montant, 0, ',', ' '),
                                                            'total' => number_format($item->pivot->total, 0, ',', ' ')
                                                        ];
                                                    }) : []) }}"
                                                    data-caissier="{{ $reglement->user->name }}">
                                                    Détails
                                                </button>

                                                <!-- Bouton Payer (si reste à payer) -->
                                                @if(($reglement->consultation && $reglement->consultation->reste_a_payer > 0) || 
                                                    ($reglement->hospitalisation && $reglement->hospitalisation->reste_a_payer > 0))
                                                    <button class="dropdown-item" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#paiement-modal"
                                                        data-type="{{ $reglement->consultation_id ? 'consultation' : 'hospitalisation' }}"
                                                        data-id="{{ $reglement->consultation_id ?? $reglement->hospitalisation_id }}"
                                                        data-montant="{{ $reglement->consultation->reste_a_payer ?? $reglement->hospitalisation->reste_a_payer }}">
                                                        Payer
                                                    </button>
                                                @endif

                                               
                                            </div>
                                        </div>
                                    </div>
                                </td> --}}

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Mouvement -->
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    // Gestion du modal de détail
    $('.detail-mouvement').on('click', function() {
        const patient = $(this).data('patient');
        const date = $(this).data('date');
        const recus = $(this).data('recus');
        const total = $(this).data('total');
        const reduction = $(this).data('reduction');
        const ticket = $(this).data('ticket');
        const encaisser = $(this).data('encaisser');
        const prestations = $(this).data('prestations');
        const caissier = $(this).data('caissier');

        $('#detail-patient').val(patient);
        $('#detail-date').val(date);
        $('#detail-recus').val(recus);
        $('#detail-montant').val(total + ' FCFA');
        $('#detail-reduction').val(reduction + ' FCFA');
        $('#detail-ticket').val(ticket + ' FCFA');
        $('#detail-caissier').val(caissier);
        $('#detail-encaisser').val(encaisser + ' FCFA');

        // Remplir le tableau des prestations
        let prestationsHtml = '';
        if (prestations && prestations.length > 0) {
            prestations.forEach(prestation => {
                prestationsHtml += `
                    <tr>
                        <td>${prestation.libelle}</td>
                        <td>${prestation.quantite}</td>
                        <td>${prestation.montant} FCFA</td>
                        <td>${prestation.total} FCFA</td>
                    </tr>
                `;
            });
        } else {
            prestationsHtml = '<tr><td colspan="4">Aucune prestation trouvée</td></tr>';
        }
        $('#detail-prestations').html(prestationsHtml);
    });
});
</script>

<script>
    $(document).ready(function() {
        $('#table-default').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/French.json"
            },
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "responsive": true,
            "order": [[4, 'desc']] // Tri par montant restant
        });

    });
</script>

 @if(session('pdf_url'))
    <script>
        window.onload = function() {
            window.open('{{ session('pdf_url') }}', '_blank');
        };
    </script>
    @endif
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