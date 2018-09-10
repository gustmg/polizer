<form id="deleteProviderForm" method="POST">
	{{ csrf_field() }}
	@method('DELETE')
</form>
<div id="deleteProviderModal" class="modal deleteProviderModal">
	<div class="modal-content">
		<h5>Eliminar proveedor?</h5>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
		<button id="delete_provider_button" onclick="submitDeleteProvider();" class="modal-action btn-flat waves-effect delete_provider_button"><b>Eliminar</b></button>
	</div>
</div>