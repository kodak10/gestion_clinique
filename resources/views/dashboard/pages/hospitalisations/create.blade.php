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

                <form action="{{ route('hospitalisations.facture.store', $hospitalisation->id) }}" method="POST" id="examenForm">
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
                                                <input type="text" class="form-control" id="assurance-taux" value="{{ $patient->assurance->taux ?? '0' }}%" readonly>
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

                        <div class="col-md-12">
                            <div class="card mb-3">
                                
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Date d'entrée -->
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label class="form-label">Date d’entrée</label>
                                                <input type="datetime-local" class="form-control" name="date_entree" value="{{ old('date_entree', now()->format('Y-m-d\TH:i')) }}">
                                            </div>
                                        </div>

                                        <!-- Date de sortie -->
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label class="form-label">Date de sortie</label>
                                                <input type="datetime-local" class="form-control" name="date_sortie" value="{{ old('date_sortie') }}">
                                            </div>
                                        </div>

                                        <!-- Caution -->
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label class="form-label">Caution</label>
                                                <input type="number" class="form-control" name="caution" placeholder="Montant versé" value="{{ old('caution', 0) }}">
                                            </div>
                                        </div>
                                        <!-- payeur -->
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label class="form-label">Payeur</label>
                                                <input type="text" class="form-control" name="payeur" placeholder="Nom de la personne" value="{{ old('payeur') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Colonne Laboratoire (catégorie 3) -->
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-3">Examens Laboratoire</h4>
                                <a href="#" target="_blank" class="btn btn-sm btn-primary mb-3">
                                    <i class="fas fa-print"></i> Imprimer
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="5%"></th>
                                            <th>Examen</th>
                                            <th>Prix unitaire</th>
                                            <th>Quantité</th>
                                            <th width="20%">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($detailsLaboratoire as $examen)
                                        <tr>
                                            <td>
                                                    <input type="checkbox" class="form-control" >
                                                </td>
                                            <td>{{ $examen->frais->libelle }}</td>
                                            
                                            <td>{{ number_format($examen->prix_unitaire, 0, ',', ' ') }}</td>
                                            <td>{{ $examen->quantite }}</td>
                                            <td>{{ number_format($examen->total, 0, ',', ' ') }}</td>
                                            
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Colonne Pharmacie (catégorie 6) -->
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-3">Pharmacie</h4>
                                <a href="#" target="_blank" class="btn btn-sm btn-primary mb-3">
                                    <i class="fas fa-print"></i> Imprimer
                                </a>
                            </div>                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="5%"></th>
                                            <th>Médicament</th>
                                            <th>Prix unitaire</th>
                                            <th>Quantité</th>
                                            <th width="20%">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($detailsPharmacie as $detail)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="form-control" >
                                                </td>
                                                <td>{{ $detail->frais->libelle }}</td>
                                                <td>{{ $detail->quantite }}</td>
                                                <td>{{ number_format($detail->prix_unitaire, 0, ',', ' ') }}</td>
                                                <td>{{ number_format($detail->total, 0, ',', ' ') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12 mt-5">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h3 class="card-title">Autres frais</h3>
                                </div>
                                <div class="card-body">
                                    <div class="autres-repeater">
                                        <!-- Changé 'autres' en 'frais' pour correspondre à la validation -->
                                        <div data-repeater-list="frais">
                                            @forelse($autresFrais as $detail)
                                            <div data-repeater-item class="mb-3 border-bottom pb-3">
                                                <div class="row align-items-end">
                                                    <div class="col-md-5">
                                                        <select class="form-select frais-select" name="frais_id" required>
                                                            @foreach($autresFrais as $categorie)
                                                                <optgroup label="{{ $categorie->libelle }}">
                                                                    @foreach($categorie->fraisHospitalisations as $frais)
                                                                        <option value="{{ $frais->id }}" data-prix="{{ $frais->montant }}"
                                                                            @if($frais->id == $detail->frais_hospitalisation_id) selected @endif>
                                                                            {{ $frais->libelle }}
                                                                        </option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control prix" name="prix" 
                                                            value="{{ $detail->prix_unitaire }}" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control quantite" name="quantite" 
                                                            value="{{ $detail->quantite }}" min="1" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control total" name="total" 
                                                            value="{{ $detail->total }}" readonly>
                                                    </div>
                                                    <div class="col-md-1 text-center">
                                                        <button type="button" data-repeater-delete class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @empty
                                            <div data-repeater-item class="mb-3 border-bottom pb-3">
                                                <div class="row align-items-end">
                                                    <div class="col-md-5">
                                                        <select class="form-select frais-select" name="frais_id" required>
                                                            @foreach($autresFrais as $categorie)
                                                                <optgroup label="{{ $categorie->libelle }}">
                                                                    @foreach($categorie->fraisHospitalisations as $frais)
                                                                        <option value="{{ $frais->id }}" data-prix="{{ $frais->montant }}">
                                                                            {{ $frais->libelle }}
                                                                        </option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control prix" name="prix" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control quantite" name="quantite" value="1" min="1" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control total" name="total" readonly>
                                                    </div>
                                                    <div class="col-md-1 text-center">
                                                        <button type="button" data-repeater-delete class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforelse
                                        </div>

                                        <button type="button" data-repeater-create class="btn bg-primary-subtle text-primary">
                                            <span class="fs-4 me-1">+</span> Ajouter un autre frais
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-5">
                                 <a href="{{ route('hospitalisations.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour
                                </a>
                                                            <button type="submit" class="btn btn-primary">Enregistrer</button>

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

<script>
$(document).ready(function() {
     const medecinSelect = document.getElementById('medecin-select');
        const specialiteInput = document.getElementById('specialite-input');

        medecinSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const specialite = selectedOption.getAttribute('data-specialite');
            specialiteInput.value = specialite || '';
        });

    // Initialisation du repeater pour les autres frais
    $('.autres-repeater').repeater({
        initEmpty: false,
        show: function() {
            $(this).slideDown(function() {
                // Initialiser Select2 pour le nouveau select
                $(this).find('.frais-select').select2({
                    width: '100%',
                    placeholder: "Sélectionner un frais"
                });

                // Déclencher le changement pour calculer le total
                $(this).find('.frais-select').trigger('change');
            });
        },
        hide: function(deleteElement) {
            $(this).slideUp(deleteElement);
            updateTotals();
        },
        ready: function(setIndexes) {
            // Initialiser Select2 pour les éléments existants
            $('.frais-select').select2({
                width: '100%',
                placeholder: "Sélectionner un frais"
            });
        }
    });

    // Calcul du total pour une ligne
    function calculateRowTotal(row) {
        const prix = parseFloat(row.find('.prix').val()) || 0;
        const quantite = parseInt(row.find('.quantite').val()) || 0;
        row.find('.total').val((prix * quantite).toFixed(0));
    }

    // Écouteurs d'événements pour les champs prix et quantité
    $(document).on('input', '.prix, .quantite', function() {
        const row = $(this).closest('[data-repeater-item]');
        calculateRowTotal(row);
        updateTotals();
    });

    // Écouteur pour le changement de sélection des frais
    $(document).on('change', '.frais-select', function() {
        const selectedOption = $(this).find('option:selected');
        const prix = selectedOption.data('prix') || 0;
        const row = $(this).closest('[data-repeater-item]');
        row.find('.prix').val(prix);
        calculateRowTotal(row);
        updateTotals();
    });

    // Mise à jour des totaux
    function updateTotals() {
        let totalAutres = 0;
        $('.autres-repeater [data-repeater-item]').each(function() {
            totalAutres += parseFloat($(this).find('.total').val()) || 0;
        });
        $('#totalAutres').text(totalAutres.toLocaleString('fr-FR') + ' XOF');

        // Mettre à jour le total général si nécessaire
        if ($('#totalGeneral').length) {
            const totalLabo = parseCurrency($('#totalLaboratoire').text());
            const totalPharma = parseCurrency($('#totalPharmacie').text());
            const totalGeneral = totalLabo + totalPharma + totalAutres;
            $('#totalGeneral').text(totalGeneral.toLocaleString('fr-FR') + ' XOF');
        }
    }

    // Fonction pour parser les valeurs monétaires
    function parseCurrency(value) {
        return parseFloat(value.replace(/[^\d,]/g, '').replace(',', '.')) || 0;
    }

    // Initialisation des valeurs pour les éléments existants
    $('.autres-repeater [data-repeater-item]').each(function() {
        calculateRowTotal($(this));
    });
    updateTotals();
});
</script>
@endpush