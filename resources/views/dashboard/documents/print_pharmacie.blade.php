<!DOCTYPE html>
<html>
<head>
    <title>Pharmacie - {{ $hospitalisation->patient->nom }}</title>
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
        <h2>Détails Pharmacie</h2>
        <p>Patient: {{ $hospitalisation->patient->nom }} {{ $hospitalisation->patient->prenoms }}</p>
        <p>Date: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Médicament</th>
                <th>Prix unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medicaments as $medicament)
            <tr>
                <td>{{ $medicament->libelle }}</td>
                <td>{{ number_format($medicament->prix_unitaire, 0, ',', ' ') }}</td>
                <td>{{ $medicament->quantite }}</td>
                <td>{{ number_format($medicament->total, 0, ',', ' ') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="text-right">TOTAL GENERAL</td>
                <td class="text-right">{{ number_format($medicaments->sum('total'), 0, ',', ' ') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>