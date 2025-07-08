<!DOCTYPE html>
<html>
<head>
    <title>Laboratoire - {{ $hospitalisation->patient->nom }}</title>
    <style>
        body { font-family: Arial; margin: 0; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Examens Laboratoire</h2>
        <p>Patient: {{ $hospitalisation->patient->nom }} {{ $hospitalisation->patient->prenoms }}</p>
        <p>Date: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Examen</th>
                <th>Quantité</th>
                <th>Prix</th>
                <th>Taux</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalGeneral = 0;
            @endphp
            
            @foreach($details as $item)
            <tr>
                <td>{{ $item->libelle }}</td>
                <td class="text-right">{{ $item->quantite }}</td>
                <td class="text-right">{{ number_format($item->prix, 0, ',', ' ') }}</td>
                <td class="text-right">{{ number_format($item->taux, 0, ',', ' ') }}%</td>
                <td class="text-right">{{ number_format($item->total, 0, ',', ' ') }}</td>
            </tr>
            @php
                $totalGeneral += $item->total;
            @endphp
            @endforeach
            
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL GENERAL</td>
                <td class="text-right">{{ number_format($totalGeneral, 0, ',', ' ') }} </td>
            </tr>
        </tbody>
    </table>
</body>
</html>