<form id="deleteBankAccountForm" method="POST">
	{{ csrf_field() }}
	@method('DELETE')
</form>
<div id="deleteBankAccountModal" class="modal deleteBankAccountModal">
	<div class="modal-content">
		<h5>Eliminar cuenta bancaria?</h5>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
		<button id="delete_bank_account_button" onclick="submitDeleteBankAccount();" class="modal-action btn-flat waves-effect"><b>Eliminar</b></button>
	</div>
</div>