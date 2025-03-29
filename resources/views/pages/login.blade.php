<!doctype html>

<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ELBARA | ADMINISTRATION</title>
    
    <link href="{{ asset('assets/libs/tom-select/dist/css/tom-select.bootstrap5.min.css') }} " rel="stylesheet">

    <link href="{{ asset('assets/dist/css/tabler.min-3.css') }}" rel="stylesheet">
   

    <link href="{{ asset('assets/dist/css/tabler-flags.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dist/css/tabler-socials.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dist/css/tabler-payments.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dist/css/tabler-vendors.min.css') }} " rel="stylesheet">
    <link href="{{ asset('assets/dist/css/tabler-marketing.min.css') }} " rel="stylesheet">
    
    
    <link href="{{ asset('assets/preview/css/demo.min-3.css') }}" rel="stylesheet">
    
    
    <style>
      @import url("{{ asset('assets/inter/inter.css') }}");
    </style>
    
    
  </head>
  <body>
    <!-- BEGIN DEMO THEME SCRIPT -->
    <script src="preview/js/demo-theme.min-2.js?1740838744"></script>
    <!-- END DEMO THEME SCRIPT -->
    <div class="page page-center">
      <div class="container container-tight py-4">
        <div class="text-center mb-4">
          <!-- BEGIN NAVBAR LOGO -->
          <img src="{{ asset('assets/icon.png') }}" style="height: 150px" alt="">
          
          <!-- END NAVBAR LOGO -->
        </div>
        <div class="card card-md">
          <div class="card-body">
            <h2 class="h2 text-center mb-4">ADMINISTRATION ELBARA EXPRESS</h2>
            <form action="{{ route('login') }}" method="POST" autocomplete="off" novalidate="">
              @csrf
              <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" placeholder="nom@wuras.ci" value="{{ old('email') }}" required autocomplete="email" autofocus>
                  @error('email')
                      <div class="text-danger">{{ $message }}</div>
                  @enderror
              </div>
          
              <div class="mb-2">
                  <label class="form-label">
                      Mot de passe
                      {{-- <span class="form-label-description">
                          <a href="{{ route('password.request') }}">Mot de passe oublié</a>
                      </span> --}}
                  </label>
                  <div class="input-group input-group-flat">
                      <input type="password" value="Elbara2025" name="password" class="form-control" placeholder="Mot de passe" required autocomplete="current-password">
                      @error('password')
                          <div class="text-danger">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
          
              <div class="form-footer">
                  <button type="submit" class="btn btn-primary w-100">Se Connecter</button>
              </div>
          </form>
          
          </div>
          
        </div>
      </div>
    </div>
    <script src="{{ asset('assets/libs/litepicker/dist/litepicker.js') }}" defer=""></script>

    <script src=" {{ asset('assets/libs/tom-select/dist/js/tom-select.base.min.js') }}" defer=""></script>

   
    <script src="{{ asset('assets/dist/js/tabler.min-1.js') }} " defer=""></script>
    
    
    <script src="{{ asset('assets/preview/js/demo.min-1.js')}} " defer=""></script>
  </body>
</html>
