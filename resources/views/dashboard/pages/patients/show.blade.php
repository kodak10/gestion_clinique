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
                                    @foreach($patient->consultations as $consultation)
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
data-encaisser="{{ number_format(collect($consultation->reglements)->sum('montant'), 0, ',', ' ') }}"
                                                            data-prestations="{{ json_encode($consultation->details->map(function($item) {
                                                                return [
                                                                    'libelle' => $item->prestation->libelle,
                                                                    'quantite' => $item->quantite,
                                                                    'montant' => number_format($item->montant, 0, ',', ' '),
                                                                    'total' => number_format($item->total, 0, ',', ' ')
                                                                ];
                                                            })) }}"
                                                            data-caissier="{{ optional(optional(collect($consultation->reglements)->first())->user)->name ?? 'N/A' }}">
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
                                            @if(is_numeric($consultation->reglements->montant ?? null))
                                                {{ number_format($consultation->reglements->montant, 0, ',', ' ') }} FCFA
                                            @else
                                                N/A
                                            @endif
                                        </td>


                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane" id="hospitalisations">
                        <div class="d-flex justify-content-between mb-3">
                            <h4>Historique des Hospitalisations</h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter" id="hospitalisations-table">
                                <thead>
                                    <tr>
                                        <th class="w-1">Actions</th>
                                        <th>Date admission</th>
                                        <th>Date sortie</th>
                                        <th>Médecin</th>
                                        <th>Total</th>
                                        <th>Payé</th>
                                        <th>Reste</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patient->hospitalisations as $hospitalisation)
                                    <tr>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item detail-mouvement" href="#" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#modal-detail"
                                                            
                                                            data-patient="{{ $hospitalisation->patient->nom }} {{ $hospitalisation->patient->prenoms }}"
                                                            data-date="{{ $hospitalisation->date_entree->format('d/m/Y H:i') }}"
                                                            data-recus="{{ $hospitalisation->patient->num_dossier }}"
                                                            data-total="{{ number_format($hospitalisation->total, 0, ',', ' ') }}"
                                                            data-reduction="{{ number_format($hospitalisation->reduction, 0, ',', ' ') }}"
                                                            data-ticket="{{ number_format($hospitalisation->ticket_moderateur, 0, ',', ' ') }}"
                                                            data-encaisser="{{ number_format($hospitalisation->total - $hospitalisation->reste_a_payer, 0, ',', ' ') }}"
                                                            data-prestations="{{ json_encode($hospitalisation->details->map(function($item) {
                                                                return [
                                                                    'libelle' => $item->fraisHospitalisation->libelle,
                                                                    'quantite' => $item->quantite,
                                                                    'montant' => number_format($item->prix_unitaire, 0, ',', ' '),
                                                                    'total' => number_format($item->total, 0, ',', ' ')
                                                                ];
                                                            })) }}"
                                                            data-caissier="{{ $hospitalisation->user->name ?? 'N/A' }}"

                                                            data-hospitalisation="{{ json_encode([
                                                                'patient_nom' => $hospitalisation->patient->nom,
                                                                'patient_prenoms' => $hospitalisation->patient->prenoms,
                                                                'date_entree' => $hospitalisation->date_entree->format('d/m/Y H:i'),
                                                                'date_sortie' => $hospitalisation->date_sortie ? $hospitalisation->date_sortie->format('d/m/Y H:i') : null,
                                                                'medecin' => $hospitalisation->medecin->nom_complet ?? 'Non spécifié',
                                                                'total' => $hospitalisation->total,
                                                                'ticket_moderateur' => $hospitalisation->ticket_moderateur,
                                                                'reduction' => $hospitalisation->reduction,
                                                                'reste_a_payer' => $hospitalisation->reste_a_payer,
                                                                'user' => $hospitalisation->user->name
                                                            ]) }}"
                                                            data-details="{{ json_encode($hospitalisation->details->map(function($item) {
                                                                return [
                                                                    'libelle' => $item->fraisHospitalisation->libelle,
                                                                    'quantite' => $item->quantite,
                                                                    'prix' => $item->prix_unitaire,
                                                                    'total' => $item->total
                                                                ];
                                                            })) }}"
                                                            data-medicaments="{{ json_encode($hospitalisation->medicaments->map(function($item) {
                                                                return [
                                                                    'nom' => $item->nom,
                                                                    'quantite' => $item->pivot->quantite,
                                                                    'prix' => $item->pivot->prix_unitaire,
                                                                    'total' => $item->pivot->total
                                                                ];
                                                            })) }}"
                                                            data-examens="{{ json_encode($hospitalisation->examens->map(function($item) {
                                                                return [
                                                                    'nom' => $item->nom,
                                                                    'resultat' => $item->pivot->resultat ?? 'Non disponible'
                                                                ];
                                                            })) }}">
                                                            Détails
                                                        </a>
                                                        <a class="dropdown-item" href="{{ route('hospitalisations.edit', $hospitalisation->id) }}">Modifier</a>
                                                        @if($hospitalisation->pdf_path)
                                                        <a class="dropdown-item" href="{{ Storage::url($hospitalisation->pdf_path) }}" target="_blank">
                                                            Imprimer le dossier
                                                        </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $hospitalisation->date_entree->format('d/m/Y H:i') }}</td>
                                        <td>{{ $hospitalisation->date_sortie ? $hospitalisation->date_sortie->format('d/m/Y H:i') : 'En cours' }}</td>
                                        <td>{{ $hospitalisation->medecin->nom_complet ?? 'Non spécifié' }}</td>
                                        <td>{{ number_format($hospitalisation->total, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ number_format($hospitalisation->total - $hospitalisation->reste_a_payer, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ number_format($hospitalisation->reste_a_payer, 0, ',', ' ') }} FCFA</td>
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

<!-- Modal Détails Hospitalisation -->
<div class="modal modal-blur fade" id="modal-hospitalisation-detail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de l'hospitalisation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Date admission</label>
                        <input type="text" class="form-control" id="hosp-date-entree" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date sortie</label>
                        <input type="text" class="form-control" id="hosp-date-sortie" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Médecin traitant</label>
                        <input type="text" class="form-control" id="hosp-medecin" readonly>
                    </div>
                </div>
                
                <!-- Frais d'hospitalisation -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Frais d'hospitalisation</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Libellé</th>
                                    <th>Quantité</th>
                                    <th>Prix unitaire</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="hosp-details">
                                <!-- Les frais seront ajoutés ici par JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Médicaments -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Médicaments</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Médicament</th>
                                    <th>Quantité</th>
                                    <th>Prix unitaire</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="hosp-medicaments">
                                <!-- Les médicaments seront ajoutés ici par JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Examens -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Examens</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Examen</th>
                                    <th>Résultat</th>
                                </tr>
                            </thead>
                            <tbody id="hosp-examens">
                                <!-- Les examens seront ajoutés ici par JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-3">
                        <label class="form-label">Total</label>
                        <input type="text" class="form-control" id="hosp-total" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ticket modérateur</label>
                        <input type="text" class="form-control" id="hosp-ticket" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Réduction</label>
                        <input type="text" class="form-control" id="hosp-reduction" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Reste à payer</label>
                        <input type="text" class="form-control" id="hosp-reste" readonly>
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
        // Gestion du modal de détail
        $('.detail-mouvement').on('click', function() {
            const patient = $(this).data('patient');
            const date = $(this).data('date');
            const recus = $(this).data('recus');
            const total = $(this).data('total');
            const reduction = $(this).data('reduction');
            const ticket = $(this).data('ticket');
            const encaisser = $(this).data('encaisser');
            const caissier = $(this).data('caissier');
            
            // Récupérer les prestations en tant qu'objet JSON
            const prestationsStr = $(this).data('prestations');
            let prestations = [];
            
            try {
                prestations = typeof prestationsStr === 'string' ? JSON.parse(prestationsStr) : prestationsStr;
            } catch (e) {
                console.error('Error parsing prestations:', e);
            }

            $('#detail-patient').val(patient);
            $('#detail-date').val(date);
            $('#detail-recus').val(recus);
            $('#detail-montant').val(total);
            $('#detail-reduction').val(reduction);
            $('#detail-ticket').val(ticket);
            $('#detail-caissier').val(caissier);
            $('#detail-encaisser').val(encaisser);

            // Remplir le tableau des prestations
            let prestationsHtml = '';
            if (Array.isArray(prestations)) {
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
        // Gestion du modal de détail d'hospitalisation
        $('.detail-hospitalisation').on('click', function() {
            const hosp = JSON.parse($(this).data('hospitalisation'));
            const details = JSON.parse($(this).data('details'));
            const medicaments = JSON.parse($(this).data('medicaments'));
            const examens = JSON.parse($(this).data('examens'));

            // Formatage des nombres
            const formatNumber = (num) => {
                return new Intl.NumberFormat('fr-FR').format(num);
            };

            // Remplir les informations de base
            $('#hosp-date-entree').val(hosp.date_entree);
            $('#hosp-date-sortie').val(hosp.date_sortie || 'En cours');
            $('#hosp-medecin').val(hosp.medecin);
            $('#hosp-total').val(formatNumber(hosp.total) + ' FCFA');
            $('#hosp-ticket').val(formatNumber(hosp.ticket_moderateur) + ' FCFA');
            $('#hosp-reduction').val(formatNumber(hosp.reduction) + ' FCFA');
            $('#hosp-reste').val(formatNumber(hosp.reste_a_payer) + ' FCFA');

            // Remplir les frais d'hospitalisation
            let detailsHtml = '';
            details.forEach(item => {
                detailsHtml += `
                    <tr>
                        <td>${item.libelle}</td>
                        <td>${item.quantite}</td>
                        <td>${formatNumber(item.prix)} FCFA</td>
                        <td>${formatNumber(item.total)} FCFA</td>
                    </tr>
                `;
            });
            $('#hosp-details').html(detailsHtml);

            // Remplir les médicaments
            let medsHtml = '';
            medicaments.forEach(med => {
                medsHtml += `
                    <tr>
                        <td>${med.nom}</td>
                        <td>${med.quantite}</td>
                        <td>${formatNumber(med.prix)} FCFA</td>
                        <td>${formatNumber(med.total)} FCFA</td>
                    </tr>
                `;
            });
            $('#hosp-medicaments').html(medsHtml);

            // Remplir les examens
            let examsHtml = '';
            examens.forEach(exam => {
                examsHtml += `
                    <tr>
                        <td>${exam.nom}</td>
                        <td>${exam.resultat}</td>
                    </tr>
                `;
            });
            $('#hosp-examens').html(examsHtml);
        });
    });
</script>
@endpush