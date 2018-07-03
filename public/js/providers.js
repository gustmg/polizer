$('.newProviderModal').modal({
	ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
	    if(trigger.hasClass('newProviderFromProvision')){
	    	var index_file_id = trigger.parent().parent().attr('data-file-index');
	    	$('#provider_name').val(jsonFilesData[index_file_id].emisor.nombreEmisor);
	    	$('label[for="provider_name"]').addClass('active');
	    	$('#provider_rfc').val(jsonFilesData[index_file_id].emisor.rfcEmisor);
	    	$('label[for="provider_rfc"]').addClass('active');
	    }
	},
	complete: function(){
		$('#provider_name').val('');
		$('label[for="provider_name"]').removeClass('active');
		$('#provider_rfc').val('');
		$('label[for="provider_rfc"]').removeClass('active');
		$('#provider_accounting_account').val('');
		$('#counterpart_accounting_account_id').val('');
	}
});
$('.updateProviderModal').modal();
$('.deleteProviderModal').modal();

$('.selectUpdate').on('change', function(event) {
	$('.selectUpdate').material_select('destroy');
	$('.selectUpdate').val($(this).val());
	$(".hidden_counterpart_account").val($(this).val());
	$('.selectUpdate').material_select();
	$('.selectUpdate option[value="'+$(this).val()+'"]').attr("selected", "selected");
});

function validateForm(){
	if (!$('.provider_name').hasClass('invalid') 
		&& !$('.provider_rfc').hasClass('invalid')
		&& !$('.provider_accounting_account').hasClass('invalid')) {
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
				$(this).find('.newProviderFromProvision').remove();
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

function submitUpdateProvider(provider_id) {
	$('.update_provider_button').attr('disabled', true);
	$('#updateProviderForm'+provider_id).submit();
}

function submitDeleteProvider(provider_id) {
	$('.delete_provider_button').attr('disabled', true);
	$('#deleteProviderForm'+provider_id).submit();
}

function updateProviderModal(id, counterpart_account_id) {
	$('#updateProviderModal').modal('open');
	$('.selectUpdate').material_select('destroy');
	$('.selectUpdate').val(counterpart_account_id);
	$('.selectUpdate').material_select();
}