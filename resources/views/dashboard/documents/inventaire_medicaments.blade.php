<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Inventaire Médicaments</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; vertical-align: middle; }
        th { background-color: #ddd; }
        .header { text-align: center; margin-bottom: 10px; }
        .header h2 { margin: 0; }
        .header .date { margin-top: 5px; font-size: 9px; }
        td.left { text-align: left; padding-left: 6px; }
    </style>
</head>
<body>

    <div class="header">
        <div>CLINIQUE MEDICALE SILOE CORPORATION</div>
        <div>Date : {{ $date }}</div>
        <h4>FICHE DE RECETTE ET DE STOCK</h4>
    </div>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Désignation</th>
                <th>STOCK</th>
                <th colspan="3">SORTIES</th>
                <th>ENTREE</th>
                <th>DISPO</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th>QTE</th>
                <th>PU</th>
                <th>MONTANT</th>
                <th>QTE</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($medicaments as $index => $med)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="left">{{ strtoupper($med->nom) }}</td>
                <td>{{ $med->stock }}</td>
                <td>{{ $med->total_sorties ?? '' }}</td>
                <td></td> {{-- PU, à renseigner si dispo --}}
                <td></td> {{-- Montant, à calculer si besoin --}}
                <td>{{ $med->total_entrees ?? '' }}</td>
                <td>{{ $med->stock_disponible }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
