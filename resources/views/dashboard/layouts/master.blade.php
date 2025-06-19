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

    <script src="{{ asset('assets/libs/litepicker/dist/litepicker.js') }} " defer=""></script>

    <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}" defer=""></script>
    <script src="{{ asset('assets/libs/jsvectormap/dist/jsvectormap.min.js') }}" defer=""></script>
    <script src="{{ asset('assets/libs/jsvectormap/dist/maps/world.js') }} " defer=""></script>
    <script src="{{ asset('assets/libs/jsvectormap/dist/maps/world-merc.js') }} " defer=""></script>
    <script src="{{ asset('assets/dist/js/tabler.min.js') }}" defer=""></script>
    <script src="{{ asset('assets/preview/js/demo.min.js') }}" defer=""></script>
    {{-- <script src="{{ asset('assets/libs/tom-select/dist/js/tom-select.base.min.js') }} " defer=""></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script> --}}

    @stack('scripts')

</body>
</html>
