@extends('dashboard.layouts.master')
@section('content')
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <h2 class="page-title">Tableau de Bord de la Clinique</h2>
        </div>
        <div class="col-auto ms-auto d-print-none">
          <div class="btn-list">
            <span class="d-none d-sm-inline">
              <a href="#" class="btn btn-white">
                Nouveau rapport
              </a>
            </span>
            <a href="#" class="btn btn-primary d-none d-sm-inline-block">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
              Nouveau RDV
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="page-body">
    <div class="container-xl">
      <div class="row row-deck row-cards">
        <!-- Statistiques principales -->
        <div class="col-sm-6 col-lg-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">Patients aujourd'hui</div>
                <div class="ms-auto lh-1">
                  <div class="dropdown">
                    <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">7 derniers jours</a>
                    <div class="dropdown-menu dropdown-menu-end">
                      <a class="dropdown-item active" href="#">7 derniers jours</a>
                      <a class="dropdown-item" href="#">30 derniers jours</a>
                      <a class="dropdown-item" href="#">3 derniers mois</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="h1 mb-3">24</div>
              <div class="d-flex mb-2">
                <div>Evolution</div>
                <div class="ms-auto">
                  <span class="text-green d-inline-flex align-items-center lh-1">
                    8% <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                  </span>
                </div>
              </div>
              <div class="progress progress-sm">
                <div class="progress-bar bg-primary" style="width: 75%" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" aria-label="75% Complete">
                  <span class="visually-hidden">75% Complete</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-sm-6 col-lg-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">RDVs programmés</div>
                <div class="ms-auto lh-1">
                  <div class="dropdown">
                    <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aujourd'hui</a>
                    <div class="dropdown-menu dropdown-menu-end">
                      <a class="dropdown-item active" href="#">Aujourd'hui</a>
                      <a class="dropdown-item" href="#">Demain</a>
                      <a class="dropdown-item" href="#">Cette semaine</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="h1 mb-3">18</div>
              <div class="d-flex mb-2">
                <div>En attente</div>
                <div class="ms-auto">
                  <span class="text-green d-inline-flex align-items-center lh-1">
                    4 <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /></svg>
                  </span>
                </div>
              </div>
              <div class="progress progress-sm">
                <div class="progress-bar bg-warning" style="width: 45%" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" aria-label="45% Complete">
                  <span class="visually-hidden">45% Complete</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-sm-6 col-lg-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">Hospitalisations</div>
                <div class="ms-auto lh-1">
                  <div class="dropdown">
                    <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">En cours</a>
                    <div class="dropdown-menu dropdown-menu-end">
                      <a class="dropdown-item active" href="#">En cours</a>
                      <a class="dropdown-item" href="#">Sorties aujourd'hui</a>
                      <a class="dropdown-item" href="#">Cette semaine</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="h1 mb-3">9</div>
              <div class="d-flex mb-2">
                <div>Lits occupés</div>
                <div class="ms-auto">
                  <span class="text-red d-inline-flex align-items-center lh-1">
                    75% <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7l6 -6l6 6l6 -6l6 6" /><path d="M3 17l6 -6l6 6l6 -6l6 6" /></svg>
                  </span>
                </div>
              </div>
              <div class="progress progress-sm">
                <div class="progress-bar bg-danger" style="width: 75%" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" aria-label="75% Complete">
                  <span class="visually-hidden">75% Complete</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-sm-6 col-lg-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">Recettes journalières</div>
                <div class="ms-auto lh-1">
                  <div class="dropdown">
                    <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aujourd'hui</a>
                    <div class="dropdown-menu dropdown-menu-end">
                      <a class="dropdown-item active" href="#">Aujourd'hui</a>
                      <a class="dropdown-item" href="#">Hier</a>
                      <a class="dropdown-item" href="#">Cette semaine</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="h1 mb-3">1,245€</div>
              <div class="d-flex mb-2">
                <div>Objectif</div>
                <div class="ms-auto">
                  <span class="text-green d-inline-flex align-items-center lh-1">
                    92% <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                  </span>
                </div>
              </div>
              <div class="progress progress-sm">
                <div class="progress-bar bg-success" style="width: 92%" role="progressbar" aria-valuenow="92" aria-valuemin="0" aria-valuemax="100" aria-label="92% Complete">
                  <span class="visually-hidden">92% Complete</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Graphique des RDVs -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Rendez-vous par spécialité</h3>
            </div>
            <div class="card-body">
              <div id="chart-specialites"></div>
            </div>
          </div>
        </div>
        
        <!-- Graphique des recettes -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Recettes des 30 derniers jours</h3>
            </div>
            <div class="card-body">
              <div id="chart-revenus"></div>
            </div>
          </div>
        </div>
        
        <!-- Tableau des RDVs à venir -->
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Rendez-vous à venir</h3>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-vcenter table-mobile-md card-table">
                  <thead>
                    <tr>
                      <th>Patient</th>
                      <th>Heure</th>
                      <th>Spécialité</th>
                      <th>Médecin</th>
                      <th>Statut</th>
                      <th class="w-1"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td data-label="Patient">
                        <div class="d-flex py-1 align-items-center">
                          <span class="avatar me-2" style="background-image: url(./static/avatars/010m.jpg)"></span>
                          <div class="flex-fill">
                            <div class="font-weight-medium">Jean Dupont</div>
                            <div class="text-muted"><a href="#" class="text-reset">jean.dupont@example.com</a></div>
                          </div>
                        </div>
                      </td>
                      <td data-label="Heure">
                        <div>10:30</div>
                        <div class="text-muted">Aujourd'hui</div>
                      </td>
                      <td data-label="Spécialité" class="text-muted">
                        Cardiologie
                      </td>
                      <td data-label="Médecin">
                        Dr. Martin
                      </td>
                      <td data-label="Statut">
                        <span class="badge bg-success me-1"></span> Confirmé
                      </td>
                      <td>
                        <div class="btn-list flex-nowrap">
                          <a href="#" class="btn btn-sm btn-primary">Détails</a>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td data-label="Patient">
                        <div class="d-flex py-1 align-items-center">
                          <span class="avatar me-2" style="background-image: url(./static/avatars/005f.jpg)"></span>
                          <div class="flex-fill">
                            <div class="font-weight-medium">Marie Lambert</div>
                            <div class="text-muted"><a href="#" class="text-reset">marie.lambert@example.com</a></div>
                          </div>
                        </div>
                      </td>
                      <td data-label="Heure">
                        <div>14:15</div>
                        <div class="text-muted">Aujourd'hui</div>
                      </td>
                      <td data-label="Spécialité" class="text-muted">
                        Pédiatrie
                      </td>
                      <td data-label="Médecin">
                        Dr. Lefèvre
                      </td>
                      <td data-label="Statut">
                        <span class="badge bg-warning me-1"></span> En attente
                      </td>
                      <td>
                        <div class="btn-list flex-nowrap">
                          <a href="#" class="btn btn-sm btn-primary">Détails</a>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td data-label="Patient">
                        <div class="d-flex py-1 align-items-center">
                          <span class="avatar me-2">PL</span>
                          <div class="flex-fill">
                            <div class="font-weight-medium">Paul Leroy</div>
                            <div class="text-muted"><a href="#" class="text-reset">paul.leroy@example.com</a></div>
                          </div>
                        </div>
                      </td>
                      <td data-label="Heure">
                        <div>09:00</div>
                        <div class="text-muted">Demain</div>
                      </td>
                      <td data-label="Spécialité" class="text-muted">
                        Chirurgie
                      </td>
                      <td data-label="Médecin">
                        Dr. Petit
                      </td>
                      <td data-label="Statut">
                        <span class="badge bg-success me-1"></span> Confirmé
                      </td>
                      <td>
                        <div class="btn-list flex-nowrap">
                          <a href="#" class="btn btn-sm btn-primary">Détails</a>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td data-label="Patient">
                        <div class="d-flex py-1 align-items-center">
                          <span class="avatar me-2" style="background-image: url(./static/avatars/006f.jpg)"></span>
                          <div class="flex-fill">
                            <div class="font-weight-medium">Sophie Bernard</div>
                            <div class="text-muted"><a href="#" class="text-reset">sophie.bernard@example.com</a></div>
                          </div>
                        </div>
                      </td>
                      <td data-label="Heure">
                        <div>11:45</div>
                        <div class="text-muted">Demain</div>
                      </td>
                      <td data-label="Spécialité" class="text-muted">
                        Dermatologie
                      </td>
                      <td data-label="Médecin">
                        Dr. Moreau
                      </td>
                      <td data-label="Statut">
                        <span class="badge bg-warning me-1"></span> En attente
                      </td>
                      <td>
                        <div class="btn-list flex-nowrap">
                          <a href="#" class="btn btn-sm btn-primary">Détails</a>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Hospitalisations en cours -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Patients hospitalisés</h3>
            </div>
            <div class="card-table table-responsive">
              <table class="table table-vcenter">
                <thead>
                  <tr>
                    <th>Patient</th>
                    <th>Chambre</th>
                    <th>Entrée</th>
                    <th>Service</th>
                    <th class="w-1"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex py-1 align-items-center">
                        <span class="avatar me-2" style="background-image: url(./static/avatars/003m.jpg)"></span>
                        <div class="flex-fill">
                          <div class="font-weight-medium">Pierre Garnier</div>
                          <div class="text-muted"><a href="#" class="text-reset">pierre.garnier@example.com</a></div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div>B203</div>
                      <div class="text-muted">2ème étage</div>
                    </td>
                    <td class="text-muted">
                      2 jours
                    </td>
                    <td class="text-muted">
                      Cardiologie
                    </td>
                    <td>
                      <a href="#" class="btn btn-sm btn-primary">Dossier</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex py-1 align-items-center">
                        <span class="avatar me-2" style="background-image: url(./static/avatars/002f.jpg)"></span>
                        <div class="flex-fill">
                          <div class="font-weight-medium">Julie Faure</div>
                          <div class="text-muted"><a href="#" class="text-reset">julie.faure@example.com</a></div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div>A107</div>
                      <div class="text-muted">1er étage</div>
                    </td>
                    <td class="text-muted">
                      5 jours
                    </td>
                    <td class="text-muted">
                      Chirurgie
                    </td>
                    <td>
                      <a href="#" class="btn btn-sm btn-primary">Dossier</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex py-1 align-items-center">
                        <span class="avatar me-2">TC</span>
                        <div class="flex-fill">
                          <div class="font-weight-medium">Thomas Caron</div>
                          <div class="text-muted"><a href="#" class="text-reset">thomas.caron@example.com</a></div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div>C305</div>
                      <div class="text-muted">3ème étage</div>
                    </td>
                    <td class="text-muted">
                      1 jour
                    </td>
                    <td class="text-muted">
                      Neurologie
                    </td>
                    <td>
                      <a href="#" class="btn btn-sm btn-primary">Dossier</a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        
        <!-- Dépenses récentes -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Dépenses récentes</h3>
            </div>
            <div class="list-group list-group-flush list-group-hoverable">
              <div class="list-group-item">
                <div class="row align-items-center">
                  <div class="col-auto"><span class="status-dot status-dot-animated bg-red d-block"></span></div>
                  <div class="col text-truncate">
                    <a href="#" class="text-reset d-block">Médicaments</a>
                    <div class="d-block text-muted text-truncate mt-n1">
                      Commande #12345 • Fournisseur PharmaPlus
                    </div>
                  </div>
                  <div class="col-auto">
                    <span class="text-danger">-1,250€</span>
                  </div>
                  <div class="col-auto">
                    <a href="#" class="list-group-item-actions">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                    </a>
                  </div>
                </div>
              </div>
              <div class="list-group-item">
                <div class="row align-items-center">
                  <div class="col-auto"><span class="status-dot d-block"></span></div>
                  <div class="col text-truncate">
                    <a href="#" class="text-reset d-block">Équipement médical</a>
                    <div class="d-block text-muted text-truncate mt-n1">
                      Scanner RX-2000 • MedEquip Inc.
                    </div>
                  </div>
                  <div class="col-auto">
                    <span class="text-danger">-8,750€</span>
                  </div>
                  <div class="col-auto">
                    <a href="#" class="list-group-item-actions show">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                    </a>
                  </div>
                </div>
              </div>
              <div class="list-group-item">
                <div class="row align-items-center">
                  <div class="col-auto"><span class="status-dot d-block"></span></div>
                  <div class="col text-truncate">
                    <a href="#" class="text-reset d-block">Maintenance</a>
                    <div class="d-block text-muted text-truncate mt-n1">
                      Contrat annuel • TechService Pro
                    </div>
                  </div>
                  <div class="col-auto">
                    <span class="text-danger">-1,200€</span>
                  </div>
                  <div class="col-auto">
                    <a href="#" class="list-group-item-actions">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                    </a>
                  </div>
                </div>
              </div>
              <div class="list-group-item">
                <div class="row align-items-center">
                  <div class="col-auto"><span class="status-dot status-dot-animated bg-green d-block"></span></div>
                  <div class="col text-truncate">
                    <a href="#" class="text-reset d-block">Fournitures bureau</a>
                    <div class="d-block text-muted text-truncate mt-n1">
                      Commande #12346 • OfficeWorld
                    </div>
                  </div>
                  <div class="col-auto">
                    <span class="text-danger">-350€</span>
                  </div>
                  <div class="col-auto">
                    <a href="#" class="list-group-item-actions">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@push('scripts')
  <script>
    // Graphique des spécialités
    document.addEventListener("DOMContentLoaded", function () {
      window.ApexCharts && (new ApexCharts(document.getElementById('chart-specialites'), {
        series: [{
          name: 'RDVs',
          data: [28, 42, 35, 19, 27, 15, 22]
        }],
        chart: {
          type: 'bar',
          height: 300,
          fontFamily: 'Inter, sans-serif',
          toolbar: {
            show: false
          }
        },
        plotOptions: {
          bar: {
            columnWidth: '50%',
            borderRadius: 4
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: ['Cardiologie', 'Pédiatrie', 'Dermatologie', 'Chirurgie', 'Neurologie', 'Ophtalmologie', 'ORL'],
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          labels: {
            style: {
              colors: '#6b7280',
              fontSize: '12px',
              fontFamily: 'Inter, sans-serif',
              fontWeight: 400
            }
          }
        },
        yaxis: {
          labels: {
            formatter: function (value) {
              return value
            },
            style: {
              colors: '#6b7280',
              fontSize: '12px',
              fontFamily: 'Inter, sans-serif',
              fontWeight: 400
            }
          }
        },
        fill: {
          opacity: 1
        },
        colors: ['#206bc4'],
        grid: {
          show: true,
          strokeDashArray: 4,
          padding: {
            left: 2,
            right: 2,
            top: -14
          }
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + " RDVs"
            }
          }
        }
      }).render()
      
      // Graphique des revenus
      window.ApexCharts && (new ApexCharts(document.getElementById('chart-revenus'), {
        series: [{
          name: 'Recettes',
          data: [1200, 1800, 1500, 2100, 1900, 2300, 2500, 2100, 2400, 2700, 2900, 3200, 
                3100, 2800, 2600, 2400, 2200, 2500, 2700, 2900, 3100, 3300, 3500, 3700, 
                3900, 4100, 4300, 4200, 4000, 3800]
        }],
        chart: {
          type: 'area',
          height: 300,
          fontFamily: 'Inter, sans-serif',
          sparkline: {
            enabled: false
          },
          toolbar: {
            show: false
          }
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.3,
          }
        },
        stroke: {
          width: 2,
          curve: 'smooth'
        },
        xaxis: {
          type: 'datetime',
          categories: Array.from({length: 30}, (_, i) => {
            const date = new Date()
            date.setDate(date.getDate() - 30 + i)
            return date.toISOString().split('T')[0]
          }),
          labels: {
            show: true,
            style: {
              colors: '#6b7280',
              fontSize: '12px',
              fontFamily: 'Inter, sans-serif',
              fontWeight: 400
            }
          },
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          }
        },
        yaxis: {
          labels: {
            formatter: function (val) {
              return val + "€"
            },
            style: {
              colors: '#6b7280',
              fontSize: '12px',
              fontFamily: 'Inter, sans-serif',
              fontWeight: 400
            }
          }
        },
        colors: ['#5eba00'],
        grid: {
          show: true,
          strokeDashArray: 4,
          padding: {
            left: 2,
            right: 2,
            top: -14
          }
        },
        tooltip: {
          x: {
            format: 'dd/MM/yyyy'
          },
          y: {
            formatter: function (val) {
              return val + "€"
            }
          }
        }
      }).render()
    })
  </script>
@endpush
@endsection