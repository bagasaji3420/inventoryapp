 <head>
     <meta charset="utf-8" />
     <meta name="viewport"
         content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

     <title>{{ $title }}</title>

     <meta name="description" content="" />

     <meta name="user-id" content="{{ auth()->id() }}">
     <meta name="csrf-token" content="{{ csrf_token() }}">

     <!-- Favicon -->
     @php $appSettings = \App\Models\Settings::current(); @endphp
     <link rel="icon" type="image/x-icon"
         href="{{ $appSettings->logo ? asset('storage/' . $appSettings->logo) : asset('assets/img/favicon/favicon.ico') }}" />

     <!-- Fonts -->
     <link rel="preconnect" href="https://fonts.googleapis.com" />
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
     <link
         href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
         rel="stylesheet" />

     <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />

     <!-- Core CSS -->
     <!-- build:css assets/vendor/css/theme.css  -->

     <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />

     <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
     <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
     <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" />

     <!-- Vendors CSS -->

     <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

     <!-- endbuild -->

     <link rel="stylesheet" href="{{ asset('assets/vendor/libs/maxLength/maxLength.css') }}" />

     <!-- Helpers -->
     <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

     <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
     {{-- <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script> --}}


     <script src="{{ asset('assets/js/config.js') }}"></script>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


     @if ($title == 'Roles')
         <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
     @endif

     <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
     <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
     <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">



     @if (View::hasSection('livewire'))
         @livewireStyles
     @endif
 </head>
