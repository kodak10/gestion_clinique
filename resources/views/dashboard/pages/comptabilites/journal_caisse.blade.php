@extends('dashboard.layouts.master')
@section('content')
       
        <!-- BEGIN PAGE BODY -->
        <div class="page-body">
            <div class="container-xl">
                <div class="col-lg-12 mb-5">
                    <div class="row row-cards">
                        <div class="col-12">
                            <form class="card">
                                <div class="card-body">
                                    
                                    <div class="row row-cards">
                                        <div class="col-sm-6 col-md-3">
                                            <div class="mb-3">
                                            <label class="form-label">Caissière</label>
                                            <select type="text" class="form-select" id="select-optgroups" value="">
                                                <option value="HTML">Toutes les caissières</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" @if(auth()->id() == $user->id) selected @endif>{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            <div class="mb-3">
                                            <label class="form-label">Période de début</label>
                                            <div class="input-icon mb-2">
                                                <input class="form-control" placeholder="Select a date" id="date_debut" value="{{ date('Y-m-d', strtotime('-1 day')) }}">
                                                <span class="input-icon-addon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                    <path d="M16 3v4"></path>
                                                    <path d="M8 3v4"></path>
                                                    <path d="M4 11h16"></path>
                                                    <path d="M11 15h1"></path>
                                                    <path d="M12 15v3"></path>
                                                </svg>
                                                </span>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            <div class="mb-3">
                                            <label class="form-label">Période de fin</label>
                                            <div class="input-icon mb-2">
                                                <input class="form-control" placeholder="Select a date" id="date_fin" value="{{ date('Y-m-d') }}">
                                                <span class="input-icon-addon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                    <path d="M16 3v4"></path>
                                                    <path d="M8 3v4"></path>
                                                    <path d="M4 11h16"></path>
                                                    <path d="M11 15h1"></path>
                                                    <path d="M12 15v3"></path>
                                                </svg>
                                                </span>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-2">
                                            <div class="mb-3">
                                            <label class="form-label">Type</label>
                                            <select type="text" class="form-select" id="select-type" value="">
                                                <option value="all" selected>Tous</option>
                                                <option value="entrée">Entrée</option>
                                                <option value="sortie">Sortir</option>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="mb-3">
                                            <label class="form-label">.</label>
                                            <button type="submit" class="btn btn-primary">Valider</button>

                                            </div>

                                        </div>
                                        
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row row-cards">
                    <div class="col-sm-6 col-lg-4">
                        <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2"></path>
                                    <path d="M12 3v3m0 12v3"></path>
                                </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">Entrées</div>
                                <div class="text-secondary">{{ number_format($totalEntrees, 0, ',', ' ') }} FCFA</div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-green text-white avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                    <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                    <path d="M17 17h-11v-14h-2"></path>
                                    <path d="M6 5l14 1l-1 7h-13"></path>
                                </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">Sorties</div>
                                <div class="text-secondary">{{ number_format($totalSorties, 0, ',', ' ') }} FCFA</div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-x text-white avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path d="M4 4l11.733 16h4.267l-11.733 -16z"></path>
                                    <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772"></path>
                                </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">Soldes</div>
                                <div class="text-secondary">{{ number_format($totalEntrees - $totalSorties, 0, ',', ' ') }} FCFA</div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    
                    </div>
                </div>

                <div class="col-12 mt-5">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Mouvements</h3>
                  </div>
                  
                  <div class="table-responsive">
                     <div id="table-default" class="table-responsive">
                  <table class="table" id="table">
                        <thead>
                            <tr>
                                <th class="w-1"></th>
                                <th>Date & Heure</th>
                                <th>Numéro de reçu </th>
                                <th>Nom & Prénoms </th>
                                <th>Montant </th>
                                <th>Encaisser Par</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reglements as $reglement)
                                <tr>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ Storage::url($reglement->consultation->pdf_path ?? '') }}" target="_blank">Réimprimer le reçu</a>
                                                    <a class="dropdown-item detail-mouvement" href="#" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modal-detail"
                                                        data-patient="{{ $reglement->consultation->patient->nom ?? $reglement->hospitalisation->patient->nom }} {{ $reglement->consultation->patient->prenoms ?? $reglement->hospitalisation->patient->prenoms }}"
                                                        data-date="{{ $reglement->created_at->format('d/m/Y H:i') }}"
                                                        data-recus="{{ $reglement->consultation->numero_recu ?? '' }}"
                                                        data-total="{{ number_format($reglement->consultation->total ?? $reglement->hospitalisation->total, 0, ',', ' ') }}"
                                                        data-reduction="{{ number_format($reglement->consultation->reduction ?? $reglement->hospitalisation->reduction, 0, ',', ' ') }}"
                                                        data-ticket="{{ number_format($reglement->consultation->ticket_moderateur ?? $reglement->hospitalisation->ticket_moderateur, 0, ',', ' ') }}"
                                                        data-encaisser="{{ number_format($reglement->consultation->montant_paye ?? $reglement->hospitalisation->montant_paye, 0, ',', ' ') }}"
                                                        data-prestations="{{ json_encode($reglement->consultation->details->map(function($item) {
                                                            return [
                                                                'libelle' => $item->prestation->libelle,
                                                                'quantite' => $item->quantite,
                                                                'montant' => number_format($item->montant, 0, ',', ' '),
                                                                'total' => number_format($item->total, 0, ',', ' ')
                                                            ];
                                                        })) }}"
                                                        data-caissier="{{ $reglement->user->name }}">
                                                        Détail du mouvement
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $reglement->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $reglement->consultation->numero_recu }}</td>
                                    <td>{{ $reglement->consultation->patient->nom }} {{ $reglement->consultation->patient->prenoms }}</td>
                                    <td>{{ number_format($reglement->montant, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ $reglement->user->name }}</td>
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
                        <label class="form-label">Montant Encaisser</label>
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
    <!-- JS DataTables -->
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