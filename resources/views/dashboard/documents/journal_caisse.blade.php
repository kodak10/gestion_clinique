<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Journal de Caisse - {{ now()->format('d/m/Y') }}</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Journal de Caisse</h1>
        <div class="header-info">
            <div>
                <strong>Période :</strong> 
                {{ \Carbon\Carbon::parse($date_debut)->format('d/m/Y') }} - 
                {{ \Carbon\Carbon::parse($date_fin)->format('d/m/Y') }}
            </div>
            <div>
                <strong>Caissière :</strong> {{ $caissiereName }}
            </div>
        </div>
        <div class="header-info">
            <div>
                <strong>Type :</strong> {{ $typeFilter == 'all' ? 'Tous' : ucfirst($typeFilter) }}
            </div>
            <div>
                <strong>Imprimé le :</strong> {{ $printedAt }}
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>N° Reçu</th>
                <th>Patient</th>
                <th>Type</th>
                <th>Montant (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reglements as $reglement)
            <tr>
                <td>{{ $reglement->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @if($reglement->consultation)
                        {{ $reglement->consultation->numero_recu }}
                    @else
                        HOSP-{{ $reglement->hospitalisation->id }}
                    @endif
                </td>
                <td>
                    {{ $reglement->consultation->patient->nom ?? $reglement->hospitalisation->patient->nom }} 
                    {{ $reglement->consultation->patient->prenoms ?? $reglement->hospitalisation->patient->prenoms }}
                </td>
                <td>{{ ucfirst($reglement->type) }}</td>
                <td style="text-align: right;">{{ number_format($reglement->montant, 0, ',', ' ') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Aucune transaction trouvée</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="totals">
        <div class="total-box">
            <strong>Total Entrées</strong><br>
            {{ number_format($totalEntrees, 0, ',', ' ') }} FCFA
        </div>
        <div class="total-box">
            <strong>Total Sorties</strong><br>
            {{ number_format($totalSorties, 0, ',', ' ') }} FCFA
        </div>
        <div class="total-box">
            <strong>Solde</strong><br>
            {{ number_format($totalEntrees - $totalSorties, 0, ',', ' ') }} FCFA
        </div>
    </div>

    <div class="footer">
        Clinique Siloe Corporation - © {{ date('Y') }}
    </div>
</body>
</html>