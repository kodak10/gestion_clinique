@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Gestion des Médicaments</h2>
            </div>
            <div class="col">
                <a href="{{ route('medicaments.historique.global.pdf') }}" 
                target="_blank"
                class="btn btn-2 float-end mr-3">
                <i class="fas fa-file-pdf"></i> Historique Global PDF
                </a>
            </div>
            <div class="col">
                <a href="{{ route('medicaments.inventaire.pdf') }}" target="_blank" class="btn btn-primary float-end">
                    <i class="fas fa-file-pdf"></i> Inventaire Médicaments PDF
                </a>
            </div>

            <div class="col">
                <a href="#" class="btn btn-2 float-end mr-3" data-bs-toggle="modal" data-bs-target="#modal-report">Ajouter un Médicament</a>
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
                                <th>Nom</th>
                                <th>Prix Achat</th>
                                <th>Prix Vente</th>
                                <th>Stock</th>
                                <th>Date Péremption</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($medicaments as $medicament)
                                <tr class="{{ $medicament->stock <= $medicament->stock_alerte ? 'bg-danger-lt' : '' }}">
                                    <td>{{ $medicament->nom }}</td>
                                    <td>{{ number_format($medicament->prix_achat, 0) }} FCFA</td>
                                    <td>{{ number_format($medicament->prix_vente, 0) }} FCFA</td>
                                    <td>
                                        <span class="{{ $medicament->stock <= $medicament->stock_alerte ? 'text-danger' : '' }}">
                                            {{ $medicament->stock }} {{ $medicament->unite_mesure }}
                                        </span>
                                    </td>
                                    <td>{{ $medicament->date_peremption ? $medicament->date_peremption->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $medicament->id }}">Modifier</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-stock-{{ $medicament->id }}">Gérer Stock</a>
                                                    <button class="dropdown-item" onclick="confirmDelete({{ $medicament->id }})">Supprimer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal d'édition -->
                                <div class="modal modal-blur fade" id="modal-edit-{{ $medicament->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modifier Médicament</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('medicaments.update', $medicament->id) }}" method="POST" class="form-loader">
                                                @csrf @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Code</label>
                                                                <input type="text" class="form-control" name="code" value="{{ $medicament->code }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Nom</label>
                                                                <input type="text" class="form-control" name="nom" value="{{ $medicament->nom }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="mb-3">
                                                                <label class="form-label">Unité de mesure</label>
                                                                <input type="text" class="form-control" name="unite_mesure" value="{{ $medicament->unite_mesure }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="mb-3">
                                                                <label class="form-label">Prix d'achat (FCFA)</label>
                                                                <input type="number" step="0.01" class="form-control" name="prix_achat" value="{{ $medicament->prix_achat }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="mb-3">
                                                                <label class="form-label">Prix de vente (FCFA)</label>
                                                                <input type="number" step="0.01" class="form-control" name="prix_vente" value="{{ $medicament->prix_vente }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="mb-3">
                                                                <label class="form-label">Stock actuel</label>
                                                                <input type="number" class="form-control" name="stock" value="{{ $medicament->stock }}" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="mb-3">
                                                                <label class="form-label">Stock d'alerte</label>
                                                                <input type="number" class="form-control" name="stock_alerte" value="{{ $medicament->stock_alerte }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="mb-3">
                                                                <label class="form-label">Date péremption</label>
                                                                <input type="date" class="form-control" name="date_peremption" value="{{ $medicament->date_peremption ? $medicament->date_peremption->format('Y-m-d') : '' }}">
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

                                <!-- Modal de gestion de stock -->
                                <div class="modal modal-blur fade" id="modal-stock-{{ $medicament->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Gestion de stock - {{ $medicament->nom }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('medicaments.update-stock', $medicament->id) }}" method="POST" class="form-loader">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Type d'opération</label>
                                                        <select class="form-select" name="operation_type" id="operation-type-{{ $medicament->id }}" required>
                                                            <option value="entree">Entrée en stock</option>
                                                            <option value="sortie">Sortie de stock</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Quantité</label>
                                                        <input type="number" class="form-control" name="quantite" min="1" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Motif (optionnel)</label>
                                                        <textarea class="form-control" name="motif" rows="2"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="#" class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal">Annuler</a>
                                                    <button type="submit" class="btn btn-primary btn-5 ms-auto">Valider</button>
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
                <h5 class="modal-title">Nouveau Médicament</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('medicaments.store') }}" method="POST" class="form-loader">
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
                                <label class="form-label">Nom</label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" value="{{ old('nom') }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Prix d'achat (FCFA)</label>
                                <input type="number" step="0.01" class="form-control @error('prix_achat') is-invalid @enderror" name="prix_achat" value="{{ old('prix_achat') }}" required>
                                @error('prix_achat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Prix de vente (FCFA)</label>
                                <input type="number" step="0.01" class="form-control @error('prix_vente') is-invalid @enderror" name="prix_vente" value="{{ old('prix_vente') }}" required>
                                @error('prix_vente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Stock initial</label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock', 0) }}" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Stock d'alerte</label>
                                <input type="number" class="form-control @error('stock_alerte') is-invalid @enderror" name="stock_alerte" value="{{ old('stock_alerte', 10) }}" required>
                                @error('stock_alerte')
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

<!-- Script pour la suppression -->
<script>
function confirmDelete(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce médicament ?')) {
        document.getElementById('delete-form-'+id).submit();
    }
}
</script>

@foreach ($medicaments as $medicament)
<form id="delete-form-{{ $medicament->id }}" action="{{ route('medicaments.destroy', $medicament->id) }}" method="POST" class="form-loader" style="display: none;">
    @csrf @method('DELETE')
</form>
@endforeach

@endsection

@push('styles')
    <!-- CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <style>
        .bg-danger-lt {
            background-color: rgba(247, 100, 100, 0.1) !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- JS DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

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
                "columnDefs": [
                    { "orderable": false, "targets": [8] } // Désactiver le tri sur la colonne Actions
                ]
            });
        });
    </script>
@endpush