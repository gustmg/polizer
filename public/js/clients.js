if(document.getElementById("clients-tablesorter") !== null)
{
	new Tablesort(document.getElementById('clients-tablesorter'));
}

$('.newClientModal').modal({
	ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
	    if(trigger.hasClass('newClientFromPolicy')){
	    	$('.submit_button').attr('disabled', true);
	    	$('#client_name').val(trigger.parent().attr('data-client-name'));
	    	$('label[for="client_name"]').addClass('active');
	    	$('#client_rfc').val(trigger.parent().attr('data-client-rfc'));
	    	$('label[for="client_rfc"]').addClass('active');
	    }
	},
	complete: function(){
		$('#client_name').val('');
		$('label[for="client_name"]').removeClass('active');
		$('#client_rfc').val('');
		$('label[for="client_rfc"]').removeClass('active');
		$('#client_accounting_account').val('');
		$('label[for="client_accounting_account"]').removeClass('active');
		$('#counterpart_accounting_account_id').val('');
		$('#client_name').removeClass('invalid');
		$('#client_rfc').removeClass('invalid');
		$('#client_accounting_account').removeClass('invalid');
		$('#client_name').removeClass('valid');
		$('#client_rfc').removeClass('valid');
		$('#client_accounting_account').removeClass('valid');
	}
});

$('.updateClientModal').modal({
	ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
    	$('#update_client_name').val(trigger.attr('data-client-name'));
    	$('label[for="update_client_name"]').addClass('active');
    	$('#update_client_rfc').val(trigger.attr('data-client-rfc'));
    	$('label[for="update_client_rfc"]').addClass('active');
    	$('#update_client_accounting_account').val(trigger.attr('data-client-accounting-account'));
    	$('label[for="update_client_accounting_account"]').addClass('active');
    	$('.selectUpdate').val(trigger.attr('data-client-counterpart-id'));
    	$('#update_client_name').removeClass('invalid');
    	$('#update_client_rfc').removeClass('invalid');
    	$('#update_client_rfc').attr('data-client-rfc', trigger.attr('data-client-rfc'));
    	$('#update_client_accounting_account').removeClass('invalid');
    	$('#update_client_name').removeClass('valid');
    	$('#update_client_rfc').removeClass('valid');
    	$('#update_client_accounting_account').removeClass('valid');
    	$('#updateClientForm').attr('action','clients/'+trigger.attr('data-client-id'));
    	$('#deleteClientModalButton').attr('data-client-id', trigger.attr('data-client-id'));
	},
});

$('.deleteClientModal').modal({
	ready: function(modal, trigger){
		$('#deleteClientForm').attr('action','clients/'+trigger.attr('data-client-id'));
	},
});

function validateNewClientForm(){
	validateClientRfc($('#newClientForm #client_rfc').val());
	if (!$('#client_name').hasClass('invalid')
		&& $('#client_name').val() != ''
		&& !$('#client_rfc').hasClass('invalid')
		&& $('#client_rfc').val() != ''
		&& !$('#client_accounting_account').hasClass('invalid')
		&& $('#client_accounting_account').val() != ''
		&& $('.selectNew option:selected').val() != '') {
		$('.submit_button').attr('disabled', false);
	} else {
		$('.submit_button').attr('disabled', true);
	}
}

function validateUpdateForm(){
	validateUpdateClientRfc($('#updateClientForm #update_client_rfc').val());
	if (!$('#update_client_name').hasClass('invalid') 
		&& !$('#update_client_rfc').hasClass('invalid')
		&& !$('#update_client_accounting_account').hasClass('invalid')) {
		$('.submit_button').attr('disabled', false);
	} else {
		$('.submit_button').attr('disabled', true);
	}
}

function submitNewClient() {
	$('.submit_button').attr('disabled', true);
	$('#newClientForm').submit();
}

function submitUpdateClient() {
	$(".hidden_counterpart_account").val($('.selectUpdate option:selected').val());
	$('#update_client_button').attr('disabled', true);
	$('#updateClientForm').submit();
}

function submitDeleteClient() {
	$('#delete_client_button').attr('disabled', true);
	$('#deleteClientForm').submit();
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
				$(this).find('.newClientFromPolicy').remove();
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

function validateClientRfc(client_rfc) {
	$('.clients-tablesorter tbody tr td:nth-child(3)').each(function(index){
		if($(this).text() == client_rfc){
			$('.client_rfc').addClass('invalid');
		}
	});
}

function validateUpdateClientRfc(client_rfc) {
	$('.clients-tablesorter tbody tr td:nth-child(3)').each(function(index){
		if($(this).text() == client_rfc){
			$('.client_rfc').addClass('invalid');
		}
	});
	if(client_rfc == $('#update_client_rfc').attr('data-client-rfc')){
		$('.client_rfc').removeClass('invalid');
		$('.client_rfc').removeClass('valid');
	}
}