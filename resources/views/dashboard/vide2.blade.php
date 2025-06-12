@extends('dashboard.layouts.master')
@section('content')
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h2 class="page-title">Empty page</h2>
              </div>
            </div>
          </div>
        </div>
        <!-- END PAGE HEADER -->
        <!-- BEGIN PAGE BODY -->
        <div class="page-body">
          <div class="container-xl">
            <!-- Content here -->
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <h3 class="text-center">Aucun règlement trouvé</h3>
                    <p class="text-center">Il n'y a pas de règlements à afficher pour le moment.</p>
                    <p class="text-center"><a href="" class="btn btn-primary">Ajouter un règlement</a></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
@endsection