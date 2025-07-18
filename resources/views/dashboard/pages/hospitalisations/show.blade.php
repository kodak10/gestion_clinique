<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Détails d'Hospitalisation - {{ $hospitalisation->patient->nom }} {{ $hospitalisation->patient->prenoms }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header-info { display: flex; justify-content: space-between; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .totals { margin-top: 15px; display: flex; justify-content: space-around; }
        .total-box { text-align: center; padding: 5px 15px; border: 1px solid #ddd; border-radius: 4px; }
        .footer { margin-top: 20px; font-size: 10px; text-align: center; color: #666; }
        .section { margin-top: 20px; }
        .section-title { background-color: #f2f2f2; padding: 5px; font-weight: bold; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Détails d'Hospitalisation</h1>
        <div class="header-info">
            <div>
                <strong>Patient :</strong> 
                {{ $hospitalisation->patient->nom }} {{ $hospitalisation->patient->prenoms }}
            </div>
            <div>
                <strong>N° Hospitalisation :</strong> HOSP-{{ $hospitalisation->id }}
            </div>
        </div>
        <div class="header-info">
            <div>
                <strong>Médecin :</strong> 
                {{ $hospitalisation->medecin ? $hospitalisation->medecin->name : 'Non spécifié' }}
            </div>
            <div>
                <strong>Statut :</strong> 
                {{ $hospitalisation->status == 'present' ? 'En cours' : 'Sorti' }}
            </div>
        </div>
        <div class="header-info">
            <div>
                <strong>Date d'entrée :</strong> 
                {{ $hospitalisation->date_entree->format('d/m/Y H:i') }}
            </div>
            <div>
                <strong>Date de sortie :</strong> 
                {{ $hospitalisation->date_sortie ? $hospitalisation->date_sortie->format('d/m/Y H:i') : 'En cours' }}
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Détails des Frais</div>
        <table>
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th>Prix Unitaire</th>
                    <th>Taux</th>
                    <th>Quantité</th>
                    <th>Réduction</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hospitalisation->details as $detail)
                <tr>
                    <td>{{ $detail->fraisHospitalisation->nom }}</td>
                    <td class="text-right">{{ number_format($detail->prix_unitaire, 0, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($detail->taux, 0, ',', ' ') }}%</td>
                    <td class="text-right">{{ $detail->quantite }}</td>
                    <td class="text-right">{{ number_format($detail->reduction, 0, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($detail->total, 0, ',', ' ') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Aucun frais enregistré</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Médicaments</div>
        <table>
            <thead>
                <tr>
                    <th>Médicament</th>
                    <th>Prix Unitaire</th>
                    <th>Quantité</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hospitalisation->medicaments as $medicament)
                <tr>
                    <td>{{ $medicament->nom }} ({{ $medicament->unite_mesure }})</td>
                    <td class="text-right">{{ number_format($medicament->pivot->prix_unitaire, 0, ',', ' ') }}</td>
                    <td class="text-right">{{ $medicament->pivot->quantite }}</td>
                    <td class="text-right">{{ number_format($medicament->pivot->total, 0, ',', ' ') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Aucun médicament prescrit</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Examens</div>
        <table>
            <thead>
                <tr>
                    <th>Examen</th>
                    <th>Prix</th>
                    <th>Taux</th>
                    <th>Quantité</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hospitalisation->examens as $examen)
                <tr>
                    <td>{{ $examen->nom }}</td>
                    <td class="text-right">{{ number_format($examen->pivot->prix, 0, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($examen->pivot->taux, 0, ',', ' ') }}%</td>
                    <td class="text-right">{{ $examen->pivot->quantite }}</td>
                    <td class="text-right">{{ number_format($examen->pivot->total, 0, ',', ' ') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Aucun examen effectué</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="totals">
        <div class="total-box">
            <strong>Total Général</strong><br>
            {{ number_format($hospitalisation->total, 0, ',', ' ') }} FCFA
        </div>
        <div class="total-box">
            <strong>Ticket Modérateur</strong><br>
            {{ number_format($hospitalisation->ticket_moderateur, 0, ',', ' ') }} FCFA
        </div>
        <div class="total-box">
            <strong>Réduction</strong><br>
            {{ number_format($hospitalisation->reduction, 0, ',', ' ') }} FCFA
        </div>
        <div class="total-box">
            <strong>Reste à Payer</strong><br>
            {{ number_format($hospitalisation->reste_a_payer, 0, ',', ' ') }} FCFA
        </div>
    </div>

    <div class="footer">
        Clinique Siloe Corporation - © {{ date('Y') }} - Imprimé le {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>