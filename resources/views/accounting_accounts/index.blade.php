@extends('layouts.app')

@section('content')
<div class="container">
<div class="row" style="margin-bottom: 0;">
	<div class="card hoverable col s12 m12" style="float: none;">
			<div class="input-field valign-wrapper" >
			<i class="material-icons prefix">search</i>
			<input placeholder="Buscar" id="search_accounting_account" type="text" style="border-bottom: none!important;box-shadow: none!important;margin-bottom: 0;">
		</div>
	</div>
</div>
</div>
<div class="row">
    @if(count($accounting_accounts)==0)
    	<h5 class="center"><b>No hay cuentas contables registradas. :^(</b></h5>
	@else
		<div class="col s12">
			<table class="card highlight" style="table-layout:fixed;">
				<thead class="grey darken-4 white-text">
					<tr>
						<th style="width: 20%;" class="center">Cuenta Contable</th>
						<th style="width: 40%;" class="center">Descripción</th>
						<th style="width: 40%;" class="center">Tipo de Cuenta</th>
					</tr>
				</thead>
				<tbody>
					@foreach($accounting_accounts as $key => $value)
					<tr onclick="updateAccountingAccountModal({{$value->accounting_account_id}},{{$value->accounting_account_type_id}});" style="cursor: pointer;" class="modal-trigger" href="#updateAccountingAccountModal{{$value->accounting_account_id}}">
						<td class="center">{{$value->accounting_account_number}}</td>
						<td class="truncate tooltipped" data-position="bottom" data-delay="600" data-tooltip="{{$value->accounting_account_description}}">{{$value->accounting_account_description}}</td>
						<td class="center">{{$accounting_account_types[($value->accounting_account_type_id)-1]->accounting_account_type_description}}</td>
					</tr>
					<div id="updateAccountingAccountModal{{$value->accounting_account_id}}" class="modal updateAccountingAccountModal modal-fixed-footer">
						<div class="modal-content">
							<div class="row">
								<div class="col s12">
									<h5 class="truncate">{{$value->accounting_account_description}}</h5>
								</div>
								<form id="updateAccountingAccountForm{{$value->accounting_account_id}}" class="col s12 no-padding" method="POST" action="accounting_accounts/{{$value->accounting_account_id}}">
									{{ csrf_field() }}
									@method('PUT')
									<div class="row" style="margin-bottom: 10px;">
										<div class="col s12 grey-text text-darken-2"><b>Información general</b></div>
										<div class="input-field col s12 m6">
									      <input id="accounting_account_number" name="accounting_account_number" type="text" value="{{$value->accounting_account_number}}" class="validate accounting_account_number black-text" onblur="validateForm();" required>
									      <label for="accounting_account_number" data-error="Verifique este campo" data-success="Campo validado">Número de cuenta contable *</label>
									    </div>
								        <div class="input-field col s12 m12">
								          <input id="accounting_account_description" name="accounting_account_description" type="text" value="{{$value->accounting_account_description}}" class="accounting_account_description validate" onblur="validateForm();" required>
								          <label for="accounting_account_description">Descripción de la cuenta contable *</label>
								        </div>
							        </div>
							        <div class="row">
										<div class="col s12 grey-text text-darken-2"><b>Tipo de cuenta contable</b></div>
										<div class="input-field col s8">
											<select class="selectUpdate">
											@foreach($accounting_account_types as $key => $value2)
												<option value="{{$value2->accounting_account_type_id}}"
													>{{$value2->accounting_account_type_description}}
												</option>
											@endforeach
											</select>
											<input type="hidden" class="hidden_account_type" name="accounting_account_type_id" value="{{$value->accounting_account_type_id}}">
										</div>
							        </div>
								</form>
							</div>
						</div>
						<div class="modal-footer">
							<a href="#deleteAccountingAccountModal{{$value->accounting_account_id}}" class="modal-action modal-close modal-trigger left" style="margin-top: 10px;margin-left: 10px;"><i class="material-icons black-text">delete</i></a>
							<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
							<button id="update_accounting_account_button" onclick="submitUpdateAccountingAccount({{$value->accounting_account_id}});" class="modal-action btn waves-effect submit_button">
								<b>Editar</b>
							</button>
						</div>
					</div>
					<form id="deleteAccountingAccountForm{{$value->accounting_account_id}}" method="POST" action="{{ route('accounting_accounts.destroy', $value->accounting_account_id) }}">
						{{ csrf_field() }}
						@method('DELETE')
					</form>
					<div id="deleteAccountingAccountModal{{$value->accounting_account_id}}" class="modal deleteAccountingAccountModal">
						<div class="modal-content">
							<h5>Eliminar cuenta contable?</h5>
							<p>Todos los clientes y proveedores que tengan asignada esta cuenta por contrapartida se verán afectados.</p>
						</div>
						<div class="modal-footer">
							<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
							<button id="delete_accounting_account_button" onclick="submitDeleteAccountingAccount({{$value->accounting_account_id}});" class="modal-action btn-flat waves-effect"><b>Eliminar</b></button>
						</div>
					</div>
					@endforeach
				</tbody>
			</table>
		</div>
    @endif
</div>
<a style="position:fixed;bottom: 24px;right: 24px;" class="btn-floating btn-large waves-effect waves-light modal-trigger teal accent-4" href="#newAccountingAccountModal">
	<i class="material-icons">add</i>
</a>
<div id="newAccountingAccountModal" class="modal newAccountingAccountModal modal-fixed-footer">
	<div class="modal-content">
		<div class="row">
			<div class="col s12">
				<h5>Nueva cuenta contable</h5>
			</div>
			<form id="newAccountingAccountForm" class="col s12 no-padding" method="POST" action="accounting_accounts">
				{{ csrf_field() }}
				<div class="row" style="margin-bottom: 10px;">
					<div class="col s12 grey-text text-darken-2"><b>Información general</b></div>
					<div class="input-field col s12 m6">
			          <input id="accounting_account_number" name="accounting_account_number" type="text" class="validate accounting_account_number" onblur="validateForm();" required>
			          <label for="accounting_account_number" data-error="Verifique este campo" data-success="Campo validado">Número de cuenta contable *</label>
			        </div>
			        <div class="input-field col s12 m12">
			          <input id="accounting_account_description" name="accounting_account_description" type="text" class="accounting_account_description validate" onblur="validateForm();" required>
			          <label for="accounting_account_description">Descripción de la cuenta contable *</label>
			        </div>
		        </div>
		        <div class="row">
					<div class="col s12 grey-text text-darken-2"><b>Tipo de cuenta contable</b></div>
					<div class="input-field col s8">
						<select name="accounting_account_type_id" class="selectNew">
						@foreach($accounting_account_types as $key => $value)
							<option value="{{$value->accounting_account_type_id}}">{{$value->accounting_account_type_description}}
							</option>
						@endforeach
						</select>
					</div>
		        </div>
			</form>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
		<button id="new_accounting_account_button" onclick="submitNewAccountingAccount();" class="modal-action btn waves-effect submit_button" disabled><b>Registrar</b></button>
	</div>
</div>
@endsection