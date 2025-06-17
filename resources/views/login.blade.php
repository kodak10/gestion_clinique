<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gestionnaire de Clinique</title>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="{{ asset('assets/dist/css/tabler.min-2.css') }}" rel="stylesheet">
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PLUGINS STYLES -->
    <link href="{{ asset('assets/dist/css/tabler-flags.min-2.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dist/css/tabler-socials.min-2.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dist/css/tabler-payments.min-2.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dist/css/tabler-vendors.min-2.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dist/css/tabler-marketing.min-2.css') }}" rel="stylesheet">
    <!-- END PLUGINS STYLES -->
    <!-- BEGIN DEMO STYLES -->
    <link href="{{ asset('assets/preview/css/demo.min-2.css') }}" rel="stylesheet">
    <!-- END DEMO STYLES -->
    <!-- BEGIN CUSTOM FONT -->
    <style>
      @import url("{{ asset('assets/inter/inter.css') }}");
    </style>
    <!-- END CUSTOM FONT -->
  </head>
  <body class="d-flex flex-column bg-white">
    <div class="row g-0 flex-fill">
      <div class="col-12 col-lg-6 col-xl-4 border-top-wide border-primary d-flex flex-column justify-content-center">
        <div class="container container-tight my-5 px-lg-5">
          <div class="text-center mb-4">
            <a href="/" class="navbar-brand navbar-brand-autodark">
              CLINIQUE MEDICALE SILOE CORPORATION 
            </a>
          </div>
          <h2 class="h3 text-center mb-3">Connectez-vous à votre compte</h2>
          
          @if($errors->any())
            <div class="alert alert-danger">
              @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
              @endforeach
            </div>
          @endif
          
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Pseudo</label>
              <input type="text" value="admin" class="form-control @error('pseudo') is-invalid @enderror" 
                     name="pseudo" value="{{ old('pseudo') }}" required autocomplete="pseudo" autofocus>
              @error('pseudo')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="mb-2">
              <label class="form-label">Mot de passe</label>
              <div class="input-group input-group-flat">
                <input type="password" value="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" required autocomplete="current-password">
                <span class="input-group-text">
                  <a href="#" class="link-secondary" title="Voir le mot de passe" data-bs-toggle="tooltip" onclick="togglePassword()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                      <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                      <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                    </svg>
                  </a>
                </span>
                @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            <div class="mb-2">
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <span class="form-check-label">Se souvenir de moi</span>
              </label>
            </div>
            <div class="form-footer">
              <button type="submit" class="btn btn-primary w-100">Se Connecter</button>
            </div>
          </form>
        </div>
      </div>
      <div class="col-12 col-lg-6 col-xl-8 d-none d-lg-block">
        <div class="bg-cover h-100 min-vh-100" style="background-image: url({{ asset('assets/dist/img/banner_login.jpg') }})"></div>
      </div>
    </div>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('assets/dist/js/tabler.min-2.js') }}" defer></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <!-- BEGIN DEMO SCRIPTS -->
    <script src="{{ asset('assets/preview/js/demo.min-2.js') }}" defer></script>
    <!-- END DEMO SCRIPTS -->
    
    <script>
      function togglePassword() {
        const passwordInput = document.querySelector('input[name="password"]');
        if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
        } else {
          passwordInput.type = 'password';
        }
      }
    </script>
  </body>
</html>