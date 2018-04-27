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
    @if(count($accounting_accounts)===1)
    	<h5 class="center"><b>No hay cuentas contables registradas. :^(</b></h5>
	@else
		<div class="col s12">
			<table class="card highlight">
				<thead class="grey darken-4 white-text">
					<tr>
						<th>Número de Cuenta Contable</th>
						<th>Descripción</th>
						<th>Tipo de Cuenta</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1200-000-001</td>
						<td>Servicio de reparacion de equipo de computo</td>
						<td>Gastos de administracion</td>
					</tr>
					<tr>
						<td>1200-000-001</td>
						<td>Servicio de reparacion de equipo de computo</td>
						<td>Gastos de administracion</td>
					</tr>
					<tr>
						<td>1200-000-001</td>
						<td>Servicio de reparacion de equipo de computo</td>
						<td>Gastos de administracion</td>
					</tr>
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
			          <input id="accounting_account_number" name="accounting_account_number" type="text" class="validate accounting_account_number" required>
			          <label for="accounting_account_number" data-error="Verifique este campo" data-success="Campo validado">Número de cuenta contable *</label>
			        </div>
			        <div class="input-field col s12 m12">
			          <input id="accounting_account_description" name="accounting_account_description" type="text" class="validate">
			          <label for="accounting_account_description">Descripción de la cuenta contable</label>
			        </div>
		        </div>
		        <div class="row">
					<div class="col s12 grey-text text-darken-2"><b>Tipo de cuenta contable</b></div>
		        </div>
			</form>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
		<button id="registrate_button" onclick="submitNewAccountingAccount();" class="modal-action btn waves-effect submit_button" disabled><b>Registrar</b></button>
	</div>
</div>
@endsection