<form id="deleteAccountingAccountForm" method="POST">
	{{ csrf_field() }}
	@method('DELETE')
</form>
<div id="deleteAccountingAccountModal" class="modal deleteAccountingAccountModal">
	<div class="modal-content">
		<h5>Eliminar cuenta contable?</h5>
		<p>Todos los clientes y proveedores que tengan asignada esta cuenta por contrapartida se verán afectados.</p>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
		<button id="delete_accounting_account_button" onclick="submitDeleteAccountingAccount();" class="modal-action btn-flat waves-effect"><b>Eliminar</b></button>
	</div>
</div>