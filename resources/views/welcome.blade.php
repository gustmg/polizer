<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#009688" />

        <title>Polizer</title>        

        <!-- Styles -->
        <link href="{{ asset('css/materialize-1.css') }}" rel="stylesheet">
        <link href="{{ asset('css/home.css') }}" rel="stylesheet">
        <!-- <link href="{{ asset('css/extra.css') }}" rel="stylesheet"> -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet"> 
    </head>
    <body>
        <div class="container full-height valign-wrapper" >
            <div class="row">
                <div class="col s12" align="center">
                    <h1><b>polizer</b></h1><br>
                </div>
                @if (Auth::check())
                    <div class="col s12" align="center">
                        <a href="/home" class="waves-effect waves-light btn teal">Ir a inicio</a>

                        
                    </div> 
                @else
                    <div class="col s12" align="center">
                        <a href="/login" class="waves-effect waves-light btn teal">Iniciar sesión</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="/register" class="waves-effect waves-light btn teal">Crear cuenta</a>
                    </div>   
                @endif           
            </div>
        </div>
        <script src="{{ asset('js/materialize-1.js') }}"></script>
        <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                M.AutoInit();
            });
        </script>
    </body>
</html>