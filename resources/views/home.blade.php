@extends('layouts.app')

@section('content')
    <br>
    <div class="row">
        <div class="col s12 m8">
            <b>Datos de la empresa</b>
            <ul class="collection card">
                <li class="collection-item">
                    <div><b >123,3456</b> Proveedores registrados<a href="#" class="secondary-content">Ver catálogo</a></div>
                </li>
                <li class="collection-item">
                    <div><b >123,3456</b> Clientes registrados<a href="#" class="secondary-content">Ver catálogo</a></div>
                </li>
                <li class="collection-item">
                    <div><b >123,3456</b> Cuentas Contables registradas<a href="#" class="secondary-content">Ver catálogo</a></div>
                </li>
                <li class="collection-item">
                    <div><b >123,3456</b> Cuentas Bancarias registradas<a href="#" class="secondary-content">Ver catálogo</a></div>
                </li>
            </ul>
        </div>
        <div class="col s12 m4">
            <b>Uso de la aplicación</b>
            <div class="card center">
            <div class="card-content"><i class="material-icons medium">insert_drive_file</i>
            <h5>123,456</h5>
            <h5>XML's procesados</h5>
            </div>
            </div>
        </div>
        @if (Session::has('company_workspace'))
            {{{ Session::get('company_workspace') }}}
            {{{ Session::get('company_workspace_id') }}}
        @else
            HOLA
        @endif
    </div>
@endsection
