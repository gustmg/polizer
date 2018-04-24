<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Polizer') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/materialize.css') }}" rel="stylesheet">
    <link href="{{ asset('css/extra.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style type="text/css">
        @auth
        header,nav, main, footer {
            padding-left: 275px;
        }
        @media only screen and (max-width : 992px) {
          header, nav, main, footer {
            padding-left: 0;
          }
        }
        @endauth
    </style>
</head>
<body class="teal lighten-5">
    <div id="app">
        @auth
        <div class="navbar-fixed">
            <nav>
                <div id="nav-bar" class="nav-wrapper blue-grey darken-4 trans-color">
                    <ul class="left">
                        <a href="#" data-activates="slide-out" class="menu hide-on-large-only"><i class="material-icons">menu</i></a>
                    </ul>

                    <a class="brand-logo right" href="{{ url('/') }}">
                        <b class="teal-text text-lighten-5">polizer</b>
                    </a>

                    <ul id="multiple-users-select-menu" class="right scale-transition scale-out">
                        <li><a href="#" class="tooltipped black-text" data-position="bottom" data-delay="50" data-tooltip="Eliminar usuarios seleccionados"><i class="material-icons">delete</i></a></li>
                    </ul>
                </div>
            </nav>
        </div>
        <ul id="slide-out" class="collapsible side-nav fixed" data-collapsible="accordion" style="border-top:0;border-left:0;border-right: 0;width: 275px;">
            <div class="card blue-grey darken-3" style="margin-top: 0;margin-bottom: 0;border-radius: 0;">
                <div class="card-content white-text">
                    <h5>{{Auth::user()->name}}</h5>
                    <a href="{{route('home')}}" class="btn-floating tooltipped halfway-fab waves-effect waves-light no-padding" data-position="bottom" data-delay="50" data-tooltip="Ir a la página principal"><i class="material-icons">home</i></a>
                </div>
            </div>
            <li class="no-padding" style="color: black;margin-left: 16px;">
                Módulos
            </li>
            <li id="purchases-menu" class="no-padding">
                <a class="collapsible-header">Integración de pólizas<i class="material-icons">library_add</i></a>
                <div class="collapsible-body">
                    <ul style="background-color:#ddd;">
                        <li><a href="#!">Provisión</a></li>
                        <li><a href="#!">Facturación</a></li>
                        <li><a href="#!">Pago a proveedores</a></li>
                        <li><a href="#!">Depósito de clientes</a></li>
                    </ul>
                </div>
            </li>
            <li id="production-menu" class="no-padding">
                <a class="collapsible-header"><i class="material-icons">business_center</i>Mis catálogos</a>
                <div class="collapsible-body">
                    <ul style="background-color:#ddd;">
                        <li><a href="{{ route('companies.index') }}">Empresas</a></li>
                        <li><a href="#!">Proveedores</a></li>
                        <li><a href="#!">Clientes</a></li>
                        <li><a href="#!">Cuentas contables</a></li>
                        <li><a href="#!">Cuentas bancarias</a></li>
                    </ul>
                </div>
            </li>
            <div class="divider"></div>
            <li class="no-padding">
                <a class="collapsible-header">Ayuda<i class="material-icons">help</i></a>
            </li>
            <li class="no-padding">
                <a class="collapsible-header">Reportar un error<i class="material-icons">error</i></a>
            </li>
            <li class="no-padding">
                <a class="collapsible-header" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Cerrar sesión<i class="material-icons">power_settings_new</i></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
        @endauth

        <main>
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/materialize.js') }}"></script>
    @if (Route::currentRouteName()=='companies.index')
        <script src="{{ asset('js/companies.js') }}"></script>
    @endif
    <script type="text/javascript">
        $(document).ready(function(){
            $(".menu").sideNav();

            @if ($errors->has('email'))
                Materialize.toast('{{ $errors->first('email') }}', 2000);
            @endif
            @if ($errors->has('password'))
                Materialize.toast('{{ $errors->first('password') }}', 2000);
            @endif

        });
    </script>
</body>
</html>
