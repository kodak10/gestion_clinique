<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de consultation</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        /* Conteneur global avec cadre rouge */
        .table-container {
            display: table;
            width: 100%;
            border: 3px solid red;
            padding: 10px;
            box-sizing: border-box;
        }

        .table-row {
            display: table-row;
        }

        /* Premier reçu - bureau plus étroit */
        .receipt.bureau {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            padding: 15px;
            box-sizing: border-box;
            border-right: 1px dashed gray;
        }

        /* Deuxième reçu - client plus large */
        .receipt.client {
            display: table-cell;
            width: 65%;
            vertical-align: top;
            padding: 15px;
            box-sizing: border-box;
        }

        .header, .footer, .inline-fields, .field, .payment-methods {
            margin-bottom: 10px;
        }

        .header .logo {
            font-weight: bold;
            font-size: 14px;
        }

        .details, .meta {
            font-size: 10px;
        }

        .meta {
            text-align: right;
        }

        .field label {
            font-weight: bold;
            display: block;
            margin-bottom: 3px;
        }

        .field span, .inline-fields .item span {
            display: block;
            border-bottom: 1px solid #000;
            padding: 2px 0;
            min-height: 16px;
        }

        .inline-fields {
            display: table;
            width: 100%;
        }

        .inline-fields .item {
            display: table-cell;
            width: 33%;
            padding-right: 10px;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }

        .footer {
            font-size: 10px;
            display: table;
            width: 100%;
        }

        .footer div {
            display: table-cell;
        }

        .signature {
            text-align: right;
        }
        .bold{
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="table-container">
        <div class="table-row">
            <!-- Copie Bureau -->
            <div class="receipt bureau">
                <div class="header" >
                    <table width="100%" style="margin-top: 0px !important">
                        <tr>
                            <!-- Logo au-dessus du téléphone -->
                            <td style="width: 60%; vertical-align: top;border:none !important">
                                <div style="text-align: left;">
                                    <img src="assets/dist/img/logo.png" alt="Logo" style="height: 40px;"><br>
                                    <span style="font-size: 10px;" class="bold">Téléphone: 0173737355</span>
                                </div>
                            </td>

                            <!-- Date + Numéro reçu alignés à droite -->
                            <td style="width: 40%; text-align: right; vertical-align: top; font-size: 10px;border:none !important">
                                <div><strong>DATE:</strong> {{ $date }}</div>
                                <div><strong>RECU N°:</strong> {{ $numeroRecu }}</div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="field">
                    <label>NOM & PRENOMS</label>
                    <span>{{ $patient->nom }} {{ $patient->prenoms }}</span>
                </div>

                <div class="inline-fields">
                    <div class="item">
                        <label class="bold">MEDECIN</label>
                        <span>{{ $medecin->nom_complet }}</span>
                    </div>
                    <div class="item">
                        <label class="bold">SPECIALITE</label>
                        <span>{{ $medecin->specialite->nom }}</span>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Prestation</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prestations as $prestation)
                        <tr>
                            <td>{{ $prestation->libelle }}</td>
                            <td>{{ number_format($prestation->pivot->total, 0, ',', ' ') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="inline-fields" style="margin-top: 5px;">
                    <div class="item">
                        <label class="bold">Total</label>
                        <span>{{ number_format($consultation->total, 0, ',', ' ') }}</span>
                    </div>
                    <div class="item">
                        <label class="bold">Ticket Mod.</label>
                        <span>{{ number_format($consultation->ticket_moderateur, 0, ',', ' ') }}</span>
                    </div>
                    <div class="item">
                        <label class="bold">Réduction</label>
                        <span>{{ number_format($consultation->reduction, 0, ',', ' ') }}</span>
                    </div>
                </div>

                <div class="inline-fields">
                    <div class="item">
                        <label class="bold">Payé</label>
                        <span>{{ number_format($consultation->montant_paye, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="item">
                        <label class="bold">Reste à payer</label>
                        <span>{{ number_format($consultation->reste_a_payer, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>

                <div class="payment-methods">
                    <label class="bold">Mode de paiement:</label>
                    <span>
                        @if($consultation->methode_paiement == 'cash')
                        Cash
                        @elseif($consultation->methode_paiement == 'mobile_money')
                        Mobile money
                        @else
                        Virement
                        @endif
                    </span>
                </div>

                <div class="footer">
                    <div class="bold">Encaisser par:</div>
                    <div class="signature">{{ $user->name }}</div>
                </div>
            </div>

            <!-- Copie Client -->
            <div class="receipt client">
                
                <div class="header" >
                    <table width="100%" style="margin-top: 0px !important">
                        <tr>
                            <!-- Logo au-dessus du téléphone -->
                            <td style="width: 60%; vertical-align: top;border:none !important">
                                <div style="text-align: left;">
                                    <img src="assets/dist/img/logo.png" alt="Logo" style="height: 40px;"><br>
                                    <span style="font-size: 10px;" class="bold">Téléphone: 0173737355</span>
                                </div>
                            </td>

                            <!-- Date + Numéro reçu alignés à droite -->
                            <td style="width: 40%; text-align: right; vertical-align: top; font-size: 10px;border:none !important">
                                <div><strong>DATE:</strong> {{ $date }}</div>
                                <div><strong>RECU N°:</strong> {{ $numeroRecu }}</div>
                            </td>
                        </tr>
                    </table>
                </div>




                <div class="field">
                    <label>NOM & PRENOMS</label>
                    <span>{{ $patient->nom }} {{ $patient->prenoms }}</span>
                </div>

                <div class="inline-fields">
                    <div class="item">
                        <label class="bold">MEDECIN</label>
                        <span>{{ $medecin->nom_complet }}</span>
                    </div>
                    <div class="item">
                        <label class="bold">SPECIALITE</label>
                        <span>{{ $medecin->specialite->nom }}</span>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Prestation</th>
                            <th>PU</th>
                            <th>Qte</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prestations as $prestation)
                        <tr>
                            <td>{{ $prestation->libelle }}</td>
                            <td>{{ number_format($prestation->pivot->montant, 0, ',', ' ') }}</td>
                            <td>{{ $prestation->pivot->quantite }}</td>
                            <td>{{ number_format($prestation->pivot->total, 0, ',', ' ') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="inline-fields" style="margin-top: 5px;">
                    <div class="item">
                        <label class="bold">Total</label>
                        <span>{{ number_format($consultation->total, 0, ',', ' ') }}</span>
                    </div>
                    <div class="item">
                        <label class="bold">Ticket Modérateur</label>
                        <span>{{ number_format($consultation->ticket_moderateur, 0, ',', ' ') }}</span>
                    </div>
                    <div class="item">
                        <label class="bold">Réduction</label>
                        <span>{{ number_format($consultation->reduction, 0, ',', ' ') }}</span>
                    </div>
                </div>

                <div class="inline-fields">
                    <div class="item">
                        <label class="bold">Payé</label>
                        <span>{{ number_format($consultation->montant_paye, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="item">
                        <label class="bold">Reste à payer</label>
                        <span>{{ number_format($consultation->reste_a_payer, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>

                <div class="payment-methods">
                    <label class="bold">Mode de paiement:</label>
                    <span>
                        @if($consultation->methode_paiement == 'cash')
                        Cash
                        @elseif($consultation->methode_paiement == 'mobile_money')
                        Mobile money
                        @else
                        Virement
                        @endif
                    </span>
                </div>

                <div class="footer">
                    <div class="bold">Encaisser par:</div>
                    <div class="signature">{{ $user->name }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
