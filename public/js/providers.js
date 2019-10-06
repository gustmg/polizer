if(document.getElementById("providers-tablesorter") !== null)
{
	new Tablesort(document.getElementById('providers-tablesorter'));
}

$('.newProviderModal').modal({
	ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
	    if(trigger.hasClass('newProviderFromPolicy')){
	    	$('.submit_button').attr('disabled', true);
	    	$('#provider_name').val(trigger.parent().attr('data-provider-name'));
	    	$('label[for="provider_name"]').addClass('active');
	    	$('#provider_rfc').val(trigger.parent().attr('data-provider-rfc'));
	    	$('label[for="provider_rfc"]').addClass('active');
	    	$('#provider_accounting_account').removeClass('valid');
	    	$('.selectNew').val('');
	    	$('.selectNewBank').val('');
	    	$('#provider_bank_account_number').val('');
			$('label[for="provider_bank_account_number"]').removeClass('active');
	    }
	},
	complete: function(){
		$('#provider_name').val('');
		$('label[for="provider_name"]').removeClass('active');
		$('#provider_rfc').val('');
		$('label[for="provider_rfc"]').removeClass('active');
		$('#provider_accounting_account').val('');
		$('label[for="provider_accounting_account"]').removeClass('active');
		$('#counterpart_accounting_account_id').val('');
		$('#provider_name').removeClass('invalid');
		$('#provider_rfc').removeClass('invalid');
		$('#provider_accounting_account').removeClass('invalid');
		$('#provider_name').removeClass('valid');
		$('#provider_rfc').removeClass('valid');
		$('#provider_accounting_account').removeClass('valid');
		$('#provider_bank_account_number').val('');
		$('label[for="provider_bank_account_number"]').removeClass('active');
	}
});

$('.updateProviderModal').modal({
	ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
    	$('#update_provider_name').val(trigger.attr('data-provider-name'));
    	$('label[for="update_provider_name"]').addClass('active');
    	$('#update_provider_rfc').val(trigger.attr('data-provider-rfc'));
    	$('label[for="update_provider_rfc"]').addClass('active');
    	$('#update_provider_accounting_account').val(trigger.attr('data-provider-accounting-account'));
    	$('label[for="update_provider_accounting_account"]').addClass('active');
    	$('.selectUpdate').val(trigger.attr('data-provider-counterpart-id'));
    	$('#update_provider_name').removeClass('invalid');
    	$('#update_provider_rfc').removeClass('invalid');
    	$('#update_provider_rfc').attr('data-provider-rfc', trigger.attr('data-provider-rfc'));
    	$('#update_provider_accounting_account').removeClass('invalid');
    	$('#update_provider_name').removeClass('valid');
    	$('#update_provider_rfc').removeClass('valid');
    	$('#update_provider_accounting_account').removeClass('valid');
    	$('#updateProviderForm').attr('action','providers/'+trigger.attr('data-provider-id'));
    	$('#deleteProviderModalButton').attr('data-provider-id', trigger.attr('data-provider-id'));
    	$('#update_provider_bank_account_number').removeClass('invalid');
    	$('#update_provider_bank_account_number').removeClass('valid');
    	$('label[for="update_provider_bank_account_number"]').addClass('active');
    	$('#update_provider_bank_account_number').val(trigger.attr('data-provider-bank-account'));
    	$('.selectUpdateBank').val(trigger.attr('data-provider-bank'));
	},
});

$('.deleteProviderModal').modal({
	ready: function(modal, trigger){
		$('#deleteProviderForm').attr('action','providers/'+trigger.attr('data-provider-id'));
	},
});

