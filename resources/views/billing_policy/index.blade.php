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
		<a id="add_more_files" class="selectable">
			<i class="material-icons black-text">note_add</i>
		</a>
		&nbsp;&nbsp;
		<a id="delete_rows" href="#modalRemoveRows" class="selectable modal-trigger">
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
							<h6>Clientes</h6>
							<h6>IVA Trasladado</h6>
						</div>
						<div class="col s12 m12 no-padding">
							<b>Abonos</b>
						</div>
						<div class="col s11 offset-s1 no-padding">
							<h6>Ventas / Ingresos</h6><br>
						</div>
					</div>
				</div>
				<div class="card-action right-align">
					<label id="policy-type-1" class="btn">
						<b>Cargar CFDI's</b>
						<input type="file" style="display: none;" name="standard_billing_files" id="standard_billing_files" accept=".xml" onclick="setPolicyType(1);" multiple>
					</label>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container section2" style="width: 95%;display: none;">
	<div class="row">
		<table class="card highlight col s12 billing-tablesorter" style="table-layout: fixed;">
		    <thead>
		        <tr>
		            <th style="width: 5%;"></th>
		            <th style="width: 7%;" class="center-align selectable">Fecha <i class="tiny material-icons no-margin">unfold_more</i></th>
		            <th style="width: 10%;" class="center-align selectable">Folio <i class="tiny material-icons no-margin">unfold_more</i></th>
		            <th style="width: 25%;" class="selectable">Cliente <i class="tiny material-icons no-margin">unfold_more</i></th>
		            <th style="width: 30%;">Descripcion</th>
		            <th style="width: 10%;" class="center-align">Total</th>
		            <th style="width: 10%;" class="center-align">Opciones</th>
		        </tr>
		    </thead>
		    <tbody></tbody>
		</table>
		<div id="modalRemoveRows" class="modal">
			<div class="modal-content">
				<h5>Eliminar CFDI's' seleccionados?</h5>
			</div>
			<div class="modal-footer">
				<button class="btn-flat modal-close"><b>Cancelar</b></button>
				<button class="btn" onclick="removeRows();"><b>Aceptar</b></button>
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
						Generar pólizas por cliente
					   <label>
					     <input type="checkbox" name="cfdi_generate_by_toggle" id="cfdi_generate_by_toggle" onchange="setGenerateByToggle();">
					     <span class="lever"></span>
					   </label>
					 </div>
				</div>
			</div>
			<div class="modal-footer">
				<button id="saveChanges" class="btn modal-close"><b>Listo</b></button>
			</div>
		</div>
		<div class="accounts" style="display: none;">
			<select class="accounting-account-list browser-default secondary-content">
			    <option value="" disabled selected>Elige una cuenta contable</option>
			    <optgroup label="Ventas / Ingresos">
			    	@foreach($accounting_accounts as $key => $accounting_account)
		    			<option value="{{$accounting_account->accounting_account_id}}" data-accounting-account-number="{{$accounting_account->accounting_account_number}}">{{$accounting_account->accounting_account_description}}</option>
			    	@endforeach
			    </optgroup>
			</select>
		</div>
	</div>
</div>
@include('clients.newClientModal')
@endsection