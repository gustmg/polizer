<div id="newProviderModal" class="modal newProviderModal modal-fixed-footer">
	<div class="modal-content">
		<div class="row">
			<div class="col s12">
				<h5>Nuevo proveedor</h5>
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
			          <input id="provider_accounting_account" name="provider_accounting_account" type="text" class="provider_accounting_account validate" onblur="validateForm();" pattern="^[^A-Za-z]+$" required>
			          <label for="provider_accounting_account">Cuenta contable del proveedor *</label>
			        </div>
		        </div>
		        <div class="row">
					<div class="col s12 grey-text text-darken-2"><b>Cuenta de contrapartida</b></div>
					<div class="col s8">
						<select id="counterpart_accounting_account_id" name="counterpart_accounting_account_id" class="accounting-account-list browser-default selectNew">
						    <option value="" disabled selected>Elige una cuenta contable</option>
						    <optgroup label="Inventarios">
						    	@foreach($accounting_accounts as $key => $accounting_account)
						    		@if($accounting_account->accounting_account_type_id ==2)
						    			<option value="{{$accounting_account->accounting_account_id}}" data-accounting-account-number="{{$accounting_account->accounting_account_number}}">{{$accounting_account->accounting_account_description}}</option>
						    		@endif
						    	@endforeach
						    </optgroup>
						    <optgroup label="Gastos de venta">
						    	@foreach($accounting_accounts as $key => $accounting_account)
						    		@if($accounting_account->accounting_account_type_id ==5)
						    			<option value="{{$accounting_account->accounting_account_id}}" data-accounting-account-number="{{$accounting_account->accounting_account_number}}">{{$accounting_account->accounting_account_description}}</option>
						    		@endif
						    	@endforeach
						    </optgroup>
						    <optgroup label="Gastos de administración">
						    	@foreach($accounting_accounts as $key => $accounting_account)
						    		@if($accounting_account->accounting_account_type_id ==6)
						    			<option value="{{$accounting_account->accounting_account_id}}" data-accounting-account-number="{{$accounting_account->accounting_account_number}}">{{$accounting_account->accounting_account_description}}</option>
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
		@if (Route::currentRouteName()=='providers.index')
			<button id="submit_button" onclick="submitNewProvider();" class="modal-action btn waves-effect submit_button" disabled><b>Registrar</b></button>
		@else
			<button id="submit_button" onclick="ajaxNewProvider();" class="modal-action btn waves-effect submit_button" disabled><b>Registrar</b></button>
		@endif
	</div>
</div>