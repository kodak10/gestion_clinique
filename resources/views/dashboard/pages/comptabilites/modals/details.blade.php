<div class="row">
    <!-- Colonne de gauche - Informations de base -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    @if($type === 'consultation')
                        <i class="fas fa-stethoscope me-2"></i>Détails Consultation
                    @else
                        <i class="fas fa-procedures me-2"></i>Détails Hospitalisation
                    @endif
                </h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Patient</label>
                    <div class="form-control-plaintext">
                        <strong>{{ $item->patient->nom }} {{ $item->patient->prenoms }}</strong>
                        <small class="text-muted ms-2">(Dossier: {{ $item->patient->numero_dossier }})</small>
                    </div>
                </div>

                @if($type === 'consultation')
                <div class="mb-3">
                    <label class="form-label">Numéro de reçu</label>
                    <div class="form-control-plaintext font-monospace">
                        {{ $item->numero_recu }}
                    </div>
                </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <div class="form-control-plaintext">
                        @if($type === 'consultation')
                            {{ $item->date_consultation->format('d/m/Y H:i') }}
                        @else
                            Du {{ $item->date_entree->format('d/m/Y H:i') }}
                            @if($item->date_sortie)
                                au {{ $item->date_sortie->format('d/m/Y H:i') }}
                                (Durée: {{ $item->date_entree->diffInHours($item->date_sortie) }}h)
                            @else
                                (En cours)
                            @endif
                        @endif
                    </div>
                </div>

                @if($type === 'consultation' && $item->medecin)
                <div class="mb-3">
                    <label class="form-label">Médecin</label>
                    <div class="form-control-plaintext">
                        {{ $item->medecin->nom_complet }}
                        <small class="text-muted">({{ $item->medecin->specialite }})</small>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Section Règlements -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-money-bill-wave me-2"></i>Historique des Paiements
                </h3>
            </div>
            <div class="card-body p-0">
                @if($item->reglements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Méthode</th>
                                <th>Caissier</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->reglements as $reglement)
                            <tr>
                                <td>{{ $reglement->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end">{{ number_format($reglement->montant, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    <span class="badge 
                                        @if($reglement->methode_paiement === 'cash') bg-blue-lt
                                        @elseif($reglement->methode_paiement === 'mobile_money') bg-green-lt
                                        @else bg-purple-lt @endif">
                                        {{ ucfirst(str_replace('_', ' ', $reglement->methode_paiement)) }}
                                    </span>
                                </td>
                                <td>{{ $reglement->user->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-light text-center">
                    Aucun paiement enregistré pour cette {{ $type === 'consultation' ? 'consultation' : 'hospitalisation' }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Colonne de droite - Détails financiers et prestations -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calculator me-2"></i>Détails Financiers
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Total</label>
                            <div class="form-control-plaintext text-end">
                                <strong>{{ number_format($item->total, 0, ',', ' ') }} FCFA</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Ticket modérateur</label>
                            <div class="form-control-plaintext text-end">
                                {{ number_format($item->ticket_moderateur, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Réduction</label>
                            <div class="form-control-plaintext text-end">
                                {{ number_format($item->reduction, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Montant payé</label>
                            <div class="form-control-plaintext text-end">
                                <span class="text-success">{{ number_format($item->total - $item->montant_a_paye, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Reste à payer</label>
                    <div class="form-control-plaintext text-end">
                        <span class="text-danger fw-bold">{{ number_format($item->montant_a_paye, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Prestations -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list-ul me-2"></i>Prestations
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Prestation</th>
                                <th class="text-end">Prix Unitaire</th>
                                <th class="text-center">Qté</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->details as $detail)
                            <tr>
                                <td>
                                    {{ $detail->prestation->libelle }}
                                    <small class="text-muted d-block">{{ $detail->prestation->categorie->nom }}</small>
                                </td>
                                <td class="text-end">{{ number_format($detail->montant, 0, ',', ' ') }} FCFA</td>
                                <td class="text-center">{{ $detail->quantite }}</td>
                                <td class="text-end">{{ number_format($detail->montant * $detail->quantite, 0, ',', ' ') }} FCFA</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total Prestations</th>
                                <th class="text-end">{{ number_format($item->total, 0, ',', ' ') }} FCFA</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>