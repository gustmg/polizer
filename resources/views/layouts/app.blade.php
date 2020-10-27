<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#009688" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Polizer') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/materialize.css') }}" rel="stylesheet">
    <link href="{{ asset('css/extra.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet"> 
    
    <style type="text/css">
        @auth
        /*header,nav, main, footer {
            padding-left: 275px;
        }
        @media only screen and (max-width : 992px) {
          header, nav, main, footer {
            padding-left: 0;
          }
        }*/
        @endauth
    </style>
</head>
<body class="grey lighten-2">
    <div id="app">
        @auth
        <div class="navbar-fixed">
            <nav>
                <div id="nav-bar" class="nav-wrapper teal trans-color">
                    <ul class="left">
                        <li><a href="#" data-activates="slide-out" class="menu"><i class="material-icons">menu</i></a></li>
                        <li><a href="#selectWorkspaceCompanyModal" class="show-on-small breadcrumb modal-trigger hide-on-med-and-up"><i class="material-icons">business</i></a></li>
                    </ul>
                    @if (count($companies)===0)
                        <span id="workspaceCompany" class="breadcrumb modal-trigger hide-on-small-only disable-select tooltipped" style="margin-left: 10px;" data-position="right" data-delay="50" data-tooltip="No hay empresas registradas :^(">Elige una empresa como entorno de trabajo</span>
                    @else
                        <a id="workspaceCompany" href="#selectWorkspaceCompanyModal" class="breadcrumb modal-trigger hide-on-small-only" style="margin-left: 10px;">
                            @if (Session::has('company_workspace'))
                                {{{ Session::get('company_workspace') }}}
                            @else
                                Elige una empresa como entorno de trabajo
                            @endif
                        </a>
                    @endif

                    <a class="brand-logo right" href="{{ url('/') }}">
                        <b>polizer</b>
                    </a>

                    <ul id="multiple-users-select-menu" class="right scale-transition scale-out">
                        <li><a href="#" class="tooltipped black-text" data-position="bottom" data-delay="50" data-tooltip="Eliminar usuarios seleccionados"><i class="material-icons">delete</i></a></li>
                    </ul>
                    <div class="progress" style="margin-top: -2px;visibility: hidden;">
                        <div class="indeterminate"></div>
                    </div>
                </div>
            </nav>
        </div>
        <ul id="slide-out" class="collapsible side-nav" data-collapsible="accordion" style="border-top:0;border-left:0;border-right: 0;width: 275px;">
            <div class="card black" style="margin-top: 0;margin-bottom: 0;border-radius: 0;">
                <div class="card-content white-text">
                    <h5>{{Auth::user()->name}}</h5>
                    <a href="{{route('home')}}" class="btn-floating halfway-fab waves-effect waves-light no-padding teal accent-4" data-position="bottom" data-delay="50"><i class="material-icons ">home</i></a>
                </div>
            </div>
            <li class="no-padding">
                <a href="{{ route('companies.index') }}" class="collapsible-header">Mis empresas<i class="material-icons">business</i></a>
            </li>
            <div class="divider no-margin"></div>
            @if(Session::has('company_workspace'))
                <li class="no-padding company-workspace-menu">
                    <a class="collapsible-header">Integración de pólizas<i class="material-icons">library_add</i></a>
                    <div class="collapsible-body">
                        <ul style="background-color:#ddd;">
                            <li><a href="{{ route ('provision_policy') }}">Provisión</a></li>
                            <li><a href="{{ route ('billing_policy') }}">Facturación</a></li>
                            <li><a href="{{ route ('provider_payment_policy') }}">Pago a proveedores</a></li>
                            <li><a href="{{ route ('client_deposit_policy') }}">Depósito de clientes</a></li>
                        </ul>
                    </div>
                </li>
                <li class="no-padding company-workspace-menu">
                    <a class="collapsible-header"><i class="material-icons">business_center</i>Mis catálogos</a>
                    <div class="collapsible-body">
                        <ul style="background-color:#ddd;">
                            <li><a href="{{route('providers.index')}}">Proveedores</a></li>
                            <li><a href="{{route('clients.index')}}">Clientes</a></li>
                            <li><a href="{{ route('accounting_accounts.index') }}">Cuentas contables</a></li>
                            <li><a href="{{ route('bank_accounts.index') }}">Cuentas bancarias</a></li>
                        </ul>
                    </div>
                </li>
                <div class="divider no-margin"></div>
            @else
                <li class="no-padding company-workspace-menu" style="display: none;">
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
                <li class="no-padding company-workspace-menu" style="display: none;">
                    <a class="collapsible-header"><i class="material-icons">business_center</i>Mis catálogos</a>
                    <div class="collapsible-body">
                        <ul style="background-color:#ddd;">
                            <li><a href="#">Proveedores</a></li>
                            <li><a href="#!">Clientes</a></li>
                            <li><a href="{{ route('accounting_accounts.index') }}">Cuentas contables</a></li>
                            <li><a href="#!">Cuentas bancarias</a></li>
                        </ul>
                    </div>
                </li>
                <div class="divider no-margin company-workspace-menu" style="display: none;"></div>
            @endif
                    
            
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
        <div id="selectWorkspaceCompanyModal" class="modal selectWorkspaceCompanyModal modal-fixed-footer">
            <div style="height: 56px;padding-left: 12px;">
                <h5>Empresa como entorno de trabajo</h5>
            </div>
            <div class="modal-content" style="max-height: calc(100% - 132px);padding-top: 0;padding-bottom: 0; overflow-y: auto;">
                <form id="selectWorkspaceForm">
                    @foreach($companies as $key => $value)
                    <p>
                        <input class="with-gap" name="company_workspace" type="radio" id="test{{$key}}" value="{{$value->company_id}}" data-company-name="{{$value->company_name}}"/>
                        <label for="test{{$key}}">{{$value->company_name}}</label>
                    </p>
                    @endforeach
                </form>
            </div>
            <div class="modal-footer">
                <a href="#" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
                <button id="select_button" class="modal-action btn-flat waves-effect" onclick="setWorkspaceCompany();"><b>Seleccionar</b></button>
            </div>
        </div>
        @endauth

        <main>
            @yield('content')
        </main>

        <div id="invalidUserModal" class="modal invalidUserModal" align="center">
            <div style="height: 56px;padding-left: 12px;" >
                <h5>Cuenta no registrada o temporalmente suspendida</h5>
            </div>
            <div class="modal-content">
                La cuenta con la que desea iniciar sesión no está registrada o está temporalmente suspendida.
            </div>
            <div class="modal-footer">
                <a href="#" class="modal-action modal-close waves-effect btn-flat"><b>Entendido</b></a>
            </div>
        </div>
    </div>
