@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Gestion des Examens</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="pharmacie-form" action="{{ route('hospitalisations.laboratoire.store', $hospitalisation->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- Section Informations Patient -->
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Informations Patient</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nom & Prénoms</label>
                                        <input type="text" class="form-control" value="{{ $patient->nom }} {{ $patient->prenoms }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label class="form-label">Assurance</label>
                                        <input type="text" class="form-control" value="{{ $patient->assurance->name ?? 'Aucune' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label class="form-label">Taux Couverture</label>
                                        <input type="text" class="form-control" value="{{ $patient->assurance->taux ?? '0' }}%" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Médicaments -->
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Examens</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Examens</th>
                                            <th>Prix unitaire</th>
                                            <th>Quantité</th>
                                            <th>Total</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <div data-repeater-list="medicaments">
                                        @foreach($medicamentsExistants as $medicament)
                                            <tr data-repeater-item>
                                                <td>
                                                    <select class="form-select medicament-select" name="medicaments[{{ $loop->index }}][medicament_id]" required>
                                                        <option value="">Sélectionner un médicament</option>
                                                        @foreach($allMedicaments as $med)
                                                            <option value="{{ $med->id }}" data-prix="{{ $med->prix_vente }}" @if($med->id == $medicament->id) selected @endif>
                                                                {{ $med->nom }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control prix" name="medicaments[{{ $loop->index }}][prix_unitaire]" 
                                                           value="{{ $medicament->pivot->prix_unitaire }}" required>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control quantite" name="medicaments[{{ $loop->index }}][quantite]" 
                                                           value="{{ $medicament->pivot->quantite }}" min="1" required>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control total" 
                                                           value="{{ $medicament->pivot->prix_unitaire * $medicament->pivot->quantite }}" readonly>
                                                </td>
                                                <td>
                                                    <button type="button" data-repeater-delete class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </div>
                                </table>
                                <button data-repeater-create type="button" class="btn btn-success mt-2">
                                    + Ajouter un médicament
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total général -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 offset-md-9">
                                    <div class="mb-3">
                                        <label class="form-label">Total Général</label>
                                        <input type="number" class="form-control" id="total-general" value="{{ $medicamentsExistants->sum(function($item) { return $item->pivot->prix_unitaire * $item->pivot->quantite; }) }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bouton de soumission -->
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery.repeater@1.2.1/jquery.repeater.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialisation Select2
    $('.medicament-select').select2({
        width: '100%',
        placeholder: "Sélectionner un médicament"
    });

    // Initialiser le repeater
    $('form').repeater({
        initEmpty: false,
        defaultValues: {
            'prix_unitaire': 0,
            'quantite': 1
        },
        show: function () {
            $(this).slideDown();
            $(this).find('.medicament-select').select2({ width: '100%' });
        },
        hide: function (deleteElement) {
            if (confirm('Supprimer ce médicament ?')) {
                $(this).slideUp(deleteElement);
            }
        }
    });

    // Calcul du total pour chaque ligne et mise à jour du total général
    $(document).on('change input', '.medicament-select, .prix, .quantite', function () {
        const row = $(this).closest('tr');
        const prix = parseFloat(row.find('.prix').val()) || 0;
        const quantite = parseInt(row.find('.quantite').val()) || 0;
        const total = prix * quantite;
        row.find('.total').val(total.toFixed(2));
        updateTotalGeneral();
    });

    // Calcul du total général
    function updateTotalGeneral() {
        let totalGeneral = 0;
        $('.total').each(function() {
            totalGeneral += parseFloat($(this).val()) || 0;
        });
        $('#total-general').val(totalGeneral.toFixed(2));
    }

    // Initialiser le total général au chargement
    updateTotalGeneral();
});
</script>
@endpush
