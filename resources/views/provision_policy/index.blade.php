@extends('layouts.app')
@section('content')
<div id="menu_navbar" class="row white valign-wrapper" style="height: 48px;display: none;">
	<div class="col s2" style="margin-left: 4px;">
		<a id="back_prev" class="selectable">
			<i class="material-icons black-text">arrow_back</i>
		</a>
	</div>
	<div class="col s10 right-align" style="margin-right: 4px;">
		<a id="cfdi_config" class="selectable">
			<i class="material-icons black-text">settings</i>
		</a>
		&nbsp;&nbsp;
		<a>
			<i class="material-icons black-text">search</i>
		</a>
		&nbsp;&nbsp;
		<a id="add_standard_provision_files" class="selectable">
			<i class="material-icons black-text">note_add</i>
		</a>
		&nbsp;&nbsp;
		<a id="send_json_files" class="selectable">
			<i class="material-icons black-text">get_app</i>
		</a>
	</div>
</div>
<div class="container section1">
	<div class="row">
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
					<label id="add_files" class="btn">
						<b>Cargar CFDI's</b>
						<input type="file" style="display: none;" name="standard_provision_files" id="standard_provision_files" accept=".xml" onclick="setProvisionType(1);" multiple>
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
						<input type="file" style="display: none;" name="standard_provision_files" id="ieps_provision_files" accept=".xml" onclick="setProvisionType(2);" multiple>
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
						<input type="file" style="display: none;" name="standard_provision_files" id="honorarium_provision_files" accept=".xml" onclick="setProvisionType(3);" multiple>
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
						<input type="file" style="display: none;" name="standard_provision_files" id="freight_provision_files" accept=".xml" onclick="setProvisionType(4);" multiple>
					</label>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container section2">
	<div class="row">
		<ul class="col s12 collection collection-cfdi" style="overflow: visible;">
		</ul>
		<div id="modalContrapartida1" class="modal modal-fixed-footer">
			<div class="modal-content">
				<ul class="collection with-header">
					<li class="collection-header teal white-text"><h5>Inventarios</h5></li>
					@foreach($accounting_accounts as $key => $accounting_account)
						@if($accounting_account->accounting_account_type_id ==2)
							<a onclick="setConceptCounterpart('{{$accounting_account->accounting_account_number}}','{{$accounting_account->accounting_account_description}}');" class="collection-item selectable">
								<span><b>
									{{$accounting_account->accounting_account_number}}
								</b></span><br>
								<span class="truncate"><i class="grey-text text-darken-2">{{$accounting_account->accounting_account_description}}</i></span>
							</a>
						@endif
					@endforeach
					<li class="collection-header teal white-text"><h5>Gastos de Venta</h5></li>
					@foreach($accounting_accounts as $key => $accounting_account)
						@if($accounting_account->accounting_account_type_id ==5)
							<a onclick="setConceptCounterpart('{{$accounting_account->accounting_account_number}}','{{$accounting_account->accounting_account_description}}');" class="collection-item selectable">
								<span data-accounting-account-number="{{$accounting_account->accounting_account_number}}"><b>
									{{$accounting_account->accounting_account_number}}
								</b></span><br>
								<span data-accounting-account-description="{{$accounting_account->accounting_account_description}}" class="truncate"><i class="grey-text text-darken-2">{{$accounting_account->accounting_account_description}}</i></span>
							</a>
						@endif
					@endforeach
					<li class="collection-header teal white-text"><h5>Gastos de administración</h5></li>
					@foreach($accounting_accounts as $key => $accounting_account)
						@if($accounting_account->accounting_account_type_id ==6)
							<a onclick="setConceptCounterpart('{{$accounting_account->accounting_account_number}}','{{$accounting_account->accounting_account_description}}');" class="collection-item selectable">
								<span><b>
									{{$accounting_account->accounting_account_number}}
								</b></span><br>
								<span class="truncate"><i class="grey-text text-darken-2">{{$accounting_account->accounting_account_description}}</i></span>
							</a>
						@endif
					@endforeach
				</ul>
			</div>
			<div class="modal-footer">
				<button class="btn-flat modal-close"><b>Cerrar</b></button>
			</div>
		</div>
		<div id="modalRemoveFile" class="modal">
			<div class="modal-content">
				<h5>Eliminar CFDI de la lista?</h5>
			</div>
			<div class="modal-footer">
				<button class="btn-flat modal-close"><b>Cancelar</b></button>
				<button id="removeFileConfirmButton" class="btn"><b>Eliminar</b></button>
			</div>
		</div>
		<div id="modalFilesConfig" class="modal modal-fixed-footer">
			<div class="modal-content">
				<div class="col s12">
					Valor inicial de serie:
					<div class="input-field inline">
						<input type="number" name="cfdi_index_serie" id="cfdi_index_serie" value="1" min="1" class="validate" onchange="validateIndexSerie();">
					</div>
				</div>
				<div class="col s12">
					<div class="switch">
						Generar pólizas por proveedor
					   <label>
					     <input type="checkbox" name="cfdi_by_provider_toggle" id="cfdi_by_provider_toggle" onchange="setProviderToggle();">
					     <span class="lever"></span>
					   </label>
					 </div>
				</div>
			</div>
			<div class="modal-footer">
				<button id="saveChanges" class="btn modal-close"><b>Listo</b></button>
			</div>
		</div>
	</div>
</div>
@include('providers.newProviderModal')
@endsection