</body>
<!-- Scripts -->
<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/materialize.js') }}"></script>
<script src="{{ asset('js/tablesort.js') }}"></script>
<script src="{{ asset('js/tablesort.date.js') }}"></script>
<script src="{{ asset('js/tablesort.number.js') }}"></script>
@if (Route::currentRouteName()=='companies.index')
    <script src="{{ asset('js/companies.js') }}"></script>
@endif
@if (Route::currentRouteName()=='accounting_accounts.index')
    <script src="{{ asset('js/accounting_accounts.js') }}"></script>
@endif
@if (Route::currentRouteName()=='providers.index')
    <script src="{{ asset('js/providers.js') }}"></script>
@endif
@if (Route::currentRouteName()=='clients.index')
    <script src="{{ asset('js/clients.js') }}"></script>
@endif
@if (Route::currentRouteName()=='bank_accounts.index')
    <script src="{{ asset('js/bank_accounts.js') }}"></script>
@endif
@if (Route::currentRouteName()=='provision_policy')
    <script src="{{ asset('js/provision.js') }}"></script>
    <script src="{{ asset('js/policy_navbar.js') }}"></script>
    <script src="{{ asset('js/jquery.hoverIntent.min.js') }}"></script>
    <script src="{{ asset('js/providers.js') }}"></script>
    <script src="{{ asset('js/accounting_accounts.js') }}"></script>
@endif
@if (Route::currentRouteName()=='billing_policy')
    <script src="{{ asset('js/billing_policy.js') }}"></script>
    <script src="{{ asset('js/policy_navbar.js') }}"></script>
    <script src="{{ asset('js/jquery.hoverIntent.min.js') }}"></script>
    <script src="{{ asset('js/clients.js') }}"></script>
    <script src="{{ asset('js/accounting_accounts.js') }}"></script>
@endif
@if (Route::currentRouteName()=='provider_payment_policy')
    <script src="{{ asset('js/provider_payment_policy.js') }}"></script>
    <script src="{{ asset('js/policy_navbar.js') }}"></script>
    <script src="{{ asset('js/jquery.hoverIntent.min.js') }}"></script>
    <script src="{{ asset('js/providers.js') }}"></script>
    <script src="{{ asset('js/accounting_accounts.js') }}"></script>
@endif
@if (Route::currentRouteName()=='client_deposit_policy')
    <script src="{{ asset('js/client_deposit_policy.js') }}"></script>
    <script src="{{ asset('js/policy_navbar.js') }}"></script>
    <script src="{{ asset('js/jquery.hoverIntent.min.js') }}"></script>
    <script src="{{ asset('js/clients.js') }}"></script>
    <script src="{{ asset('js/accounting_accounts.js') }}"></script>
@endif

<script type="text/javascript">
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".menu").sideNav();
        $('.tooltipped').tooltip({delay: 50});
        $('.selectWorkspaceCompanyModal').modal();
        $('.invalidUserModal').modal();

        @if(session()->has('company_workspace_id'))
            $('#workspaceCompany').on('click', function (){
                $('#selectWorkspaceForm input[value="'+{{Session::get('company_workspace_id')}}+'"]').prop('checked',true);
            });
        @endif

        @if ($errors->has('email'))
            Materialize.toast('{{ $errors->first('email') }}', 2000);
        @endif
        
        @if ($errors->has('password'))
            Materialize.toast('{{ $errors->first('password') }}', 2000);
        @endif
    });

    function setWorkspaceCompany() {
        var company_workspace_id=$('#selectWorkspaceForm :checked').val();
        var company_workspace=$('#selectWorkspaceForm :checked').attr('data-company-name');

        $.ajax({
            url: "./workspace",
            type: 'POST',
            data: {company_workspace_id: company_workspace_id},
        })
        .done(function() {
            Materialize.toast('Entorno de trabajo cambiado correctamente', 4000);
            $("#workspaceCompany").text(company_workspace);
            showCompanyWorkspaceMenu();
            $('.selectWorkspaceCompanyModal').modal('close');
            location.reload();
        })
        .fail(function() {
            Materialize.toast('Error al seleccionar entorno de trabajo', 4000);
        })
    }

    function showCompanyWorkspaceMenu() {
        $('.company-workspace-menu').slideDown();
    }

    function validateUser(){
        var email=$("#email").val();
        if(email != "gustavo.mitre.gallardo@gmail.com" && email != "guslopez3@hotmail.com" && email != "yazzmin_815yeickra@hotmail.com" && email != "contabilidadfiscal70@hotmail.com" && email != "julisa_chr@live.com.mx" && email != ""){
            $('.invalidUserModal').modal('open');
        }
        else{
            $(".login-form").submit();
        }
    }
</script>
{{app('debugbar')->disable()}}
</html>
