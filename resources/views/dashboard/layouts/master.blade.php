<!doctype html>

<html lang="fr">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Gestionnaire de clinique</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/libs/tom-select/dist/css/tom-select.bootstrap5.min.css') }} " rel="stylesheet">

    <link href="{{ asset('assets/dist/css/tabler.min.css') }}" rel="stylesheet">
   
    <link href="{{ asset('assets/dist/css/tabler-payments.min.css') }} " rel="stylesheet">
    <link href="{{ asset('assets/dist/css/tabler-vendors.min.css') }} " rel="stylesheet">

    <link href="{{ asset('assets/preview/css/demo.min.css') }} " rel="stylesheet">
    
    
    @stack('styles')

  </head>

  <body>

    <!-- Form Preloader -->
    <div id="form-preloader" style="display: none; position: fixed; top: 0; left: 0; z-index: 9999; width: 100vw; height: 100vh; background: white;" class="page page-center">
      <div class="container container-slim py-4">
        <div class="text-center">
          <div class="text-secondary mb-3">Veuillez patienter...</div>
          <div class="progress progress-sm">
            <div class="progress-bar progress-bar-indeterminate"></div>
          </div>
        </div>
      </div>
    </div>


    
    <div class="page">
      
      @include('dashboard.layouts.header')

      <div class="page-wrapper">
        
        <div class="page-body">
          @yield('content')
        </div>
       
        @include('dashboard.layouts.footer')

      </div>
    </div>

    


   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
      $(function () {
            $('.form-loader').on('submit', function () {
                // Afficher le preloader
                $('#form-preloader').show();

                // Désactiver le bouton et afficher le spinner
                const $btn = $('#submit-btn');
                $btn.prop('disabled', true);
                $('#spinner').removeClass('d-none');
                $('.btn-text').text('Patientez...');
            });
        });
    </script>
   @if ($errors->any())
  <script>
    $(document).ready(function() {
      $('#form-preloader').hide();
      // Réactiver le bouton si nécessaire
      $('#submit-btn').prop('disabled', false);
      $('#spinner').addClass('d-none');
      $('.btn-text').text('Soumettre');
    });
  </script>
@endif

    <script>
  $(function () {
    // Cible uniquement les liens du menu principal en ignorant les dropdowns
    $('.navbar a.nav-link').not('.dropdown-menu a, .dropdown-toggle').on('click', function (e) {
      const href = $(this).attr('href');

      if (href && href !== '#' && !$(this).attr('target')) {
        $('#form-preloader').fadeIn(100);
      }
    });
  });
</script>



    <script src="{{ asset('assets/libs/litepicker/dist/litepicker.js') }} " defer=""></script>

    <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}" defer=""></script>
    <script src="{{ asset('assets/libs/jsvectormap/dist/jsvectormap.min.js') }}" defer=""></script>
    <script src="{{ asset('assets/libs/jsvectormap/dist/maps/world.js') }} " defer=""></script>
    <script src="{{ asset('assets/libs/jsvectormap/dist/maps/world-merc.js') }} " defer=""></script>
    <script src="{{ asset('assets/dist/js/tabler.min.js') }}" defer=""></script>
    <script src="{{ asset('assets/preview/js/demo.min.js') }}" defer=""></script>    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @stack('scripts')

    <script>
      // Affichage des messages flash avec SweetAlert
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: '{{ session('error') }}'
            });
        @endif
    </script>

</body>
</html>
