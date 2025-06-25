@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Gestion des Médicaments</h2>
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

        <form id="pharmacie-form" action="{{ route('hospitalisations.pharmacie.store', $hospitalisation->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- Section Informations Patient (inchangée) -->
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
                                        <input type="text" class="form-control" id="assurance-taux" value="{{ $patient->assurance->taux ?? '0' }}%" readonly>
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
                            <h3 class="card-title">Médicaments prescrits</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="medicaments-table">
                                    <thead>
                                        <tr>
                                            <th>Médicament</th>
                                            <th>Prix unitaire</th>
                                            <th>Quantité</th>
                                            <th>Total</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($medicamentsExistants as $index => $medicament)
                                            <tr>
                                                <td>
                                                    <select class="form-select medicament-select" name="medicaments[{{ $index }}][medicament_id]" required>
                                                        <option value="">Sélectionner un médicament</option>
                                                        @foreach($allMedicaments as $med)
                                                            <option value="{{ $med->id }}" data-prix="{{ $med->prix_vente }}"
                                                                @if($med->id == $medicament->id) selected @endif>
                                                                {{ $med->nom }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control prix" name="medicaments[{{ $index }}][prix_unitaire]" 
                                                        value="{{ $medicament->pivot->prix_unitaire }}" required>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control quantite" name="medicaments[{{ $index }}][quantite]" 
                                                        value="{{ $medicament->pivot->quantite }}" min="1" required>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control total" 
                                                        value="{{ $medicament->pivot->prix_unitaire * $medicament->pivot->quantite }}" readonly>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm btn-supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <!-- Template pour nouvelle ligne (caché) -->
                                        
                                    </tbody>
                                </table>
                                <button type="button" id="btn-add-medicament" class="btn bg-primary-subtle text-primary mt-2">
                                    <span class="fs-4 me-1">+</span> Ajouter un médicament
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialisation Select2
    $('.medicament-select').select2({
        width: '100%',
        placeholder: "Sélectionner un médicament"
    });

    // Compteur pour les nouveaux médicaments
    let nouveauMedicamentCounter = {{ $medicamentsExistants->count() }};

    // Ajouter un nouveau médicament
    $('#btn-add-medicament').click(function() {
        const newRow = $('#new-row-template').clone();
        newRow.attr('id', '').show();
        
        // Remplacer __INDEX__ par le compteur actuel
        newRow.html(newRow.html().replace(/__INDEX__/g, nouveauMedicamentCounter));
        
        $('#medicaments-table tbody').append(newRow);
        
        // Initialiser Select2 pour le nouveau select
        newRow.find('.medicament-select').select2({
            width: '100%',
            placeholder: "Sélectionner un médicament"
        });

        // Déclencher le calcul du prix initial
        newRow.find('.medicament-select').trigger('change');
        
        nouveauMedicamentCounter++;
    });

    // Supprimer un médicament
    $(document).on('click', '.btn-supprimer', function() {
        const row = $(this).closest('tr');
        
        Swal.fire({
            title: 'Confirmation',
            text: "Voulez-vous vraiment supprimer ce médicament?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                row.fadeOut(300, function() {
                    row.remove();
                    updateTotalGeneral();
                });
            }
        });
    });

    // Calcul du total pour une ligne
    function calculateRowTotal(row) {
        const prix = parseFloat(row.find('.prix').val()) || 0;
        const quantite = parseInt(row.find('.quantite').val()) || 0;
        const total = prix * quantite;
        row.find('.total').val(total.toFixed(2));
        return total;
    }

    // Mise à jour du total général
    function updateTotalGeneral() {
        let totalGeneral = 0;

        $('#medicaments-table tbody tr:visible').each(function() {
            if ($(this).attr('id') !== 'new-row-template') {
                totalGeneral += calculateRowTotal($(this));
            }
        });

        $('#total-general').val(totalGeneral.toFixed(2));
    }

    // Écouteurs d'événements
    $(document)
        .on('change', '.medicament-select', function() {
            const selectedOption = $(this).find('option:selected');
            const prix = selectedOption.data('prix') || 0;
            const row = $(this).closest('tr');
            row.find('.prix').val(prix);
            calculateRowTotal(row);
            updateTotalGeneral();
        })
        .on('input', '.prix, .quantite', function() {
            const row = $(this).closest('tr');
            calculateRowTotal(row);
            updateTotalGeneral();
        });

    // Confirmation avant soumission
    $('#pharmacie-form').on('submit', function(e) {
        e.preventDefault();
        
        // Valider les champs requis
        let isValid = true;
        $('#medicaments-table tbody tr:visible').each(function() {
            if ($(this).attr('id') !== 'new-row-template') {
                const medicamentSelect = $(this).find('.medicament-select');
                if (medicamentSelect.val() === '') {
                    isValid = false;
                    medicamentSelect.addClass('is-invalid');
                } else {
                    medicamentSelect.removeClass('is-invalid');
                }
            }
        });
        
        if (!isValid) {
            Swal.fire('Erreur', 'Veuillez sélectionner un médicament pour toutes les lignes', 'error');
            return;
        }
        
        Swal.fire({
            title: 'Confirmation',
            text: "Voulez-vous enregistrer ces modifications?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui, enregistrer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Soumission normale du formulaire (pas en AJAX)
                this.submit();
            }
        });
    });

    // Initialisation des valeurs
    updateTotalGeneral();
});
</script>
@endpush