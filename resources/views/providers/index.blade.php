@extends('layouts.app')

@section('content')
<div class="container">
<div class="row" style="margin-bottom: 0;">
	<div class="card hoverable col s12 m12" style="float: none;">
			<div class="input-field valign-wrapper" >
			<i class="material-icons prefix">search</i>
			<input placeholder="Buscar" id="search_provider" type="text" style="border-bottom: none!important;box-shadow: none!important;margin-bottom: 0;">
		</div>
	</div>
</div>
</div>
<div class="row">
    @if(count($providers)===0)
    	<h5 class="center"><b>No hay proveedores registrados. :^(</b></h5>
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
					@foreach($providers as $key => $value)
					<tr onclick="updateProviderModal({{$value->provider_id}},{{$value->counterpart_accounting_account_id}});" style="cursor: pointer;" class="modal-trigger" href="#updateProviderModal{{$value->provider_id}}">
						<td class="center">{{$value->provider_accounting_account}}</td>
						<td class="truncate">{{$value->provider_name}}</td>
						<td class="center">{{$value->provider_rfc}}</td>
						@if($value->counterpart_accounting_account_id === null)
							<td class="center">N / A</td>
						@else
							<td class="center tooltipped" data-position="bottom" data-delay="600" data-tooltip="{{$value->counterpart_account->accounting_account_description}}">{{$value->counterpart_account->accounting_account_number}}</td>
						@endif
					</tr>
					<div id="updateProviderModal{{$value->provider_id}}" class="modal updateProviderModal modal-fixed-footer">
						<div class="modal-content">
							<div class="row">
								<div class="col s12">
									<h5 class="truncate">{{$value->provider_name}}</h5>
								</div>
								<form id="updateProviderForm{{$value->provider_id}}" class="col s12 no-padding" method="POST" action="providers/{{$value->provider_id}}">
									{{ csrf_field() }}
									@method('PUT')
									<div class="row" style="margin-bottom: 10px;">
										<div class="col s12 grey-text text-darken-2"><b>Información general</b></div>
										<div class="input-field col s12 m6">
									      <input id="provider_name" name="provider_name" type="text" value="{{$value->provider_name}}" class="validate provider_name black-text" onblur="validateForm();" required>
									      <label for="provider_name" data-error="Verifique este campo" data-success="Campo validado">Nombre del proveedor *</label>
									    </div>
								        <div class="input-field col s12 m12">
								          <input id="provider_rfc" name="provider_rfc" type="text" value="{{$value->provider_rfc}}" class="provider_rfc validate" onblur="validateForm();" required>
								          <label for="provider_rfc">RFC del proveedor *</label>
								        </div>
								        <div class="input-field col s12 m12">
								          <input id="provider_accounting_account" name="provider_accounting_account" type="text" value="{{$value->provider_accounting_account}}" class="provider_accounting_account validate" onblur="validateForm();" required>
								          <label for="provider_accounting_account">Cuenta contable del proveedor *</label>
								        </div>
							        </div>
							        <div class="row">
			        					<div class="col s12 grey-text text-darken-2"><b>Cuenta de contrapartida</b></div>
			        					<div class="input-field col s8">
			        						<select class="selectUpdate">
			        							<option value="0" disabled selected>Elige una cuenta contable</option>
			        							<optgroup label="Inventarios">
			        								@foreach($accounting_accounts as $key => $value2)
			        									@if($value2->accounting_account_type_id===2)
			        										<option value="{{$value2->accounting_account_id}}">{{$value2->accounting_account_description}}</option>
			        									@endif
			        								@endforeach
			        							</optgroup>
			        							<optgroup label="Gastos de Venta">
			        								@foreach($accounting_accounts as $key => $value3)
			        									@if($value3->accounting_account_type_id===5)
			        										<option value="{{$value3->accounting_account_id}}">{{$value3->accounting_account_description}}</option>
			        									@endif
			        								@endforeach
			        							</optgroup>
			        							<optgroup label="Gastos de Administración">
			        								@foreach($accounting_accounts as $key => $value4)
			        									@if($value4->accounting_account_type_id===6)
			        										<option value="{{$value4->accounting_account_id}}">{{$value4->accounting_account_description}}</option>
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
							<a href="#deleteProviderModal{{$value->provider_id}}" class="modal-action modal-close modal-trigger left" style="margin-top: 10px;margin-left: 10px;"><i class="material-icons black-text">delete</i></a>
							<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
							<button id="submit_button" onclick="submitUpdateProvider({{$value->provider_id}});" class="modal-action btn waves-effect submit_button">
								<b>Editar</b>
							</button>
						</div>
					</div>
					<form id="deleteProviderForm{{$value->provider_id}}" method="POST" action="{{ route('providers.destroy', $value->provider_id) }}">
							{{ csrf_field() }}
							@method('DELETE')
						</form>
						<div id="deleteProviderModal{{$value->provider_id}}" class="modal deleteProviderModal">
							<div class="modal-content">
								<h5>Eliminar proveedor?</h5>
							</div>
							<div class="modal-footer">
								<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
								<button id="delete_button" onclick="submitDeleteProvider({{$value->provider_id}});" class="modal-action btn-flat waves-effect"><b>Eliminar</b></button>
							</div>
						</div>
					@endforeach
				</tbody>
			</table>
		</div>
    @endif
</div>
<a style="position:fixed;bottom: 24px;right: 24px;" class="btn-floating btn-large waves-effect waves-light modal-trigger teal accent-4" href="#newProviderModal" onclick="createSelectCounterpart();">
	<i class="material-icons">add</i>
</a>
<div id="newProviderModal" class="modal newProviderModal modal-fixed-footer">
	<div class="modal-content">
		<div class="row">
			<div class="col s12">
				<h5>Nueva cuenta contable</h5>
			</div>
			<form id="newProviderForm" class="col s12 no-padding" method="POST" action="providers">
				{{ csrf_field() }}
				<div class="row" style="margin-bottom: 10px;">
					<div class="col s12 grey-text text-darken-2"><b>Información general</b></div>
					<div class="input-field col s12 m6">
			          <input id="provider_name" name="provider_name" type="text" class="validate provider_name" onblur="validateForm();" required>
			          <label for="provider_name" data-error="Verifique este campo" data-success="Campo validado">Nombre del proveedor *</label>
			        </div>
			        <div class="input-field col s12 m6">
			          <input id="provider_rfc" name="provider_rfc" type="text" class="validate provider_rfc" onblur="validateForm();" required>
			          <label for="provider_rfc" data-error="Verifique este campo" data-success="Campo validado">RFC del proveedor *</label>
			        </div>
			        <div class="input-field col s12 m12">
			          <input id="provider_accounting_account" name="provider_accounting_account" type="text" class="provider_accounting_account validate" onblur="validateForm();" required>
			          <label for="provider_accounting_account">Cuenta contable del proveedor *</label>
			        </div>
		        </div>
		        <div class="row">
					<div class="col s12 grey-text text-darken-2"><b>Cuenta de contrapartida</b></div>
					<div class="input-field col s8">
						<select name="counterpart_accounting_account_id" class="selectNew">
							<option value="0" disabled selected>Elige una cuenta contable</option>
							<optgroup label="Inventarios">
								@foreach($accounting_accounts as $key => $value)
									@if($value->accounting_account_type_id===2)
										<option value="{{$value->accounting_account_id}}">{{$value->accounting_account_description}}</option>
									@endif
								@endforeach
							</optgroup>
							<optgroup label="Gastos de Venta">
								@foreach($accounting_accounts as $key => $value)
									@if($value->accounting_account_type_id===5)
										<option value="{{$value->accounting_account_id}}">{{$value->accounting_account_description}}</option>
									@endif
								@endforeach
							</optgroup>
							<optgroup label="Gastos de Administración">
								@foreach($accounting_accounts as $key => $value)
									@if($value->accounting_account_type_id===6)
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
		<button id="submit_button" onclick="submitNewProvider();" class="modal-action btn waves-effect submit_button" disabled><b>Registrar</b></button>
	</div>
</div>
@endsection