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
			          <input id="client_name" name="client_name" type="text" class="validate client_name" onblur="validateNewClientForm();" required>
			          <label for="client_name" data-error="Verifique este campo" data-success="Campo validado">Nombre del cliente *</label>
			        </div>
			        <div class="input-field col s12 m6">
			          <input id="client_rfc" name="client_rfc" type="text" class="validate client_rfc" onblur="validateNewClientForm();" required>
			          <label for="client_rfc" data-error="Verifique este campo" data-success="Campo validado">RFC del cliente *</label>
			        </div>
			        <div class="input-field col s12 m12">
			          <input id="client_accounting_account" name="client_accounting_account" type="text" class="client_accounting_account validate" onblur="validateNewClientForm();" pattern="^[^A-Za-z]+$" required>
			          <label for="client_accounting_account">Cuenta contable del cliente *</label>
			        </div>
		        </div>
		        <div class="row">
					<div class="col s12 grey-text text-darken-2"><b>Cuenta de contrapartida</b></div>
					<div class="col s8">
						<select id="counterpart_accounting_account_id" name="counterpart_accounting_account_id" class="accounting-account-list browser-default selectNew" onchange="validateNewClientForm();">
							<option value="" disabled selected>Elige una cuenta contable</option>
							<optgroup label="Ventas / Ingresos">
								@foreach($accounting_accounts as $key => $value)
									@if($value->accounting_account_type_id==3)
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
		@if (Route::currentRouteName()=='clients.index')
			<button id="submit_button" onclick="submitNewClient();" class="modal-action btn waves-effect submit_button" disabled><b>Registrar</b></button>
		@else
			<button id="submit_button" onclick="ajaxNewClient();" class="modal-action btn waves-effect submit_button" disabled><b>Registrar</b></button>
		@endif
	</div>
</div>