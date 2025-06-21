<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Signalétique Patient - Clinique Siloé</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
            color: #333;
        }

        .fiche-wrapper {
            width: 100vw;
/*             height: 100vh;
 */            padding: 5mm;
            background-color: white;
        }

        .fiche-container {
            width: 100%;
            /* height: 100%; */
            border: 4px solid #003c58;
            position: relative;
        }

        .header {
            display: flex;
            align-items: center;
            background-color: #003c58;
            color: white;
            padding: 15px;
            border-bottom: 4px solid #3498db;
        }

        .header img {
            width: 100px;
            height: auto;
            margin-right: 20px;
        }

        .header-text {
            flex: 1;
            text-align: center;
        }

        .header-text h1 {
            margin: 5px 0;
            font-size: 18px;
        }

        .header-text p {
            margin: 3px 0;
            font-size: 12px;
            opacity: 0.9;
        }

        .dossier-title-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 15px 0;
        }

        .dossier-title {
            border: 1px solid #3498db;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #003c58;
            padding: 10px 20px;
        }

        .photo-dossier-container {
            display: flex;
            justify-content: space-between; /* ou center */
            align-items: center;
            gap: 20px;
            margin: 20px 30px;
        }


        .patient-photo {
            width: 120px;
            height: 150px;
            border: 2px solid #3498db;
            border-radius: 5px;
            background-color: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7f8c8d;
            font-size: 12px;
            flex-shrink: 0;
        }

        .patient-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dossier-number {
            width: 200px;
            background-color: #e74c3c;
            color: white;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            line-height: 1.4;
            text-align: center;
            padding: 6px 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .photo-dossier-container {
            padding: 0 30px 20px;
        }

        .patient-content {
            padding: 0 30px 20px;
        }
        

        .info-grid {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px;
        }
        .info-row {
            display: table-row;
        }
        .info-item {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-bottom: 10px;
        }
        .info-item2 {
            display: table-cell;
            width: 33.33%;
            vertical-align: top;
            padding-bottom: 10px;
        }

        .info-label {
            font-weight: bold;
            color: #2c3e50;
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .info-value {
            padding: 8px 12px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border-left: 4px solid #3498db;
            min-height: 18px;
        }

        .footer {
            position: absolute;
            bottom: 10px;
            left: 0;
            width: 100%;
            background-color: #ecf0f1;
            padding: 8px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            border-top: 1px solid #ddd;
        }

        .dev {
            position: absolute;
            bottom: 0px;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            
        }
        .photo img{
            width: 150px !important;
            height: 150px !important;
            object-fit: cover;
            border-radius: 5px;
        }
        
    </style>
</head>
<body>
    <div class="fiche-wrapper">
        <div class="fiche-container">
            <div class="header">
                <img src="assets/dist/img/logo.png" alt="Logo Clinique">
                <div class="header-text">
                    <h1>CLINIQUE MEDICALE SILOE CORPORATION</h1>
                    <p>23 BP 1613 ABIDJAN 23</p>
                    <p>Tel : (+229) 23 45 54 42 / 07 01 96 18 37 / 01 73 73 73 85</p>
                    <p>YOPOUGON SIDECI TERMINUS 42</p>
                </div>
            </div>

            <div class="dossier-title-container">
                <div class="dossier-title">DOSSIER MÉDICAL</div>
            </div>

            <div class="photo-dossier-container">
               <div class="info-grid">
                    <div class="info-row">
                        <div class="info-item2 photo">
                            @if($patient->photo && Storage::disk('public')->exists($patient->photo))
                                <img src="{{ storage_path('app/public/'.$patient->photo) }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                Photo patient
                            @endif
                        </div>
                        <div class="info-item2">
                           N°: {{ $patient->num_dossier }}
                        </div>
                        <div class="info-item2">
                           Enregistré le: {{ $patient->created_at->format('d/m/Y') }}
                        </div>
                    </div>

                    {{-- <div class="patient-photo">
                        
                    </div>
                    <div class="dossier-number">
                        
                    </div>
                    <div class="dossier-number">
                        
                    </div> --}}
               </div>
            </div>

            <div class="patient-content">
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">NOM & PRÉNOMS</div>
                            <div class="info-value">{{ $patient->nom }} {{ $patient->prenoms }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">DATE DE NAISSANCE</div>
                            <div class="info-value">
                                {{ $patient->date_naissance->format('d/m/Y') }} 
                                ({{ $patient->date_naissance->age }} ans)
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">SEXE</div>
                            <div class="info-value">{{ $patient->sexe }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">DOMICILE</div>
                            <div class="info-value">{{ $patient->domicile }}</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">ETHNIE</div>
                            <div class="info-value">{{ $patient->ethnie->nom ?? '' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">RELIGION</div>
                            <div class="info-value">{{ $patient->religion ?? '' }}</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">CONTACT</div>
                            <div class="info-value">{{ $patient->contact_patient }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">PARENT PROCHE</div>
                            <div class="info-value">{{ $patient->contact_urgence ?? '' }}</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">GROUPE RHÉSUS</div>
                            <div class="info-value">{{ $patient->groupe_rhesus ?? '' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">ÉLECTROPHORÈSE</div>
                            <div class="info-value">{{ $patient->electrophorese ?? ''}}</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">ASSURANCE</div>
                            <div class="info-value">{{ $patient->assurance->name ?? 'Aucune' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">TAUX</div>
                            <div class="info-value">{{ $patient->assurance->taux ?? '00' }}%</div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="footer">
                &laquo; Crois seulement et tu verras la gloire de DIEU &raquo;
            </div>
            <div class="dev">
               Dévoloppé par Kouassi Atchin Parfait . +225 01 03 81 09 98
            </div>
        </div>
    </div>
</body>
</html>
