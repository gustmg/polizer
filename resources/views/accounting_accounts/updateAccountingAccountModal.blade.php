<div id="updateAccountingAccountModal" class="modal updateAccountingAccountModal modal-fixed-footer">
	<div class="modal-content">
		<div class="row">
			<div class="col s12">
				<h5 class="truncate">Editar cuenta contable</h5>
			</div>
			<form id="updateAccountingAccountForm" class="col s12 no-padding" method="POST">
				{{ csrf_field() }}
				@method('PUT')
				<div class="row" style="margin-bottom: 10px;">
					<div class="col s12 grey-text text-darken-2"><b>Información general</b></div>
					<div class="input-field col s12 m6">
				      <input id="update_accounting_account_number" name="accounting_account_number" type="text" class="validate accounting_account_number black-text" onblur="validateUpdateForm();" pattern="^[^A-Za-z]+$" required>
				      <label for="accounting_account_number" data-error="Verifique este campo" data-success="Campo validado">Número de cuenta contable *</label>
				    </div>
			        <div class="input-field col s12 m12">
			          <input id="update_accounting_account_description" name="accounting_account_description" type="text" class="accounting_account_description validate" onblur="validateUpdateForm();" required>
			          <label for="accounting_account_description">Descripción de la cuenta contable *</label>
			        </div>
		        </div>
		        <div class="row">
					<div class="col s12 grey-text text-darken-2"><b>Tipo de cuenta contable</b></div>
					<div class="col s8">
						<select class="browser-default selectUpdate">
						@foreach($accounting_account_types as $key => $value2)
							<option value="{{$value2->accounting_account_type_id}}"
								>{{$value2->accounting_account_type_description}}
							</option>
						@endforeach
						</select>
						<input type="hidden" class="hidden_account_type" name="accounting_account_type_id">
					</div>
		        </div>
			</form>
		</div>
	</div>
	<div class="modal-footer">
		<a id="deleteAccountingAccountModalButton" href="#deleteAccountingAccountModal" class="modal-action modal-close modal-trigger left" style="margin-top: 10px;margin-left: 10px;"><i class="material-icons black-text">delete</i></a>
		<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
		<button id="update_accounting_account_button" onclick="submitUpdateAccountingAccount();" class="modal-action btn waves-effect submit_button">
			<b>Editar</b>
		</button>
	</div>
</div>