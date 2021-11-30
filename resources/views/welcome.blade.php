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
    <!-- datepicker -->
    <link href="{{url('assets/libs/air-datepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- jvectormap -->
    <link href="{{url('assets/libs/jqvmap/jqvmap.min.css')}}" rel="stylesheet" />

    <!-- Bootstrap Css -->
    <link href="{{url('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{url('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{url('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />

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

</head>

<body data-topbar="colored">
<div id="layout-wrapper">
    <div class="main-content">

        <div class="page-content">

            <body class="bg-primary bg-pattern">
            <div class="account-pages my-5 pt-sm-5">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-sm-8">
                            <div class="card">
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="text-center mb-5">
                                                <img src="{{url('assets/images/whelson_logo.png')}}" height="32" alt="logo">
                                                <br><br>
                                                <h5 class="font-size-16 text-black-50 mb-4">Whelson / GDC Transport Fuel Management System</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-2">
                                        <form method="POST" class="form-horizontal" action="{{ route('login') }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-custom mb-4">
                                                        <input id="paynumber" type="text" class="form-control{{ $errors->has('paynumber') || $errors->has('email') ? ' is-invalid' : '' }}" name="paynumber" value="{{ old('paynumber') ?: old('email') }}" required autofocus>
                                                        <label for="paynumber">Pay Number or Email:</label>

                                                        @if ($errors->has('paynumber') || $errors->has('email'))
                                                            <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('paynumber') ?: $errors->first('email')}}</strong>
                                                    </span>
                                                        @endif
                                                    </div>

                                                    <div class="form-group form-group-custom mb-4">
                                                        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" required>
                                                        <label for="userpassword">Password</label>

                                                        @if ($errors->has('password'))
                                                            <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                        @endif
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="customControlInline" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="customControlInline">Remember me</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="text-md-right mt-3 mt-md-0">
                                                                <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock"></i> Forgot your password?</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4">
                                                        <button class="btn btn-success btn-block waves-effect waves-light" type="submit">Log In</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
            </div>

            </body>
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

<script src="{{url('assets/js/pages/dashboard.init.js')}}"></script>

<script src="{{url('assets/js/app.js')}}"></script>

</body>
</html>