$('.newClientModal').modal();
$('.updateClientModal').modal();
$('.deleteClientModal').modal();

$('.selectNew').on('change', function(event) {
	$('.selectNew').material_select('destroy');
	$('.selectNew').material_select();
	$('.selectNew').val($(this).val());
	$('.selectNew option[value="'+$(this).val()+'"]').attr("selected", "selected");
});

$('.selectUpdate').on('change', function(event) {
	$('.selectUpdate').material_select('destroy');
	$('.selectUpdate').val($(this).val());
	$(".hidden_counterpart_account").val($(this).val());
	$('.selectUpdate').material_select();
	$('.selectUpdate option[value="'+$(this).val()+'"]').attr("selected", "selected");
});


function validateForm(){
	if (!$('.client_name').hasClass('invalid') 
		&& !$('.client_rfc').hasClass('invalid')
		&& !$('.client_accounting_account').hasClass('invalid')) {
		$('.submit_button').attr('disabled', false);
	} else {
		$('.submit_button').attr('disabled', true);
	}
}

function submitNewClient() {
	$('#new_client_button').attr('disabled', true);
	$('#newClientForm').submit();
}

function submitUpdateClient(client_id) {
	$('#update_client_button').attr('disabled', true);
	$('#updateClientForm'+client_id).submit();
}

function submitDeleteClient(client_id) {
	$('#delete_client_button').attr('disabled', true);
	$('#deleteClientForm'+client_id).submit();
}

function updateClientModal(id, counterpart_account_id) {
	$('#updateClientModal').modal('open');
	$('.selectUpdate').material_select('destroy');
	$('.selectUpdate').val(counterpart_account_id);
	$('.selectUpdate').material_select();
}

function createSelectCounterpart() {
	$('.selectNew').material_select('destroy');
	$('.selectNew').val(0);
	$('.selectNew').material_select();
}

