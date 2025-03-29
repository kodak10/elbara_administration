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

    <script src="{{ asset('assets/preview/js/demo-theme.min.js') }}"></script>

    <div class="page">

        @include('layouts.header')

        <div class="page-wrapper">
        
          @yield('content')
       
      

       @include('layouts.footer')

    </div>
    </div>
    <script src="{{ asset('assets/libs/litepicker/dist/litepicker.js') }}" defer=""></script>

    <script src=" {{ asset('assets/libs/tom-select/dist/js/tom-select.base.min.js') }}" defer=""></script>

   
    <script src="{{ asset('assets/dist/js/tabler.min-1.js') }} " defer=""></script>
    
    
    <script src="{{ asset('assets/preview/js/demo.min-1.js')}} " defer=""></script>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        var el;
        window.TomSelect &&
          new TomSelect((el = document.getElementById("select-states")), {
            copyClassesToDropdown: false,
            dropdownParent: "body",
            controlInput: "<input>",
            render: {
              item: function (data, escape) {
                if (data.customProperties) {
                  return '<div><span class="dropdown-item-indicator">' + data.customProperties + "</span>" + escape(data.text) + "</div>";
                }
                return "<div>" + escape(data.text) + "</div>";
              },
              option: function (data, escape) {
                if (data.customProperties) {
                  return '<div><span class="dropdown-item-indicator">' + data.customProperties + "</span>" + escape(data.text) + "</div>";
                }
                return "<div>" + escape(data.text) + "</div>";
              },
            },
          });
      });
    </script>
  </body>
</html>