function validateNewProviderForm(){
	validateProviderRfc($('#newProviderForm #provider_rfc').val());
	if (!$('#provider_name').hasClass('invalid') 
		&& $('#provider_name').val() != ''
		&&!$('#provider_rfc').hasClass('invalid')
		&& $('#provider_rfc').val() != ''
		&& !$('#provider_accounting_account').hasClass('invalid')
		&& $('#provider_accounting_account').val() != ''
		&& $('.selectNew option:selected').val() != ''
		&& $('.selectNewBank option:selected').val() == ''
		&& $('#provider_bank_account_number').val() =='') {
		$('.submit_button').attr('disabled', false);
	}
	else if(!$('#provider_name').hasClass('invalid') 
		&& $('#provider_name').val() != ''
		&&!$('#provider_rfc').hasClass('invalid')
		&& $('#provider_rfc').val() != ''
		&& !$('#provider_accounting_account').hasClass('invalid')
		&& $('#provider_accounting_account').val() != ''
		&& $('.selectNew option:selected').val() != ''
		&& $('.selectNewBank option:selected').val() != ''
		&& $('#provider_bank_account_number').val() !=''
		){
			$('.submit_button').attr('disabled', false);
	}
	else if(!$('#provider_name').hasClass('invalid') 
		&& $('#provider_name').val() != ''
		&&!$('#provider_rfc').hasClass('invalid')
		&& $('#provider_rfc').val() != ''
		&& !$('#provider_accounting_account').hasClass('invalid')
		&& $('#provider_accounting_account').val() != ''
		&& $('.selectNew option:selected').val() != ''
		&& $('.selectNewBank option:selected').val() == ''
		&& $('#provider_bank_account_number').val() !=''
		){
			$('.submit_button').attr('disabled', true);
	}
	else if(!$('#provider_name').hasClass('invalid') 
		&& $('#provider_name').val() != ''
		&&!$('#provider_rfc').hasClass('invalid')
		&& $('#provider_rfc').val() != ''
		&& !$('#provider_accounting_account').hasClass('invalid')
		&& $('#provider_accounting_account').val() != ''
		&& $('.selectNew option:selected').val() != ''
		&& $('.selectNewBank option:selected').val() != ''
		&& $('#provider_bank_account_number').val() ==''
		){
			$('.submit_button').attr('disabled', true);
	}
	else {
		$('.submit_button').attr('disabled', true);
	}
}

function validateUpdateForm(){
	validateUpdateProviderRfc($('#updateProviderForm #update_provider_rfc').val());
	if (!$('#update_provider_name').hasClass('invalid') 
		&& !$('#update_provider_rfc').hasClass('invalid')
		&& !$('#update_provider_accounting_account').hasClass('invalid')) {
		$('.submit_button').attr('disabled', false);
	} else {
		$('.submit_button').attr('disabled', true);
	}
}

function submitNewProvider() {
	$('.submit_button').attr('disabled', true);
	$('#newProviderForm').submit();
	//$('#newProviderForm').off('submit');
}

function submitUpdateProvider() {
	$(".hidden_counterpart_account").val($('.selectUpdate option:selected').val());
	$('.update_provider_button').attr('disabled', true);
	$('#updateProviderForm').submit();
}

function submitDeleteProvider() {
	$('.delete_provider_button').attr('disabled', true);
	$('#deleteProviderForm').submit();
}

function ajaxNewProvider() {
	// $('.submit_button').attr('disabled', true);
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

	$.ajax({
		url: 'ajaxProvision',
		type: 'POST',
		data: { _token: CSRF_TOKEN,
				handler: 'newProvider',
				provider_name: $("#provider_name").val(),
				provider_rfc: $("#provider_rfc").val(),
				provider_accounting_account: $("#provider_accounting_account").val(),
				counterpart_accounting_account_id: $("#counterpart_accounting_account_id").val(),
		},
	})
	.done(function(data) {
		$("#newProviderModal").modal('close');
		$("tr").each(function(index){
			if($(this).attr('data-rfc-provider')===data[0].provider_rfc){
				$(this).find('.provider-name').removeClass('red-text');
				$(this).find('.newProviderFromPolicy').remove();
				var row_index=$(this).attr('data-file-index');
				$('#modalShowConcepts'+row_index+' .accounting-account-list').each(function(){
					$(this).val(data[0].counterpart_accounting_account_id);
				});
				setConceptsToJson(row_index);
				var provider_accounting_account=[data[0].provider_accounting_account];
				$.extend(jsonFilesData[row_index].proveedor.cuentaContable, provider_accounting_account);
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

function validateProviderRfc(provider_rfc) {
	$('.providers-tablesorter tbody tr td:nth-child(3)').each(function(index){
		if($(this).text() == provider_rfc){
			$('.provider_rfc').addClass('invalid');
		}
	});
}

function validateUpdateProviderRfc(provider_rfc) {
	$('.providers-tablesorter tbody tr td:nth-child(3)').each(function(index){
		if($(this).text() == provider_rfc){
			$('.provider_rfc').addClass('invalid');
		}
	});
	if(provider_rfc == $('#update_provider_rfc').attr('data-provider-rfc')){
		$('.provider_rfc').removeClass('invalid');
		$('.provider_rfc').removeClass('valid');
	}
}