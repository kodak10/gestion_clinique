@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Pharmacie du patient</h2>
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
        
        
        <form action="{{ route('hospitalisations.pharmacie.store', $hospitalisation->id) }}" method="POST">

            @csrf
            <div class="row">
                <!-- Carte Info Patient -->
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Informations Patient</h3>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Nom & Prénoms</label>
                                    <input type="text" class="form-control" value="{{ $patient->nom }} {{ $patient->prenoms }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Numéro d'hospitalisation</label>
                                    <input type="text" class="form-control" value="{{ $patient->hospitalisationEnCours->numero ?? 'N/A' }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Prestations -->
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Médicaments prescrits</h3>
                        </div>
                        <div class="card-body">
                            <div class="prestations-repeater">
                                <div data-repeater-list="prestations">
                                    <!-- Afficher d'abord les médicaments existants -->
                                    @foreach($medicamentsExistants as $medicament)
                                    <div data-repeater-item class="mb-3 border-bottom pb-3">
                                        <div class="row mt-2">
                                            <div class="col-md-5">
                                                <label class="form-label">Médicament</label>
                                                <select class="form-control prestation-select" name="prestation_id" required>
                                                    <option value="{{ $medicament['prestation_id'] }}" selected data-montant="{{ $medicament['montant'] }}">
                                                        {{ $medicament['prestation']['libelle'] }}
                                                    </option>
                                                    @foreach($prestations as $prestation)
                                                        @if($prestation->id != $medicament['prestation_id'])
                                                        <option value="{{ $prestation->id }}" data-montant="{{ $prestation->montant }}">
                                                            {{ $prestation->libelle }}
                                                        </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Montant (FCFA)</label>
                                                <input type="number" class="form-control montant" name="montant" value="{{ $medicament['montant'] }}" required readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Quantité</label>
                                                <input type="number" class="form-control quantite" name="quantite" min="1" value="{{ $medicament['quantite'] }}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Total (FCFA)</label>
                                                <input type="number" class="form-control total" name="total" value="{{ $medicament['total'] }}" readonly>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label d-block">.</label>
                                                <button type="button" data-repeater-delete class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                    <!-- Ajouter un champ vide pour les nouveaux médicaments -->
                                    @if(count($medicamentsExistants) == 0)
                                    <div data-repeater-item class="mb-3 border-bottom pb-3">
                                        <div class="row mt-2">
                                            <div class="col-md-5">
                                                <label class="form-label">Médicament</label>
                                                <select class="form-control prestation-select" name="prestation_id" required>
                                                    <option value="">Sélectionner les médicaments</option>
                                                    @foreach($prestations as $medicament)
                                                        <option value="{{ $medicament->id }}" data-montant="{{ $medicament->montant }}">
                                                            {{ $medicament->libelle }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Montant (FCFA)</label>
                                                <input type="number" class="form-control montant" name="montant" required readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Quantité</label>
                                                <input type="number" class="form-control quantite" name="quantite" min="1" value="1" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Total (FCFA)</label>
                                                <input type="number" class="form-control total" name="total" readonly>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label d-block">.</label>
                                                <button type="button" data-repeater-delete class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <button type="button" data-repeater-create class="btn bg-primary-subtle text-primary">
                                    <span class="fs-4 me-1">+</span> Ajouter un autre médicament
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="col-md-4 offset-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="form-label">Total Général</label>
                                <input type="number" id="total-general" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bouton Enregistrer -->
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Enregistrer les médicaments</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- <script>
    $(document).ready(function() {
        $('.prestations-repeater').repeater({
            initEmpty: false,
            isFirstItemUndeletable: true,
            show: function() {
                $(this).slideDown(function() {
                    $(this).find('.prestation-select').select2({
                        width: '100%',
                        placeholder: "Sélectionner un médicament"
                    });
                    $(this).find('.montant').val(0);
                    $(this).find('.quantite').val(1);
                    $(this).find('.total').val(0);
                    updateTotalGeneral();
                });
            },
            hide: function(deleteElement) {
                if ($('[data-repeater-item]').length > 1) {
                    $(this).slideUp(deleteElement, function() {
                        $(this).remove();
                        updateTotalGeneral();
                    });
                }
            },
            ready: function(setIndexes) {
                $('.prestation-select').select2();
                updateTotalGeneral();
            }
        });

        // Gestion des événements
        $(document)
            .on('change', '.prestation-select', function() {
                const selected = $(this).find('option:selected');
                const montant = selected.data('montant') || 0;
                const ligne = $(this).closest('[data-repeater-item]');
                ligne.find('.montant').val(montant);
                calculerTotalLigne(ligne);
            })
            .on('input', '.quantite', function() {
                const ligne = $(this).closest('[data-repeater-item]');
                calculerTotalLigne(ligne);
            });

        function calculerTotalLigne(ligne) {
            const qte = parseFloat(ligne.find('.quantite').val()) || 0;
            const montant = parseFloat(ligne.find('.montant').val()) || 0;
            const total = qte * montant;
            ligne.find('.total').val(total.toFixed(2));
            updateTotalGeneral();
        }

        function updateTotalGeneral() {
            let total = 0;
            $('[data-repeater-item]').each(function() {
                const totalLigne = parseFloat($(this).find('.total').val()) || 0;
                total += totalLigne;
            });
            $('#total-general').val(total.toFixed(2));
        }
    });
</script> --}}

