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
            height: 150px;
        }

        .header img {
            width: 100px;
            height: auto;
            margin-right: 20px;
        }

        .header-text {
            margin-top: 40px;
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
            margin: 0px 30px 0px 30px;
        }

        


        /* .patient-photo {
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
        } */

        

        /* .dossier-number {
            width: 200px;
            height: 50px !important;
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
        } */

       
        

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
        .img-position{
            position: absolute;
            top: 35px;
            left: 20px;
        }
        .img-position img{
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body>
    <div class="fiche-wrapper">
        <div class="fiche-container">
            <div class="header">
                
                <div class="header-text">
                    <h1>CLINIQUE MEDICALE SILOE CORPORATION</h1>
                    <p>23 BP 1613 ABIDJAN 23</p>
                    <p>Tel : (+225) 23 45 54 42 / 07 01 96 18 37 / 01 73 73 73 85</p>
                    <p>YOPOUGON SIDECI TERMINUS 42</p>
                </div>
            </div>

            <div class="dossier-title-container">
                <div class="dossier-title">DOSSIER MÉDICAL</div>
            </div>

            <div class="img-position">
                <img src="assets/dist/img/logo.png" alt="Logo Clinique">
            </div>

            <div style="display: table; width: 100%; margin-bottom: 20px;">
                <!-- Colonne Photo -->
                <div style="display: table-cell; width: 33%; vertical-align: middle; text-align: center;">
                    @if($patient->photo && Storage::disk('public')->exists($patient->photo))
                        <img src="{{ storage_path('app/public/'.$patient->photo) }}" style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #3498db;">
                    @else
                        <div style="width: 120px; height: 120px; border: 2px solid #3498db; display: flex; align-items: center; justify-content: center;">
                            Photo patient
                        </div>
                    @endif
                </div>

                <!-- Colonne Numéro -->
                <div style="display: table-cell; width: 34%; vertical-align: middle; text-align: center;">
                    <div style="width:80%; background-color: #e74c3c; color: white; padding: 10px; border-radius: 5px; font-weight: bold; display: inline-block;">
                        N°: {{ $patient->num_dossier }}
                    </div>
                </div>

                <!-- Colonne Date -->
                <div style="display: table-cell; width: 33%; vertical-align: middle; text-align: center;">
                    <div style="font-size: 14px;">
                        Enregistré le : {{ $patient->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>





            <div class="patient-content" style="margin-top: 25px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="width: 50%; vertical-align: top; padding: 12px 8px;">
                            <strong>NOM & PRÉNOMS</strong><br>
                            <div style="background: #f9f9f9; border-left: 4px solid #3498db; padding: 8px 10px; min-height: 30px;">
                                {{ $patient->nom }} {{ $patient->prenoms }}
                            </div>
                        </td>
                        <td style="width: 50%; vertical-align: top; padding: 12px 8px;">
                            <strong>DATE DE NAISSANCE</strong><br>
                            <div style="background: #f9f9f9; border-left: 4px solid #3498db; padding: 8px 10px; min-height: 30px;">
                                {{ $patient->date_naissance->format('d/m/Y') }} ({{ $patient->date_naissance->age }} ans)
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 12px 8px;">
                            <strong>SEXE</strong><br>
                            <div style="background: #f9f9f9; border-left: 4px solid #3498db; padding: 8px 10px; min-height: 30px;">
                                {{ $patient->sexe }}
                            </div>
                        </td>
                        <td style="padding: 12px 8px;">
                            <strong>DOMICILE</strong><br>
                            <div style="background: #f9f9f9; border-left: 4px solid #3498db; padding: 8px 10px; min-height: 30px;">
                                {{ $patient->domicile }}
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 12px 8px;">
                            <strong>ETHNIE</strong><br>
                            <div style="background: #f9f9f9; border-left: 4px solid #3498db; padding: 8px 10px; min-height: 30px;">
                                {{ $patient->ethnie->nom ?? '' }}
                            </div>
                        </td>
                        <td style="padding: 12px 8px;">
                            <strong>RELIGION</strong><br>
                            <div style="background: #f9f9f9; border-left: 4px solid #3498db; padding: 8px 10px; min-height: 30px;">
                                {{ $patient->religion ?? '' }}
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 12px 8px;">
                            <strong>CONTACT</strong><br>
                            <div style="background: #f9f9f9; border-left: 4px solid #3498db; padding: 8px 10px; min-height: 30px;">
                                {{ $patient->contact_patient }}
                            </div>
                        </td>
                        <td style="padding: 12px 8px;">
                            <strong>PARENT PROCHE</strong><br>
                            <div style="background: #f9f9f9; border-left: 4px solid #3498db; padding: 8px 10px; min-height: 30px;">
                                {{ $patient->contact_urgence ?? '' }}
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 12px 8px;">
                            <strong>GROUPE RHÉSUS</strong><br>
                            <div style="background: #f9f9f9; border-left: 4px solid #3498db; padding: 8px 10px; min-height: 30px;">
                                {{ $patient->groupe_rhesus ?? '' }}
                            </div>
                        </td>
                        <td style="padding: 12px 8px;">
                            <strong>ÉLECTROPHORÈSE</strong><br>
                            <div style="background: #f9f9f9; border-left: 4px solid #3498db; padding: 8px 10px; min-height: 30px;">
                                {{ $patient->electrophorese ?? '' }}
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 12px 8px;">
                            <strong>ASSURANCE</strong><br>
                            <div style="background: #f9f9f9; border-left: 4px solid #3498db; padding: 8px 10px; min-height: 30px;">
                                {{ $patient->assurance->name ?? 'Aucune' }}
                            </div>
                        </td>
                        <td style="padding: 12px 8px;">
                            <strong>TAUX</strong><br>
                            <div style="background: #f9f9f9; border-left: 4px solid #3498db; padding: 8px 10px; min-height: 30px;">
                                {{ $patient->assurance->taux ?? '00' }}%
                            </div>
                        </td>
                    </tr>
                </table>
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
