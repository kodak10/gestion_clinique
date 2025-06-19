@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Examen du patient : {{ $patient->nom }} {{ $patient->prenom }}</h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('hospitalisations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Informations Patient</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Nom & Prénoms -->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Nom & Prénoms</label>
                                <input type="text" class="form-control" value="{{ $patient->nom }} {{ $patient->prenoms }}" readonly>
                            </div>
                        </div>

                        <!-- Assurance -->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Assurance</label>
                                <input type="text" class="form-control" value="{{ $patient->assurance->name ?? 'Aucune' }}" readonly>
                            </div>
                        </div>

                        <!-- Taux Couverture -->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Taux Couverture</label>
                                <input type="text" class="form-control" id="assurance-taux" value="{{ $patient->assurance->taux ?? '0' }}%" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                @if(session('swal_success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('swal_success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('hospitalisations.laboratoire.store', $hospitalisation->id) }}" method="POST" id="examenForm">
                    @csrf
                    <div class="examens-repeater">
                        <div data-repeater-list="examens">
                            @forelse($details as $detail)
                                <div data-repeater-item class="mb-3 border-bottom pb-3">
                                    <input type="hidden" name="examens[{{ $loop->index }}][id]" value="{{ $detail->id }}">
                                    <div class="row mt-2 align-items-end">
                                        <div class="col-md-5">
                                            <select class="form-select examen-select" name="examens[{{ $loop->index }}][frais_id]" required>
                                                @foreach($examens as $categorie)
                                                    <optgroup label="{{ $categorie->libelle }}">
                                                        @if(!empty($categorie->fraisHospitalisations))
                                                            @foreach($categorie->fraisHospitalisations as $med)
                                                                <option value="{{ $med->id }}" data-prix="{{ $med->montant }}"
                                                                    @if($med->id == $detail->frais_hospitalisation_id) selected @endif>
                                                                    {{ $med->libelle }}
                                                                </option>
                                                            @endforeach
                                                        @else
                                                            <p>Aucun frais trouvé pour cette catégorie.</p>
                                                        @endif
                                                        {{-- @foreach($categorie->fraisHospitalisations as $med)
                                                            <option value="{{ $med->id }}" data-prix="{{ $med->montant }}"
                                                                @if($med->id == $detail->frais_hospitalisation_id) selected @endif>
                                                                {{ $med->libelle }}
                                                            </option>
                                                        @endforeach --}}
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control prix" name="examens[{{ $loop->index }}][prix]" 
                                                   value="{{ $detail->prix_unitaire }}" >
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control quantite" name="examens[{{ $loop->index }}][quantite]" 
                                                   value="{{ $detail->quantite }}" min="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control total" name="examens[{{ $loop->index }}][total]" 
                                                   value="{{ $detail->total }}" readonly>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <button type="button" data-repeater-delete class="btn btn-danger btn-sm" title="Supprimer cette ligne">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div data-repeater-item class="mb-3 border-bottom pb-3">
                                    <div class="row mt-2 align-items-end">
                                        <div class="col-md-5">
                                            <label class="form-label">Médicament <span class="text-danger">*</span></label>
                                            <select class="form-select examen-select" name="examens[0][frais_id]" required>
                                                @foreach($examens as $categorie)
                                                    <optgroup label="{{ $categorie->libelle }}">
                                                        {{-- @foreach($categorie->fraisHospitalisations as $med)
                                                            <option value="{{ $med->id }}" data-prix="{{ $med->montant }}">
                                                                {{ $med->libelle }} ({{ number_format($med->montant, 0, ',', ' ') }} XOF)
                                                            </option>
                                                        @endforeach --}}
                                                        @forelse($categorie->fraisHospitalisations ?? [] as $med)
                                                            <option value="{{ $med->id }}" data-prix="{{ $med->montant }}">
                                                                {{ $med->libelle }} ({{ number_format($med->montant, 0, ',', ' ') }} XOF)
                                                            </option>
                                                        @empty
                                                            <option disabled>Aucun frais disponible</option>
                                                        @endforelse

                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Prix unitaire (XOF)</label>
                                            <input type="number" class="form-control prix" name="examens[0][prix]" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Quantité <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control quantite" name="examens[0][quantite]" value="1" min="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Total (XOF)</label>
                                            <input type="number" class="form-control total" name="examens[0][total]" readonly>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <button type="button" data-repeater-delete class="btn btn-danger btn-sm" title="Supprimer cette ligne">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <button type="button" data-repeater-create class="btn bg-primary-subtle text-primary">
                            <span class="fs-4 me-1">+</span> Ajouter un médicament
                        </button>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary">Enregistrer le point</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
    // Initialisation de Select2 pour les éléments existants
    $('.examen-select').select2({
        width: '100%',
        placeholder: "Sélectionner un médicament"
    });

    // Attacher les événements aux éléments existants
    $('[data-repeater-item]').each(function() {
        attachexamenEvents($(this));
        updateFieldNames($(this));
    });

    // Déclencher le changement initial
    $('.examen-select').each(function() {
        $(this).trigger('change');
    });

    // Configuration du repeater
    $('.examens-repeater').repeater({
        initEmpty: false,
        
        show: function() {
            $(this).slideDown(function() {
                // Initialisation des valeurs
                $(this).find('.prix').val(0);
                $(this).find('.quantite').val(1);
                $(this).find('.total').val(0);
                
                // Attacher les événements au nouvel élément
                attachexamenEvents($(this));
                
                // Mettre à jour les noms des champs
                updateFieldNames($(this));
            });
        },
        
        hide: function(deleteElement) {
            Swal.fire({
                title: 'Confirmer la suppression',
                text: "Êtes-vous sûr de vouloir supprimer ce médicament ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Détruire Select2 avant de supprimer l'élément
                    $(this).find('.examen-select').select2('destroy');
                    $(this).slideUp(deleteElement);
                }
            });
        },
        
        ready: function(setIndexes) {
            $('[data-repeater-item]').each(function() {
                attachexamenEvents($(this));
                updateFieldNames($(this));
            });

            $('.examen-select').each(function() {
                $(this).trigger('change');
            });
        }
    });

    function updateFieldNames($item) {
        var index = $item.index();
        $item.find('[name]').each(function() {
            var name = $(this).attr('name');
            name = name.replace(/\[\d+\]/, '[' + index + ']');
            $(this).attr('name', name);
        });
    }

    function attachexamenEvents($container) {
        // Gestion du select
        const $select = $container.find('.examen-select');
        
        // Détruire Select2 s'il est déjà initialisé
        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }
        
        // Initialiser Select2
        $select.select2({
            width: '100%',
            placeholder: "Sélectionner un médicament"
        });
        
        // Événement change personnalisé pour Select2
        $select.off('select2:select').on('select2:select', function(e) {
            var prix = $(this).find(':selected').data('prix') || 0;
            var $parent = $(this).closest('[data-repeater-item]');
            $parent.find('.prix').val(prix);
            calculateTotal($parent);
        });
        
        // Déclencher le change pour mise à jour initiale
        $select.trigger('change');
        
        // Gestion de la quantité
        $container.find('.quantite').off('input').on('input', function() {
            calculateTotal($(this).closest('[data-repeater-item]'));
        });
    }

    function calculateTotal($item) {
        var prix = parseFloat($item.find('.prix').val()) || 0;
        var quantite = parseInt($item.find('.quantite').val()) || 0;
        $item.find('.total').val((prix * quantite).toFixed(0));
    }

    $('#examenForm').on('submit', function(e) {
        e.preventDefault();
        var isValid = true;

        $('[data-repeater-item]').each(function() {
            var $item = $(this);
            var examen = $item.find('.examen-select').val();
            var quantite = $item.find('.quantite').val();

            $item.find('.is-invalid').removeClass('is-invalid');
            if (!examen) {
                $item.find('.examen-select').addClass('is-invalid');
                isValid = false;
            }
            if (!quantite || quantite < 1) {
                $item.find('.quantite').addClass('is-invalid');
                isValid = false;
            }
        });

        if (!isValid) {
            Swal.fire('Erreur', 'Veuillez vérifier tous les champs obligatoires', 'error');
            return;
        }

        Swal.fire({
            title: 'Confirmer',
            text: 'Enregistrer cette ordonnance ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui, enregistrer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});
</script>
{{-- <script>
    $(document).ready(function() {
    // Initialisation de Select2 pour les éléments existants
    $('.medicament-select').select2({
        width: '100%',
        placeholder: "Sélectionner un médicament"
    });

    // Attacher les événements aux éléments existants
    $('[data-repeater-item]').each(function() {
        attachMedicamentEvents($(this));
        updateFieldNames($(this));
    });

    // Déclencher le changement initial
    $('.medicament-select').each(function() {
        $(this).trigger('change');
    });

    // Configuration du repeater
    $('.medicaments-repeater').repeater({
        initEmpty: false,
        
        show: function() {
            $(this).slideDown(function() {
                // Initialisation des valeurs
                $(this).find('.prix').val(0);
                $(this).find('.quantite').val(1);
                $(this).find('.total').val(0);
                
                // Attacher les événements au nouvel élément
                attachMedicamentEvents($(this));
                
                // Mettre à jour les noms des champs
                updateFieldNames($(this));
            });
        },
        
        hide: function(deleteElement) {
            Swal.fire({
                title: 'Confirmer la suppression',
                text: "Êtes-vous sûr de vouloir supprimer ce médicament ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Détruire Select2 avant de supprimer l'élément
                    $(this).find('.medicament-select').select2('destroy');
                    $(this).slideUp(deleteElement);
                }
            });
        },
        
        ready: function(setIndexes) {
            $('[data-repeater-item]').each(function() {
                attachMedicamentEvents($(this));
                updateFieldNames($(this));
            });

            $('.medicament-select').each(function() {
                $(this).trigger('change');
            });
        }
    });

    function updateFieldNames($item) {
        var index = $item.index();
        $item.find('[name]').each(function() {
            var name = $(this).attr('name');
            name = name.replace(/\[\d+\]/, '[' + index + ']');
            $(this).attr('name', name);
        });
    }

    function attachMedicamentEvents($container) {
        // Gestion du select
        const $select = $container.find('.medicament-select');
        
        // Détruire Select2 s'il est déjà initialisé
        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }
        
        // Initialiser Select2
        $select.select2({
            width: '100%',
            placeholder: "Sélectionner un médicament"
        });
        
        // Événement change personnalisé pour Select2
        $select.off('select2:select').on('select2:select', function(e) {
            var prix = $(this).find(':selected').data('prix') || 0;
            var $parent = $(this).closest('[data-repeater-item]');
            $parent.find('.prix').val(prix);
            calculateTotal($parent);
        });
        
        // Déclencher le change pour mise à jour initiale
        $select.trigger('change');
        
        // Gestion de la quantité
        $container.find('.quantite').off('input').on('input', function() {
            calculateTotal($(this).closest('[data-repeater-item]'));
        });
    }

    function calculateTotal($item) {
        var prix = parseFloat($item.find('.prix').val()) || 0;
        var quantite = parseInt($item.find('.quantite').val()) || 0;
        $item.find('.total').val((prix * quantite).toFixed(0));
    }

    $('#examenForm').on('submit', function(e) {
        e.preventDefault();
        var isValid = true;

        $('[data-repeater-item]').each(function() {
            var $item = $(this);
            var medicament = $item.find('.medicament-select').val();
            var quantite = $item.find('.quantite').val();

            $item.find('.is-invalid').removeClass('is-invalid');
            if (!medicament) {
                $item.find('.medicament-select').addClass('is-invalid');
                isValid = false;
            }
            if (!quantite || quantite < 1) {
                $item.find('.quantite').addClass('is-invalid');
                isValid = false;
            }
        });

        if (!isValid) {
            Swal.fire('Erreur', 'Veuillez vérifier tous les champs obligatoires', 'error');
            return;
        }

        Swal.fire({
            title: 'Confirmer',
            text: 'Enregistrer cette ordonnance ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui, enregistrer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});
</script> --}}
@endpush

