@extends('layouts.app')

@section('content')
<div class="container">
<div class="row" style="margin-bottom: 0;">
	<div class="card hoverable col s12 m12" style="float: none;">
			<div class="input-field valign-wrapper" >
			<i class="material-icons prefix">search</i>
			<input placeholder="Buscar" id="search_client" type="text" style="border-bottom: none!important;box-shadow: none!important;margin-bottom: 0;">
		</div>
	</div>
</div>
</div>
<div class="row">
    @if(count($clients)===0)
    	<h5 class="center"><b>No hay clientes registrados. :^(</b></h5>
	@else
		<div class="col s12">
			<table class="card highlight" style="table-layout:fixed;">
				<thead class="grey darken-4 white-text">
					<tr>
						<th style="width: 20%;" class="center">Cuenta Contable</th>
						<th style="width: 40%;" class="center">Proveedor</th>
						<th style="width: 20%;" class="center">RFC</th>
						<th style="width: 20%;" class="center">Contrapartida</th>
					</tr>
				</thead>
				<tbody>
					@foreach($clients as $key => $value)
					<tr onclick="updateClientModal({{$value->client_id}},{{$value->counterpart_accounting_account_id}});" style="cursor: pointer;" class="modal-trigger" href="#updateClientModal{{$value->client_id}}">
						<td class="center">{{$value->client_accounting_account}}</td>
						<td class="truncate">{{$value->client_name}}</td>
						<td class="center">{{$value->client_rfc}}</td>
						@if($value->counterpart_accounting_account_id === null)
							<td class="center">N / A</td>
						@else
							<td class="center tooltipped" data-position="bottom" data-delay="600" data-tooltip="{{$value->counterpart_account->accounting_account_description}}">{{$value->counterpart_account->accounting_account_number}}</td>
						@endif
					</tr>
					<div id="updateClientModal{{$value->client_id}}" class="modal updateClientModal modal-fixed-footer">
						<div class="modal-content">
							<div class="row">
								<div class="col s12">
									<h5 class="truncate">{{$value->client_name}}</h5>
								</div>
								<form id="updateClientForm{{$value->client_id}}" class="col s12 no-padding" method="POST" action="clients/{{$value->client_id}}">
									{{ csrf_field() }}
									@method('PUT')
									<div class="row" style="margin-bottom: 10px;">
										<div class="col s12 grey-text text-darken-2"><b>Información general</b></div>
										<div class="input-field col s12 m6">
									      <input id="client_name" name="client_name" type="text" value="{{$value->client_name}}" class="validate client_name black-text" onblur="validateForm();" required>
									      <label for="client_name" data-error="Verifique este campo" data-success="Campo validado">Nombre del cliente *</label>
									    </div>
								        <div class="input-field col s12 m6">
								          <input id="client_rfc" name="client_rfc" type="text" value="{{$value->client_rfc}}" class="client_rfc validate" onblur="validateForm();" required>
								          <label for="client_rfc">RFC del cliente *</label>
								        </div>
								        <div class="input-field col s12 m12">
								          <input id="client_accounting_account" name="client_accounting_account" type="text" value="{{$value->client_accounting_account}}" class="client_accounting_account validate" onblur="validateForm();" required>
								          <label for="client_accounting_account">Cuenta contable del cliente *</label>
								        </div>
							        </div>
							        <div class="row">
			        					<div class="col s12 grey-text text-darken-2"><b>Cuenta de contrapartida</b></div>
			        					<div class="input-field col s8">
			        						<select class="selectUpdate">
			        							<option value="0" disabled selected>Elige una cuenta contable</option>
			        							<optgroup label="Ventas / Ingresos">
			        								@foreach($accounting_accounts as $key => $value2)
			        									@if($value2->accounting_account_type_id===3)
			        										<option value="{{$value2->accounting_account_id}}">{{$value2->accounting_account_description}}</option>
			        									@endif
			        								@endforeach
			        							</optgroup>
			        						</select>
			        						<input type="hidden" class="hidden_counterpart_account" name="counterpart_accounting_account_id" value="{{$value->counterpart_accounting_account_id}}">
			        					</div>
			        		        </div>
								</form>
							</div>
						</div>
						<div class="modal-footer">
							<a href="#deleteClientModal{{$value->client_id}}" class="modal-action modal-close modal-trigger left" style="margin-top: 10px;margin-left: 10px;"><i class="material-icons black-text">delete</i></a>
							<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
							<button id="submit_button" onclick="submitUpdateClient({{$value->client_id}});" class="modal-action btn waves-effect submit_button">
								<b>Editar</b>
							</button>
						</div>
					</div>
					<form id="deleteClientForm{{$value->client_id}}" method="POST" action="{{ route('clients.destroy', $value->client_id) }}">
							{{ csrf_field() }}
							@method('DELETE')
						</form>
						<div id="deleteClientModal{{$value->client_id}}" class="modal deleteClientModal">
							<div class="modal-content">
								<h5>Eliminar cliente?</h5>
							</div>
							<div class="modal-footer">
								<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
								<button id="delete_button" onclick="submitDeleteClient({{$value->client_id}});" class="modal-action btn-flat waves-effect"><b>Eliminar</b></button>
							</div>
						</div>
					@endforeach
				</tbody>
			</table>
		</div>
    @endif
</div>
<a style="position:fixed;bottom: 24px;right: 24px;" class="btn-floating btn-large waves-effect waves-light modal-trigger teal accent-4" href="#newClientModal" onclick="createSelectCounterpart();">
	<i class="material-icons">add</i>
</a>
<div id="newClientModal" class="modal newClientModal modal-fixed-footer">
	<div class="modal-content">
		<div class="row">
			<div class="col s12">
				<h5>Nuevo cliente</h5>
			</div>
			<form id="newClientForm" class="col s12 no-padding" method="POST" action="clients">
				{{ csrf_field() }}
				<div class="row" style="margin-bottom: 10px;">
					<div class="col s12 grey-text text-darken-2"><b>Información general</b></div>
					<div class="input-field col s12 m6">
			          <input id="client_name" name="client_name" type="text" class="validate client_name" onblur="validateForm();" required>
			          <label for="client_name" data-error="Verifique este campo" data-success="Campo validado">Nombre del cliente *</label>
			        </div>
			        <div class="input-field col s12 m6">
			          <input id="client_rfc" name="client_rfc" type="text" class="validate client_rfc" onblur="validateForm();" required>
			          <label for="client_rfc" data-error="Verifique este campo" data-success="Campo validado">RFC del cliente *</label>
			        </div>
			        <div class="input-field col s12 m12">
			          <input id="client_accounting_account" name="client_accounting_account" type="text" class="client_accounting_account validate" onblur="validateForm();" required>
			          <label for="client_accounting_account">Cuenta contable del cliente *</label>
			        </div>
		        </div>
		        <div class="row">
					<div class="col s12 grey-text text-darken-2"><b>Cuenta de contrapartida</b></div>
					<div class="input-field col s8">
						<select name="counterpart_accounting_account_id" class="selectNew">
							<option value="0" disabled selected>Elige una cuenta contable</option>
							<optgroup label="Ventas / Ingresos">
								@foreach($accounting_accounts as $key => $value)
									@if($value->accounting_account_type_id===3)
										<option value="{{$value->accounting_account_id}}">{{$value->accounting_account_description}}</option>
									@endif
								@endforeach
							</optgroup>
						</select>
					</div>
		        </div>
			</form>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
		<button id="submit_button" onclick="submitNewClient();" class="modal-action btn waves-effect submit_button" disabled><b>Registrar</b></button>
	</div>
</div>
@endsection