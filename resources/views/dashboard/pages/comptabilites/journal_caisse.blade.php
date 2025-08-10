@extends('dashboard.layouts.master')
@section('content')
       
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="page-title m-0">Journal de Caisse</h2>
            <a href="{{ route('caisse.print') }}?{{ http_build_query(request()->query()) }}" 
               class="btn bg-gray-500" 
               target="_blank">
               Imprimer
            </a>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="col-lg-12 mb-5">
            <div class="row row-cards">
                <div class="col-12">
                    <form class="card" method="GET" action="{{ route('comptabilite.journalcaisse') }}">
                        <div class="card-body">
                            <div class="row row-cards">
                                <div class="col-sm-6 col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Caissière</label>
                                        @php
                                            $readonly = Auth::user()->hasAnyRole(['Caissière']);
                                        @endphp

                                        <select name="user_id" class="form-select" id="select-optgroups" {{ $readonly ? 'disabled' : '' }}>
                                            <option value="">Toutes les caissières</option>
                                            @foreach($users as $userOption)
                                                <option value="{{ $userOption->id }}" 
                                                    @if(request('user_id', Auth::id()) == $userOption->id) selected @endif>
                                                    {{ $userOption->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @if($readonly)
                                            <!-- Champ caché pour transmettre la valeur de l'utilisateur connecté si champ désactivé -->
                                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                        @endif

                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Période de début</label>
                                        <div class="input-icon mb-2">
                                            <input type="date" name="date_debut" class="form-control" placeholder="Select a date" 
                                                id="date_debut" value="{{ request('date_debut', date('Y-m-d', strtotime('-1 day'))) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Période de fin</label>
                                        <div class="input-icon mb-2">
                                            <input type="date" name="date_fin" class="form-control" placeholder="Select a date" 
                                                id="date_fin" value="{{ request('date_fin', date('Y-m-d')) }}">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">Type</label>
                                        <select name="type" class="form-select" id="select-type">
                                            <option value="all" @if(request('type') == 'all') selected @endif>Tous</option>
                                            <option value="entrée" @if(request('type') == 'entrée') selected @endif>Entrée</option>
                                            <option value="sortie" @if(request('type') == 'sortie') selected @endif>Sortir</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="mb-3">
                                        <label class="form-label">.</label>
                                        <button type="submit" class="btn btn-primary">Filtrer</button>
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
                    <table class="table" id="table" style="min-height: 200px">
                        <thead>
                            <tr>
                                <th class="w-1"></th>
                                <th>Date & Heure</th>
                                <th>Numéro de reçu</th>
                                <th>Nom & Prénoms</th>
                                <th>Montant</th>
                                <th>Utilisateur</th>
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
                                                    @if($reglement->consultation && $reglement->consultation->pdf_path)
                                                        <a class="dropdown-item" href="{{ Storage::url($reglement->consultation->pdf_path) }}" target="_blank">
                                                            Réimprimer le reçu d'Acte Ambulatoire
                                                        </a>
                                                    @elseif($reglement->hospitalisation && $reglement->hospitalisation->facture_path)
                                                        <a class="dropdown-item" href="{{ Storage::url($reglement->hospitalisation->facture_path) }}" target="_blank">
                                                            Réimprimer le reçu hospitalisation
                                                        </a>
                                                    @endif
                                                    
                                                    {{-- <a class="dropdown-item detail-mouvement" href="#" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="@if($reglement->consultation) #modal-detail @else #modal-hospitalisation-detail @endif"
                                                        data-type="{{ $reglement->consultation ? 'consultation' : 'hospitalisation' }}"
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
                                                        data-hospitalisation="{{ json_encode($reglement->hospitalisation ? [
                                                            'date_entree' => $reglement->hospitalisation->date_entree->format('d/m/Y'),
                                                            'date_sortie' => $reglement->hospitalisation->date_sortie ? $reglement->hospitalisation->date_sortie->format('d/m/Y') : 'En cours',
                                                            'medecin' => $reglement->hospitalisation->medecin->nom_complet ?? 'Non spécifié',
                                                            'details' => $reglement->hospitalisation->details->map(function($item) {
                                                                return [
                                                                    'libelle' => $item->frais->libelle,
                                                                    'quantite' => $item->quantite,
                                                                    'prix' => number_format($item->prix_unitaire, 0, ',', ' '),
                                                                    'total' => number_format($item->total, 0, ',', ' ')
                                                                ];
                                                            }),
                                                            'medicaments' => $reglement->hospitalisation->medicaments->map(function($item) {
                                                                return [
                                                                    'libelle' => $item->nom,
                                                                    'quantite' => $item->pivot->quantite,
                                                                    'prix' => number_format($item->pivot->prix_unitaire, 0, ',', ' '),
                                                                    'total' => number_format($item->pivot->total, 0, ',', ' ')
                                                                ];
                                                            }),
                                                            'reste' => number_format($reglement->hospitalisation->reste_a_payer, 0, ',', ' ')
                                                        ] : null) }}"
                                                        data-caissier="{{ $reglement->user->name }}">
                                                        Détail du mouvement
                                                    </a> --}}
                                                    <a class="dropdown-item detail-mouvement" href="#" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="@if($reglement->consultation) #modal-detail @elseif($reglement->hospitalisation) #modal-hospitalisation-detail @else #modal-depense-detail @endif"
                                                        data-type="{{ $reglement->consultation ? 'consultation' : ($reglement->hospitalisation ? 'hospitalisation' : 'depense') }}"
                                                        data-patient="@if($reglement->consultation) {{ $reglement->consultation->patient->nom }} {{ $reglement->consultation->patient->prenoms }} @elseif($reglement->hospitalisation) {{ $reglement->hospitalisation->patient->nom }} {{ $reglement->hospitalisation->patient->prenoms }} @else {{ $reglement->depense->libelle ?? 'Dépense' }} @endif"
                                                        data-date="{{ $reglement->created_at->format('d/m/Y H:i') }}"
                                                        data-recus="@if($reglement->consultation) {{ $reglement->consultation->numero_recu }} @elseif($reglement->hospitalisation) HOSP-{{ $reglement->hospitalisation->id }} @else {{ $reglement->numero_recu }} @endif"
                                                        data-total="{{ number_format($reglement->consultation->total ?? ($reglement->hospitalisation->total ?? $reglement->montant), 0, ',', ' ') }}"
                                                        data-reduction="{{ number_format($reglement->consultation->reduction ?? ($reglement->hospitalisation->reduction ?? 0), 0, ',', ' ') }}"
                                                        data-ticket="{{ number_format($reglement->consultation->ticket_moderateur ?? ($reglement->hospitalisation->ticket_moderateur ?? 0), 0, ',', ' ') }}"
                                                        data-encaisser="{{ number_format($reglement->montant, 0, ',', ' ') }}"
                                                        data-caissier="{{ $reglement->user->name }}"
                                                        @if($reglement->depense)
                                                            data-categorie="{{ $reglement->depense->category->name ?? 'Non catégorisé' }}"
                                                            data-methode="{{ $reglement->methode_paiement }}"
                                                            data-description="{{ $reglement->depense->description }}"
                                                            data-cheque="{{ $reglement->depense->numero_cheque }}"
                                                        @endif>
                                                        Détail du mouvement
                                                    </a>
                                                    
                                                    @auth
                                                        @if(auth()->user()->hasAnyRole(['Admin', 'Développeur', 'Comptable', 'Respo Caissière']))
                                                            <form action="{{ route('reglements.destroy', $reglement->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce règlement?')">
                                                                    Supprimer
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $reglement->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($reglement->consultation)
                                            {{ $reglement->consultation->numero_recu }}
                                        @elseif($reglement->hospitalisation)
                                            HOSP-{{ $reglement->hospitalisation->id }}
                                        @else
                                            {{ $reglement->depense->numero_recu ?? 'N/A' }}
                                        @endif
                                    </td>
                                    {{-- <td>{{ $reglement->consultation->numero_recu ?? 'HOSP-'.$reglement->hospitalisation->id }}</td> --}}
                                    <td>
                                        @if($reglement->consultation)
                                            {{ $reglement->consultation->patient->nom }} {{ $reglement->consultation->patient->prenoms }}
                                        @elseif($reglement->hospitalisation)
                                            {{ $reglement->hospitalisation->patient->nom }} {{ $reglement->hospitalisation->patient->prenoms }}
                                        @else
                                           {{ $reglement->depense->libelle ?? 'N/A' }}
                                        @endif
                                    </td>
                                    {{-- <td>{{ $reglement->consultation->patient->nom ?? $reglement->hospitalisation->patient->nom }} {{ $reglement->consultation->patient->prenoms ?? $reglement->hospitalisation->patient->prenoms }}</td> --}}
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

