@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Nouvelle Consultation</h2>
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
        <form id="consultation-form" action="{{ route('consultations.store', $patient) }}" method="POST">
            @csrf
            <input type="hidden" name="numero_recu" id="numero-recu">
            <div class="row">
                <!-- Carte Info Patient (Gauche) -->
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
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Assurance</label>
                                        <input type="text" class="form-control" value="{{ $patient->assurance->name ?? 'Aucune' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Taux Couverture</label>
                                        <input type="text" class="form-control" id="assurance-taux" value="{{ $patient->assurance->taux ?? '0' }}%" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Carte Medecin Traitant (Droite) -->
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Medecin Traitant</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Médecin</label>
                                        <select class="form-control select2" id="medecin-select" name="medecin_id">
                                            <option value="">Sélectionner un Médecin</option>
                                            @foreach($categorie_medecins as $categorie_medecin)
                                                <optgroup label="{{ $categorie_medecin->nom }}">
                                                    @foreach($categorie_medecin->medecins as $medecin)
                                                        <option value="{{ $medecin->id }}" data-specialite="{{ $categorie_medecin->nom }}">
                                                            {{ $medecin->nom_complet }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Spécialité</label>
                                        <input type="text" class="form-control" id="specialite-input" name="specialite" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Prestations -->
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="prestations-repeater">
                                <div data-repeater-list="prestations">
                                    <div data-repeater-item class="mb-3 border-bottom pb-3">
                                        <div class="row mt-2">
                                            <div class="col-md-5">
                                                <label class="form-label">Prestation</label>
                                                <select class="form-control prestation-select" name="prestation_id">
                                                    <option value="">Sélectionner une prestation</option>
                                                    @foreach($categories as $categorie)
                                                        <optgroup label="{{ $categorie->nom }}">
                                                            @foreach($categorie->prestations as $prestation)
                                                                <option value="{{ $prestation->id }}" data-montant="{{ $prestation->montant }}">
                                                                    {{ $prestation->libelle }}
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                                
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Montant (FCFA)</label>
                                                <input type="number" class="form-control montant" name="montant">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Quantité</label>
                                                <input type="number" class="form-control quantite" name="quantite" min="1" value="1">
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
                                </div>
                                
                                <button type="button" data-repeater-create class="btn bg-primary-subtle text-primary">
                                    <span class="fs-4 me-1">+</span>
                                    Ajouter une autre prestation
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte Paiement -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Total Prestations</label>
                                <input type="number" class="form-control" id="total-prestations" name="total" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Ticket Modérateur</label>
                                <input type="number" class="form-control" id="ticket-moderateur" name="ticket_moderateur" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Réduction</label>
                                <input type="number" class="form-control" id="reduction" name="reduction" min="0" value="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">À Payer</label>
                                <input type="number" class="form-control" id="a-payer" name="montant_a_paye"  readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Montant Perçu</label>
                                <input type="number" class="form-control" id="payer" name="montant_paye" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Méthodes de paiement -->
            <div class="row mt-3">
                <div class="col-lg-3">
                    <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                        <label class="form-selectgroup-item flex-fill">
                            <input type="radio" name="methode_paiement" value="cash" class="form-selectgroup-input" checked>
                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                <div class="me-3">
                                    <span class="form-selectgroup-check"></span>
                                </div>
                                <div>
                                    <span class="payment payment-provider-mastercard payment-xs me-2"></span>
                                    <strong>Cash</strong>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="col-lg-5">
                    <label class="form-selectgroup-item flex-fill">
                        <input type="radio" name="methode_paiement" value="mobile_money" class="form-selectgroup-input">
                        <div class="form-selectgroup-label d-flex align-items-center p-3">
                            <div class="me-3">
                                <span class="form-selectgroup-check"></span>
                            </div>
                            <div>
                                <span class="payment payment-provider-visa payment-xs me-2"></span>
                                <strong>Wave / Orange Money / Momo / Moov Money</strong>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="col-lg-4">
                    <label class="form-selectgroup-item flex-fill">
                        <input type="radio" name="methode_paiement" value="virement" class="form-selectgroup-input">
                        <div class="form-selectgroup-label d-flex align-items-center p-3">
                            <div class="me-3">
                                <span class="form-selectgroup-check"></span>
                            </div>
                            <div>
                                <span class="payment payment-provider-visa payment-xs me-2"></span>
                                <strong>Virement</strong>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Bouton de soumission -->
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Enregistrer la consultation</button>
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
        const montantPerçuInput = document.getElementById('payer');

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Bloquer la soumission

            const montant = parseFloat(montantPerçuInput.value || 0).toFixed(0);

            Swal.fire({
                title: 'Confirmation',
                text: `Êtes-vous sûr de l'encaissement de ${Number(montant).toLocaleString('fr-FR')} FCFA ?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, confirmer',
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
    const medecinSelect = document.getElementById('medecin-select');
        const specialiteInput = document.getElementById('specialite-input');

        medecinSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const specialite = selectedOption.getAttribute('data-specialite');
            specialiteInput.value = specialite || '';
        });

    // Initialisation du répéteur avec gestion du recalcul
    $('.prestations-repeater').repeater({
        initEmpty: false, // Toujours afficher au moins une ligne
        isFirstItemUndeletable: true, // Empêche la suppression de la dernière ligne
        
        show: function() {
            $(this).slideDown(function() {
                // Initialisation Select2 pour la nouvelle ligne
                $(this).find('.prestation-select').select2({
                    width: '100%',
                    placeholder: "Sélectionner une prestation"
                });
                
                // Initialisation des valeurs par défaut
                $(this).find('.montant').val(0);
                $(this).find('.quantite').val(1);
                $(this).find('.total').val(0);
            });
        },
        
        hide: function(deleteElement) {
            // Vérifier le nombre de lignes avant suppression
            if ($('[data-repeater-item]').length > 1) {
                $(this).slideUp(deleteElement, function() {
                    $(this).remove(); // Suppression effective
                    recalculerTotauxGlobaux(); // Recalcul après suppression
                });
            }
        },
        
        ready: function(setIndexes) {
            // Initialisation pour les lignes existantes
            $('.prestation-select').select2();
            recalculerTotauxGlobaux();
            
            // Empêcher la suppression de la dernière ligne
            $('[data-repeater-delete]').each(function() {
                if ($('[data-repeater-item]').length === 1) {
                    $(this).hide();
                }
            });
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
        .on('input', '.montant, .quantite', function() {
            const ligne = $(this).closest('[data-repeater-item]');
            calculerTotalLigne(ligne);
        })
        .on('input', '#reduction, #payer', recalculerTotauxGlobaux)
        .on('click', '[data-repeater-delete]', function() {
            // Mise à jour de l'affichage des boutons de suppression
            if ($('[data-repeater-item]').length === 2) {
                $('[data-repeater-delete]').hide();
            }
        })
        .on('click', '[data-repeater-create]', function() {
            // Réafficher les boutons de suppression si nécessaire
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
    function recalculerTotauxGlobaux() {
        let totalPrestations = 0;
        
        // Calcul du total des prestations
        $('[data-repeater-item]').each(function() {
            const total = parseFloat($(this).find('.total').val()) || 0;
            totalPrestations += total;
        });

        // Calcul du ticket modérateur
        const tauxAssurance = parseFloat($('#assurance-taux').val().replace('%', '')) || 0;
        const ticketModerateur = totalPrestations * (1 - tauxAssurance / 100);
        
        // Application de la réduction
        const reduction = parseFloat($('#reduction').val()) || 0;
        const montantAPayer = Math.max(0, ticketModerateur - reduction);
        
        // Mise à jour des champs globaux
        $('#total-prestations').val(totalPrestations.toFixed(2));
        $('#ticket-moderateur').val(ticketModerateur.toFixed(2));
        $('#a-payer').val(montantAPayer.toFixed(2));
       // Ne modifier que si l'utilisateur n'a encore rien saisi
        const montantPercu = $('#payer').val();
        if (!montantPercu || parseFloat(montantPercu) === 0) {
            $('#payer').val(montantAPayer.toFixed(2));
        }


    }
});
</script>
@endpush