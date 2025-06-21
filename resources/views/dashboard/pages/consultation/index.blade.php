@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Consultations</h2>
            </div>
            
            <div class="col">
                <a href="{{ route('patients.create') }}" class="btn btn-2 float-end">Ajouter</a>
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
                                <th>Numéro de reçu</th>
                                <th>Patient</th>
                                <th>Médecin</th>
                                <th>Date consultation</th>
                                <th>Total</th>
                                <th>Payé</th>
                                <th>Méthode paiement</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($consultations as $consultation)
                                <tr>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('consultations.edit', $consultation->id) }}">Modifier</a>

                                                    @if($consultation->pdf_path)
                                                    <a class="dropdown-item" 
                                                       href="{{ Storage::url($consultation->pdf_path) }}" 
                                                       target="_blank">
                                                       Imprimer le reçu
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $consultation->numero_recu }}</td>
                                    <td>
                                        {{ $consultation->patient->nom }} {{ $consultation->patient->prenoms }}
                                        <small class="text-muted d-block">Dossier: {{ $consultation->patient->num_dossier }}</small>
                                    </td>
                                    <td>{{ $consultation->medecin->nom_complet }}</td>
                                    <td>{{ $consultation->date_consultation->format('d/m/Y H:i') }}</td>
                                    <td>{{ number_format($consultation->total, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ number_format($consultation->montant_paye, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        @if($consultation->methode_paiement == 'cash')
                                        Cash
                                        @elseif($consultation->methode_paiement == 'mobile_money')
                                        Mobile Money
                                        @else
                                        Virement
                                        @endif
                                    </td>
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
    <!-- CSS DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<style>
    .text-muted {
        color: #6c757d!important;
        font-size: 0.85em;
    }
</style>
@endpush

@push('scripts')
    <!-- JS DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

@if(session('pdf_url'))
<script>
    window.onload = function() {
        window.open('{{ session('pdf_url') }}', '_blank');
    };
</script>
@endif

<script>
    $(document).ready(function() {
        $('#table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/French.json"
            },
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "responsive": true,
            "order": [[4, 'desc']] // Tri par date de consultation décroissante
        });
    });
</script>
@endpush