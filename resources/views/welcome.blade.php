<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#009688" />

        <title>Polizer</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/materialize-1.css') }}" rel="stylesheet">
        <link href="{{ asset('css/home.css') }}" rel="stylesheet">
        <!-- <link href="{{ asset('css/extra.css') }}" rel="stylesheet"> -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet"> 
    </head>
    <body>
        <nav class="transparent">
            <div class="nav-wrapper">
                <a href="#" class="brand-logo black-text"><b>polizer</b></a>
                <a href="#" data-target="mobile-demo" class="sidenav-trigger black-text"><i class="material-icons">menu</i></a>
                <ul class="right hide-on-med-and-down">
                    <li><a href="#" class="btn-small white black-text"><b>Iniciar Sesión</b></a></li>
                    <li><a href="#" class="btn-small "><b>Registrarse</b></a></li>
                </ul>
            </div>
        </nav>

        <ul class="sidenav" id="mobile-demo">
            <li><a href="sass.html">Sass</a></li>
            <li><a href="badges.html">Components</a></li>
            <li><a href="collapsible.html">Javascript</a></li>
            <li><a href="mobile.html">Mobile</a></li>
        </ul>
        <!-- @if (Route::has('login'))
            <div class="top-right links">
                @auth
                    <a href="{{ url('/home') }}">Inicio</a>
                @else
                    <a href="{{ route('login') }}">Iniciar sesión</a>
                    <a href="{{ route('register') }}">Registrarse</a>
                @endauth
            </div>
        @endif -->

        <script src="{{ asset('js/materialize-1.js') }}"></script>
        <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                M.AutoInit();
            });
        </script>
    </body>
</html>
{{app('debugbar')->disable()}}