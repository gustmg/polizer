@extends('layouts.app')

@section('content')
    <br>
    <div class="row">
        <div class="col s12 m8">
            <b>Datos de la empresa</b>
            <ul class="collection card">
                <li class="collection-item">
                    <div><b>0&nbsp;&nbsp;&nbsp;</b> Proveedores registrados<a href="#" class="secondary-content">Ver catálogo</a></div>
                </li>
                <li class="collection-item">
                    <div><b>0&nbsp;&nbsp;&nbsp;</b> Clientes registrados<a href="#" class="secondary-content">Ver catálogo</a></div>
                </li>
                <li class="collection-item">
                    <div><b>0&nbsp;&nbsp;&nbsp;</b> Cuentas Contables registradas<a href="#" class="secondary-content">Ver catálogo</a></div>
                </li>
                <li class="collection-item">
                    <div><b>0&nbsp;&nbsp;&nbsp;</b> Cuentas Bancarias registradas<a href="#" class="secondary-content">Ver catálogo</a></div>
                </li>
            </ul>
        </div>
        <div class="col s12 m4">
            <b>Uso de la aplicación</b>
            <div class="card center">
            <div class="card-content">
                @if($company_processed_xml)
                    <i class="material-icons medium">insert_drive_file</i>
                    <h5>{{$company_processed_xml}}</h5>
                    <h5>XML's procesados</h5>
                @else
                    Elige una empresa como entorno de trabajo.
                @endif
            </div>
            </div>
        </div>
    </div>    
@endsection
