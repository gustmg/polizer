<div id="updateClientModal" class="modal updateClientModal modal-fixed-footer">
	<div class="modal-content">
		<div class="row">
			<div class="col s12">
				<h5 class="truncate">Editar cliente</h5>
			</div>
			<form id="updateClientForm" class="col s12 no-padding" method="POST">
				{{ csrf_field() }}
				@method('PUT')
				<div class="row" style="margin-bottom: 10px;">
					<div class="col s12 grey-text text-darken-2"><b>Información general</b></div>
					<div class="input-field col s12 m6">
				      <input id="update_client_name" name="client_name" type="text" class="validate client_name black-text" onblur="validateUpdateForm();" required>
				      <label for="update_client_name" data-error="Verifique este campo" data-success="Campo validado">Nombre del cliente *</label>
				    </div>
			        <div class="input-field col s12 m6">
			          <input id="update_client_rfc" name="client_rfc" type="text" class="client_rfc validate" onblur="validateUpdateForm();" required>
			          <label for="update_client_rfc" data-error="Verifique este campo" data-success="Campo validado">RFC del cliente *</label>
			        </div>
			        <div class="input-field col s12 m12">
			          <input id="update_client_accounting_account" name="client_accounting_account" type="text" class="client_accounting_account validate" onblur="validateUpdateForm();" pattern="^[^A-Za-z]+$" required>
			          <label for="update_client_accounting_account" data-error="Verifique este campo" data-success="Campo validado">Cuenta contable del cliente *</label>
			        </div>
		        </div>
		        <div class="row">
					<div class="col s12 grey-text text-darken-2"><b>Cuenta de contrapartida</b></div>
					<div class="col s8">
						<select class="selectUpdate browser-default">
							<option value="" disabled selected>Elige una cuenta contable</option>
							<optgroup label="Ventas / Ingresos">
								@foreach($accounting_accounts as $key => $value2)
									@if($value2->accounting_account_type_id==3)
										<option value="{{$value2->accounting_account_id}}">{{$value2->accounting_account_description}}</option>
									@endif
								@endforeach
							</optgroup>
						</select>
						<input type="hidden" class="hidden_counterpart_account" name="counterpart_accounting_account_id">
					</div>
		        </div>
			</form>
		</div>
	</div>
	<div class="modal-footer">
		<a id="deleteClientModalButton" href="#deleteClientModal" class="modal-action modal-close modal-trigger left" style="margin-top: 10px;margin-left: 10px;"><i class="material-icons black-text">delete</i></a>
		<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
		<button id="update_client_button" onclick="submitUpdateClient();" class="modal-action btn waves-effect submit_button update_client_button">
			<b>Editar</b>
		</button>
	</div>
</div>