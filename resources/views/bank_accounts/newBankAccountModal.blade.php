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
			          <input id="bank_account_number" name="bank_account_number" type="text" class="validate bank_account_number" pattern="[0-9]+" data-length="18" maxlength="18" onblur="validateForm();" required>
			          <label for="bank_account_number" data-error="Verifique este campo" data-success="Campo validado">Número de cuenta bancaria *</label>
			        </div>
		        </div>
		        <div class="row">
					<div class="col s12 grey-text text-darken-2"><b>Banco</b></div>
					<div class="col s8">
						<select id="selectNewBank" name="bank_id" class="selectNewBank browser-default">
							@foreach($banks as $key => $value2)
								<option value="{{$value2->bank_id}}">{{$value2->bank_name}}</option>
							@endforeach
						</select>
					</div>
					<div class="col s12 grey-text text-darken-2"><b>Cuenta de contrapartida</b></div>
					<div class="col s8">
						<select id="counterpart_accounting_account_id" name="counterpart_accounting_account_id" class="selectNewAccountingAccount browser-default" 
							@if(count($accounting_accounts)==0) 
								disabled>
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
		<button id="new_bank_account_button" onclick="submitNewBankAccount();" class="modal-action btn waves-effect submit_button" disabled><b>Registrar</b></button>
	</div>
</div>