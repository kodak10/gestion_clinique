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
        <form id="consultation-form" action="{{ route('consultations.store', $patient) }}" method="POST">
            @csrf
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
                                        <label class="form-label">Medecin</label>
                                        <input type="text" class="form-control" value="{{ $patient->assurance->name ?? 'Aucune' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Spécialité</label>
                                        <input type="text" class="form-control" id="assurance-taux" value="{{ $patient->assurance->taux ?? '0' }}%" readonly>
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
                            <div class="repeater">
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
                                                                    {{ $prestation->libelle }} ({{ $prestation->montant }} FCFA)
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Montant</label>
                                                <input type="text" class="form-control montant" name="montant" >
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Quantité</label>
                                                <input type="number" class="form-control quantite" name="quantite" min="1" value="1">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Total</label>
                                                <input type="text" class="form-control total" name="total" readonly>
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
                                <button type="button" data-repeater-create class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Ajouter une prestation
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
                                <input type="text" class="form-control" id="total-prestations" name="total_prestations" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Ticket Modérateur</label>
                                <input type="text" class="form-control" id="ticket-moderateur" name="ticket_moderateur" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Réduction</label>
                                <input type="number" class="form-control" id="reduction" name="reduction" min="0" value="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">À Payer</label>
                                <input type="text" class="form-control" id="a-payer" name="montant_paye" readonly>
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
                            <input type="radio" name="form-payment" value="mastercard" class="form-selectgroup-input" checked="">
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
                        <input type="radio" name="form-payment" value="visa" class="form-selectgroup-input">
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
                        <input type="radio" name="form-payment" value="paypal" class="form-selectgroup-input">
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
<script>
$(document).ready(function() {
    // Initialiser le repeater
    $('.repeater').repeater({
        initEmpty: false,
        defaultValues: {
            'quantite': 1
        },
        show: function() {
            $(this).slideDown();
            // Réinitialiser les champs pour la nouvelle ligne
            $(this).find('select[name="prestation_id"]').val('');
            $(this).find('.montant, .total').val('');
            $(this).find('.quantite').val(1);
        },
        hide: function(deleteElement) {
            if(confirm('Voulez-vous vraiment supprimer cette prestation ?')) {
                $(this).slideUp(deleteElement);
                updateTotals();
            }
        },
        ready: function() {
            updateTotals();
        }
    });

    // Mettre à jour le montant lorsqu'une prestation est sélectionnée
    $(document).on('change', 'select[name="prestation_id"]', function() {
        let selectedOption = $(this).find('option:selected');
        let montant = selectedOption.data('montant') || 0;
        let item = $(this).closest('[data-repeater-item]');
        
        item.find('.montant').val(montant);
        updateItemTotal(item);
        updateTotals();
    });

    // Mettre à jour le total lorsqu'on change la quantité
    $(document).on('input', '.quantite', function() {
        let item = $(this).closest('[data-repeater-item]');
        updateItemTotal(item);
        updateTotals();
    });

    // Mettre à jour la réduction
    $(document).on('input', '#reduction', function() {
        updateTotals();
    });

    // Calculer le total pour une ligne
    function updateItemTotal(item) {
        let montant = parseFloat(item.find('.montant').val()) || 0;
        let quantite = parseInt(item.find('.quantite').val()) || 1;
        let total = montant * quantite;
        item.find('.total').val(total.toFixed(2));
    }

    // Calculer les totaux globaux
    function updateTotals() {
        let totalPrestations = 0;
        
        $('[data-repeater-item]').each(function() {
            let total = parseFloat($(this).find('.total').val()) || 0;
            totalPrestations += total;
        });

        let assuranceTaux = parseFloat($('#assurance-taux').val().replace('%', '')) || 0;
        let ticketModerateur = totalPrestations * (assuranceTaux / 100);
        let reduction = parseFloat($('#reduction').val()) || 0;
        let aPayer = totalPrestations - ticketModerateur - reduction;

        $('#total-prestations').val(totalPrestations.toFixed(2) + ' FCFA');
        $('#ticket-moderateur').val(ticketModerateur.toFixed(2) + ' FCFA');
        $('#a-payer').val(Math.max(0, aPayer).toFixed(2) + ' FCFA');
    }

    // Soumission du formulaire
    $('#consultation-form').on('submit', function(e) {
        e.preventDefault();
        
        // Vérifier qu'il y a au moins une prestation
        if ($('[data-repeater-item]').length === 0) {
            alert('Veuillez ajouter au moins une prestation');
            return;
        }

        // Soumettre le formulaire
        this.submit();
    });
});
</script>
@endpush