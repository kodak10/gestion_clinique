@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Dépenses</h2>
            </div>
            <div class="col">
                <div class="float-end">
                    <a href="{{ route('depenses.create') }}" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modal-depense">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Ajouter une dépense
                    </a>
                   <a href="#" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-category">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Ajouter une catégorie
                    </a>
                </div>
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
                                <th>Numéro de reçu</th>
                                <th>Libellé</th>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>N° Cheque</th>
                                <th>Catégorie</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($depenses as $depense)
                                <tr>
                                    <td>{{ $depense->numero_recu }}</td>
                                    <td>{{ $depense->libelle }}</td>
                                    <td>{{ number_format($depense->montant, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ $depense->date->format('d/m/Y') }}</td>
                                    <td>{{ $depense->numero_cheque ?? '-' }}</td>
                                    <td>{{ optional($depense->category)->nom ?? 'Non catégorisé' }}</td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $depense->id }}">
                                                        Modifier
                                                    </a>
                                                    <button class="dropdown-item" onclick="confirmDelete({{ $depense->id }})">
                                                        Supprimer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal d'édition -->
                                <div class="modal modal-blur fade" id="modal-edit-{{ $depense->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modifier la dépense</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('depenses.update', $depense->id) }}" method="POST" class="form-loader">
                                                @csrf @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Catégorie</label>
                                                                <select class="form-select" name="category_depense_id">
                                                                    <option value="">Non catégorisé</option>
                                                                    @foreach($categories as $category)
                                                                        <option value="{{ $category->id }}" {{ $depense->category_depense_id == $category->id ? 'selected' : '' }}>
                                                                            {{ $category->nom }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Numéro de reçu</label>
                                                                <input type="text" class="form-control" name="numero_recu" value="{{ $depense->numero_recu }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Montant (FCFA)</label>
                                                                <input type="number" step="0.01" class="form-control" name="montant" value="{{ $depense->montant }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Date</label>
                                                                <input type="date" class="form-control" name="date" value="{{ $depense->date->format('Y-m-d') }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">N° Chèque (optionnel)</label>
                                                                <input type="text" class="form-control" name="numero_cheque" value="{{ $depense->numero_cheque }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Libellé</label>
                                                                <input type="text" class="form-control" name="libelle" value="{{ $depense->libelle }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Description (optionnelle)</label>
                                                        <textarea class="form-control" name="description" rows="3">{{ $depense->description }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Annuler</a>
                                                    <button type="submit" class="btn btn-primary ms-auto">Enregistrer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Formulaire de suppression caché -->
                                <form id="delete-form-{{ $depense->id }}" action="{{ route('depenses.destroy', $depense->id) }}" method="POST" style="display: none;">
                                    @csrf @method('DELETE')
                                </form>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter une catégorie -->
<div class="modal modal-blur fade" id="modal-category" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle catégorie de dépense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('category-depenses.store') }}" method="POST" class="form-loader">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom de la catégorie</label>
                        <input type="text" class="form-control" name="nom" placeholder="Ex: Fournitures de bureau" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description (optionnelle)</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Description de la catégorie..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Annuler</a>
                    <button type="submit" class="btn btn-primary ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 12l5 5l10 -10"></path>
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de création -->
<div class="modal modal-blur fade" id="modal-depense" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle dépense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('depenses.store') }}" method="POST" class="form-loader">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Catégorie</label>
                                <select class="form-select" name="category_depense_id">
                                    <option value="">Non catégorisé</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Numéro de reçu</label>
                                <input type="text" class="form-control" name="numero_recu" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Montant (FCFA)</label>
                                <input type="number" step="0.01" class="form-control" name="montant" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">N° Chèque (optionnel)</label>
                                <input type="text" class="form-control" name="numero_cheque">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Libellé</label>
                                <input type="text" class="form-control" name="libelle" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description (optionnelle)</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Annuler</a>
                    <button type="submit" class="btn btn-primary ms-auto">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette dépense ?')) {
        document.getElementById('delete-form-'+id).submit();
    }
}
</script>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/French.json"
                },
                "order": [[3, "desc"]], // Tri par date par défaut
                "responsive": true
            });
        });
    </script>
@endpush