<script>
    $(document).ready(function() {
    // Initialiser le répéteur
    $('.prestations-repeater').repeater({
        initEmpty: false,
        isFirstItemUndeletable: true,
        show: function() {
            $(this).slideDown(function() {
                initSelect2($(this));
                initMontant($(this));
                updateTotalGeneral();
            });
        },
        hide: function(deleteElement) {
            if ($('[data-repeater-item]').length > 1) {
                $(this).slideUp(deleteElement, function() {
                    $(this).remove();
                    updateTotalGeneral();
                });
            }
        },
        ready: function(setIndexes) {
            initSelect2($('[data-repeater-item]'));
            updateTotalGeneral();
        }
    });

    // Initialiser Select2 pour un élément
    function initSelect2(element) {
        element.find('.prestation-select').select2({
            width: '100%',
            placeholder: "Sélectionner un médicament"
        });
    }

    // Initialiser les valeurs de montant
    function initMontant(element) {
        element.find('.montant').val(0);
        element.find('.quantite').val(1);
        element.find('.total').val(0);
    }

    // Gestion des événements
    $(document)
        .on('change', '.prestation-select', function() {
            const selected = $(this).find('option:selected');
            const montant = selected.data('montant') || 0;
            const ligne = $(this).closest('[data-repeater-item]');
            ligne.find('.montant').val(montant);
            calculerTotalLigne(ligne);
        })
        .on('input', '.quantite, .montant', function() {
            const ligne = $(this).closest('[data-repeater-item]');
            calculerTotalLigne(ligne);
        });

    // Calculer le total pour une ligne
    function calculerTotalLigne(ligne) {
        const qte = parseFloat(ligne.find('.quantite').val()) || 0;
        const montant = parseFloat(ligne.find('.montant').val()) || 0;
        const total = qte * montant;
        ligne.find('.total').val(total.toFixed(2));
        updateTotalGeneral();
    }

    // Mettre à jour le total général
    function updateTotalGeneral() {
        let total = 0;
        $('[data-repeater-item]').each(function() {
            const totalLigne = parseFloat($(this).find('.total').val()) || 0;
            total += totalLigne;
        });
        $('#total-general').val(total.toFixed(2));
    }

    // Initialiser les valeurs pour les médicaments existants
    $('[data-repeater-item]').each(function() {
        calculerTotalLigne($(this));
    });

    // Gestion de la soumission du formulaire avec SweetAlert
        $('form').on('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Confirmation',
                text: 'Voulez-vous vraiment enregistrer ces médicaments ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, enregistrer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Soumettre le formulaire si confirmé
                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            Swal.fire(
                                'Succès !',
                                'Les médicaments ont été enregistrés avec succès.',
                                'success'
                            ).then(() => {
                                // Redirection ou autre action après succès
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Erreur !',
                                'Une erreur est survenue lors de l\'enregistrement.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
});
</script>
@endpush