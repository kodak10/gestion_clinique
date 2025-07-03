@extends('dashboard.layouts.master')

@section('content')
<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <h2 class="page-title">Historique</h2>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <!-- Carte Règlement -->
                <div class="col-md-6 col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Règlement</h3>
                            
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Caissière</th>
                                            <th>Patient</th>
                                            <th>Montant</th>
                                            <th>Méthode</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reglements as $reglement)
                                        <tr>
                                            <td>{{ $reglement->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $reglement->user->name }}</td>
                                            <td>{{ $reglement->consultation->patient->nom ?? '' }} {{ $reglement->consultation->patient->prenoms ?? '' }}</td>
                                            <td>{{ number_format($reglement->montant, 0, ',', ' ') }}</td>
                                            <td>{{ $reglement->methode_paiement }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Consultation -->
                <div class="col-md-6 col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Consultation</h3>
                            
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Patient</th>
                                            <th>Montant</th>
                                            <th>Caissière</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($consultations as $consultation)
                                        <tr>
                                            <td>{{ $consultation->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $consultation->patient->nom }} {{ $consultation->patient->prenoms }}</td>
                                            <td>{{ number_format($consultation->montant_a_paye, 0, ',', ' ') }}</td>
                                            <td>{{ $consultation->user->name }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Hospitalisation -->
                <div class="col-md-6 col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Hospitalisation</h3>
                            
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Entrée</th>
                                            <th>Sortie</th>
                                            <th>Montant</th>
                                            <th>Facturié</th>
                                            <th>Patient</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($hospitalisations as $hospitalisation)
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{ number_format($hospitalisation->montant, 0, ',', ' ') }} FCFA</td>
                                            <td>{{ $hospitalisation->user->name }}</td>
                                            <td>{{ $hospitalisation->patient->nom_complet }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Dépense -->
                <div class="col-md-6 col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Dépense</h3>
                            
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Libellé</th>
                                            <th>Montant</th>
                                            <th>Caissière</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($depenses as $depense)
                                        <tr>
                                            <td>{{ $depense->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $depense->libelle }}</td>
                                            <td>{{ number_format($depense->montant, 0, ',', ' ') }} FCFA</td>
                                            <td>{{ $depense->user->name }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection