<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>@hasSection('template_title')@yield('template_title') | @endif {{ config('app.name', Lang::get('titles.app')) }}</title>
        {{-- CSRF Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Whelson Transport Fuel Management System" name="description" />
        <meta content="Vincent H Guyo" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{url('assets/images/favicon.png')}}">

        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        {{-- Fonts --}}
        @yield('template_linked_fonts')
        <!-- datepicker -->
        <link href="{{url('assets/libs/air-datepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css" />

        <!-- jvectormap -->
        <link href="{{url('assets/libs/jqvmap/jqvmap.min.css')}}" rel="stylesheet" />

        <!-- alertifyjs Css -->
        <link href="{{url('assets/libs/alertifyjs/build/css/alertify.min.css')}}" rel="stylesheet" type="text/css" />

        <!-- alertifyjs default themes  Css -->
        <link href="{{url('assets/libs/alertifyjs/build/css/themes/default.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Bootstrap Css -->
        <link href="{{url('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{url('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{url('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
        @yield('template_linked_css')

        <style type="text/css">
            @yield('template_fastload_css')

            @if (Auth::User() && (Auth::User()->profile) && (Auth::User()->profile->avatar_status == 0))
                .user-avatar-nav {
                background: url({{ Gravatar::get(Auth::user()->email) }}) 50% 50% no-repeat;
                background-size: auto 100%;
            }
            @endif

        </style>

        {{-- Scripts --}}
        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
        </script>

        @if (Auth::User() && (Auth::User()->profile) && $theme->link != null && $theme->link != 'null')
            <link rel="stylesheet" type="text/css" href="{{ $theme->link }}">
        @endif

        @yield('head')
    </head>

    <body data-topbar="colored">
        <div id="layout-wrapper">

            @include('partials.nav')
            <div class="main-content">

                <div class="page-content">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                @include('partials.form-status')
                            </div>
                        </div>
                    </div>
                    @yield('content')

                    <footer class="footer">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-6">
                                    @php echo date('Y'); @endphp © Whelson GDC Transport - Fuel Management System.
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-sm-right d-none d-sm-block">
                                        Powered by Whelson IT.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>

    <!-- JAVASCRIPT -->
    <script src="{{url('assets/libs/jquery/jquery.min.js')}}"></script>
    <script src="{{url('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{url('assets/libs/metismenu/metisMenu.min.js')}}"></script>
    <script src="{{url('assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{url('assets/libs/node-waves/waves.min.js')}}"></script>

    <script src="https://unicons.iconscout.com/release/v2.0.1/script/monochrome/bundle.js"></script>

    <!-- datepicker -->
    <script src="{{url('assets/libs/air-datepicker/js/datepicker.min.js')}}"></script>
    <script src="{{url('assets/libs/air-datepicker/js/i18n/datepicker.en.js')}}"></script>

    <!-- apexcharts -->
    <script src="{{url('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

    <script src="{{url('assets/libs/jquery-knob/jquery.knob.min.js')}}"></script>

    <!-- Jq vector map -->
    <script src="{{url('assets/libs/jqvmap/jquery.vmap.min.js')}}"></script>
    <script src="{{url('assets/libs/jqvmap/maps/jquery.vmap.usa.js')}}"></script>

    {{--<script src="{{url('assets/js/pages/dashboard.init.js')}}"></script>--}}

    <!-- alertifyjs js -->
    <script src="{{url('assets/libs/alertifyjs/build/alertify.min.js')}}"></script>

    <script src="{{url('assets/js/pages/alertifyjs.init.js')}}"></script>

    <script src="{{url('assets/js/app.js')}}"></script>
        <script >
            function markNotificationAsRead() {
               $.get('/whelsonfuel/markNotifsAsRead');
            }
        </script>

    @yield('footer_scripts')

    </body>
</html>










