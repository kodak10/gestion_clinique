@extends('dashboard.layouts.master')
@section('content')
    <div class="container-xl">
            <div class="row row-cards">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <div class="accordion" id="accordion-default">
                      <div class="accordion-item">
                        <button class="accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1-default" aria-expanded="true">
                          <div class="accordion-header-text">
                            <h4>Comment voir la liste des patients</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-1-default" class="accordion-collapse collapse" data-bs-parent="#accordion-default">
                          <div class="accordion-body">
                            <img src="{{ asset('assets/dist/img/header.png') }}" alt="">
                            Cliquez sur le bouton "Patients" dans le menu de navigation pour accéder à la liste des patients. En mobile, appuyez sur le bouton de menu en haut à gauche pour afficher les options.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2-default" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>Comment Rechercher - Ajouter un Patient </h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-2-default" class="accordion-collapse collapse" data-bs-parent="#accordion-default">
                          <div class="accordion-body">
                            <img src="{{ asset('assets/dist/img/patient.png') }}" alt="">
                            <ul>
                              <li>Pour Rechercher un patient cliqué dans la zone de "search" ou "Rechercher" Puis entrer le nom du patient</li>
                              <li>Pour Ajouter un patient cliqué sur le bouton "Ajouter"</li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3-default" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>Comment Enregistrer un Patient</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-3-default" class="accordion-collapse collapse" data-bs-parent="#accordion-default">
                          <div class="accordion-body">
                            <img src="{{ asset('assets/dist/img/Inscription_patient.png') }}" alt="">
                            Veuillez remplir tous les champs requis (avec astreis devant) dans le formulaire d'inscription du patient. Assurez-vous de fournir des informations précises et complètes pour garantir un suivi médical efficace.
                            <br> Pour les informations (Ethnies, Profession), Vous pouvez en ajouté en fesant le +

                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4-default" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>Comment modifier, Imprimer le dossier, Faire un Acte Ambulatoire, Hospitaliser, Suivi du Patient</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-4-default" class="accordion-collapse collapse" data-bs-parent="#accordion-default">
                          <div class="accordion-body">
                            <img src="{{ asset('assets/dist/img/action_patient.png') }}" alt="">
                            <ul>
                              <li>Modifier : Pour modifier les informations liées au patient.</li>
                              <li>Ouvrir la fiche de dossier: Avoir la fiche médicale du patient</li>
                              <li>Acte Ambulatoire : Faire un Acte Ambulatoire</li>
                              <li>A Hospitaliser : Pour declarer que le patient est Hospitalisé</li>
                              <li>Suivi du Patient : Pour avoir l'historique des prestations (Acte Ambulatoire, Hospitalisation) du patient. </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="accordion accordion-flush" id="accordion-flush">
                    <div class="accordion-item">
                      <button class="accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1-flush" aria-expanded="true">
                        <div class="accordion-header-text">
                          <h4>Enregistrement d'un acte Ambulatoire</h4>
                        </div>
                        <div class="accordion-header-toggle">
                          <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                            <path d="M6 9l6 6l6 -6"></path>
                          </svg>
                        </div>
                      </button>
                      <div id="collapse-1-flush" class="accordion-collapse collapse" data-bs-parent="#accordion-flush">
                        <div class="accordion-body">
                          <img src="{{ asset('assets/dist/img/Consultation.png') }}" alt="">
                          <ul>
                            Veuillez selectionner le Medecin traitant
                            <li>Selectionner la prestation</li>
                            <li>Le prix unitaire sort automatiquement mais vous pouvez le modifié</li>
                            <li>La quantité</li>
                            <li>Le taux de prise en charge de l'assurance</li>
                            <li>Le cout total de la ligne se calcule sur la dernière ligne</li>

                          </ul>
                          Faire + Ajouter une autre prestation si vous voulez ajouter une autre.
                          <br>Renseigner les informations liées aux prix à savoir : La reduction, le montant perçu puis faire Enregistrer pour enregistrer l'Acte Ambulatoire.
                          <br>NB: LORSQUE VOTRE ROLE (EN HAUT A DROITE) EST CAISSIERE ALORS VOUS ALLEZ ENCAISSER L'ARGENT DIRECTEMENT ET ACCEDER A VOTRE JOURNAL DE CAISSE DANS LE CAS CONTRAIRE LE PATIENT DEVRA MONTER POUR PAYER EN HAUT)
                        </div>
                      </div>
                    </div>

                    <div class="accordion-item">
                      <button class="accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2-flush" aria-expanded="true">
                        <div class="accordion-header-text">
                          <h4>Enregistrement des Médicaments du patient (hospitalisation)</h4>
                        </div>
                        <div class="accordion-header-toggle">
                          <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                            <path d="M6 9l6 6l6 -6"></path>
                          </svg>
                        </div>
                      </button>
                      <div id="collapse-2-flush" class="accordion-collapse collapse" data-bs-parent="#accordion-flush">
                        <div class="accordion-body">
                          <img src="{{ asset('assets/dist/img/Consultation.png') }}" alt="">
                          <ul>
                            Veuillez selectionner le Medecin traitant
                            <li>Selectionner la prestation</li>
                            <li>Le prix unitaire sort automatiquement mais vous pouvez le modifié</li>
                            <li>La quantité</li>
                            <li>Le taux de prise en charge de l'assurance</li>
                            <li>Le cout total de la ligne se calcule sur la dernière ligne</li>

                          </ul>
                          Faire + Ajouter une autre prestation si vous voulez ajouter une autre.
                          <br>Renseigner les informations liées aux prix à savoir : La reduction, le montant perçu puis faire Enregistrer pour enregistrer l'Acte Ambulatoire.
                          <br>NB: LORSQUE VOTRE ROLE (EN HAUT A DROITE) EST CAISSIERE ALORS VOUS ALLEZ ENCAISSER L'ARGENT DIRECTEMENT ET ACCEDER A VOTRE JOURNAL DE CAISSE DANS LE CAS CONTRAIRE LE PATIENT DEVRA MONTER POUR PAYER EN HAUT)
                        </div>
                      </div>
                    </div>


                    <div class="accordion-item">
                      <button class="accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3-flush" aria-expanded="true">
                        <div class="accordion-header-text">
                          <h4>Enregistrement des Examens du patient (hospitalisation)</h4>
                        </div>
                        <div class="accordion-header-toggle">
                          <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                            <path d="M6 9l6 6l6 -6"></path>
                          </svg>
                        </div>
                      </button>
                      <div id="collapse-3-flush" class="accordion-collapse collapse" data-bs-parent="#accordion-flush">
                        <div class="accordion-body">
                          <img src="{{ asset('assets/dist/img/Consultation.png') }}" alt="">
                          <ul>
                            Veuillez selectionner le Medecin traitant
                            <li>Selectionner la prestation</li>
                            <li>Le prix unitaire sort automatiquement mais vous pouvez le modifié</li>
                            <li>La quantité</li>
                            <li>Le taux de prise en charge de l'assurance</li>
                            <li>Le cout total de la ligne se calcule sur la dernière ligne</li>

                          </ul>
                          Faire + Ajouter une autre prestation si vous voulez ajouter une autre.
                          <br>Renseigner les informations liées aux prix à savoir : La reduction, le montant perçu puis faire Enregistrer pour enregistrer l'Acte Ambulatoire.
                          <br>NB: LORSQUE VOTRE ROLE (EN HAUT A DROITE) EST CAISSIERE ALORS VOUS ALLEZ ENCAISSER L'ARGENT DIRECTEMENT ET ACCEDER A VOTRE JOURNAL DE CAISSE DANS LE CAS CONTRAIRE LE PATIENT DEVRA MONTER POUR PAYER EN HAUT)
                        </div>
                      </div>
                    </div>

                    <div class="accordion-item">
                      <button class="accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4-flush" aria-expanded="true">
                        <div class="accordion-header-text">
                          <h4>Enregistrement de la facture d'hospitalisation du Patient</h4>
                        </div>
                        <div class="accordion-header-toggle">
                          <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                            <path d="M6 9l6 6l6 -6"></path>
                          </svg>
                        </div>
                      </button>
                      <div id="collapse-4-flush" class="accordion-collapse collapse" data-bs-parent="#accordion-flush">
                        <div class="accordion-body">
                          <img src="{{ asset('assets/dist/img/Consultation.png') }}" alt="">
                          <ul>
                            Veuillez selectionner le Medecin traitant
                            <li>Selectionner la prestation</li>
                            <li>Le prix unitaire sort automatiquement mais vous pouvez le modifié</li>
                            <li>La quantité</li>
                            <li>Le taux de prise en charge de l'assurance</li>
                            <li>Le cout total de la ligne se calcule sur la dernière ligne</li>

                          </ul>
                          Faire + Ajouter une autre prestation si vous voulez ajouter une autre.
                          <br>Renseigner les informations liées aux prix à savoir : La reduction, le montant perçu puis faire Enregistrer pour enregistrer l'Acte Ambulatoire.
                          <br>NB: LORSQUE VOTRE ROLE (EN HAUT A DROITE) EST CAISSIERE ALORS VOUS ALLEZ ENCAISSER L'ARGENT DIRECTEMENT ET ACCEDER A VOTRE JOURNAL DE CAISSE DANS LE CAS CONTRAIRE LE PATIENT DEVRA MONTER POUR PAYER EN HAUT)
                        </div>
                      </div>
                    </div>

                    <div class="accordion-item">
                      <button class="accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-5-flush" aria-expanded="true">
                        <div class="accordion-header-text">
                          <h4>Gestion du Stock des médicaments</h4>
                        </div>
                        <div class="accordion-header-toggle">
                          <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                            <path d="M6 9l6 6l6 -6"></path>
                          </svg>
                        </div>
                      </button>
                      <div id="collapse-5-flush" class="accordion-collapse collapse" data-bs-parent="#accordion-flush">
                        <div class="accordion-body">
                          <img src="{{ asset('assets/dist/img/Consultation.png') }}" alt="">
                          <ul>
                            Veuillez selectionner le Medecin traitant
                            <li>Selectionner la prestation</li>
                            <li>Le prix unitaire sort automatiquement mais vous pouvez le modifié</li>
                            <li>La quantité</li>
                            <li>Le taux de prise en charge de l'assurance</li>
                            <li>Le cout total de la ligne se calcule sur la dernière ligne</li>

                          </ul>
                          Faire + Ajouter une autre prestation si vous voulez ajouter une autre.
                          <br>Renseigner les informations liées aux prix à savoir : La reduction, le montant perçu puis faire Enregistrer pour enregistrer l'Acte Ambulatoire.
                          <br>NB: LORSQUE VOTRE ROLE (EN HAUT A DROITE) EST CAISSIERE ALORS VOUS ALLEZ ENCAISSER L'ARGENT DIRECTEMENT ET ACCEDER A VOTRE JOURNAL DE CAISSE DANS LE CAS CONTRAIRE LE PATIENT DEVRA MONTER POUR PAYER EN HAUT)
                        </div>
                      </div>
                    </div>
                   
                    
                    
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <div class="accordion accordion-tabs" id="accordion-tabs">
                      <div class="accordion-item">
                        <button class="accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1-tabs" aria-expanded="true">
                          <div class="accordion-header-text">
                            <h4>Encaisser un payement</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-1-tabs" class="accordion-collapse collapse" data-bs-parent="#accordion-tabs">
                          <div class="accordion-body">
                            <img src="{{ asset('assets/dist/img/header.png') }}" alt="">
                            Cliquez sur le bouton "Comptabilité" dans le menu de navigation puis "Règlements" pour accéder à la liste des Factures ou  des Actes Ambulatoires non soldé.
                            Dans le champs "Search", entrez le nom du patient ou le numéro de la facture ou de la Acte Ambulatoire pour trouver l'Acte Ambulatoire ou la facture à encaisser. <br>
                            Cliquez sur le bouton "Action" du patient puis "Payer" pour ouvrir le formulaire d'encaissement. Cliquez sur "Detail" pour voir les informations de l'Acte Ambulatoire ou de la facture. <br>

                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4-tabs" aria-expanded="true">
                          <div class="accordion-header-text">
                            <h4>Faire Une depense</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-4-tabs" class="accordion-collapse collapse" data-bs-parent="#accordion-tabs">
                          <div class="accordion-body">
                            <img src="{{ asset('assets/dist/img/header.png') }}" alt="">
                            Cliquez sur le bouton "Comptabilité" dans le menu de navigation puis "Règlements" pour accéder à la liste des factures ou Acte Ambulatoire non soldé.
                            Dans le champs "Search", entrez le nom du patient ou le numéro de la facture ou de l'Acte Ambulatoire pour trouver l'Acte Ambulatoire ou la facture à encaisser. <br>
                            Cliquez sur le bouton "Action" du patient puis "Payer" pour ouvrir le formulaire d'encaissement. Cliquez sur "Detail" pour voir les informations de l'Acte Ambulatoire ou de la facture. <br>

                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2-tabs" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>Journal de caisse</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-2-tabs" class="accordion-collapse collapse" data-bs-parent="#accordion-tabs">
                          <div class="accordion-body">
                            <img src="{{ asset('assets/dist/img/header.png') }}" alt="">
                            Cliquez sur le bouton "Comptabilité" dans le menu de navigation puis "Journal de caisse" pour accéder au journal de caisse.
                            Dans le champs "Search", entrez le nom du patient ou le numéro de la facture ou de l'Acte Ambulatoire pour trouver l'Acte Ambulatoire ou la facture à encaisser. <br>
                            Cliquez sur le bouton "Action" du patient puis "Payer" pour ouvrir le formulaire d'encaissement. Cliquez sur "Detail" pour voir les informations de l'Acte Ambulatoire ou de la facture. <br>
                          </div>
                        </div>
                      </div>
                       <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3-tabs" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>Erreur sur l'Acte Ambulatoire et le montant est dejà dans le point ou Encaissement  (Admin)</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-3-tabs" class="accordion-collapse collapse" data-bs-parent="#accordion-tabs">
                          <div class="accordion-body">
                            Etape 01
                            <ul>
                              <li>
                                1 - Cliquer le sur le bouton de navigation Comptabilité puis journal de caisse <br>
                                <span></span>
                                <img src="{{ asset('assets/dist/img/journal.png') }}" alt="">

                                2 - Selectionner le paiement puis cliqué sur le bouton Action Puis Supprimé <br>
                                <img src="{{ asset('assets/dist/img/journal_caisse.png') }}" alt="">
                              </li>
                              Etape 02
                              
                                <li>
                                1 - Allez sur le menu des patient cliqué sur Action du patient puis Suivi du Patient <br>
                                <span></span>
                                <img src="{{ asset('assets/dist/img/action_patient.png') }}" alt="">

                                2 - Selectionner la consultaion puis cliqué sur Modifier puis modifié les informations paiement puis cliqué sur le bouton Action Puis Supprimé <br>
                                <img src="{{ asset('assets/dist/img/journal_caisse.png') }}" alt="">
                              </li>
                            
                            </ul>
                            
                          </div>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <div class="accordion accordion-inverted" id="accordion-inverted">
                      <div class="accordion-item">
                        <button class="accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1-inverted" aria-expanded="true">
                          <div class="accordion-header-text">
                            <h4>Creation | Modification d'accès Utilisateur</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-1-inverted" class="accordion-collapse collapse" data-bs-parent="#accordion-inverted">
                          <div class="accordion-body">
                            Tabler offers a modern, responsive design with a clean aesthetic, built on Bootstrap for ease of use and flexibility.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2-inverted" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>Creation | Modification d'assurances</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-2-inverted" class="accordion-collapse collapse" data-bs-parent="#accordion-inverted">
                          <div class="accordion-body">
                            You can customize Tabler components using CSS variables, SCSS, and utility classes to match your design preferences.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3-inverted" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>Creation | Modification des frais d'hospitalisation</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-3-inverted" class="accordion-collapse collapse" data-bs-parent="#accordion-inverted">
                          <div class="accordion-body">
                            Yes, Tabler is lightweight, optimized for modern browsers, and follows best practices for fast loading and efficiency.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4-inverted" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>Creation | Modification des prestations d'accueil</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-4-inverted" class="accordion-collapse collapse" data-bs-parent="#accordion-inverted">
                          <div class="accordion-body">
                            Tabler components follow WAI-ARIA standards and support keyboard navigation, screen readers, and accessibility best practices.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-5-inverted" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>Creation | Modification des Medecins</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-5-inverted" class="accordion-collapse collapse" data-bs-parent="#accordion-inverted">
                          <div class="accordion-body">
                            Tabler components follow WAI-ARIA standards and support keyboard navigation, screen readers, and accessibility best practices.
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
    </div>
@endsection