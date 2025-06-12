@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Patients</h2>
            </div>
            <div class="col">
                <a href="{{ route('patients.create') }}" class="btn btn-2 float-end" >Ajouter</a>
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
                                <th>Numéro de dossier </th>
                                <th>Nom </th>
                                <th>Prénoms</th>
                                <th>Date de naissance</th>
                                <th>Contact</th>
                                <th>En cas d'urgence</th>
                                <th>Assurance</th>
                                <th>Taux</th>
                                <th>Dernier passage</th>
                                <th>Solde non reglé</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patients as $patient)
                                <tr>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('patients.edit', $patient->id) }}">Modifier</a>
                                                    <a class="dropdown-item" href="#" >Imprimer le dossier</a>
                                                   <a class="dropdown-item" href="{{ route('consultations.create', $patient->id) }}">Consultation</a>

                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $patient->id }}">A Hospitaliser</a>
                                                    <button class="dropdown-item" onclick="confirmDelete({{ $patient->id }})">Supprimer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $patient->numero_dossier }}</td>
                                    <td>{{ $patient->nom }}</td>
                                    <td>{{ $patient->prenoms }}</td>
                                    <td>{{ $patient->date_naissance }}</td>
                                    <td>{{ $patient->contact_patient }}</td>
                                    <td>{{ $patient->contact_urgence }}</td>
                                    <td>{{ $patient->assurance->name ?? 'Aucune' }}</td>
                                    <td>{{ $patient->assurance->taux ?? '0' }}</td>
                                    <td></td>
                                    <td></td>
                                    
                                </tr>

                                
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Script pour la suppression -->
<script>
function confirmDelete(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet Patient ?')) {
        document.getElementById('delete-form-'+id).submit();
    }
}
</script>

@foreach ($patients as $patient)
<form id="delete-form-{{ $patient->id }}" action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display: none;">
    @csrf @method('DELETE')
</form>
@endforeach

@endsection

@push('styles')
    <!-- CSS DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

@endpush

@push('scripts')
    <!-- JS DataTables -->
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