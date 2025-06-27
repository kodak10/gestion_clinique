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
                                            <input type="number" class="form-control @error('caution') is-invalid @enderror" name="caution" placeholder="Montant versé" value="{{ old('caution', $hospitalisation->caution ?? 0) }}">
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
                            <h4 class="mb-3">Examens Laboratoire</h4>
                            <div>
                                <span class="badge bg-primary me-2" id="totalLaboratoire">
                                    {{ number_format($detailsLaboratoire->sum('total'), 0, ',', ' ') }} XOF
                                </span>
                                <a href="#" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-print"></i> Imprimer
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-3">Pharmacie</h4>
                            <div>
                                <span class="badge bg-primary me-2" id="totalPharmacie">
                                    {{ number_format($detailsPharmacie->sum('total'), 0, ',', ' ') }} XOF
                                </span>
                                <a href="#" target="_blank" class="btn btn-sm btn-primary">
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
                                        <div class="mb-3 border-bottom pb-3">
                                            <div class="row align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label">Libellé</label>
                                                    <input type="text" class="form-control" value="Pharmacie" readonly>
                                                    <input type="hidden" name="frais[0][frais_id]" value="2">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Prix unitaire</label>
                                                    <input type="number" class="form-control prix" name="frais[0][prix]" value="{{ $detailsPharmacie->first()->prix_unitaire ?? 0 }}" required readonly>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">Quantité</label>
                                                    <input type="number" class="form-control quantite" name="frais[0][quantite]" value="{{ $detailsPharmacie->first()->quantite ?? 1 }}" min="1" required readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Taux (%)</label>
                                                    <input type="number" class="form-control taux" name="frais[0][taux]" value="{{ $patient->taux_couverture }}" min="0" max="100" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Total</label>
                                                    <input type="number" class="form-control total" name="frais[0][total]" value="{{ $detailsPharmacie->first()->total ?? 0 }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3 border-bottom pb-3">
                                            <div class="row align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label">Libellé</label>
                                                    <input type="text" class="form-control" value="Laboratoire" readonly>
                                                    <input type="hidden" name="frais[1][frais_id]" value="1">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Prix unitaire</label>
                                                    <input type="number" class="form-control prix" name="frais[1][prix]" value="{{ $detailsLaboratoire->first()->prix_unitaire ?? 0 }}" required readonly>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">Quantité</label>
                                                    <input type="number" class="form-control quantite" name="frais[1][quantite]" value="{{ $detailsLaboratoire->first()->quantite ?? 1 }}" min="1" required readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Taux (%)</label>
                                                    <input type="number" class="form-control taux" name="frais[1][taux]" value="{{ $patient->taux_couverture }}" min="0" max="100" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Total</label>
                                                    <input type="number" class="form-control total" name="frais[1][total]" value="{{ $detailsLaboratoire->first()->total ?? 0 }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        @foreach($autresDetails as $index => $detail)
                                        <div data-repeater-item class="mb-3 border-bottom pb-3">
                                            <div class="row align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label">Libellé</label>
                                                    <input type="text" class="form-control" value="{{ $detail->fraisHospitalisation->libelle }}" readonly>
                                                    <input type="hidden" name="frais[{{ $index + 2 }}][frais_id]" value="{{ $detail->frais_hospitalisation_id }}">
                                                    <input type="hidden" name="frais[{{ $index + 2 }}][detail_id]" value="{{ $detail->id }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Prix unitaire</label>
                                                    <input type="number" class="form-control prix" name="frais[{{ $index + 2 }}][prix]" value="{{ $detail->prix_unitaire }}" required>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">Quantité</label>
                                                    <input type="number" class="form-control quantite" name="frais[{{ $index + 2 }}][quantite]" value="{{ $detail->quantite }}" min="1" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Taux (%)</label>
                                                    <input type="number" class="form-control taux" name="frais[{{ $index + 2 }}][taux]" value="{{ $detail->taux ?? $patient->taux_couverture }}" min="0" max="100" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Total</label>
                                                    <input type="number" class="form-control total" name="frais[{{ $index + 2 }}][total]" value="{{ $detail->total }}" readonly>
                                                </div>
                                                <div class="col-md-1 text-center">
                                                    <button type="button" data-repeater-delete class="btn btn-danger btn-sm mt-4">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach

                                        <div data-repeater-item class="mb-3 border-bottom pb-3">
                                            <div class="row align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label">Libellé</label>
                                                    <select class="form-select frais-select" name="frais_id" required>
                                                        <option value="" disabled selected>Choisir un frais</option>
                                                        @foreach($autresFrais as $frais)
                                                            <option value="{{ $frais->id }}" data-prix="{{ $frais->montant }}">
                                                                {{ $frais->libelle }} ({{ number_format($frais->montant, 0, ',', ' ') }} XOF)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Prix unitaire</label>
                                                    <input type="number" class="form-control prix" name="prix" value="0" required>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">Quantité</label>
                                                    <input type="number" class="form-control quantite" name="quantite" value="1" min="1" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Taux (%)</label>
                                                    <input type="number" class="form-control taux" name="taux" value="{{ $patient->taux_couverture }}" min="0" max="100" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Total</label>
                                                    <input type="number" class="form-control total" name="total" value="0" readonly>
                                                </div>
                                                <div class="col-md-1 text-center">
                                                    <button type="button" data-repeater-delete class="btn btn-danger btn-sm mt-4">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" data-repeater-create class="btn bg-primary-subtle text-primary">
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
        // Initialisation Select2 pour les selects
        $('.select2').select2({
            width: '100%',
            placeholder: "Sélectionner un élément"
        });

        // Gestion du médecin et spécialité
        $('#medecin-select').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const specialite = selectedOption.data('specialite');
            const specialiteId = selectedOption.data('specialite-id');
            
            $('#specialite-input').val(specialite || '');
            $('#specialite-id').val(specialiteId || '');
        });

        // Initialisation du repeater pour les autres frais
        $('.autres-repeater').repeater({
            initEmpty: false,
            show: function() {
                $(this).slideDown(function() {
                    $(this).find('.frais-select').select2({
                        width: '100%',
                        placeholder: "Sélectionner un frais"
                    }).trigger('change');
                });
            },
            // hide: function(deleteElement) {
            //     Swal.fire({
            //         title: 'Êtes-vous sûr?',
            //         text: "Vous ne pourrez pas revenir en arrière!",
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Oui, supprimer!',
            //         cancelButtonText: 'Annuler'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             $(this).slideUp(deleteElement, function() {
            //                 updateTotals();
            //                 updateCalculs();
            //                 Swal.fire(
            //                     'Supprimé!',
            //                     'Le frais a été supprimé.',
            //                     'success'
            //                 );
            //             });
            //         }
            //     });
            // },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
                updateTotals();
            },
            ready: function(setIndexes) {
                $('.frais-select').select2({
                    width: '100%',
                    placeholder: "Sélectionner un frais"
                });
            }
        });

        // Calcul du total pour une ligne avec taux
        function calculateRowTotal(row) {
            const prix = parseFloat(row.find('.prix').val()) || 0;
            const quantite = parseInt(row.find('.quantite').val()) || 0;
            const taux = parseInt(row.find('.taux').val()) || 100;
            const totalBrut = prix * quantite;
            const totalNet = totalBrut * (taux / 100);
            row.find('.total').val(totalNet.toFixed(0));
        }

        // Fonction pour mettre à jour les totaux
        function updateTotals() {
            // Calcul du total pharmacie
            const prixPharma = parseFloat($('[name="frais[0][prix]"]').val()) || 0;
            const qtePharma = parseInt($('[name="frais[0][quantite]"]').val()) || 0;
            const tauxPharma = parseInt($('[name="frais[0][taux]"]').val()) || 100;
            const totalPharma = prixPharma * qtePharma * (tauxPharma / 100);
            $('[name="frais[0][total]"]').val(totalPharma.toFixed(0));

            // Calcul du total laboratoire
            const prixLabo = parseFloat($('[name="frais[1][prix]"]').val()) || 0;
            const qteLabo = parseInt($('[name="frais[1][quantite]"]').val()) || 0;
            const tauxLabo = parseInt($('[name="frais[1][taux]"]').val()) || 100;
            const totalLabo = prixLabo * qteLabo * (tauxLabo / 100);
            $('[name="frais[1][total]"]').val(totalLabo.toFixed(0));

            // Calcul du total autres frais
            let totalAutres = 0;
            $('.autres-repeater [data-repeater-item]').each(function() {
                totalAutres += parseFloat($(this).find('.total').val()) || 0;
            });

            // Mise à jour des affichages
            $('#totalPharmacie').text(formatCurrency(totalPharma));
            $('#totalLaboratoire').text(formatCurrency(totalLabo));
            $('#totalAutres').text(formatCurrency(totalAutres));

            // Calcul du total général
            const totalGeneral = totalPharma + totalLabo + totalAutres;
            $('#totalGeneral').text(formatCurrency(totalGeneral));
        }

        // Fonction pour calculer et mettre à jour tous les montants
        function updateCalculs() {
            // 1. Calcul du total des prestations
            let totalPrestations = 0;
            
            // Ajouter le total pharmacie
            const prixPharma = parseFloat($('[name="frais[0][prix]"]').val()) || 0;
            const qtePharma = parseInt($('[name="fris[0][quantite]"]').val()) || 0;
            totalPrestations += prixPharma * qtePharma;
            
            // Ajouter le total laboratoire
            const prixLabo = parseFloat($('[name="frais[1][prix]"]').val()) || 0;
            const qteLabo = parseInt($('[name="frais[1][quantite]"]').val()) || 0;
            totalPrestations += prixLabo * qteLabo;
            
            // Ajouter les autres frais
            $('.autres-repeater [data-repeater-item]').each(function() {
                const prix = parseFloat($(this).find('.prix').val()) || 0;
                const quantite = parseInt($(this).find('.quantite').val()) || 0;
                totalPrestations += prix * quantite;
            });
            
            $('#total-prestations').val(totalPrestations.toFixed(2));
            
            // 2. Calcul du ticket modérateur (part non couverte par l'assurance)
            const tauxAssurance = parseFloat("{{ $patient->taux_couverture ?? 0 }}") || 0;
            const tauxNonCouvert = 100 - tauxAssurance;
            const ticketModerateur = totalPrestations * (tauxNonCouvert / 100);
            $('#ticket-moderateur').val(ticketModerateur.toFixed(2));
            
            // 3. Récupération de la réduction (saisie manuelle)
            const reduction = parseFloat($('#reduction').val()) || 0;
            
            // 4. Calcul du montant à payer
            const montantAPayer = totalPrestations - ticketModerateur - reduction;
            $('#a-payer').val(Math.max(0, montantAPayer).toFixed(2)); // Éviter les valeurs négatives
        }

        // Fonction pour formater les montants
        function formatCurrency(amount) {
            return new Intl.NumberFormat('fr-FR', { 
                style: 'currency', 
                currency: 'XOF',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        // Écouteurs d'événements pour les champs prix, quantité et taux
        $(document).on('input', '.prix, .quantite, .taux, #reduction', function() {
            let row = $(this).closest('[data-repeater-item]');
            if (!row.length) {
                row = $(this).closest('.mb-3');
            }
            calculateRowTotal(row);
            updateTotals();
            updateCalculs();
        });

        // Écouteur pour le changement de sélection des frais
        $(document).on('change', '.frais-select', function() {
            const selectedOption = $(this).find('option:selected');
            const prix = selectedOption.data('prix') || 0;
            const row = $(this).closest('[data-repeater-item]');
            row.find('.prix').val(prix);
            calculateRowTotal(row);
            updateTotals();
            updateCalculs();
        });

        // Validation de la réduction
        $('#reduction').on('change', function() {
            const reduction = parseFloat($(this).val()) || 0;
            if (reduction > 0 && $('#reduction_par').val() === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Veuillez indiquer qui a accordé la réduction'
                });
                $(this).focus();
            }
        });

        // Initialisation des valeurs
        $('.mb-3:not([data-repeater-item])').each(function() {
            calculateRowTotal($(this));
        });
        $('.autres-repeater [data-repeater-item]').each(function() {
            calculateRowTotal($(this));
        });
        updateTotals();
        updateCalculs();

        // Initialiser la spécialité si un médecin est déjà sélectionné
        if ($('#medecin-select').val()) {
            $('#medecin-select').trigger('change');
        }

        // Gestion de la soumission du formulaire
        $('#examenForm').on('submit', function(e) {
            const submitBtn = $('#submitBtn');
            const submitText = $('#submitText');
            const submitSpinner = $('#submitSpinner');
            
            // Désactiver le bouton et afficher le spinner
            submitBtn.prop('disabled', true);
            submitText.text('Enregistrement...');
            submitSpinner.removeClass('d-none');

            // Validation de la réduction
            const reduction = parseFloat($('#reduction').val()) || 0;
            if (reduction > 0 && $('#reduction_par').val() === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Veuillez indiquer qui a accordé la réduction'
                });
                
                // Réactiver le bouton
                submitBtn.prop('disabled', false);
                submitText.text('Enregistrer');
                submitSpinner.addClass('d-none');
                
                return false;
            }

            // Validation des dates
            const dateEntree = new Date($('[name="date_entree"]').val());
            const dateSortie = new Date($('[name="date_sortie"]').val());
            
            if (dateSortie && dateSortie < dateEntree) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'La date de sortie ne peut pas être antérieure à la date d\'entrée'
                });
                
                // Réactiver le bouton
                submitBtn.prop('disabled', false);
                submitText.text('Enregistrer');
                submitSpinner.addClass('d-none');
                
                return false;
            }

            
            return true;
        });
    });
</script>
@endpush