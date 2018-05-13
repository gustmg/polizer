@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row section1">
		<div class="col s12 m6">
			<div class="card">
				<div class="card-content" style="padding: 8px 24px 0 24px;">
					<div class="row no-margin">
						<h5><b>Estándar</b></h5>
						<div class="col s12 m12 no-padding">
							<b>Cargos</b>
						</div>
						<div class="col s11 offset-s1 no-padding">
							<h6>Almacén / Inventario / Gastos</h6>
							<h6>IVA Acreditable Pendiente</h6>
						</div>
						<div class="col s12 m12 no-padding">
							<b>Abonos</b>
						</div>
						<div class="col s11 offset-s1 no-padding">
							<h6>Proveedores</h6><br>
						</div>
					</div>
				</div>
				<div class="card-action right-align">
					<label class="btn">
						<b>Cargar CFDI's</b>
						<input type="file" style="display: none;" name="standard_provision_files" id="standard_provision_files" accept=".xml" multiple>
					</label>
				</div>
			</div>
		</div>
		<div class="col s12 m6">
			<div class="card">
				<div class="card-content" style="padding: 8px 24px 0 24px;">
					<div class="row no-margin">
						<h5><b>IEPS</b></h5>
						<div class="col s12 m12 no-padding">
							<b>Cargos</b>
						</div>
						<div class="col s11 offset-s1 no-padding">
							<h6>Almacén / Inventario / Gastos</h6>
							<h6>IEPS</h6>
							<h6>IVA Acreditable Pendiente</h6>
						</div>
						<div class="col s12 m12 no-padding">
							<b>Abonos</b>
						</div>
						<div class="col s11 offset-s1 no-padding">
							<h6>Proveedores</h6>
						</div>
					</div>
				</div>
				<div class="card-action right-align">
					<label class="btn">
						<b>Cargar CFDI's</b>
						<input type="file" style="display: none;" name="standard_provision_files" id="standard_provision_files" accept=".xml" multiple>
					</label>
				</div>
			</div>
		</div>
		<div class="col s12 m6">
			<div class="card">
				<div class="card-content" style="padding: 8px 24px 0 24px;">
					<div class="row no-margin">
						<h5><b>Honorarios</b></h5>
						<div class="col s12 m12 no-padding">
							<b>Cargos</b>
						</div>
						<div class="col s11 offset-s1 no-padding">
							<h6>Honorarios</h6>
							<h6>IVA Acreditable Pendiente</h6>
						</div>
						<div class="col s12 m12 no-padding">
							<b>Abonos</b>
						</div>
						<div class="col s11 offset-s1 no-padding">
							<h6>Proveedores</h6><br>
						</div>
					</div>
				</div>
				<div class="card-action right-align">
					<label class="btn">
						<b>Cargar CFDI's</b>
						<input type="file" style="display: none;" name="standard_provision_files" id="standard_provision_files" accept=".xml" multiple>
					</label>
				</div>
			</div>
		</div>
		<div class="col s12 m6">
			<div class="card">
				<div class="card-content" style="padding: 8px 24px 0 24px;">
					<div class="row no-margin">
						<h5><b>Fletes</b></h5>
						<div class="col s12 m12 no-padding">
							<b>Cargos</b>
						</div>
						<div class="col s11 offset-s1 no-padding">
							<h6>Fletes</h6>
							<h6>IVA Acreditable Pendiente</h6>
						</div>
						<div class="col s12 m12 no-padding">
							<b>Abonos</b>
						</div>
						<div class="col s11 offset-s1 no-padding">
							<h6>Proveedores</h6><br>
						</div>
					</div>
				</div>
				<div class="card-action right-align">
					<label class="btn">
						<b>Cargar CFDI's</b>
						<input type="file" style="display: none;" name="standard_provision_files" id="standard_provision_files" accept=".xml" multiple>
					</label>
				</div>
			</div>
		</div>
	</div>
	<div class="row section2">
		<ul class="collection" style="overflow: visible;">
			{{-- <li class="collection-item avatar" data-file-index="1">
				<i class="material-icons circle white-text">subject</i>
				<span>
					<b>Gustavo Mitre Gallardo (RFC123456789)</b>
				</span>
				<a href="#!" class="secondary-content dropdown-button" data-activates="dropdown-menu1" data-alignment="right">
					<i class="material-icons">more_vert</i>
				</a><br>
				<span class="grey-text text-darken-2">
					<b>Serie:</b> <i>A</i>&nbsp;&nbsp;<b>Folio:</b> <i>1026</i>
				</span><br>
				<span class="grey-text text-darken-2">
					<b>Fecha de emisión:</b> <i>Marzo, 2015</i></span><br>
				<span class="grey-text text-darken-2">
					<b>Conceptos:</b> <i>Recarga telefónica&nbsp;&nbsp;</i>
					<a href="#">Ver todos los conceptos...</a>
				</span><br>
				<span class="grey-text text-darken-2">
					<b>Contrapartida:</b> <i>1200-000-000 Recarga telefónica</i>
				</span>
				<ul id='dropdown-menu1' class='dropdown-content' style="min-width: 200px;">
					<li><a href="#!">Agregar proveedor</a></li>
					<li><a href="#!">Cambiar cuenta destino</a></li>
					<li><a href="#!">Eliminar XML</a></li>
				</ul>
			</li> --}}
			
		</ul>
	</div>
</div>
@endsection