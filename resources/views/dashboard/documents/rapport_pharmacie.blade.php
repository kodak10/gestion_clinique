<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Historique global des médicaments</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 15px; }
        .title { font-size: 16px; font-weight: bold; }
        .subtitle { font-size: 12px; }
        .footer { margin-top: 20px; font-size: 10px; text-align: right; }
        .text-center { text-align: center; }
        .page-break { page-break-after: always; }
        .medicament-header { background-color: #e9ecef; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">HISTORIQUE GLOBAL DES MÉDICAMENTS</div>
        <div class="subtitle">
            Généré le {{ $date }} | Nombre total de médicaments: {{ $medicaments->count() }}
        </div>
    </div>

    @foreach($medicaments as $medicament)
    <div class="medicament-section">
        <table>
            <tr class="medicament-header">
                <td colspan="6">
                    {{ $medicament->nom }} ({{ $medicament->code }}) - 
                    Stock: {{ $medicament->stock }} | 
                    Prix: {{ number_format($medicament->prix_vente, 2) }} FCFA | 
                    Prescriptions: {{ $medicament->total_prescriptions }}
                </td>
            </tr>
            @if($medicament->hospitalisations->count() > 0)
            <tr>
                <th>Date</th>
                <th>Patient</th>
                <th>Hospitalisation</th>
                <th>Médecin</th>
                <th class="text-center">Quantité</th>
                <th class="text-center">Total</th>
            </tr>
            @foreach($medicament->hospitalisations as $prescription)
            <tr>
                <td>{{ $prescription->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $prescription->hospitalisation->patient->nom ?? '' }} {{ $prescription->hospitalisation->patient->prenoms ?? '' }}</td>
                <td>{{ $prescription->hospitalisation->numero ?? 'N/A' }}</td>
                <td>{{ $prescription->hospitalisation->medecin->nom_complet ?? 'N/A' }}</td>
                <td class="text-center">{{ $prescription->quantite }}</td>
                <td class="text-center">{{ number_format($prescription->total, 2) }} FCFA</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="6" class="text-center">Aucune prescription enregistrée</td>
            </tr>
            @endif
        </table>
        
        @if(!$loop->last)
        <div style="margin-bottom: 20px;"></div>
        @endif
    </div>
    @endforeach

    <div class="footer">
        Document généré par {{ $user->name ?? 'Système' }} | Page @{{ $page }} sur @{{ $pages }}
    </div>
</body>
</html>