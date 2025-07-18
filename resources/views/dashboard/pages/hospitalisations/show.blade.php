<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détails d'Hospitalisation - {{ $hospitalisation->patient->nom ?? '' }} {{ $hospitalisation->patient->prenoms ?? '' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print, .no-print * {
                display: none !important;
            }
            body {
                font-size: 12px;
                padding: 0;
            }
            .container {
                width: 100%;
                max-width: 100%;
                padding: 0;
            }
        }
        .total-box {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            text-align: center;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 8px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-weight: 600;
        }
        .hospitalisation-header {
            border-bottom: 2px solid #0d6efd;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }
        .hospitalisation-info div {
            margin-bottom: 5px;
        }
        .table-responsive {
            margin-bottom: 30px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="hospitalisation-header text-center">
            <h1 class="h3 mb-3">Détails d'Hospitalisation</h1>
            
            <div class="row hospitalisation-info">
                <div class="col-md-6">
                    <div><strong>Patient :</strong> {{ $hospitalisation->patient->nom ?? 'N/A' }} {{ $hospitalisation->patient->prenoms ?? '' }}</div>
                    <div><strong>Facture N° :</strong> {{ $hospitalisation->numero_facture }}</div>
                </div>
                <div class="col-md-6">
                    <div><strong>Médecin :</strong> {{ $hospitalisation->medecin->nom_complet }}</div>
                    <div><strong>Statut :</strong> 
                        <span class="badge bg-{{ $hospitalisation->status == 'present' ? 'success' : 'secondary' }}">
                            {{ $hospitalisation->status == 'present' ? 'En cours' : 'Sorti' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="row mt-2">
                <div class="col-md-6">
                    <strong>Date d'entrée :</strong> {{ $hospitalisation->date_entree->format('d/m/Y H:i') }}
                </div>
                <div class="col-md-6">
                    <strong>Date de sortie :</strong> 
                    {{ $hospitalisation->date_sortie ? $hospitalisation->date_sortie->format('d/m/Y H:i') : 'En cours' }}
                </div>
            </div>
        </div>

        <!-- Section Détails des Frais -->
        <div class="card mb-4">
            <div class="card-header section-title">
                Détails des Frais
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Libellé</th>
                                <th class="text-end">Prix Unitaire</th>
                                <th class="text-end">Prise en charge</th>
                                <th class="text-end">Quantité</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hospitalisation->details as $detail)
                            <tr>
                                <td>{{ $detail->fraisHospitalisation->libelle }}</td>
                                <td class="text-end">{{ number_format($detail->prix_unitaire, 0, ',', ' ') }}</td>
                                <td class="text-end">{{ number_format($detail->taux, 0, ',', ' ') }}%</td>
                                <td class="text-end">{{ $detail->quantite }}</td>
                                <td class="text-end">{{ number_format($detail->total, 0, ',', ' ') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Aucun frais enregistré</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
            NB : Les frais de pharmacie, de laboratoire et d'examen sont déjà calculés et affichés dans leurs sections respectives.
        </div>


        <!-- Section Médicaments -->
        <div class="card mb-4">
            <div class="card-header section-title">
                Médicaments
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Médicament</th>
                                <th class="text-end">Prix Unitaire</th>
                                <th class="text-end">Quantité</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hospitalisation->medicaments as $medicament)
                            <tr>
                                <td>{{ $medicament->nom }}</td>
                                <td class="text-end">{{ number_format($medicament->pivot->prix_unitaire, 0, ',', ' ') }}</td>
                                <td class="text-end">{{ $medicament->pivot->quantite }}</td>
                                <td class="text-end">{{ number_format($medicament->pivot->total, 0, ',', ' ') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Aucun médicament prescrit</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section Examens -->
        <div class="card mb-4">
            <div class="card-header section-title">
                Examens
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Examen</th>
                                <th class="text-end">Prix</th>
                                <th class="text-end">Prise en charge</th>
                                <th class="text-end">Quantité</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hospitalisation->examens as $examen)
                            <tr>
                                <td>{{ $examen->nom }}</td>
                                <td class="text-end">{{ number_format($examen->pivot->prix, 0, ',', ' ') }}</td>
                                <td class="text-end">{{ number_format($examen->pivot->taux, 0, ',', ' ') }}%</td>
                                <td class="text-end">{{ $examen->pivot->quantite }}</td>
                                <td class="text-end">{{ number_format($examen->pivot->total, 0, ',', ' ') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Aucun examen effectué</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Totaux -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="total-box">
                    <strong>Total Général</strong><br>
                    <span class="h5">{{ number_format($hospitalisation->total, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="total-box">
                    <strong>Ticket Modérateur</strong><br>
                    <span class="h5">{{ number_format($hospitalisation->ticket_moderateur, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="total-box">
                    <strong>Réduction</strong><br>
                    <span class="h5">{{ number_format($hospitalisation->reduction, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="total-box">
                    <strong>Reste à Payer</strong><br>
                    <span class="h5">{{ number_format($hospitalisation->reste_a_payer, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center text-muted small no-print">
            Clinique Siloe Corporation - © {{ date('Y') }} - Consulté le {{ now()->format('d/m/Y H:i') }} <br>

            <span>Développé par ATCHIN PARFAIT | Email: Atchinaymard10@gmail.com | Tél: +2250103810998</span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>