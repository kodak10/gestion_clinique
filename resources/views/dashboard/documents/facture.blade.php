<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture d'Hospitalisation</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1cm;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 12px;
        }
        
        .invoice-container {
            width: 100%;
            margin: 0 auto;
        }
        
        .header {
            width: 100%;
            margin-bottom: 1px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1a5276;
            position: relative;
            overflow: hidden;
        }
        
        .logo-placeholder img {
            width: 80px;
            height: 80px;            
            text-align: center;
            line-height: 80px;
            float: left;
        }
        
        .header-center {
            text-align: center;
            margin: 20 100px;
        }
        
        .header-center h1 {
            color: #1a5276;
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        
        .header-center p {
            margin: 3px 0;
            font-size: 12px;
            color: #555;
        }
        
        .qr-code {
            width: 80px;
            height: 80px;
            background-color: #f0f0f0;
            border: 1px dashed #999;
            color: #666;
            text-align: center;
            line-height: 80px;
            float: right;
        }
        
        .invoice-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            color: #1a5276;
            text-decoration: underline;
        }
        
        .invoice-number {
            text-align: center;
            margin: 10px auto;
            padding: 8px;
            background-color: #1a5276;
            color: white;
            font-weight: bold;
            border-radius: 3px;
            width: 200px;
            font-size: 12px;
        }
        
        .info-grid {
            width: 100%;
            margin-bottom: 15px;
            overflow: hidden;
        }
        
        .column-left {
            width: 48%;
            float: left;
        }
        
        .column-right {
            width: 48%;
            float: right;
        }
        
        .info-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            background: #f8f9fa;
        }
        
        .info-card h3 {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: white;
            padding: 6px;
            background-color: #1a5276;
            border-radius: 3px;
        }
        
        .info-row {
            margin-bottom: 6px;
            font-size: 12px;
        }
        
        .info-row strong {
            display: inline-block;
            width: 120px;
            color: #555;
        }
        
        .highlight-box {
            background: #1a5276;
            border-left: 4px solid #3498db;
            padding: 6px;
            margin: 8px 0;
            font-weight: bold;
            color: white;
            font-size: 12px;
            width: 48%;
            display: inline-block;
            box-sizing: border-box;
        }
        
        .dates-container {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .payment-row {
            width: 100%;
            margin-bottom: 15px;
            font-size: 12px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 12px;
        }
        
        .items-table th {
            background-color: #1a5276;
            color: white;
            padding: 8px;
            text-align: left;
        }
        
        .items-table td {
            padding: 6px;
            border: 1px solid #ddd;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        .total-section {
            width: 35%;
            float: right;
            border: 1px solid #ddd;
            border-top: none;
            margin-top: -1px;
        }
        
        .total-row {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            display: table;
            width: 100%;
        }
        
        .total-row span {
            display: table-cell;
            width: 60%;
        }
        
        .total-row strong {
            display: table-cell;
            width: 40%;
            text-align: right;
        }
        
        .total-row:last-child {
            border-bottom: none;
        }
        
        .signature-section {
            
            width: 100%;
        }
        
        .signature-box {
            margin-bottom: 15px;
            font-size: 12px;
        }
        
        .footer {
            width: 100%;
            margin-top: 20px;
            padding-top: 10px;
            font-size: 11px;
            text-align: center;
            border-top: 1px solid #ddd;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }
        
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            
            <div class="logo-placeholder"><img src="assets/dist/img/logo.png" alt=""></div>
            
            {{-- <div class="qr-code">
              <img src="{{ public_path('storage/' . $hospitalisation->qr_code_path) }}" alt="QR Code" style="max-width:100%; max-height:100%;">
            </div> --}}

            
            <div class="header-center">
                <h1>CLINIQUE SILOE CORPORATION</h1>
                <p> Tél: 01 73 73 73 55 | Email: cliniquesiloevie@gmail.com | Site internet : cliniquesiloecorporation.com</p>
            </div>
            <div class="clear"></div>
        </div>
        
        <div class="invoice-title">FACTURE D'HOSPITALISATION</div>
        
        <div class="invoice-number">N° Facture : {{ $hospitalisation->numero_facture ?? 'H00540' }}</div>
        
        <div class="info-grid">
            <div class="column-left">
                <div class="info-card">
                    <h3>Coordonnées du patient</h3>
                    <div class="info-row"><strong>Dossier n°:</strong> {{ $hospitalisation->patient->num_dossier }}</div>
                    <div class="info-row"><strong>Nom du Patient :</strong> {{ $hospitalisation->patient->nom }} {{ $hospitalisation->patient->prenoms }}</div>
                    <div class="info-row"><strong>Date de Naissance:</strong> {{ optional($hospitalisation->patient->date_naissance)->format('d/m/Y') }}</div>
                    <div class="info-row"><strong>Contact :</strong> {{ $hospitalisation->patient->contact_patient ?? '' }}</div>
                    <div class="info-row"><strong>Contact parent:</strong> {{ $hospitalisation->patient->contact_urgence ?? '' }}</div>
                </div>
            </div>
            
            <div class="column-right">
                <div class="info-card">
                    <h3>Medecin Traitant</h3>
                    <div class="info-row"><strong>Nom :</strong> {{ $hospitalisation->medecin->nom_complet }}</div>
                    <div class="info-row"><strong>Spécialité:</strong> {{ $hospitalisation->medecin->specialite->nom }}</div>
                </div>
                
                <div class="info-card">
                    <h3>Assurance</h3>
                    <div class="info-row"><strong>Assurance :</strong> {{ $hospitalisation->patient->assurance->name ?? 'Aucun' }}</div>
                    <div class="info-row"><strong>Matricule:</strong> {{ $hospitalisation->patient->matricule_assurance ?? 'Aucun' }}</div>
                    <div class="info-row"><strong>Taux de couverture:</strong> {{ $hospitalisation->patient->taux_couverture ?? '0' }}%</div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        
        <div class="dates-container">
            <div class="highlight-box" style="float: left;"><strong>Date D'entrée:</strong> {{ optional($hospitalisation->date_entree)->format('d/m/Y') }}</div>
            <div class="highlight-box" style="float: right;"><strong>Date de Sortie:</strong> {{ optional($hospitalisation->date_sortie)->format('d/m/Y') }}</div>
            <div class="clear"></div>
        </div>
        
        <div class="payment-row">
            <div style="float: left; width: 48%;">
                <div class="info-row"><strong>Caution:</strong> {{ number_format($hospitalisation->caution ?? 6000, 0, ',', ' ') }} FCFA</div>
            </div>
            <div style="float: right; width: 48%;">
                <div class="info-row"><strong>Payeur:</strong> {{ $hospitalisation->payeur ?? '' }}</div>
            </div>
            <div class="clear"></div>
        </div>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">N°</th>
                    <th width="35%">Libellé</th>
                    <th width="15%">Prix Unitaire</th>
                    <th width="10%">Qte</th>
                    <th width="15%">Prise en charge</th>
                    <th width="20%">Montant à payer</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $detail)
                  <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $detail->frais->libelle }}</td>
                      <td>{{ number_format($detail->prix_unitaire ?? 0, 0, ',', ' ') }}</td>
                      <td>{{ $detail->quantite ?? 1 }}</td>
                      <td>{{ $detail->taux ?? 0 }}</td>
                      <td>{{ number_format($detail->total ?? 0, 0, ',', ' ') }}</td>
                  </tr>
                  @endforeach

            </tbody>
        </table>
        
       <div class="total-section">
          <div class="total-row">
              <span>Total TTC :</span>
              <strong>{{ number_format($hospitalisation->total ?? 0, 0, ',', ' ') }}</strong>
          </div>
          <div class="total-row">
              <span>Ticket Modérateur :</span>
              <strong>{{ number_format($hospitalisation->ticket_moderateur ?? 0, 0, ',', ' ') }}</strong>
          </div>
          <div class="total-row">
              <span>Réduction :</span>
              <strong>{{ number_format($hospitalisation->reduction ?? 0, 0, ',', ' ') }}</strong>
          </div>
          <div class="total-row">
              <span>Net à Payer :</span>
              <strong>{{ number_format($hospitalisation->montant_a_paye ?? 0, 0, ',', ' ') }} FCFA</strong>
          </div>
      </div>

        
        <div class="signature-section">
            <div class="signature-box">
                <p>Etablie le : <span>{{ now()->format('d/m/Y à H\hi') }}</span></p>
                <p style="margin-bottom: 50px;">Par : <span>{{ $hospitalisation->user->name }}</span></p>
                <span>Arrêté la présente facture à la somme de:</span><br>
                <p><strong>{{ $montantEnLettres }} francs CFA</strong></p>
            </div>
        </div>
        
        <div class="footer">
            <span>Aucun remboursement ne sera possible après règlement de la facture.</span><br>
            <span><strong>La Direction</strong></span>
            <div style="margin-top: 5px; font-size: 10px;">
                Développé par ATCHIN PARFAIT | Email: Atchinaymard10@gmail.com | Tél: +2250103810998
            </div>
        </div>
    </div>
</body>
</html>