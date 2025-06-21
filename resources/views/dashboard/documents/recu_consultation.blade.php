<!DOCTYPE html>
<html lang="en">
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

        .container {
            display: flex;
            justify-content: space-between;
            border: 3px solid red;
            padding: 10px;
            height: 100%;
        }

        .receipt {
            width: 49%;
            border-right: 1px dashed gray;
            padding: 15px;
            box-sizing: border-box;
            position: relative;
        }

        .receipt:last-child {
            border-right: none;
        }

        .header {
            display: flex;
            justify-content: space-between;
        }

        .logo {
            font-weight: bold;
        }

        .details {
            font-size: 10px;
        }

        .meta {
            text-align: right;
            font-size: 10px;
        }

        .field {
            margin: 10px 0;
        }

        .field label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .double-field {
            display: flex;
            gap: 10px;
        }

        .double-field div {
            flex: 1;
        }

        .double-field span {
            display: block;
            border-bottom: 1px solid #000;
            padding: 3px 0;
            min-height: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }

        .inline-fields {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .inline-fields .item {
            flex: 1;
        }

        .inline-fields .item label {
            font-weight: bold;
            display: block;
            margin-bottom: 3px;
        }

        .inline-fields .item span {
            display: block;
            border-bottom: 1px solid #000;
            min-height: 16px;
        }

        .payment-methods {
            margin: 10px 0;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 10px;
        }

        .signature {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Copie Bureau -->
        <div class="receipt">
            <div class="header">
                <div class="logo">
                    🔴 LOGO
                    <div class="details">
                        Phone: +0123456789
                    </div>
                </div>
                <div class="meta">
                    <div><strong>DATE:</strong> {{ $date }}</div>
                    <div><strong>RECU N°:</strong> {{ $numeroRecu }}</div>
                </div>
            </div>

            <div class="field">
                <label>NOM & PRENOMS</label>
                <span>{{ $patient->nom }} {{ $patient->prenoms }}</span>
            </div>

            <div class="inline-fields">
                <div class="item">
                    <label>MEDECIN</label>
                    <span>{{ $medecin->nom_complet }}</span>
                </div>
                <div class="item">
                    <label>SPECIALITE</label>
                    <span>{{ $medecin->specialite->nom }}</span>
                </div>
            </div>
            
            <div class="field">
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
            </div>

            <div class="inline-fields">
                <div class="item">
                    <label>Total</label>
                    <span>{{ number_format($consultation->total, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="item">
                    <label>Ticket Modérateur</label>
                    <span>{{ number_format($consultation->ticket_moderateur, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="item">
                    <label>Réduction</label>
                    <span>{{ number_format($consultation->reduction, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            <div class="inline-fields">
                <div class="item">
                    <label>Payé</label>
                    <span>{{ number_format($consultation->montant_paye, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="item">
                    <label>Reste à payer</label>
                    <span>{{ number_format($consultation->reste_a_payer, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            <div class="payment-methods">
                <label>Mode de paiement:</label>
                <span>
                    @if($consultation->methode_paiement == 'cash')
                    ☑ Cash □ Mobile money □ Virement
                    @elseif($consultation->methode_paiement == 'mobile_money')
                    □ Cash ☑ Mobile money □ Virement
                    @else
                    □ Cash □ Mobile money ☑ Virement
                    @endif
                </span>
            </div>

            <div class="footer">
                <div>Encaisser par</div>
                <div class="signature">{{ $user->name }}</div>
            </div>
        </div>

        <!-- Copie Client -->
        <div class="receipt">
            <div class="header">
                <div class="logo">
                    🔴 LOGO
                    <div class="details">
                        Phone: +0123456789
                    </div>
                </div>
                <div class="meta">
                    <div><strong>DATE:</strong> {{ $date }}</div>
                    <div><strong>RECU N°:</strong> {{ $numeroRecu }}</div>
                </div>
            </div>

            <div class="field">
                <label>NOM & PRENOMS</label>
                <span>{{ $patient->nom }} {{ $patient->prenoms }}</span>
            </div>

            <div class="field">
                <label>MEDECIN & SPÉCIALITÉ</label>
                <div class="double-field">
                    <div><span>{{ $medecin->nom_complet }}</span></div>
                    <div><span>{{ $medecin->specialite->nom }}</span></div>
                </div>
            </div>

            <div class="field">
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
            </div>

            <div class="inline-fields">
                <div class="item">
                    <label>Total</label>
                    <span>{{ number_format($consultation->total, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="item">
                    <label>Ticket Modérateur</label>
                    <span>{{ number_format($consultation->ticket_moderateur, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="item">
                    <label>Réduction</label>
                    <span>{{ number_format($consultation->reduction, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            <div class="inline-fields">
                <div class="item">
                    <label>Payé</label>
                    <span>{{ number_format($consultation->montant_paye, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="item">
                    <label>Reste à payer</label>
                    <span>{{ number_format($consultation->reste_a_payer, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            <div class="payment-methods">
                <label>Mode de paiement:</label>
                <span>
                    @if($consultation->methode_paiement == 'cash')
                    ☑ Cash □ Mobile money □ Virement
                    @elseif($consultation->methode_paiement == 'mobile_money')
                    □ Cash ☑ Mobile money □ Virement
                    @else
                    □ Cash □ Mobile money ☑ Virement
                    @endif
                </span>
            </div>

            <div class="footer">
                <div>Encaisser par</div>
                <div class="signature">{{ $user->name }}</div>
            </div>
        </div>
    </div>
</body>
</html>