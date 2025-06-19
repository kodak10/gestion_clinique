@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Patients hospitalisés</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card p-2">
            <div class="card-body p-0">
                <div id="table-default" class="table-responsive">
                  <table class="table" id="table">
                        <thead>
                            <tr>
                                <th class="w-1"></th>
                                <th>Numéro de dossier</th>
                                <th>Nom</th>
                                <th>Prénoms</th>
                                <th>Date de naissance</th>
                                <th>Contact</th>
                                <th>Total Pharmacie</th>
                                <th>Total Laboratoire</th>
                                <th>Montant de facture Actuel</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hospitalisations as $hospitalisation)
                                <tr>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    {{-- <a class="dropdown-item" href="{{ route('factures.create', $hospitalisation->id) }}">Facture</a>
                                                    <a class="dropdown-item" href="{{ route('pharmacie.commande', $hospitalisation->id) }}">Pharmacie</a>
                                                    <a class="dropdown-item" href="{{ route('laboratoire.demande', $hospitalisation->id) }}">Laboratoire</a> --}}

                                                    <a class="dropdown-item" href="{{ route('hospitalisations.facture.create', ['hospitalisation' => $hospitalisation->id]) }}">Facture</a>
                                                    <a class="dropdown-item" href="{{ route('hospitalisations.pharmacie.create', ['hospitalisation' => $hospitalisation->id]) }}">Pharmacie</a>
                                                    <a class="dropdown-item" href="{{ route('hospitalisations.laboratoire.create', ['hospitalisation' => $hospitalisation->id]) }}">Laboratoire</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $hospitalisation->patient->numero_dossier }}</td>
                                    <td>{{ $hospitalisation->patient->nom }}</td>
                                    <td>{{ $hospitalisation->patient->prenoms }}</td>
                                    <td>{{ $hospitalisation->patient->date_naissance }}</td>
                                    <td>{{ $hospitalisation->patient->contact_patient }}</td>
                                    <td>{{ $hospitalisation->hospitalisation->service ?? '-' }}</td>
                                    <td>{{ $hospitalisation->hospitalisation->date_entree ?? '-' }}</td>
                                    <td>{{ $hospitalisation->hospitalisation->lit ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/French.json"
            },
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "responsive": true
        });
    });
</script>
@endpush
