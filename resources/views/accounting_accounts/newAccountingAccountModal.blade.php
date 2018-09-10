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
			          <input id="accounting_account_number" name="accounting_account_number" type="text" class="validate accounting_account_number" onblur="validateForm();" pattern="^[^A-Za-z]+$" required>
			          <label for="accounting_account_number" data-error="Verifique este campo" data-success="Campo validado">Número de cuenta contable *</label>
			        </div>
			        <div class="input-field col s12 m12">
			          <input id="accounting_account_description" name="accounting_account_description" type="text" class="accounting_account_description validate" onblur="validateForm();" required>
			          <label for="accounting_account_description">Descripción de la cuenta contable *</label>
			        </div>
		        </div>
		        <div class="row">
					<div class="col s12 grey-text text-darken-2"><b>Tipo de cuenta contable</b></div>
					<div class="col s8">
						<select id="selectNewAccountingAccount" name="accounting_account_type_id" class="selectNew browser-default">
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