@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Frais d'Hospitalisations</h2>
            </div>
            <div class="col">
                <a href="#" class="btn btn-2 float-end mr-3" data-bs-toggle="modal" data-bs-target="#modal-report">Ajouter un Frais</a>

                {{-- <a href="#" class="btn btn-2 float-end" data-bs-toggle="modal" data-bs-target="#modal-category">Ajouter Une Catégorie</a> --}}
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body p-0">
                <div id="table-default" class="table-responsive">
                  <table class="table" id="table">
                        <thead>
                            <tr>
                                
                                <th>Libellé</th>
                                <th>Montant</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($frais as $fraisItem)
                                <tr>
                                    

                                    <td>{{ $fraisItem->libelle }}</td>
                                    <td>{{ number_format($fraisItem->montant, 0) }}</td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $fraisItem->id }}">Modifier</a>
                                                    <button class="dropdown-item" onclick="confirmDelete({{ $fraisItem->id }})">Supprimer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal d'édition -->
                                <div class="modal modal-blur fade" id="modal-edit-{{ $fraisItem->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modifier Frais d'Hospitalisation</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('frais_hospitalisations.update', $fraisItem->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row">
                                                        {{-- <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Catégorie</label>
                                                                <select class="form-select" name="category_id" required>
                                                                    @foreach($categories as $category)
                                                                        <option value="{{ $category->id }}" {{ $fraisItem->category_id == $category->id ? 'selected' : '' }}>{{ $category->nom }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div> --}}
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Libellé</label>
                                                                <input type="text" class="form-control" name="libelle" value="{{ $fraisItem->libelle }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Montant (FCFA)</label>
                                                                <input type="number" step="0.01" class="form-control" name="montant" value="{{ $fraisItem->montant }}" required>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>

                                                    
                                                    
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="#" class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal">Annuler</a>
                                                    <button type="submit" class="btn btn-primary btn-5 ms-auto">Enregistrer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de création -->
<div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau Frais d'Hospitalisation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('frais_hospitalisations.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-6">
                           <div class="mb-3">
                                <label class="form-label">Libellé</label>
                                <input type="text" class="form-control @error('libelle') is-invalid @enderror" name="libelle" value="{{ old('libelle') }}" required>
                                @error('libelle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                             <div class="mb-3">
                                <label class="form-label">Montant</label>
                                <input type="number" step="0.01" class="form-control @error('montant') is-invalid @enderror" name="montant" value="{{ old('montant') }}" required>
                                @error('montant')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    
                    
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal">Annuler</a>
                    <button type="submit" class="btn btn-primary btn-5 ms-auto">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- <div class="modal modal-blur fade" id="modal-category" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle catégorie d'Hospitalisation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <form action="{{ route('category.hospitalisation.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Nom de la catégorie</label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" value="{{ old('nom') }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                    
                    
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal">Annuler</a>
                    <button type="submit" class="btn btn-primary btn-5 ms-auto">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}

<!-- Script pour la suppression -->
<script>
function confirmDelete(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette ?')) {
        document.getElementById('delete-form-'+id).submit();
    }
}
</script>

@foreach ($frais as $fraisItem)
<form id="delete-form-{{ $fraisItem->id }}" action="{{ route('frais_hospitalisations.destroy', $fraisItem->id) }}" method="POST" style="display: none;">
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