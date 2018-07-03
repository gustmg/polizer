$('.newClientModal').modal({
	ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
	    if(trigger.hasClass('newClientFromProvision')){
	    	var index_file_id = trigger.parent().parent().attr('data-file-index');
	    	$('#client_name').val(jsonFilesData[index_file_id].receptor.nombreReceptor);
	    	$('label[for="client_name"]').addClass('active');
	    	$('#client_rfc').val(jsonFilesData[index_file_id].receptor.rfcReceptor);
	    	$('label[for="client_rfc"]').addClass('active');
	    }
	},
	complete: function(){
		$('#client_name').val('');
		$('label[for="client_name"]').removeClass('active');
		$('#client_rfc').val('');
		$('label[for="client_rfc"]').removeClass('active');
		$('#client_accounting_account').val('');
		$('#counterpart_accounting_account_id').val('');
	}
});
$('.updateClientModal').modal();
$('.deleteClientModal').modal();

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

function ajaxNewClient() {
	// $('.submit_button').attr('disabled', true);
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

	$.ajax({
		url: 'ajaxBilling',
		type: 'POST',
		data: { _token: CSRF_TOKEN,
				handler: 'newClient',
				client_name: $("#client_name").val(),
				client_rfc: $("#client_rfc").val(),
				client_accounting_account: $("#client_accounting_account").val(),
				counterpart_accounting_account_id: $("#counterpart_accounting_account_id").val(),
		},
	})
	.done(function(data) {
		$("#newClientModal").modal('close');
		$("tr").each(function(index){
			if($(this).attr('data-rfc-client')===data[0].client_rfc){
				$(this).find('.client-name').removeClass('red-text');
				$(this).find('.newClientFromProvision').remove();
				var row_index=$(this).attr('data-file-index');
				$('#modalShowConcepts'+row_index+' .accounting-account-list').each(function(){
					$(this).val(data[0].counterpart_accounting_account_id);
				});
				setConceptsToJson(row_index);
				var client_accounting_account=[data[0].client_accounting_account];
				$.extend(jsonFilesData[row_index].cliente.cuentaContable, client_accounting_account);
				//console.log(jsonFilesData[row_index]);
			}
		});
		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
}

