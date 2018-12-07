<div id="updateProviderModal" class="modal updateProviderModal modal-fixed-footer">
	<div class="modal-content">
		<div class="row">
			<div class="col s12">
				<h5 class="truncate">Editar proveedor</h5>
			</div>
			<form id="updateProviderForm" class="col s12 no-padding" method="POST">
				{{ csrf_field() }}
				@method('PUT')
				<div class="row" style="margin-bottom: 10px;">
					<div class="col s12 grey-text text-darken-2"><b>Información general</b></div>
					<div class="input-field col s12 m6">
				      <input id="update_provider_name" name="provider_name" type="text" class="validate provider_name black-text" onblur="validateUpdateForm();" required>
				      <label for="update_provider_name" data-error="Verifique este campo" data-success="Campo validado">Nombre del proveedor *</label>
				    </div>
			        <div class="input-field col s12 m12">
			          <input id="update_provider_rfc" name="provider_rfc" type="text" class="provider_rfc validate" onblur="validateUpdateForm();" required>
			          <label for="update_provider_rfc" data-error="Verifique este campo" data-success="Campo validado">RFC del proveedor *</label>
			        </div>
			        <div class="input-field col s12 m12">
			          <input id="update_provider_accounting_account" name="provider_accounting_account" type="text" class="provider_accounting_account validate" onblur="validateUpdateForm();" pattern="^[^A-Za-z]+$" required>
			          <label for="update_provider_accounting_account" data-error="Verifique este campo" data-success="Campo validado">Cuenta contable del proveedor *</label>
			        </div>
		        </div>
		        <div class="row">
					<div class="col s12 grey-text text-darken-2"><b>Cuenta de contrapartida</b></div>
					<div class="col s8">
						<select class="selectUpdate browser-default">
							<option value="" disabled selected>Elige una cuenta contable</option>
							<optgroup label="Inventarios">
								@foreach($accounting_accounts as $key => $value2)
									@if($value2->accounting_account_type_id==2)
										<option value="{{$value2->accounting_account_id}}">{{$value2->accounting_account_description}}</option>
									@endif
								@endforeach
							</optgroup>
							<optgroup label="Gastos de Venta">
								@foreach($accounting_accounts as $key => $value3)
									@if($value3->accounting_account_type_id==5)
										<option value="{{$value3->accounting_account_id}}">{{$value3->accounting_account_description}}</option>
									@endif
								@endforeach
							</optgroup>
							<optgroup label="Gastos de Administración">
								@foreach($accounting_accounts as $key => $value4)
									@if($value4->accounting_account_type_id==6)
										<option value="{{$value4->accounting_account_id}}">{{$value4->accounting_account_description}}</option>
									@endif
								@endforeach
							</optgroup>
						</select>
						<input type="hidden" class="hidden_counterpart_account" name="counterpart_accounting_account_id">
					</div>
		        </div>
		        <div class="row">
		        	<div class="col s12 grey-text text-darken-2"><b>Banco</b></div>
					<div class="col s8">
						<select id="selectUpdateBank" name="bank_id" class="selectUpdateBank browser-default">
							@foreach($banks as $key => $value2)
								<option value="{{$value2->bank_id}}">{{$value2->bank_sat_key}} - {{$value2->bank_name}}</option>
							@endforeach
						</select><br>
					</div>
		        	<div class="col s12 grey-text text-darken-2"><b>Cuenta bancaria</b></div>
		        	<div class="input-field col s6 m6">
						<input id="update_provider_bank_account_number" name="provider_bank_account_number" type="text" class="provider_bank_account_number" onblur="validateUpdateForm();">
						<label for="update_provider_bank_account_number" data-error="Verifique este campo" data-success="Campo validado">Cuenta bancaria del proveedor *</label>
			        </div>
		        </div>
			</form>
		</div>
	</div>
	<div class="modal-footer">
		<a id="deleteProviderModalButton" href="#deleteProviderModal" class="modal-action modal-close modal-trigger left" style="margin-top: 10px;margin-left: 10px;"><i class="material-icons black-text">delete</i></a>
		<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
		<button id="update_provider_button" onclick="submitUpdateProvider();" class="modal-action btn waves-effect submit_button update_provider_button">
			<b>Editar</b>
		</button>
	</div>
</div>