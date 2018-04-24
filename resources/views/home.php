<!DOCTYPE html>
<html>
<head>
	<title>Polizer</title>
	<link rel="stylesheet" type="text/css" href="css/materialize.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
	<div class="row">
		<div class="col s12 m6">
			<h5>Modal Empresa</h5>
			<a class="btn-floating btn-large waves-effect waves-light modal-trigger" href="#newCompanyModal"><i class="material-icons">add</i></a>

			<div id="newCompanyModal" class="modal">
				<div class="modal-content">
					<h5>Nueva empresa</h5>
					<div class="row">
						<form class="col s12">
							<div class="row">
								<div class="col s12"><b>Información general</b></div>
								<div class="input-field col s8">
						          <input id="company_name" type="text" class="validate" required>
						          <label for="company_name" data-error="Verifique este campo" data-success="Campo validado">Nombre de la empresa</label>
						        </div>
						        <div class="input-field col s4">
						          <input id="company_rfc" type="text" class="validate">
						          <label for="company_rfc">RFC de la empresa</label>
						        </div>
					        </div>
					        <div class="row">
								<div class="col s12"><b>Cuentas contables</b></div>
								<div class="input-field col s6">
						          <input id="" type="text" class="validate">
						          <label for="company_name">Cuenta 1</label>
						        </div>
						        <div class="input-field col s6">
						          <input id="" type="text" class="validate">
						          <label for="company_rfc">Cuenta 2</label>
						        </div>
						        <div class="input-field col s6">
						          <input id="" type="text" class="validate">
						          <label for="company_name">Cuenta 3</label>
						        </div>
						        <div class="input-field col s6">
						          <input id="" type="text" class="validate">
						          <label for="company_name">Cuenta 4</label>
						        </div>
						        <div class="input-field col s6">
						          <input id="" type="text" class="validate">
						          <label for="company_name">Cuenta 5</label>
						        </div>
						        <div class="input-field col s6">
						          <input id="" type="text" class="validate">
						          <label for="company_name">Cuenta 6</label>
						        </div>
					        </div>
						</form>
					</div>
				</div>
				<div class="modal-footer">
					<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
					<a id="registrate_button" href="#!" class="modal-action btn waves-effect waves-green" disabled>Registrar</a>
				</div>
			</div>
		</div>

		<div class="col s12 m6">
			<h5>Modal Cuenta Contable</h5>
			<a class="btn-floating btn-large waves-effect waves-light modal-trigger" href="#newAccountingAccountModal"><i class="material-icons">add</i></a>

			<div id="newAccountingAccountModal" class="modal"  style="overflow-y: visible;">
				<div class="modal-content">
					<h5>Nueva cuenta contable</h5>
					<div class="row">
						<form class="col s12">
							<div class="row">
								<div class="col s12"><b>Información general</b></div>
								<div class="input-field col s12 m4">
						          <input id="accounting_account_number" type="text" class="validate" required>
						          <label for="accounting_account_number" data-error="Verifique este campo" data-success="Campo validado">Número de cuenta contable</label>
						        </div>
						        <div class="input-field col s12 m8">
						          <input id="accounting_account_description" type="text" class="validate" required>
						          <label for="accounting_account_description" data-error="Verifique este campo" data-success="Campo validado">Descripción de la cuenta contable</label>
						        </div>
					        </div>
					        <div class="row">
								<div class="col s12"><b>Tipo de cuenta contable</b></div>
								<div class="input-field col s12 m6">
								    <select required>
								      <option value="" disabled selected>Choose your option</option>
								      <option value="1">Option 1</option>
								      <option value="2">Option 2</option>
								      <option value="3">Option 3</option>
								      <option value="3">Option 3</option>
								      <option value="3">Option 3</option>
								      <option value="3">Option 3</option>
								    </select>
								</div>
					        </div>
						</form>
					</div>
				</div>
				<div class="modal-footer">
					<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
					<a id="registrate_button" href="#!" class="modal-action btn waves-effect waves-green" disabled>Registrar</a>
				</div>
			</div>
		</div>

		<div class="col s12 m6">
			<h5>Modal proveedor</h5>
			<a class="btn-floating btn-large waves-effect waves-light modal-trigger" href="#newProviderModal"><i class="material-icons">add</i></a>

			<div id="newProviderModal" class="modal"  style="overflow-y: visible;">
				<div class="modal-content">
					<h5>Nuevo proveedor</h5>
					<div class="row">
						<form class="col s12">
							<div class="row">
								<div class="col s12"><b>Información general</b></div>
								<div class="input-field col s12 m8">
						          <input id="provider_name" type="text" class="validate" required>
						          <label for="provider_name" data-error="Verifique este campo" data-success="Campo validado">Nombre del proveedor</label>
						        </div>
						        <div class="input-field col s12 m4">
						          <input id="provider_rfc" type="text" class="validate" required>
						          <label for="provider_rfc" data-error="Verifique este campo" data-success="Campo validado">RFC del proveedor</label>
						        </div>
						        <div class="input-field col s12 m6">
						          <input id="provider_accounting_account" type="text" class="validate" required>
						          <label for="provider_accounting_account" data-error="Verifique este campo" data-success="Campo validado">Cuenta contable del proveedor</label>
						        </div>
						        <div class="input-field col s12 m6">
						          <input id="provider_counterpart_accounting_account" placeholder="Cuenta de contrapartida del proveedor" type="text" class="validate" required>
						          <label for="provider_counterpart_accounting_account" data-error="Verifique este campo" data-success="Campo validado">Cuenta de contrapartida del proveedor</label>
						        </div>
					        </div>
					        <div class="row">
								<div class="col s12"><b>Buscar cuenta de contrapartida</b></div>
								
								<div class="input-field col s12 m6">
								    <select required>
								      <option value="" disabled selected>Choose your option</option>
								      <option value="1">Option 1</option>
								      <option value="2">Option 2</option>
								      <option value="3">Option 3</option>
								      <option value="3">Option 3</option>
								      <option value="3">Option 3</option>
								      <option value="3">Option 3</option>
								    </select>
								</div>
					        </div>
						</form>
					</div>
				</div>
				<div class="modal-footer">
					<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
					<a id="registrate_button" href="#!" class="modal-action btn waves-effect waves-green" disabled>Registrar</a>
				</div>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="js/materialize.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
	  // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
	  $('.modal').modal();
	  $('select').material_select();

	  
	});
</script>
</html>