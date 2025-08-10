@extends('dashboard.layouts.master')

@section('content')
<div class="page-body">
    <div class="container-xl">
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
            

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Des erreurs ont été détectées :</strong>
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('hospitalisations.facture.store', $hospitalisation->id) }}" method="POST" id="examenForm" >
                @csrf
                <div class="row">
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
                                            <input type="text" class="form-control" id="assurance-taux" value="{{ $patient->taux_couverture ?? '0' }}%" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                            <select class="form-control select2" id="medecin-select" name="medecin_id" required>
                                                <option value="">Sélectionner un Médecin</option>
                                                @foreach($categorie_medecins as $categorie_medecin)
                                                    <optgroup label="{{ $categorie_medecin->nom }}">
                                                        @foreach($categorie_medecin->medecins as $medecin)
                                                            <option value="{{ $medecin->id }}" 
                                                                data-specialite="{{ $categorie_medecin->nom }}" 
                                                                data-specialite-id="{{ $categorie_medecin->id }}"
                                                                {{ old('medecin_id', $hospitalisation->medecin_id) == $medecin->id ? 'selected' : '' }}>
                                                                {{ $medecin->nom_complet }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                            @error('medecin_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Spécialité</label>
                                            <input type="text" class="form-control" id="specialite-input" name="specialite" value="{{ old('specialite', $hospitalisation->medecin->specialite->nom ?? '') }}" readonly>
                                            <input type="hidden" id="specialite-id" name="specialite_id" value="{{ old('specialite_id', $hospitalisation->medecin->specialite_id ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label">Date d'entrée</label>
                                            <input type="datetime-local" class="form-control @error('date_entree') is-invalid @enderror" name="date_entree" 
                                                value="{{ old('date_entree', $hospitalisation->date_entree ? (\Carbon\Carbon::parse($hospitalisation->date_entree)->format('Y-m-d\TH:i')) : now()->format('Y-m-d\TH:i')) }}">
                                            @error('date_entree')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label">Date de sortie</label>
                                            <input type="datetime-local" class="form-control @error('date_sortie') is-invalid @enderror" name="date_sortie" 
                                                value="{{ old('date_sortie', $hospitalisation->date_sortie ? \Carbon\Carbon::parse($hospitalisation->date_sortie)->format('Y-m-d\TH:i') : '') }}">
                                            @error('date_sortie')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label">Caution</label>
                                            <input type="number" class="form-control @error('caution') is-invalid @enderror" id="caution" name="caution" placeholder="Montant versé" value="{{ old('caution', $hospitalisation->caution ?? 0) }}">
                                            @error('caution')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label">Payeur</label>
                                            <input type="text" class="form-control @error('payeur') is-invalid @enderror" name="payeur" placeholder="Nom de la personne" value="{{ old('payeur', $hospitalisation->payeur ?? '') }}">
                                            @error('payeur')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-3 ">Examens Laboratoire</h4>
                            <div>
                                <span class="badge bg-primary me-2 text-white">
                                    {{ number_format($detailsLaboratoire->sum('total'), 0, ',', ' ') }} XOF
                                </span>
                                <a href="{{ route('print.laboratoire', $hospitalisation->id) }}" 
                                target="_blank"
                                class="btn btn-sm btn-primary">
                                    <i class="fas fa-print"></i> Imprimer
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-3">Pharmacie</h4>
                            <div>
                                <span class="badge bg-primary me-2 text-white">
                                    {{ number_format($detailsPharmacie->sum('total'), 0, ',', ' ') }} XOF
                                </span>
                                <a href="{{ route('print.pharmacie', $hospitalisation->id) }}" 
                                target="_blank"
                                class="btn btn-sm btn-primary">
                                    <i class="fas fa-print"></i> Imprimer
                                </a>
                            </div>
                        </div>
                    </div>
                   
                    <div class="col-md-12 mt-5">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3 class="card-title">Frais d'hospitalisation</h3>
                            </div>
                            <div class="card-body">
                                <div class="autres-repeater">
                                    <div data-repeater-list="frais">

                                        {{-- Ligne fixe : Pharmacie --}}
                                        <div class="mb-3 border-bottom pb-3">
                                            <div class="row align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label">Libellé</label>
                                                    <input type="text" class="form-control" value="Pharmacie" readonly>
                                                    <input type="hidden" name="frais_pharmacie[frais_id]" value="2">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Prix unitaire</label>
                                                    <input type="hidden" name="frais_pharmacie[frais_id]" value="2">
                                                    <input type="number" class="form-control prix" name="frais_pharmacie[prix]" value="{{ $detailsPharmacie->first()->prix_unitaire ?? 0 }}" readonly>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">Quantité</label>
                                                    <input type="number" class="form-control quantite" name="frais_pharmacie[quantite]" value="{{ $detailsPharmacie->first()->quantite ?? 1 }}" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Prise en charge</label>
                                                    <input type="number" class="form-control taux" name="frais_pharmacie[taux]" value="0" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Total</label>
                                                    <input type="number" class="form-control total" name="frais_pharmacie[total]" value="{{ $detailsPharmacie->first()->total ?? 0 }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Ligne fixe : Laboratoire --}}
                                        <div class="mb-3 border-bottom pb-3">
                                            <div class="row align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label">Libellé</label>
                                                    <input type="text" class="form-control" value="Laboratoire" readonly>
                                                    <input type="hidden" name="frais_laboratoire[frais_id]" value="1">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Prix unitaire</label>
                                                    <input type="hidden" name="frais_laboratoire[frais_id]" value="1">
                                                    <input type="number" class="form-control prix" name="frais_laboratoire[prix]" value="{{ $detailsLaboratoire->first()->prix_unitaire ?? 0 }}" readonly>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">Quantité</label>
                                                    <input type="number" class="form-control quantite" name="frais_laboratoire[quantite]" value="{{ $detailsLaboratoire->first()->quantite ?? 1 }}" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Prise en charge</label>
                                                    <input type="number" class="form-control taux" name="frais_laboratoire[taux]" value="0" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Total</label>
                                                    <input type="number" class="form-control total" name="frais_laboratoire[total]" value="{{ $detailsLaboratoire->first()->total ?? 0 }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Autres frais dynamiques --}}
                                        @php $currentIndex = 0; @endphp
                                        @foreach($autresDetails as $detail)
                                            <div data-repeater-item class="mb-3 border-bottom pb-3">
                                                <div class="row align-items-end">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Libellé</label>
                                                        <input type="text" class="form-control" value="{{ $detail->fraisHospitalisation->libelle }}" readonly>
                                                        <input type="hidden" name="frais[{{ $currentIndex }}][frais_id]" value="{{ $detail->frais_hospitalisation_id }}">
                                                        <input type="hidden" name="frais[{{ $currentIndex }}][detail_id]" value="{{ $detail->id }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Prix unitaire</label>
                                                        <input type="number" class="form-control prix" name="frais[{{ $currentIndex }}][prix]" value="{{ $detail->prix_unitaire }}" required>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label class="form-label">Quantité</label>
                                                        <input type="number" class="form-control quantite" name="frais[{{ $currentIndex }}][quantite]" value="{{ $detail->quantite }}" min="1" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Prise en charge</label>
                                                        <input type="number" class="form-control taux" name="frais[{{ $currentIndex }}][taux]" value="{{ $detail->taux ?? $patient->taux_couverture }}" min="0" max="100" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Total</label>
                                                        <input type="number" class="form-control total" name="frais[{{ $currentIndex }}][total]" value="{{ $detail->total }}" readonly>
                                                    </div>
                                                    <div class="col-md-1 text-center">
                                                        <button type="button" data-repeater-delete class="btn btn-danger btn-sm mt-4">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @php $currentIndex++; @endphp
                                        @endforeach

                                        {{-- Modèle pour ajout dynamique --}}
                                        <div data-repeater-item class="mb-3 border-bottom pb-3">
                                            <div class="row align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label">Libellé</label>
                                                    <select class="form-select frais-select" data-name="frais[__index__][frais_id]" required>
                                                        <option value="" disabled selected>Choisir un frais</option>
                                                        @foreach($autresFrais as $frais)
                                                            <option value="{{ $frais->id }}" data-prix="{{ $frais->montant }}">
                                                                {{ $frais->libelle }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Prix unitaire</label>
                                                    <input type="number" class="form-control prix" data-name="frais[__index__][prix]" value="0" required>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">Quantité</label>
                                                    <input type="number" class="form-control quantite" data-name="frais[__index__][quantite]" value="1" min="1" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Prise en charge</label>
                                                    <input type="number" class="form-control taux" data-name="frais[__index__][taux]" value="{{ $patient->taux_couverture ?? 70 }}" min="0" max="100" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Total</label>
                                                    <input type="number" class="form-control total" data-name="frais[__index__][total]" value="0" readonly>
                                                </div>
                                                <div class="col-md-1 text-center">
                                                    <button type="button" data-repeater-delete class="btn btn-danger btn-sm mt-4">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <button type="button" data-repeater-create class="btn bg-primary-subtle text-primary mt-2">
                                        <span class="fs-4 me-1">+</span> Ajouter un autre frais
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Total</label>
                                    <input type="number" class="form-control" id="total-prestations" name="total" value="{{ old('total', 0) }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Ticket Modérateur</label>
                                    <input type="number" class="form-control" id="ticket-moderateur" name="ticket_moderateur" value="{{ old('ticket_moderateur', 0) }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Réduction</label>
                                    <div class="d-flex">
                                        <input type="number" class="form-control mr-3 @error('reduction') is-invalid @enderror" id="reduction" name="reduction" min="0" value="{{ old('reduction', $hospitalisation->reduction ?? 0) }}">
                                        <input type="text" class="form-control @error('reduction_par') is-invalid @enderror" id="reduction_par" name="reduction_par" value="{{ old('reduction_par', $hospitalisation->reduction_par ?? '') }}" placeholder="Accordé par">
                                    </div>
                                    @error('reduction')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @error('reduction_par')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">À Payer</label>
                                    <input type="number" class="form-control" id="a-payer" name="montant_a_paye" value="{{ old('montant_a_paye', 0) }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-5">
                    <a href="{{ route('hospitalisations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span id="submitText">Enregistrer</span>
                        <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
$(document).ready(function() {
    // Initialisation Select2
    $('.select2').select2({ width: '100%' });

    // Fonction pour initialiser Select2 sur un élément
    function initSelect2(element) {
        $(element).select2({
            width: '100%',
            placeholder: "Choisir un frais"
        }).on('change', function() {
            const prix = $(this).find('option:selected').data('prix') || 0;
            const row = $(this).closest('[data-repeater-item]');
            row.find('.prix').val(prix);
            calculerTotalLigne(row);
            calculerTousLesTotaux();
        });
    }

    // Repeater pour les autres frais
    $('.autres-repeater').repeater({
        initEmpty: false,
        show: function() {
            const newItem = $(this).slideDown();
            // Initialiser Select2 après la création de l'élément
            initSelect2(newItem.find('.frais-select'));
            
            // Mettre à jour les noms des champs
            newItem.find('[data-name]').each(function() {
                const name = $(this).data('name').replace('__index__', newItem.index());
                $(this).attr('name', name);
            });
        },
        hide: function(deleteElement) {
            $(this).slideUp(deleteElement, function() {
                $(this).remove();
                calculerTousLesTotaux();
            });
        },
        ready: function(setIndexes) {
            // Initialiser Select2 pour les éléments existants
            $('.frais-select').each(function() {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    initSelect2(this);
                }
            });
        }
    });

    // Calcul du total d'une ligne (affiche la part patient)
    function calculerTotalLigne(row) {
        const prix = parseFloat(row.find('.prix').val()) || 0;
        const quantite = parseInt(row.find('.quantite').val()) || 0;
        const taux = parseFloat(row.find('.taux').val()) || 0;
        const montantBrut = prix * quantite;
        const partCouverture = (montantBrut * taux) / 100;
        const partPatient = montantBrut - partCouverture;
        row.find('.total').val(Math.round(partPatient));
        row.data('montant-brut', montantBrut); // Pour le calcul du total général
    }

    // Calcul général
    function calculerTousLesTotaux() {
        // Pharmacie
        const prixPharma = parseFloat($('[name="frais_pharmacie[prix]"]').val()) || 0;
        const qtePharma = parseInt($('[name="frais_pharmacie[quantite]"]').val()) || 0;
        const tauxPharma = parseFloat($('[name="frais_pharmacie[taux]"]').val()) || 100;
        const montantPharma = prixPharma * qtePharma;
        const partPharma = montantPharma;

        // Laboratoire
        const prixLabo = parseFloat($('[name="frais_laboratoire[prix]"]').val()) || 0;
        const qteLabo = parseInt($('[name="frais_laboratoire[quantite]"]').val()) || 0;
        const tauxLabo = parseFloat($('[name="frais_laboratoire[taux]"]').val()) || 100;
        const montantLabo = prixLabo * qteLabo;
        const partLabo = montantLabo ;

        // Autres frais
        let totalAutresBrut = 0;
        let totalAutresPatient = 0;
        $('.autres-repeater [data-repeater-item]').each(function() {
            calculerTotalLigne($(this)); // Toujours recalculer la ligne
            const prix = parseFloat($(this).find('.prix').val()) || 0;
            const quantite = parseInt($(this).find('.quantite').val()) || 0;
            const taux = parseFloat($(this).find('.taux').val()) || 0;
            const montantBrut = prix * quantite;
            const partCouverture = (montantBrut * taux) / 100;
            const partPatient = montantBrut - partCouverture;
            totalAutresBrut += montantBrut;
            totalAutresPatient += partPatient;
        });

        // Total général (somme des montants bruts)
        const totalPrestations = montantPharma + montantLabo + totalAutresBrut;
        $('#total-prestations').val(Math.round(totalPrestations));

        // Ticket modérateur (somme des parts patient)
        const ticketModerateur = partPharma + partLabo + totalAutresPatient;
        $('#ticket-moderateur').val(Math.round(ticketModerateur));

        // Réduction et à payer
        const reduction = parseFloat($('#reduction').val()) || 0;
        const caution = parseFloat($('#caution').val()) || 0;
        const montantAPayer = Math.max(0, ticketModerateur - reduction - caution);
        $('#a-payer').val(Math.round(montantAPayer));
    }

    // Sélection d'un frais => maj prix unitaire
    $(document).on('change', '.frais-select', function() {
        const prix = $(this).find('option:selected').data('prix') || 0;
        const row = $(this).closest('[data-repeater-item]');
        row.find('.prix').val(prix);
        calculerTotalLigne(row);
        calculerTousLesTotaux();
    });

    // Saisie sur un champ de frais => recalcul
    $(document).on('input', '.prix, .quantite, .taux, #reduction, #caution', function() {
        let row = $(this).closest('[data-repeater-item]');
        if (!row.length) row = $(this).closest('.mb-3');
        calculerTotalLigne(row);
        calculerTousLesTotaux();
    });

    // Initialisation au chargement
    $('.autres-repeater [data-repeater-item]').each(function() {
        calculerTotalLigne($(this));
    });
    calculerTousLesTotaux();

    // Validation réduction
    $('#reduction').on('change', function() {
        if (parseFloat($(this).val()) > 0 && $('#reduction_par').val() === '') {
            Swal.fire({ icon: 'error', title: 'Erreur', text: 'Veuillez indiquer qui a accordé la réduction' });
            $(this).focus();
        }
    });

    // Spécialité médecin
    $('#medecin-select').on('change', function() {
        const specialite = $(this).find('option:selected').data('specialite') || '';
        const specialiteId = $(this).find('option:selected').data('specialite-id') || '';
        $('#specialite-input').val(specialite);
        $('#specialite-id').val(specialiteId);
    });
    if ($('#medecin-select').val()) $('#medecin-select').trigger('change');

    // Soumission formulaire
    $('#examenForm').on('submit', function(e) {
        $('[data-name]').each(function () {
            $(this).attr('name', $(this).data('name'));
        });
        const reduction = parseFloat($('#reduction').val()) || 0;
        if (reduction > 0 && $('#reduction_par').val() === '') {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Erreur', text: 'Veuillez indiquer qui a accordé la réduction' });
            return false;
        }
        const dateEntree = new Date($('[name="date_entree"]').val());
        const dateSortie = new Date($('[name="date_sortie"]').val());
        if (dateSortie && dateSortie < dateEntree) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Erreur', text: 'La date de sortie ne peut pas être antérieure à la date d\'entrée' });
            return false;
        }
        return true;
    });
});
</script>

<script>
$(document).ready(function() {
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            html: `{!! addslashes(session('error')) !!}`
        });
    @endif
});
</script>


@if(session('pdf_url'))
    <script>
        window.onload = function() {
            window.open('{{ session('pdf_url') }}', '_blank');
        };
    </script>
@endif

@endpush