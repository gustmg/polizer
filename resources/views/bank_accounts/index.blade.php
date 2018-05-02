@extends('layouts.app')

@section('content')
<div class="container">
<div class="row" style="margin-bottom: 0;">
	<div class="card hoverable col s12 m12" style="float: none;">
			<div class="input-field valign-wrapper" >
			<i class="material-icons prefix">search</i>
			<input placeholder="Buscar" id="search_bank_account" type="text" style="border-bottom: none!important;box-shadow: none!important;margin-bottom: 0;">
		</div>
	</div>
</div>
</div>
<div class="row">
    @if(count($bank_accounts)===0)
    	<h5 class="center"><b>No hay cuentas bancarias registradas. :^(</b></h5>
	@else
		<div class="col s12">
			<table class="card highlight" style="table-layout:fixed;">
				<thead class="grey darken-4 white-text">
					<tr>
						<th style="width: 40%;" class="center">Cuenta bancaria</th>
						<th style="width: 40%;" class="center">Cuenta Contable</th>
						<th style="width: 20%;" class="center">Banco</th>
					</tr>
				</thead>
				<tbody>
					@foreach($bank_accounts as $key => $value)
					<tr onclick="updateBankAccountModal({{$value->bank_account_id}},{{$value->bank->bank_id}},{{$value->counterpart_accounting_account_id}});" style="cursor: pointer;" class="modal-trigger" href="#updateBankAccountModal{{$value->bank_account_id}}">
						<td class="center">{{$value->bank_account_number}}</td>
						@if($value->counterpart_accounting_account_id === null)
							<td class="center">N / A</td>
						@else
							<td class="center tooltipped" data-position="bottom" data-delay="600" data-tooltip="{{$value->counterpart_account->accounting_account_number}}">{{$value->counterpart_account->accounting_account_description}}</td>
						@endif
						@if($value->bank_id === null)
							<td class="center">N / A</td>
						@else
							<td class="center">
								{{$value->bank->bank_name}}
							</td>
						@endif
					</tr>
					<div id="updateBankAccountModal{{$value->bank_account_id}}" class="modal updateBankAccountModal modal-fixed-footer">
						<div class="modal-content">
							<div class="row">
								<div class="col s12">
									<h5 class="truncate">Editar cuenta bancaria</h5>
								</div>
								<form id="updateBankAccountForm{{$value->bank_account_id}}" class="col s12 no-padding" method="POST" action="bank_accounts/{{$value->bank_account_id}}">
									{{ csrf_field() }}
									@method('PUT')
									<div class="row" style="margin-bottom: 10px;">
										<div class="col s12 grey-text text-darken-2"><b>Información general</b></div>
										<div class="input-field col s12 m6">
									      <input id="bank_account_number" name="bank_account_number" type="text" value="{{$value->bank_account_number}}" class="validate bank_account_number black-text" onblur="validateForm();" required>
									      <label for="bank_account_number" data-error="Verifique este campo" data-success="Campo validado">Número de la cuenta bancaria *</label>
									    </div>
							        </div>
							        <div class="row">
			        					<div class="col s12 grey-text text-darken-2"><b>Banco</b></div>
			        					<div class="input-field col s8">
			        						<select class="selectUpdateBank">
			        							<option value="0" disabled selected>Elige un banco</option>
			        							@foreach($banks as $key => $value2)
			        								<option value="{{$value2->bank_id}}">{{$value2->bank_name}}</option>
			        							@endforeach
			        						</select>
			        						<input type="hidden" class="hidden_bank" name="bank_id" value="{{$value->bank_id}}">
			        					</div>
			        					<div class="col s12 grey-text text-darken-2"><b>Cuenta de contrapartida</b></div>
			        					<div class="input-field col s8">
			        						<select class="selectUpdateAccountingAccount"
			        							@if(count($accounting_accounts)===0) 
			        								disabled>
													<option>No hay cuentas contables registardas</option>
												@else
													>
												@endif
			        							<option value="0" disabled selected>Elige una cuenta contable</option>
			        							<optgroup label="Bancos">
			        								@foreach($accounting_accounts as $key => $value3)
			        									<option value="{{$value3->accounting_account_id}}">{{$value3->accounting_account_description}}</option>
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
							<a href="#deleteBankAccountModal{{$value->bank_account_id}}" class="modal-action modal-close modal-trigger left" style="margin-top: 10px;margin-left: 10px;"><i class="material-icons black-text">delete</i></a>
							<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
							<button id="submit_button" onclick="submitUpdateBankAccount({{$value->bank_account_id}});" class="modal-action btn waves-effect submit_button">
								<b>Editar</b>
							</button>
						</div>
					</div>
					<form id="deleteBankAccountForm{{$value->bank_account_id}}" method="POST" action="{{ route('bank_accounts.destroy', $value->bank_account_id) }}">
							{{ csrf_field() }}
							@method('DELETE')
						</form>
						<div id="deleteBankAccountModal{{$value->bank_account_id}}" class="modal deleteBankAccountModal">
							<div class="modal-content">
								<h5>Eliminar cuenta bancaria?</h5>
							</div>
							<div class="modal-footer">
								<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
								<button id="delete_button" onclick="submitDeleteBankAccount({{$value->bank_account_id}});" class="modal-action btn-flat waves-effect"><b>Eliminar</b></button>
							</div>
						</div>
					@endforeach
				</tbody>
			</table>
		</div>
    @endif
</div>
<a style="position:fixed;bottom: 24px;right: 24px;" class="btn-floating btn-large waves-effect waves-light modal-trigger teal accent-4" href="#newBankAccountModal" onclick="createSelects();">
	<i class="material-icons">add</i>
</a>
<div id="newBankAccountModal" class="modal newBankAccountModal modal-fixed-footer">
	<div class="modal-content">
		<div class="row">
			<div class="col s12">
				<h5>Nueva cuenta bancaria</h5>
			</div>
			<form id="newBankAccountForm" class="col s12 no-padding" method="POST" action="bank_accounts">
				{{ csrf_field() }}
				<div class="row" style="margin-bottom: 10px;">
					<div class="col s12 grey-text text-darken-2"><b>Información general</b></div>
					<div class="input-field col s12 m6">
			          <input id="bank_account_number" name="bank_account_number" type="text" class="validate bank_account_number" onblur="validateForm();" required>
			          <label for="bank_account_number" data-error="Verifique este campo" data-success="Campo validado">Número de cuenta bancaria *</label>
			        </div>
		        </div>
		        <div class="row">
					<div class="col s12 grey-text text-darken-2"><b>Banco</b></div>
					<div class="input-field col s8">
						<select name="bank_id" class="selectNewBank">
							@foreach($banks as $key => $value2)
								<option value="{{$value2->bank_id}}">{{$value2->bank_name}}</option>
							@endforeach
						</select>
					</div>
					<div class="col s12 grey-text text-darken-2"><b>Cuenta de contrapartida</b></div>
					<div class="input-field col s8">
						<select name="counterpart_accounting_account_id" class="selectNewAccountingAccount" 
							@if(count($accounting_accounts)===0) disabled>
								<option>No hay cuentas contables registardas</option>
							@else
								>
							@endif
								<optgroup label="Bancos">
										@foreach($accounting_accounts as $key => $value3)
											<option value="{{$value3->accounting_account_id}}">{{$value3->accounting_account_description}}</option>
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
		<button id="submit_button" onclick="submitNewBankAccount();" class="modal-action btn waves-effect submit_button" disabled><b>Registrar</b></button>
	</div>
</div>
@endsection