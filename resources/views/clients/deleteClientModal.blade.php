<form id="deleteClientForm" method="POST">
	{{ csrf_field() }}
	@method('DELETE')
</form>
<div id="deleteClientModal" class="modal deleteClientModal">
	<div class="modal-content">
		<h5>Eliminar cliente?</h5>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
		<button id="delete_client_button" onclick="submitDeleteClient();" class="modal-action btn-flat waves-effect"><b>Eliminar</b></button>
	</div>
</div>