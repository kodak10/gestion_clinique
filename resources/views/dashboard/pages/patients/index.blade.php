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
                                                    <a class="dropdown-item" 
                                                    href="{{ route('patients.view-pdf', $patient->id) }}" 
                                                    target="_blank">
                                                    <i class="fas fa-file-pdf mr-2"></i> Ouvrir le dossier
                                                    </a>                                                   
                                                    <a class="dropdown-item" href="{{ route('consultations.create', $patient->id) }}">Consultation</a>

                                                    <a class="dropdown-item"
                                                    href="{{ route('hospitalisations.store.simple', ['patient' => $patient->id]) }}"
                                                    onclick="return confirmHospitalisation(event)">
                                                    À Hospitaliser
                                                    </a>

                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $patient->num_dossier }}</td>
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




</script>


@endsection

@push('styles')
    <!-- CSS DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

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

        // Fonction pour gérer la confirmation d'hospitalisation
        function confirmHospitalisation(event) {
            event.preventDefault();
            const href = event.currentTarget.href;
            
            Swal.fire({
                title: 'Confirmer l\'hospitalisation',
                text: "Voulez-vous vraiment hospitaliser ce patient ?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, hospitaliser',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Créer un formulaire virtuel et le soumettre
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = href;
                    
                    // Ajouter le token CSRF
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
                    form.appendChild(csrfToken);
                    
                    // Ajouter le formulaire au DOM et le soumettre
                    document.body.appendChild(form);
                    form.submit();
                }
            });
            
            // Retourner false pour empêcher le comportement par défaut
            return false;
        }

        
    </script>
@endpush