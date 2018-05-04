@extends('layouts.app')
@section('content')

	<div class="row section1">
		<div class="col s12 m12">
			<h4>Pólizas de provisión</h4>
			¿Qué tipo de provisión deseas generar?
		</div>
		<div class="col s12 m6">
			<div class="card">
				<div class="card-content" style="padding: 8px 24px 0 24px;">
					<h5>Provisión estándar</h5>
					<div class="row">
						<div class="col s12 m12 no-padding">
							<b>Cargos</b>
						</div>
						<div class="col s11 offset-s1 no-padding">
							<h6>Almacén / Inventario / Gastos</h6>
							<h6>IVA Acreditable Pendiente</h6><br>
						</div>
						<div class="col s12 m12 no-padding">
							<b>Abonos</b>
						</div>
						<div class="col s11 offset-s1 no-padding">
							<h6>Proveedores</h6>
						</div>
					</div>
				</div>
				<div class="card-action left-align">
					{{-- <button onclick="removeSelectProvisionTypeSection(1);" class="btn">Cargar CFDI's</button> --}}
					<label class="btn">
						Cargar CFDI's
						<input type="file" style="display: none;" name="standard_provision_files" id="standard_provision_files" accept=".xml" multiple>
					</label>
				</div>
				
			</div>
		</div>
		<div class="col s12 m6">
			<div class="card">
				<div class="card-content" style="padding: 8px 24px 0 24px;">
					<h5>Provisión IEPS</h5>
					<div class="row">
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
				<div class="card-action left-align">
					<button onclick="removeSelectProvisionTypeSection(2);" class="btn">Cargar CFDI's</button>
				</div>
			</div>
		</div>
		<div class="col s12 m6">
			<div class="card">
				<div class="card-content" style="padding: 8px 24px 0 24px;">
					<h5>Provisión Honorarios</h5>
					<div class="row">
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
							<h6>Proveedores</h6>
						</div>
					</div>
				</div>
				<div class="card-action left-align">
					<button onclick="removeSelectProvisionTypeSection(3);" class="btn">Cargar CFDI's</button>
				</div>
			</div>
		</div>
		<div class="col s12 m6">
			<div class="card">
				<div class="card-content" style="padding: 8px 24px 0 24px;">
					<h5>Provisión Fletes</h5>
					<div class="row">
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
							<h6>Proveedores</h6>
						</div>
					</div>
				</div>
				<div class="card-action left-align">
					<button onclick="removeSelectProvisionTypeSection(4);" class="btn">Cargar CFDI's</button>
				</div>
			</div>
		</div>
		<div class="col s12 m12 section2">
			
		</div>
	</div>
@endsection