<!-- Modal Détails Dépense -->
<div class="modal modal-blur fade" id="modal-depense-detail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de la dépense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Libellé</label>
                        <input type="text" class="form-control" id="depense-libelle" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date</label>
                        <input type="text" class="form-control" id="depense-date" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Numéro de reçu</label>
                        <input type="text" class="form-control" id="depense-numero" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Enregistré par</label>
                        <input type="text" class="form-control" id="depense-caissier" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Catégorie</label>
                        <input type="text" class="form-control" id="depense-categorie" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Méthode de paiement</label>
                        <input type="text" class="form-control" id="depense-methode" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="depense-description" rows="3" readonly></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Montant</label>
                        <input type="text" class="form-control" id="depense-montant" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Numéro de chèque</label>
                        <input type="text" class="form-control" id="depense-cheque" readonly>
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

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
    // Gestion des détails de mouvement
    document.querySelectorAll('.detail-mouvement').forEach(item => {
        item.addEventListener('click', function() {
            const type = this.getAttribute('data-type');
            const patient = this.getAttribute('data-patient');
            const date = this.getAttribute('data-date');
            const recus = this.getAttribute('data-recus');
            const total = this.getAttribute('data-total');
            const reduction = this.getAttribute('data-reduction');
            const ticket = this.getAttribute('data-ticket');
            const encaisser = this.getAttribute('data-encaisser');
            const caissier = this.getAttribute('data-caissier');
            
            if (type === 'consultation') {
                // Remplissage modal consultation
                document.getElementById('detail-patient').value = patient;
                document.getElementById('detail-date').value = date;
                document.getElementById('detail-recus').value = recus;
                document.getElementById('detail-caissier').value = caissier;
                document.getElementById('detail-montant').value = total + ' XOF';
                document.getElementById('detail-ticket').value = ticket + ' XOF';
                document.getElementById('detail-reduction').value = reduction + ' XOF';
                document.getElementById('detail-encaisser').value = encaisser + ' XOF';
                
                // Remplissage des prestations
                const prestations = JSON.parse(this.getAttribute('data-prestations'));
                const tbody = document.getElementById('detail-prestations');
                tbody.innerHTML = '';
                
                prestations.forEach(presta => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${presta.libelle}</td>
                        <td>${presta.quantite}</td>
                        <td>${presta.montant}</td>
                        <td>${presta.total} </td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                // Remplissage modal hospitalisation
                const hospData = JSON.parse(this.getAttribute('data-hospitalisation'));
                
                document.getElementById('hosp-date-entree').value = hospData.date_entree;
                document.getElementById('hosp-date-sortie').value = hospData.date_sortie;
                document.getElementById('hosp-medecin').value = hospData.medecin;
                document.getElementById('hosp-total').value = total + ' XOF';
                document.getElementById('hosp-ticket').value = ticket + ' XOF';
                document.getElementById('hosp-reduction').value = reduction + ' XOF';
                document.getElementById('hosp-reste').value = hospData.reste + ' XOF';
                
                // Remplissage des frais d'hospitalisation
                const detailsTbody = document.getElementById('hosp-details');
                detailsTbody.innerHTML = '';
                
                hospData.details.forEach(detail => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${detail.libelle}</td>
                        <td>${detail.quantite}</td>
                        <td>${detail.prix} </td>
                        <td>${detail.total} </td>
                    `;
                    detailsTbody.appendChild(tr);
                });
                
                // Remplissage des médicaments
                const medsTbody = document.getElementById('hosp-medicaments');
                medsTbody.innerHTML = '';
                
                hospData.medicaments.forEach(med => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${med.libelle}</td>
                        <td>${med.quantite}</td>
                        <td>${med.prix} </td>
                        <td>${med.total} </td>
                    `;
                    medsTbody.appendChild(tr);
                });
            }
        });
    });
});
</script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Gestion des détails de mouvement
    document.querySelectorAll('.detail-mouvement').forEach(item => {
        item.addEventListener('click', function() {
            const type = this.getAttribute('data-type');
            const patient = this.getAttribute('data-patient');
            const date = this.getAttribute('data-date');
            const recus = this.getAttribute('data-recus');
            const total = this.getAttribute('data-total');
            const reduction = this.getAttribute('data-reduction');
            const ticket = this.getAttribute('data-ticket');
            const encaisser = this.getAttribute('data-encaisser');
            const caissier = this.getAttribute('data-caissier');
            
            if (type === 'consultation') {
                // Remplissage modal consultation
                document.getElementById('detail-patient').value = patient;
                document.getElementById('detail-date').value = date;
                document.getElementById('detail-recus').value = recus;
                document.getElementById('detail-caissier').value = caissier;
                document.getElementById('detail-montant').value = total + ' XOF';
                document.getElementById('detail-ticket').value = ticket + ' XOF';
                document.getElementById('detail-reduction').value = reduction + ' XOF';
                document.getElementById('detail-encaisser').value = encaisser + ' XOF';
                
                // Remplissage des prestations
                const prestations = JSON.parse(this.getAttribute('data-prestations'));
                const tbody = document.getElementById('detail-prestations');
                tbody.innerHTML = '';
                
                prestations.forEach(presta => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${presta.libelle}</td>
                        <td>${presta.quantite}</td>
                        <td>${presta.montant}</td>
                        <td>${presta.total} </td>
                    `;
                    tbody.appendChild(tr);
                });
            } else if (type === 'hospitalisation') {
                // Remplissage modal hospitalisation
                const hospData = JSON.parse(this.getAttribute('data-hospitalisation'));
                
                document.getElementById('hosp-date-entree').value = hospData.date_entree;
                document.getElementById('hosp-date-sortie').value = hospData.date_sortie;
                document.getElementById('hosp-medecin').value = hospData.medecin;
                document.getElementById('hosp-total').value = total + ' XOF';
                document.getElementById('hosp-ticket').value = ticket + ' XOF';
                document.getElementById('hosp-reduction').value = reduction + ' XOF';
                document.getElementById('hosp-reste').value = hospData.reste + ' XOF';
                
                // Remplissage des frais d'hospitalisation
                const detailsTbody = document.getElementById('hosp-details');
                detailsTbody.innerHTML = '';
                
                hospData.details.forEach(detail => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${detail.libelle}</td>
                        <td>${detail.quantite}</td>
                        <td>${detail.prix} </td>
                        <td>${detail.total} </td>
                    `;
                    detailsTbody.appendChild(tr);
                });
            } else {
                // Remplissage modal dépense
                document.getElementById('depense-libelle').value = patient; // On réutilise patient pour le libellé
                document.getElementById('depense-date').value = date;
                document.getElementById('depense-numero').value = recus;
                document.getElementById('depense-caissier').value = caissier;
                document.getElementById('depense-montant').value = encaisser + ' XOF';
                
                // Pour les autres champs, vous devrez les ajouter dans vos data-attributes
                // Exemple:
                // document.getElementById('depense-categorie').value = this.getAttribute('data-categorie');
                // document.getElementById('depense-methode').value = this.getAttribute('data-methode');
                // document.getElementById('depense-description').value = this.getAttribute('data-description');
                // document.getElementById('depense-cheque').value = this.getAttribute('data-cheque');
            }
        });
    });
});
</script>
@endpush