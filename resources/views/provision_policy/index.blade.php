@extends('layouts.app')
@section('content')
<div id="menu_navbar" class="row white valign-wrapper" style="height: 48px;display: none;">
	<div class="col s4" style="margin-left: 4px;">
		<a id="back_prev" class="selectable">
			<i class="material-icons black-text">arrow_back</i>
		</a>
	</div>
	<div class="col s4">
		<a>
			<i class="material-icons black-text">search</i>
		</a>
	</div>
	<div class="col s4 right-align" style="margin-right: 4px;">
		<a id="toggle_select_all_rows" class="selectable">
			<i class="material-icons black-text">select_all</i>
		</a>
		&nbsp;&nbsp;
		<a id="add_standard_provision_files" class="selectable">
			<i class="material-icons black-text">note_add</i>
		</a>
		&nbsp;&nbsp;
		<a id="delete_rows" class="selectable">
			<i class="material-icons black-text">delete</i>
		</a>		
		&nbsp;&nbsp;
		<a id="cfdi_config" class="selectable">
			<i class="material-icons black-text">settings</i>
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
<div class="container section2" style="width: 95%;display: none;">
	<div class="row">
		<table class="card highlight col s12" style="table-layout: fixed;">
		    <thead>
		        <tr>
		            <th style="width: 5%;"></th>
		            <th style="width: 7%;" class="center-align">Fecha</th>
		            <th style="width: 10%;" class="center-align">Serie</th>
		            <th style="width: 25%;">Proveedor</th>
		            <th style="width: 30%;">Descripcion</th>
		            <th style="width: 10%;" class="center-align">Total</th>
		            <th style="width: 10%;" class="center-align">Opciones</th>
		        </tr>
		    </thead>
		    <tbody>
		        {{-- <tr data-file-index="" data-rfc-provider="">
		            <td class="center-align valign-wrapper">
		                <input type="checkbox" class="filled-in row-select" id="row-select-1" checked="checked" />
		                <label for="row-select-1"></label>
		            </td>
		            <td style="width: 7%;" class="center-align">Ene. 01</td>
		            <td style="width: 10%;" class="center-align">01</td>
		            <td style="width: 25%;" class="hover">
		            	<span class="truncate" >Nombre del Proveedor SA de CV</span>
		                <div class="card-panel" style="position: absolute;display: none;">
		                    <span>Nombre competo del proveedor</span><br>
		                    <span>RFC123456789</span><br>
		                    <span>FOLIO: 1234</span><br>
		                    <span>Serie: 00</span><br>
		                </div>
		            </td>
		            <td style="width: 30%;">
		            	<span class="truncate">Primer descripcion de cfdi</span>
		            </td>
		            <td style="width: 10%;" class="center-align">$1234.56</td>
		            <td style="width: 10%;" class="center-align">
						<a href="#newProviderModal" class="modal-trigger newProviderFromProvision">
							<i class="material-icons black-text">person_add</i>
						</a>
						&nbsp;
						<a href="#modalShowConcepts" class="modal-trigger">
							<i class="material-icons black-text">list</i>
						</a>
		            </td>
				</tr> --}}
		    </tbody>
		</table>

		{{-- <ul class="col s12 collection collection-cfdi" style="overflow: visible;">
		</ul> --}}
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
		<div id="modalShowConcepts" class="modal modal-fixed-footer">
			<div class="modal-content">
				<ul id="conceptList" class="collection">
					<li class="collection-item">
						<div class="row no-margin">
							<div class="col s6">
								<span ><b class="truncate">Descripción del concepto verdaderamente</b> $200.00</span>
							</div>
							<div class="col s6">
								<select class="browser-default secondary-content">
								    <option value="" disabled selected>Choose your option</option>
								    <option value="1">Option 1</option>
								    <option value="2">Option 2</option>
								    <option value="3">Option 3</option>
								</select>
							</div>
						</div>
					</li>
				</ul>
			</div>
			<div class="modal-footer">
				<button id="saveChanges" class="btn modal-close"><b>Listo</b></button>
			</div>
		</div>
	</div>
</div>
@include('providers.newProviderModal')
@endsection