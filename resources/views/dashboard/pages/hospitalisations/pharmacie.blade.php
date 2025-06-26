@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Pharmacie</h2>
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
        
        <form id="consultation-form" action="{{ route('hospitalisations.pharmacie.store', $hospitalisation->id) }}" method="POST">
    @csrf
    <input type="hidden" name="numero_recu" id="numero-recu">

    <div class="row">
        <!-- Informations Patient -->
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

        <!-- Carte Médicaments -->
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="medicaments-repeater">
                        <div data-repeater-list="medicaments">

                            {{-- Médicaments déjà prescrits --}}
                            @foreach($medicamentsPrescrits as $med)
                                <div data-repeater-item class="mb-3 border-bottom pb-3">
                                    <div class="row mt-2">
                                        <div class="col-md-5">
                                            <select class="form-select medicament-select" name="medicament_id" required>
                                                <option value="">Sélectionner un médicament</option>
                                                @foreach($allMedicaments as $option)
                                                    <option value="{{ $option->id }}"
                                                        data-prix="{{ $option->prix_vente }}"
                                                        {{ $option->id == $med->id ? 'selected' : '' }}>
                                                        {{ $option->nom }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control montant" name="montant" value="{{ $med->pivot->prix_unitaire }}">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control quantite" name="quantite" value="{{ $med->pivot->quantite }}">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control total" name="total" value="{{ $med->pivot->total }}" readonly>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" data-repeater-delete class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Ligne vide pour nouveau médicament (si aucun médicament prescrit) --}}
                            @if($medicamentsPrescrits->isEmpty())
                                <div data-repeater-item class="mb-3 border-bottom pb-3">
                                    <div class="row mt-2">
                                        <div class="col-md-5">
                                            <select class="form-select medicament-select" name="medicament_id" required>
                                                <option value="">Sélectionner un médicament</option>
                                                @foreach($allMedicaments as $med)
                                                    <option value="{{ $med->id }}" data-prix="{{ $med->prix_vente }}">
                                                        {{ $med->nom }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control montant" name="montant" value="0">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control quantite" name="quantite" value="1" min="1">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control total" name="total" value="0" readonly>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" data-repeater-delete class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>

                        <button type="button" data-repeater-create class="btn bg-primary-subtle text-primary mt-3">
                            <span class="fs-4 me-1">+</span>
                            Ajouter un autre médicament
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouton soumettre -->
        <div class="row mt-3">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Enregistrer la consultation</button>
            </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('consultation-form');

    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Bloquer la soumission

        Swal.fire({
            title: 'Confirmation',
            text: `Êtes-vous sûr de vouloir enregistrer cette consultation ?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui, enregistrer',
            cancelButtonText: 'Annuler',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

</script>
<script>
    $(document).ready(function() {
       
$('#a-payer').data('last-value', parseFloat($('#a-payer').val()) || 0);
        // Initialisation Select2
        
        // $('.select2').select2({
        //     width: '100%',
        //     placeholder: "Sélectionner un Médecin"
        // });

        // Initialisation du répéteur avec gestion du recalcul
        $('.medicaments-repeater').repeater({
            initEmpty: false,
            isFirstItemUndeletable: true,
            
            show: function() {
                $(this).slideDown(function() {
                    // Initialisation Select2 pour la nouvelle ligne
                    $(this).find('.medicament-select').select2({
                        width: '100%',
                        placeholder: "Sélectionner une medicament"
                    });
                    
                    // Initialisation des valeurs par défaut
                    $(this).find('.montant').val(0);
                    $(this).find('.quantite').val(1);
                    $(this).find('.total').val(0);
                });
            },
            
           
            hide: function(deleteElement) {
                const item = $(this); // L'élément à supprimer

                Swal.fire({
                    title: 'Confirmation',
                    text: 'Voulez-vous vraiment supprimer ce médicament ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler',
                }).then((result) => {
                    if (result.isConfirmed) {
                        item.slideUp(deleteElement, function() {
                            item.remove();
                            recalculerTotauxGlobaux();
                        });
                    }
                });
            },

            
            ready: function(setIndexes) {
                $('.medicament-select').select2();
                recalculerTotauxGlobaux();
                
                $('[data-repeater-delete]').each(function() {
                    if ($('[data-repeater-item]').length === 1) {
                        $(this).hide();
                    }
                });
            }
        });

        // Gestion des événements
        $(document)
            .on('change', '.medicament-select', function() {
                const selected = $(this).find('option:selected');
                //const montant = selected.data('montant') || 0;
                const montant = selected.data('prix') || 0;
                const ligne = $(this).closest('[data-repeater-item]');
                
                ligne.find('.montant').val(montant);
                calculerTotalLigne(ligne);
            })
            .on('input', '.montant, .quantite', function() {
                const ligne = $(this).closest('[data-repeater-item]');
                calculerTotalLigne(ligne);
            })
            .on('input', '#reduction, #a-payer, #payer', recalculerTotauxGlobaux)
            .on('click', '[data-repeater-delete]', function() {
                if ($('[data-repeater-item]').length === 2) {
                    $('[data-repeater-delete]').hide();
                }
            })
            .on('click', '[data-repeater-create]', function() {
                if ($('[data-repeater-item]').length >= 1) {
                    $('[data-repeater-delete]').show();
                }
            });

        // Fonction de calcul pour une ligne
        function calculerTotalLigne(ligne) {
            const qte = parseFloat(ligne.find('.quantite').val()) || 0;
            const montant = parseFloat(ligne.find('.montant').val()) || 0;
            const total = (qte * montant).toFixed(2);
            
            ligne.find('.total').val(total);
            recalculerTotauxGlobaux();
        }

        // Fonction de recalcul global
        // function recalculerTotauxGlobaux() {
        //     let totalmedicaments = 0;
            
        //     $('[data-repeater-item]').each(function() {
        //         const total = parseFloat($(this).find('.total').val()) || 0;
        //         totalmedicaments += total;
        //     });

        //     const tauxAssurance = parseFloat($('#assurance-taux').val().replace('%', '')) || 0;
        //     const ticketModerateur = totalmedicaments * (1 - tauxAssurance / 100);
            
        //     const reduction = parseFloat($('#reduction').val()) || 0;
        //     const montantAPayer = Math.max(0, ticketModerateur - reduction);
            
        //     $('#total-medicaments').val(totalmedicaments.toFixed(2));
        //     $('#ticket-moderateur').val(ticketModerateur.toFixed(2));
        //     $('#a-payer').val(montantAPayer.toFixed(2));
        //     $('#payer').val(montantAPayer.toFixed(2));
        // }

        function recalculerTotauxGlobaux() {
            let totalmedicaments = 0;
            
            $('[data-repeater-item]').each(function() {
                const total = parseFloat($(this).find('.total').val()) || 0;
                totalmedicaments += total;
            });

            const tauxAssurance = parseFloat($('#assurance-taux').val().replace('%', '')) || 0;
            const ticketModerateur = totalmedicaments * (1 - tauxAssurance / 100);
            
            const reduction = parseFloat($('#reduction').val()) || 0;
            const montantAPayer = Math.max(0, ticketModerateur - reduction);
            
            $('#total-medicaments').val(totalmedicaments.toFixed(2));
            $('#ticket-moderateur').val(ticketModerateur.toFixed(2));
            $('#a-payer').val(montantAPayer.toFixed(2));
            
            // Ne remplir que si le champ est vide ou si la valeur actuelle correspond au montant précédent
            const payerActuel = parseFloat($('#payer').val()) || 0;
            if (payerActuel === 0 || payerActuel === parseFloat($('#a-payer').data('last-value'))) {
                $('#payer').val(montantAPayer.toFixed(2));
            }
            
            // Stocker la nouvelle valeur pour comparaison future
            $('#a-payer').data('last-value', montantAPayer.toFixed(2));
        }

        // Initialisation des valeurs si old() existe
        @if(old('medicaments'))
            recalculerTotauxGlobaux();
        @endif
    });
</script>
@endpush