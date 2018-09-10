<div id="updateBankAccountModal" class="modal updateBankAccountModal modal-fixed-footer">
	<div class="modal-content">
		<div class="row">
			<div class="col s12">
				<h5 class="truncate">Editar cuenta bancaria</h5>
			</div>
			<form id="updateBankAccountForm" class="col s12 no-padding" method="POST">
				{{ csrf_field() }}
				@method('PUT')
				<div class="row" style="margin-bottom: 10px;">
					<div class="col s12 grey-text text-darken-2"><b>Información general</b></div>
					<div class="input-field col s12 m6">
				      <input id="update_bank_account_number" name="bank_account_number" type="text" class="validate bank_account_number black-text" onblur="validateUpdateForm();" pattern="[0-9]+" data-length="18" maxlength="18" required>
				      <label for="bank_account_number" data-error="Verifique este campo" data-success="Campo validado">Número de la cuenta bancaria *</label>
				    </div>
		        </div>
		        <div class="row">
					<div class="col s12 grey-text text-darken-2"><b>Banco</b></div>
					<div class="col s8">
						<select class="selectUpdateBank browser-default">
							<option value="0" disabled selected>Elige un banco</option>
							@foreach($banks as $key => $value2)
								<option value="{{$value2->bank_id}}">{{$value2->bank_name}}</option>
							@endforeach
						</select>
						<input type="hidden" class="hidden_bank" name="bank_id">
					</div>
					<div class="col s12 grey-text text-darken-2"><b>Cuenta de contrapartida</b></div>
					<div class="col s8">
						<select class="selectUpdateAccountingAccount browser-default"
							@if(count($accounting_accounts)==0) 
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
						<input type="hidden" class="hidden_counterpart_account" name="counterpart_accounting_account_id">
					</div>
		        </div>
			</form>
		</div>
	</div>
	<div class="modal-footer">
		<a id="deleteBankAccountModalButton" href="#deleteBankAccountModal" class="modal-action modal-close modal-trigger left" style="margin-top: 10px;margin-left: 10px;"><i class="material-icons black-text">delete</i></a>
		<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
		<button id="update_bank_account_button" onclick="submitUpdateBankAccount();" class="modal-action btn waves-effect submit_button">
			<b>Editar</b>
		</button>
	</div>
</div>